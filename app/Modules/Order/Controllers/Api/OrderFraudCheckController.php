<?php


namespace Modules\Order\Controllers\Api;


use Modules\Core\Models\FraudCheckColumnModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\LanguageModel;
use Modules\Order\Helpers\FraudCheckHelper;
use Modules\Order\Models\FraudCheckModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderFraudFACheckModel;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Payment\Models\ProcessorModel;
use Modules\User\Models\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class OrderFraudCheckController extends Controller
{
    public function getBaseSettings(int $order_id = null)
    {
        $order_model = OrderModel::objects()->get(['orderid' => $order_id]);

        $count_frauds = OrderFraudFACheckModel::objects()->filter(['order_id' => $order_model->orderid])->count();
        $count_fa_frauds = OrderFraudFACheckModel::objects()->filter(['order_id' => $order_model->orderid])->count();
        $ar_result = [
            'status' => false
        ];
        if (!($count_frauds && $count_fa_frauds)) {
            return $this->jsonResponse($ar_result);
        }
        $ar_settings = [];
        $time_for_order_in_mins = 10; //Setting: operators can be on this mage during this time.
        $current_time = time();
        /** @var OrderModel $order_model */
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
        if (!$you_have_right_to_change_order) {
            $operator_firstname = '';
            if ($operator_on_order = UserModel::objects()->get(['login' => $login_last_opened_or_saved])) {
                $operator_firstname = $operator_on_order->firstname ?: $operator_on_order->s_firstname ?: $operator_on_order->b_firstname;
            }
            $ar_settings['operator'] = [
                'firstname' => $operator_firstname,
                'loginLastOpened' => $login_last_opened_or_saved,
            ];
            $ar_settings['modifyOrder'] = 'Y';
        } else {
            $ar_settings['lock'] = ['status' => true, 'timeUnlocked' => date("G:i", $time_unlock)];
            $tmp_diff_time = time() - 60 * $time_for_order_in_mins;
            $count_locked_orders = OrderModel::objects()->filter(
                [
                    'login_last_opened_or_saved' => Xcart::app()->user->login,
                    'time_last_opened_or_saved__gt' => $tmp_diff_time
                ]
            )->count();
            if ($count_locked_orders > 1) {
                $ar_settings['locked_orders'] = $count_locked_orders;
            }
        }
        $ar_settings['status'] = FraudStatusModel::objects()->order('order_by')->valuesList(['code', 'name']);
        $ar_settings['lang'] = [
            'basement' => LanguageModel::objects()->get(['name' => 'lbl_fraud_check_expert_section'])->value
        ];
        $base_list = ['fraud_code', 'fraud_name', 'type', 'fraud_id', 'fraud_id'];
        $ar_settings['column_fn'] = FraudCheckColumnModel::objects()->filter(['type' => 'full_name'])->valuesList($base_list);
        $ar_settings['column_address'] = FraudCheckColumnModel::objects()->filter(['type' => 'address'])->valuesList($base_list);

        if (!empty($ar_settings)) {
            $ar_result['settings'] = $ar_settings;
        }

        $ar_answer = $this->getBaseAnswerOrder($order_model);
        $ar_fa_answer = $this->getAnswerFAOrder($order_model);
        if (!empty($ar_fa_answer) && !empty($ar_answer)) {
            $ar_result['answer'] = array_merge($ar_answer, $ar_fa_answer);
        }
        $ar_payment_answer = $this->getAnswerPaymentFrauds($order_model);
        if (!empty($ar_payment_answer)) {
            $ar_result['answer'] = array_merge($ar_result['answer'], ['payment' => $ar_payment_answer]);
        }

        $ar_result['status'] = true;
        $this->jsonResponse($ar_result);
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
        } catch (\Exception $exception) {
            $ar_result = ['status' => false, 'error' => $exception->getMessage()];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }

    public function getFAQuestionByType($type = ''): array
    {
        $ar_frauds = FraudFAQuestionModel::objects()->filter(['type' => $type])->order('order_by')
            ->valuesList(['f_fraud_id', 't_fraud_id', 'weight', 'template', 'question_id']);
        return $ar_frauds;
    }

    public function getAnswerPaymentFrauds(OrderModel $order_model): array
    {
        $ar_payment_frauds = [];
        $frauds_payment = OrderFraudCheckModel::objects()->filter([
            'orderid' => $order_model->orderid,
            'question__type' => FraudCheckModel::FRAUD_TYPE_PAY_PAL
        ]);
        if ($frauds_payment->count() > 0) {
            $oTransaction = $order_model->getFirstTransaction();
            $sTransactionReplaceText = '';
            $sPaymentMethodReplaceText = '';
            if ($oTransaction && $oPaymentMethod = PaymentMethodModel::objects()->get(['paymentid' => $oTransaction->paymentid])) {
                $sTransactionLink = str_replace('{{trans-id}}', $oTransaction->transaction_id, $oPaymentMethod->transaction_id_link);
                $sTransactionReplaceText = "<a target='_blank' href='{$sTransactionLink}' style='color:#1F08F8;'>Link to transaction</a>";
                $sPaymentMethodReplaceText = "{$oPaymentMethod->payment_method} ({$oPaymentMethod->transaction_link_anchor})";
            }
            /** @var OrderFraudCheckModel $answer_item */
            foreach ($frauds_payment as $answer_item) {
                $template = str_replace(
                    [
                        '{{link_to_paypal_transaction}}',
                        '{{shipping_address}}',
                        '{{payment_method}}',
                        '{{customer_email}}'
                    ],
                    [
                        $sTransactionReplaceText,
                        $order_model->getShippingAddressString(),
                        $sPaymentMethodReplaceText,
                        $order_model->email
                    ],
                    $answer_item->question->question_template_body
                );
                array_push($ar_payment_frauds, [
                    'template' => $template,
                    'fraud_result' => $answer_item->fraud_result,
                    'fraud_score' => $answer_item->fraud_score,
                    'question_id' => $answer_item->question_id,
                    'question_code' => $answer_item->question->question_code,
                    'question_auto' => $answer_item->question->auto,
                    'question_weight' => $answer_item->question->weight
                ]);
            }
            return $ar_payment_frauds;
        }
        return [];
    }

    public function getAnswerFAOrder(OrderModel $orderModel): array
    {
        $ar_anwer = ['full_name' => [], 'address' => []];
        /** @var OrderFraudFACheckModel $fraud */
        foreach (OrderFraudFACheckModel::objects()->filter(['order_id' => $orderModel->orderid]) as $fraud) {
            [$replace_template, $replace_value] = $this->getTemplateData($fraud);
            $data = [
                'question_id' => $fraud->question_id,
                'fraud_result' => $fraud->fraud_result,
                'fraud_score' => $fraud->fraud_score,
                'f_fraud_name' => $fraud->question->f_fraud->fraud_name,
                't_fraud_name' => $fraud->question->t_fraud->fraud_name,
                'question_weight' => $fraud->question->weight,
                'template' => str_replace($replace_template, $replace_value, $fraud->question->template)
            ];
            array_push($ar_anwer[$fraud->question->type], $data);
        }
        return $ar_anwer;
    }

    private function getTemplateData(OrderFraudFACheckModel $answer)
    {
        $ar_info = json_decode($answer->additional_info, true);
        $result = [];
        $code_list = [$answer->question->f_fraud->fraud_code, $answer->question->t_fraud->fraud_code];
        foreach ($code_list as $code) {
            $template = [];
            switch ($code) {
                case 'FN_CI':
                    $template = ['{{contact_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_SA':
                    $template = ['{{shipping_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_BA':
                    $template = ['{{billing_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_CH':
                    $template = ['{{card_owner_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_T_SA':
                case 'FN_T_BA':
                    $template = ['{{tenant_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_O_SA':
                case 'FN_O_BA':
                    $template = ['{{owner_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_TN':
                    $template = ['{{telephone_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'FN_EA':
                    $template = ['{{email_name}}' => $ar_info["value{$code}"]];
                    break;
                case 'SA':
                    $template = ['{{shipping_address}}' => $ar_info["value{$code}"]];
                    break;
                case 'BA':
                    $template = ['{{billing_address}}' => $ar_info["value{$code}"]];
                    break;
                case 'ORA_SA':
                case 'ORA_BA':
                    $template = ['{{owner_residence_address}}' => $ar_info["value{$code}"]];
                    break;
                case 'CSZ_TN':
                    $template = ['{{telephone_address}}' => $ar_info["value{$code}"]];
                    break;
                case 'CSZ-IP':
                    $template = ['{{ip_address}}' => $ar_info["value{$code}"]];
                    break;
            }
            $result = array_merge($result, $template);
        }
        return [array_keys($result), array_values($result)];
    }

    public function getBaseAnswerOrder(OrderModel $orderModel): array
    {
        $ar_res_answer = ['diagonal' => [], 'red_flags' => []];
        $base_answer_fraud = OrderFraudCheckModel::objects()->filter([
            'orderid' => $orderModel->orderid,
            'question__active' => 'Y',
            'question__type__in' => ['diagonal', 'red_flags']
        ]);
        $email_domain = $orderModel->getEmailDomain();
        $email_domain_temp = <<<HTML
<a target="_blank" href="//www.{$email_domain}" style="color: #1F08F8;">www.{$email_domain}</a>
HTML;
        $shipping_address = $orderModel->getShippingAddressString();
        $customer_email = $orderModel->email;
        $orders_full_names = "{$orderModel->s_firstname}<br />{$orderModel->b_firstname}<br />{$orderModel->firstname}";
        $orders_company_names = "{$orderModel->s_company}<br />{$orderModel->b_company}";
        /** @var OrderFraudCheckModel $answer */
        foreach ($base_answer_fraud as $answer) {
            $template = str_replace(
                [
                    '{{emails_domain}}',
                    '{{email_domain_website}}',
                    '{{shipping_address}}',
                    '{{customer_email}}',
                    '{{orders_full_names}}',
                    '{{orders_company_names}}'
                ],
                [
                    "@{$email_domain}",
                    $email_domain_temp,
                    $shipping_address,
                    $customer_email,
                    $orders_full_names,
                    $orders_company_names
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
                'question_weight' => $answer->question->weight
            ];
            array_push($ar_res_answer[$answer->question->type], $ar_answer);
        }
        return $ar_res_answer;
    }

    public function saveOrderFraudStatus(): void
    {
        $ar_result = ['status' => true];
        try {
            $post = Xcart::app()->request->post;
            $orderModel = OrderModel::objects()->get(['orderid' => $post->order_id]);
            $orderModel->fraud_status = $post->status;
            $orderModel->save();
        } catch (\Exception $exception) {
            $ar_result = [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }

    public function forceFraudCheck(int $order_id = null): void
    {
        $ar_result = ['status' => false];
        try {
            $order_model = OrderModel::objects()->get(['orderid' => $order_id]);
            if ($order_model instanceof OrderModel) {
                $order_model->orderFraudCheck();
                $ar_result['status'] = true;
            }
        } catch (\Exception $exception) {
            $ar_result = ['status' => false, 'error' => $exception->getMessage()];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }
}