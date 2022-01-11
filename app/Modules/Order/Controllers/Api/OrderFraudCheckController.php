<?php


namespace Modules\Order\Controllers\Api;


use Exception;
use Modules\Core\Models\FraudCheckColumnModel;
use Modules\Core\Models\LanguageModel;
use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Order\Helpers\FraudCheckFAHelper;
use Modules\Order\Models\FraudCheckBaseQuestionModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderBaseFraudCheckModelV2;
use Modules\Order\Models\OrderFraudFACheckModel;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Sites\Models\SiteModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Exceptions\UnknownPropertyException;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Q\QOr;

class OrderFraudCheckController extends Controller
{
    /**
     * @throws Exception
     */
    public function getBaseSettings(int $order_id = null)
    {
        try {
            /** @var OrderModel $order_model */
            $order_model = OrderModel::objects()->get(['orderid' => $order_id]);

            $count_frauds = OrderBaseFraudCheckModelV2::objects()->filter(['order_id' => $order_model->orderid])->count();
            $count_fa_frauds = OrderFraudFACheckModel::objects()->filter(['order_id' => $order_model->orderid])->count();

            if (!($count_frauds && $count_fa_frauds)) {
                $this->jsonResponse([], 404);
                return;
            }
            $ar_settings = ['locked_orders' => false];
            $time_for_order_in_mins = 10;
            $current_time = time();
            $login_last_opened_or_saved = $order_model->login_last_opened_or_saved;
            $time_last_opened_or_saved = $order_model->time_last_opened_or_saved;
            $diff_time_in_mins = ($current_time - $time_last_opened_or_saved) / 60;
            $you_have_right_to_change_order = true;
            if ($login_last_opened_or_saved === Xcart::app()->user->login) {
                $order_model->time_last_opened_or_saved = $current_time;
                $time_last_opened_or_saved = $current_time;
            } else if ($diff_time_in_mins > $time_for_order_in_mins) {
                $order_model->login_last_opened_or_saved = Xcart::app()->user->login;
                $order_model->time_last_opened_or_saved = $current_time;
                $time_last_opened_or_saved = $current_time;
            } else {
                $you_have_right_to_change_order = false;
            }
            $order_model->save();
            $time_unlock = $time_last_opened_or_saved + $time_for_order_in_mins * 60 + 60 * 60;
            if ($you_have_right_to_change_order) {

                $ar_settings = ['lock' => true, 'timeUnlocked' => date("G:i", $time_unlock)];
                $tmp_diff_time = time() - 60 * $time_for_order_in_mins;
                $count_locked_orders = OrderModel::objects()->filter(
                    [
                        'login_last_opened_or_saved' => Xcart::app()->user->login,
                        'time_last_opened_or_saved__gt' => $tmp_diff_time
                    ]
                )->count();
                if ($count_locked_orders > 1) {
                    $ar_settings['locked_orders'] = true;
                }
            }
            $ar_settings['statusList'] = FraudStatusModel::objects()->order(['order_by'])->valuesList(['code', 'name']);
            $ar_settings['prefix'] = $order_model->order_prefix;
            /** @var LanguageModel $template */
            $template = LanguageModel::objects()->get(['name' => 'lbl_fraud_check_expert_section']);
            $ar_settings['template'] = $template->value;

            $ar_response['settings'] = $ar_settings;
            $base_list = ['code', 'name', 'type', 'fraud_id', 'fraud_id'];
            $ar_response['columns'] = [
                'fullName' => FraudCheckColumnModel::objects()->filter(['type' => 'full_name'])->valuesList($base_list),
                'address' => FraudCheckColumnModel::objects()->filter(['type' => 'address'])->valuesList($base_list)
            ];

            $ar_answer = $this->getBaseAnswerOrder($order_model);
            $ar_fa_answer = $this->getAnswerFAOrder($order_model);
            if (!empty($ar_fa_answer) && !empty($ar_answer)) {
                $ar_response['answer'] = array_merge($ar_answer, $ar_fa_answer);
            }
            $ar_payment_answer = $this->getAnswerPaymentFrauds($order_model);
            if (!empty($ar_payment_answer)) {
                $ar_response['answer'] = array_merge($ar_response['answer'] ?? [], ['payment' => $ar_payment_answer]);
            }
            $ar_response['legend'] = $this->getLegendInfo($order_model);
            $ar_response['resultChange'] = $this->getManualAction($ar_answer);
            $ar_response['orderInfo'] = [
                'bareResult' => (float)$order_model->bare_fraud_score_v2,
                'fraudStatus' => [
                    'name' => $order_model->fraud_status_model->name,
                    'code' => $order_model->fraud_status
                ]
            ];
            $ar_response['addressesLocation'] = $order_model->getAddressesGeoLocation();

            $this->jsonResponse($ar_response);
        } catch (Throwable $exception) {
            $log = "OrderID: $order_id. By get fraud check data";
            Xcart::app()->logger->error($log, [$exception->getMessage()], 'fraud_check');
            $this->jsonResponse(['message' => 'Error by get fraud check data, repeat operation later'], 400);
        }
    }


    public function unlockOrder(int $order_id = null)
    {
        $ar_result = ['status' => true];
        try {
            if ($order_id) {
                $order_model = OrderModel::objects()->get(['orderid' => $order_id]);
            }
            /** @var OrderModel $order_model */
            $order_model->time_last_opened_or_saved = 0;
            $order_model->save();
        } catch (Exception $exception) {
            $ar_result = ['status' => false, 'error' => $exception->getMessage()];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }

    public function getAnswerPaymentFrauds(OrderModel $order_model): array
    {
        $ar_payment_frauds = ['general_payment' => [], 'stripe' => [], 'pay_pal' => []];
        $frauds_payment = OrderBaseFraudCheckModelV2::objects()->filter([
            'order_id' => $order_model->orderid,
            'question__type__in' => [FraudCheckBaseQuestionModel::FRAUD_TYPE_PAY_PAL, FraudCheckBaseQuestionModel::FRAUD_TYPE_STRIPE, FraudCheckBaseQuestionModel::FRAUD_TYPE_GENERAL_PAYMENT]
        ]);
        $oTransaction = $order_model->getFirstTransaction();
        $sTransactionReplaceText = '';
        $sPaymentMethodReplaceText = '';
        if ($oTransaction && $oPaymentMethod = PaymentMethodModel::objects()->get(['paymentid' => $oTransaction->paymentid])) {
            $sTransactionLink = str_replace('{{trans-id}}', $oTransaction->transaction_id, $oPaymentMethod->transaction_id_link);
            $sTransactionReplaceText = "<a target='_blank' href='$sTransactionLink' style='color:#1F08F8;'>Link to transaction</a>";
            $sPaymentMethodReplaceText = "$oPaymentMethod->payment_method ($oPaymentMethod->transaction_link_anchor)";
        }
        $avs_code = $brand_card = $name_card = '';
        /** @var OrderBaseFraudCheckModelV2 $answer_item */
        foreach ($frauds_payment as $answer_item) {
            switch ($answer_item->question->question_code) {
                case 'GC-AVS':
                    $avs_code = $answer_item->additional_info['AVS'];
                    break;
                case 'GC-CT':
                    $brand_card = $answer_item->additional_info['card_type'];
                    break;
                case 'ST-DC':
                    $name_card = $answer_item->additional_info['name_card'];
                    break;
            }
            $template = str_replace(
                [
                    '{{link_to_paypal_transaction}}',
                    '{{shipping_address}}',
                    '{{payment_method}}',
                    '{{customer_email}}',
                    '{{avs_code}}',
                    '{{type_card}}',
                    '{{name_card}}'
                ],
                [
                    $sTransactionReplaceText,
                    $order_model->getShippingAddressString(),
                    $sPaymentMethodReplaceText,
                    $order_model->email,
                    $avs_code,
                    $brand_card,
                    $name_card
                ],
                $answer_item->question->question_template_body
            );
            $ar_payment_frauds[$answer_item->question->type][] = [
                'template' => $template,
                'fraud_result' => $answer_item->fraud_result,
                'fraud_score' => $answer_item->fraud_score,
                'question_id' => $answer_item->question_id,
                'question_code' => $answer_item->question->question_code,
                'question_auto' => $answer_item->question->auto,
                'outcome' => $answer_item->outcome,
                'question_weight' => (float)$answer_item->question->weight
            ];
        }
        return $ar_payment_frauds;
    }

    public function getAnswerFAOrder(OrderModel $orderModel): array
    {
        $ar_answer = ['full_name' => [], 'address' => []];
        /** @var OrderFraudFACheckModel $fraud */
        foreach (OrderFraudFACheckModel::objects()->filter(['order_id' => $orderModel->orderid]) as $fraud) {
            [$replace_template, $replace_value] = $this->getTemplateData($fraud);
            $data = [
                'question_id' => $fraud->question_id,
                'fraud_score' => $fraud->fraud_score,
                'f_fraud_name' => $fraud->question->f_fraud->name,
                't_fraud_name' => $fraud->question->t_fraud->name,
                'question_weight' => (float)$fraud->question->weight,
                'template' => str_replace($replace_template, $replace_value, $fraud->question->template),
                'outcome' => (float)$fraud->outcome,
                'type' => $fraud->question->f_fraud->type,
                'arAdditional' => $fraud->additional_info["value{$fraud->question->f_fraud->code}"]

            ];
            $ar_answer[$fraud->question->f_fraud->type][] = $data;
        }
        return $ar_answer;
    }

    private function getTemplateData(OrderFraudFACheckModel $answer): array
    {
        $ar_info = $answer->additional_info;
        $result = [];
        $code_list = [$answer->question->f_fraud->code, $answer->question->t_fraud->code];
        foreach ($code_list as $code) {
            $template = [];
            if ($answer->question->f_fraud->type === 'full_name') {
                $value = $ar_info["value$code"]['full_name'] ?? $ar_info["value$code"];
            } else {
                if (isset($ar_info["value$code"]['state'])) {
                    $value = FraudCheckFAHelper::getStringAddressByArray($ar_info["value$code"]);
                } else {
                    $value = $ar_info["value$code"];
                }
            }
            switch ($code) {
                case 'FN_CI':
                    $template = ['{{contact_name}}' => $value];
                    break;
                case 'FN_SA':
                    $template = ['{{shipping_name}}' => $value];
                    break;
                case 'FN_BA':
                    $template = ['{{billing_name}}' => $value];
                    break;
                case 'FN_CH':
                    $template = ['{{card_owner_name}}' => $value];
                    break;
                case 'FN_T_SA':
                    $template = ['{{tenant_s_name}}' => $value];
                    break;
                case 'FN_T_BA':
                    $template = ['{{tenant_b_name}}' => $value];
                    break;
                case 'FN_O_SA':
                    $template = ['{{owner_s_name}}' => $value];
                    break;
                case 'FN_O_BA':
                    $template = ['{{owner_b_name}}' => $value];
                    break;
                case 'FN_TN':
                    $template = ['{{telephone_name}}' => $value];
                    break;
                case 'FN_EA':
                    $template = ['{{email_name}}' => $value];
                    break;
                case 'SA':
                    $template = ['{{shipping_address}}' => $value];
                    break;
                case 'BA':
                    $template = ['{{billing_address}}' => $value];
                    break;
                case 'ORA_SA':
                    $template = ['{{owner_s_residence_address}}' => $value];
                    break;
                case 'ORA_BA':
                    $template = ['{{owner_b_residence_address}}' => $value];
                    break;
                case 'CSZ_TN':
                    $template = ['{{telephone_address}}' => $value];
                    break;
                case 'CSZ_IP':
                    $template = ['{{ip_address}}' => $value];
                    break;
            }
            $result = array_merge($result, $template);
        }
        return [array_keys($result), array_values($result)];
    }

    /**
     * @throws UnknownPropertyException
     */
    public function getBaseAnswerOrder(OrderModel $orderModel): array
    {
        $ar_res_answer = ['diagonal' => [], 'red_flags' => []];
        $base_answer_fraud = OrderBaseFraudCheckModelV2::objects()->filter([
            'order_id' => $orderModel->orderid,
            'question__type__in' => ['diagonal', 'red_flags']
        ]);
        $email_domain = $orderModel->getEmailDomain();
        $email_domain_temp = <<<HTML
<a target="_blank" href="//www.$email_domain" style="color: #1F08F8;">www.$email_domain</a>
HTML;
        $google_shipping_l = <<<HTML
<a target="_blank" href="https://www.google.com/search?q={$orderModel->getGoogleShippingAddress()}" style="color: #1F08F8;">Google shipping address</a>
HTML;
        $shipping_address = $orderModel->getShippingAddressString();
        $customer_email = $orderModel->email;
        $order_chargeback_list = '';
        $orders_full_names = "$orderModel->s_firstname<br />$orderModel->b_firstname<br />$orderModel->firstname";
        $orders_company_names = "$orderModel->s_company<br />$orderModel->b_company";
        if ($aProductLinks = BaseFraudCheckHelperV2::getProductList($orderModel)) {
            $links_to_ordered_products = implode('<br>', $aProductLinks);
        }
        /** @var OrderBaseFraudCheckModelV2 $answer */
        foreach ($base_answer_fraud as $answer) {
            $question = $answer->question;
            switch ($question->question_code) {
                case 'RF-CB':
                    /** @var SiteModel $site */
                    $site = Xcart::app()->getModule('Sites')->getSite();
                    foreach ($answer->additional_info as $order_id) {
                        /** @var OrderModel $order_model */
                        $order_model = OrderModel::objects()->get(['pk' => $order_id]);
                        if ($order_model) {
                            $order_chargeback_list .= <<<HTML
<br/><a target="_blank" href="https://$site->domain{$order_model->getAdminUrl()}" style="color: #1F08F8;">{$order_model->getOrderNumber()}</a>
HTML;
                        }
                    }
                    break;
            }
            $template = str_replace(
                [
                    '{{emails_domain}}',
                    '{{email_domain_website}}',
                    '{{shipping_address}}',
                    '{{customer_email}}',
                    '{{orders_full_names}}',
                    '{{orders_company_names}}',
                    '{{links_to_ordered_products}}',
                    '{{google_shipping}}',
                    '{{orders_url_list}}'
                ],
                [
                    "@$email_domain",
                    $email_domain_temp,
                    $shipping_address,
                    $customer_email,
                    $orders_full_names,
                    $orders_company_names,
                    $links_to_ordered_products ?? '',
                    $google_shipping_l,
                    $order_chargeback_list
                ],
                $answer->question->question_template_body
            );
            $ar_answer = [
                'template' => $template,
                'question_code' => $answer->question->question_code,
                'fraud_result' => $answer->fraud_result,
                'fraud_score' => $answer->fraud_score,
                'question_id' => $answer->question_id,
                'question_auto' => $answer->question->auto,
                'question_weight' => (float)$answer->question->weight,
                'outcome' => $answer->outcome,
                'manual_action' => $answer->manual_action ?? null
            ];
            $ar_res_answer[$answer->question->type][] = $ar_answer;
        }
        return $ar_res_answer;
    }

    public function saveOrderFraudStatus(): void
    {
        try {
            $post = json_decode(file_get_contents('php://input'));
            /** @var OrderModel $orderModel */
            $orderModel = OrderModel::objects()->get(['orderid' => $post->orderId]);
            $orderModel->fraud_status = $post->code;
            $orderModel->save();
            /** @var FraudStatusModel $status */
            if ($status = FraudStatusModel::objects()->get(['code' => $post->code])) {
                $this->jsonResponse(['code' => $status->code, 'name' => $status->name]);
            }
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => $exception->getMessage()], 400);
        }
    }

    public function forceFraudCheck(int $order_id = null): void
    {
        try {
            /** @var OrderModel $order_model */
            $order_model = OrderModel::objects()->get(['orderid' => $order_id]);
            $order_model->orderFraudCheck();
            $this->jsonResponse(['status' => true]);
        } catch (Throwable $e) {
            $log = "OrderID: $order_id. By force fraud check data";
            Xcart::app()->logger->error($log, [$e->getMessage(), $e->getFile(), $e->getLine()], 'fraud_check');
            $this->jsonResponse(['message' => 'Error by force fraud check, repeat operation later'], 400);
        }
    }

    public function getManualAction(array $base_answer): array
    {
        $ar_action = [];
        foreach ($base_answer as $answer_list) {
            foreach ($answer_list as $answer) {
                if (!is_null($answer['manual_action'])) {
                    $ar_action[$answer['question_code']] = $answer['manual_action'];
                }
            }
        }
        return $ar_action;
    }

    public function changeFraudCheckResult(): void
    {
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->orderId;
        try {
            $field_change = $post->change;
            /** @var OrderModel $order_model */
            $order_model = OrderModel::objects()->get(['orderid' => $order_id]);
            $ar_answer = [];
            foreach ($field_change as $code => $value) {
                /** @var OrderBaseFraudCheckModelV2 $order_answer */
                $order_answer = OrderBaseFraudCheckModelV2::objects()->get(['question__question_code' => $code, 'order_id' => $order_id]);
                if ($order_answer->manual_action !== $value) {
                    $weight = $order_answer->question->weight;
                    $is_red_flag = $order_answer->question->type === FraudCheckBaseQuestionModel::FRAUD_TYPE_RED_FLAGS;
                    switch ($value) {
                        case 'Y':
                            $result = 'positive';
                            $outcome = 1;
                            if ($is_red_flag) {
                                $outcome = 0;
                                $result = 'negative';
                            }
                            $order_model->bare_fraud_score_v2 += $weight;
                            break;
                        case 'N':
                            $result = 'negative';
                            $outcome = 0;
                            if ($is_red_flag) {
                                $result = 'positive';
                                $outcome = 1;
                            }
                            $order_model->bare_fraud_score_v2 -= $weight;
                            break;
                    }
                    $score = $outcome * $order_answer->question->weight;
                    $order_answer->fraud_result = $result;
                    $order_answer->fraud_score = $score;
                    $order_answer->outcome = $outcome ?? 0;
                    $order_answer->manual_action = $value;

                    $order_answer->save();
                    $ar_answer[] = [
                        'fraud_result' => $result,
                        'fraud_score' => $score,
                        'outcome' => $outcome ?? 0,
                        'question_code' => $order_answer->question->question_code,
                    ];

                }
            }
            $order_model->save();
            $this->jsonResponse([
                'bareResult' => $order_model->bare_fraud_score_v2,
                'answerList' => $ar_answer
            ]);
        } catch (Throwable $exception) {
            $log = "OrderID: $order_id. By by change fraud check result";
            Xcart::app()->logger->error($log, [$exception->getMessage()], 'fraud_check');
            $this->jsonResponse(['message' => 'Error by change fraud check result, repeat operation later'], 400);
        }
    }

    public function unlockOrders(): void
    {
        try {
            OrderModel::objects()->filter(['login_last_opened_or_saved' => Xcart::app()->user->login])->update(['time_last_opened_or_saved' => 0]);
            $this->jsonResponse(['status' => true]);
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => 'Error by unlock orders, repeat operation later'], 400);
        }
    }

    public function getLegendInfo(OrderModel $order_model): array
    {
        $ar_history = ['full_name' => [], 'address' => []];
        /** @var FraudCheckColumnModel $column */
        foreach (FraudCheckColumnModel::objects()->all() as $column) {
            /** @var OrderFraudFACheckModel $fraud_model */
            $fraud_model = OrderFraudFACheckModel::objects()->filter([
                new QOr([
                    'question__f_fraud_id' => $column->fraud_id,
                    'question__t_fraud_id' => $column->fraud_id
                ]),
                'order_id' => $order_model->orderid
            ])->limit(1)->get();
            if ($fraud_model instanceof OrderFraudFACheckModel) {
                $value = $fraud_model->additional_info["value$column->code"];
                if ($column->type === 'full_name') {
                    $value_str = $value['full_name'] ?? $value;
                    $value_text = $value_str;
                    if (!empty($value['zip'])) {
                        $value_str .= " {$value['zip']}";
                    }
                } else {
                    $value_text = $value;
                    if (isset($value['state'])) {
                        $value_str = FraudCheckFAHelper::getStringAddressByArray($value);
                        $value_str = str_replace(',', '', $value_str); // Обрезает у value лишние символы и превращает в google link
                    }
                }
                $link = '';
                if (isset($value_str) && $value_str !== 'N/A') {
                    $link = 'https://www.google.com/search?';
                    $full_link = $link . http_build_query(['q' => trim($value_str)]);
                }

                $ar_history[$column->type][] = [
                    'value' => $value_text,
                    'link' => !empty($link),
                    'columnName' => $column->name,
                    'description' => $column->description,
                    'linkUrl' => $full_link ?? '',
                    'type' => $column->type,
                    'frontendType' => $column->frontend_type,
                    'provider' => $column->frontend_provider,
                    'isMelissa' => $column->is_melissa_data,
                    'sourceType' => $column->source_type,
                    'inferredFrom' => $column->inferred_from,
                ];
            }
        }
        return $ar_history;
    }
}