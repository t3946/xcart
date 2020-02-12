<?php

namespace Modules\Order\Models;

use Modules\Core\Models\TelephoneAreaModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductHardResellModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class FraudCheckModel extends Model
{
    use AutoMetaTrait;

    private $result;
    private $order_fraud_model;

    public static function tableName()
    {
        return 'xcart_fraud_check';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'question_template_body' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ]
        ];
    }

    public function getCompiledBody(OrderModel $orderModel)
    {
        $oPaymentMethod = null;
        $phone = $orderModel->phone . ($orderModel->phone_ext ? " ext {$orderModel->phone_ext}" : '');
        if ($address = $orderModel->getAddressInfo()) {
            foreach ($address as $key => $a) {
                $addr = $a['address'][0] . (!empty($a['address'][1]) ? " {$a['address'][1]}" : '') . " {$a['city']} {$a['state']} {$a['zipcode']}";
                $addr = str_replace([' ', '#', '&'], ['+', '', 'and'], $addr);
                if ($key) {
                    $google_billing_address = $addr;
                } else {
                    $google_shipping_address = $addr;
                }
            }
        }
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();
        foreach (['fraud_Google_address_search_exclusions', 'fraud_Google_phone_search_exclusions', 'fraud_Google_email_search_exclusions'] as $exclusion) {
            if ($$exclusion = trim($config[$exclusion])) {
                $$exclusion = str_replace([',', ' '], ['+-', '+'], $$exclusion);
                $$exclusion = "+-{$$exclusion}";
            }
        }

        [$userinfo_area_code, $google_phone] = PhoneHelper::getGooglePhone($orderModel->phone, $orderModel->phone_ext);
        /** @var OrderTransactionModel $oTransaction */
        $oTransaction = $this->getFirstTransaction($orderModel);
        if ($customer_ip = $orderModel->getIp()) {
            if ($geo_litecity_location = GeoIpHelper::getGeoipLocation($customer_ip)) {
                $geoip_state = $geo_litecity_location->region;
                $geoip_address = $geo_litecity_location;
            }
        }
        if ($telephoneModel = TelephoneAreaModel::objects()->get(['area_code' => $userinfo_area_code])) {
            $areacode_state = $telephoneModel->state_code;
            $phone_area_code_state = "{$telephoneModel->area} ({$telephoneModel->state_code})";
            $phone_area_code_address = "{$telephoneModel->country_code}, {$telephoneModel->state_code}, {$telephoneModel->area}";
        }

        $email_domain_website = <<<HTML
<a target="_blank" href="//www.{$orderModel->getEmailDomain()}" style="color: #1F08F8;">www.{$orderModel->getEmailDomain()}</a>
HTML;
        $google_shipping_l = <<<HTML
<a target="_blank" href="https://www.google.com/#q={$google_shipping_address}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">Google shipping address</a>
HTML;
        $google_bill_l = <<<HTML
<a target="_blank" href="https://www.google.com/#q={$google_billing_address}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">Google billing address</a>
HTML;
        $google_email = <<<HTML
<a target="_blank" href="https://www.google.com/#q={$orderModel->email}{$fraud_Google_email_search_exclusions}" style="color: #1F08F8;">Google email</a>
HTML;
        $google_phone_l = <<<HTML
<a target="_blank" href="https://www.google.com/#q={$google_phone}{$fraud_Google_phone_search_exclusions}" style="color: #1F08F8;">Google phone</a>
HTML;
        $fs_phone = substr($orderModel->phone, 0, 3) . '-' . substr($orderModel->phone, 3, 3) . '-' . substr($orderModel->phone, 6);
        $fs_phone = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/{$fs_phone}" style="color: #1F08F8;">Fast People Search phone</a>
HTML;
        $fs_address = str_replace(' ', '-', $orderModel->s_address);
        $fastpeoplesearch_shipping = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/address/{$fs_address}_{$orderModel->s_zipcode}" style="color: #1F08F8;">Fast People Search shipping address</a>
HTML;
        $fb_address = str_replace(' ', '-', $orderModel->b_address);
        $fastpeoplelink = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/address/{$fb_address}_{$orderModel->b_zipcode}" style="color: #1F08F8;">Fast People Search billing address</a>
HTML;
        $sTransactionReplaceText = '';
        $sPaymentMethodReplaceText = '';
        if ($oTransaction && $oPaymentMethod = PaymentMethodModel::objects()->get(['paymentid' => $oTransaction->paymentid])) {
            $sTransactionLink = str_replace('{{trans-id}}', $oTransaction->transaction_id, $oPaymentMethod->transaction_id_link);
            $sTransactionReplaceText = "<a target='_blank' href='{$sTransactionLink}' style='color:#1F08F8;'>Link to transaction</a>";
            $sPaymentMethodReplaceText = "{$oPaymentMethod->payment_method} ({$oPaymentMethod->transaction_link_anchor})";
        }
        $google_shipping_link = <<<HTML
<a target="_blank" href="http://www.spokeo.com/search?q={$google_shipping_address}" style="color: #1F08F8;">Spokeo shipping address</a>
HTML;
        $google_billing_link = <<<HTML
<a target="_blank" href="http://www.spokeo.com/search?q={$google_billing_address}" style="color: #1F08F8;">Spokeo billing address</a>
HTML;
        $orders_full_names = "{$orderModel->s_firstname}<br />{$orderModel->b_firstname}<br />{$orderModel->firstname}";
        $orders_company_names = "{$orderModel->s_company}<br />{$orderModel->b_company}";

        if ($aProductLinks = $this->getProductList($orderModel)) {
            $links_to_ordered_products = implode('<br>', $aProductLinks);
        }

        $question_template_body = str_replace(
            ['{{customer_phone}}', '{{google_shipping}}', '{{google_billing}}', '{{google_email}}', '{{google_phone}}', '{{fastpeoplesearch_phone}}', '{{fastpeoplesearch_shipping}}', '{{emails_domain}}', '{{link_to_paypal_transaction}}', '{{payment_method}}', '{{spokeo_shipping}}', '{{spokeo_billing}}', '{{links_to_ordered_products}}', '{{billing_address}}', '{{shipping_address}}', '{{orders_full_names}}', '{{shipping_state}}', '{{billing_state}}', '{{geoip_state}}', '{{phone_area_code_state}}', '{{customer_email}}', '{{areacode_state}}', '{{geoip_address}}', '{{phone_area_code_address}}', '{{orders_company_names}}', '{{email_domain_website}}', '{{fastpeoplesearch_billing}}'],
            [$phone, $google_shipping_l, $google_bill_l, $google_email, $google_phone_l, $fs_phone, $fastpeoplesearch_shipping, "@{$orderModel->getEmailDomain()}"
                , $sTransactionReplaceText, $sPaymentMethodReplaceText, $google_shipping_link, $google_billing_link, $links_to_ordered_products ?? '', $orderModel->getBillingAddressString(), $orderModel->getShippingAddressString(), $orders_full_names, $orderModel->s_state, $orderModel->b_state, $geoip_state, $phone_area_code_state, $orderModel->email, $areacode_state, $geoip_address, $phone_area_code_address, $orders_company_names, $email_domain_website, $fastpeoplelink], $this->question_template_body);

        return $question_template_body;
    }

    public function getProductList(OrderModel $orderModel): array
    {
        foreach ($orderModel->detail_models as $detailModel) {
            /** @var ProductHardResellModel $hardResellModel */
            if ($hardResellModel = ProductHardResellModel::objects()->get(['product_id' => $detailModel->productid])) {
                switch ($hts = $hardResellModel->getHardToResellStatus()) {
                    case ProductHardResellModel::HARD_TO_RESELL_UNKNOWN :
                        $aProductLinks[] = <<<HTML
<a href='{$detailModel->getAbsoluteUrl(true)}' target='_blank' style='color: #1F08F8;'>{$detailModel->productcode}</a>
HTML;
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_YES:
                        $aProductLinks[] = <<<HTML
<span style='background-color: #D9EAD3;'>{$detailModel->productcode}</span> HARD TO RESELL
HTML;
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_NO:
                        $aProductLinks[] = <<<HTML
<span style='background-color: #F4CCCC;'>{$detailModel->productcode}</span> EASY TO RESELL
HTML;
                        break;
                }
            } else {
                $aProductLinks[] = <<<HTML
<a href='{$detailModel->getAbsoluteUrl(true)}' target='_blank' style='color: #1F08F8;'>{$detailModel->productcode}</a>
HTML;
            }
        }
        return $aProductLinks ?? [];
    }

    public function getImportanceFactor()
    {
        return explode(',', str_replace(' ', '', $this->importance_factor));
    }

    public function getScore(OrderModel $order, $recalc = true)
    {
        if ($result = $this->getMethodResult($order, $recalc)) {
            [$fraud_result, $bare_fraud_score, $additional_info] = $result;
            $importance_factor_arr = $this->getImportanceFactor();
            switch ($fraud_result) {
                case 'negative':
                    $selected_importance_factor = $importance_factor_arr[0];
                    break;
                case 'positive':
                    $selected_importance_factor = $importance_factor_arr[2];
                    break;
                default:
                    $selected_importance_factor = $importance_factor_arr[1];
            }
            return round($bare_fraud_score * $selected_importance_factor, 2);
        }
        return null;
    }

    public function getMethodResult(OrderModel $order, $recalc = true)
    {
        if ($this->result === null) {
            if (!$recalc && $order_fraud = OrderFraudCheckModel::objects()->get(['orderid' => $order, 'question_code' => $this->question_code])) {
                $this->result = [$order_fraud->fraud_result, $order_fraud->bare_fraud_score, $order_fraud->additional_info, $order_fraud->manual_action];
                return $this->result;
            }
            $method = "score{$this->question_code}";
            if (method_exists($this, $method)) {
                $this->result = $this->$method($order);
            }
        }
        return $this->result;
    }

    protected function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE(OrderModel $order): array
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();
        if (stripos($config['fraud_domains_free_email_provider'], $order->getEmailDomain()) !== false) {
            $fraud_score = 1;
            $fraud_result = 'negative';
            $manual_action = 'N';
        }
        return [$fraud_result, round($fraud_score, 2), null, $manual_action];
    }

    private function getFirstTransaction(OrderModel $order)
    {
        return $order->transactions->limit(1)->order(['date'])->get();
    }

    protected function scoreMANUAL_XPAY_AVS(OrderModel $order)
    {
        $score = -1;
        $fraud_result = 'negative';
        $manual_action = 'N';

        if (($oTransaction = $this->getFirstTransaction($order)) && $cv = $oTransaction->transaction_response['cardValidation'] ?? null) {
            if ((int)$cv['avs_z'] === 1 && (int)$cv['avs_c'] === 1 && (int)$cv['avs_a'] && $cv['cvv_code'] === 'M') {
                $score = 1;
                $fraud_result = 'positive';
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $score, null, $manual_action];
    }

    protected function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE_FOR_SHIPPING_ADDRESS(OrderModel $order): array
    {
        return $this->scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE($order);
    }

    private function isPaypalPyment(OrderModel $order): bool
    {
        /** @var OrderTransactionModel $oTransaction */
        return !(($oTransaction = $this->getFirstTransaction($order)) &&
            ($oPaymentMethod = $oTransaction->payment_method_model) && in_array((int)$oPaymentMethod->paymentid, [21, 102], true));
    }

    protected function scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING(OrderModel $order)
    {
        $fraud_result = 'negative';
        $fraud_score = 1;
        $manual_action = 'N';
        /** @var OrderTransactionModel $oTransaction */
        if ($this->isPaypalPyment($order) && $oTransaction = $this->getFirstTransaction($order)) {
            if ($oTransaction->transaction_response['address_country_code'] === $order->s_country &&
                $oTransaction->transaction_response['address_state'] === $order->s_state &&
                $oTransaction->transaction_response['address_city'] === $order->s_city &&
                $oTransaction->transaction_response['address_zip'] === $order->s_zipcode &&
                stripos($order->s_address, $oTransaction->transaction_response['address_street']) !== false) {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
        }
        return [$fraud_result, $fraud_score, null, $manual_action];
    }

    protected function scoreMANUAL_PAYPAL_SHIPPING_CONFIRMED(OrderModel $order)
    {
        $fraud_result = 'negative';
        $fraud_score = 1;
        $manual_action = 'N';
        [$r] = $this->scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING($order);
        if ($r === 'positive' && $this->isPaypalPyment($order) && $oTransaction = $this->getFirstTransaction($order)) {
            if ($oTransaction->transaction_response['address_status'] === 'confirmed') {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $fraud_score,  null, $manual_action];
    }

    protected function scoreMANUAL_PAYPAL_FULLNAME_VERIFIED(OrderModel $order)
    {
        $fraud_result = 'negative';
        $fraud_score = 1;
        $manual_action = 'N';
        if ($this->isPaypalPyment($order) && $oTransaction = $this->getFirstTransaction($order)) {
            if ($oTransaction->transaction_response['payer_status'] === 'verified') {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $fraud_score, null, $manual_action];
    }

    protected function scoreMANUAL_PAYPAL_EMAIL_EQUAL_TO_ORDER(OrderModel $order)
    {
        $fraud_result = 'negative';
        $fraud_score = 1;
        $manual_action = 'N';
        if ($this->isPaypalPyment($order) && $oTransaction = $this->getFirstTransaction($order)) {
            if ($oTransaction->transaction_response['payer_email'] === $order->email) {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $fraud_score, null, $manual_action];
    }

    protected function scoreMANUAL_PAYPAL_FULLNAME_EQUAL_TO_ORDER(OrderModel $order)
    {
        $fraud_result = 'negative';
        $fraud_score = 1;
        $manual_action = 'N';
        if ($this->isPaypalPyment($order) && $oTransaction = $this->getFirstTransaction($order)) {
            $ar = array_unique([$order->s_firstname, $order->b_firstname, $order->firstname]);
            if (count($ar) === 1) {
                $name = reset($ar);
                if (stripos($name, $oTransaction->transaction_response['first_name']) !== false &&
                    stripos($name, $oTransaction->transaction_response['last_name']) !== false) {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
            }
            if (stripos($order->s_company, $oTransaction->transaction_response['first_name']) !== false &&
                stripos($order->s_company, $oTransaction->transaction_response['last_name']) !== false) {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
            if (stripos($order->b_company, $oTransaction->transaction_response['first_name']) !== false &&
                stripos($order->b_company, $oTransaction->transaction_response['last_name']) !== false) {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $fraud_score, null, $manual_action];
    }

    private static function correct($field)
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace("/[^\w\s\[,.\-\/\@_\]]/", "", $field);
        $field = strtoupper($field);
        return $field;
    }

    protected function scoreCHECK_B_S(OrderModel $order): array
    {

        [$s_address, $b_address] = $order->getAddressInfo();
        $full_address_s = "{$s_address['address'][0]}-{$s_address['address'][1]}-{$order->s_city}-{$order->s_state}-{$order->s_country}-{$order->s_zipcode}";
        $full_address_s = self::correct($full_address_s);

        $full_addresb_b = "{$b_address['address'][0]}-{$b_address['address'][1]}-{$order->b_city}-{$order->b_state}-{$order->b_country}-{$order->b_zipcode}";
        $full_addresb_b = self::correct($full_addresb_b);

        if ($full_address_s === $full_addresb_b) {
            $fraud_score = 1;
            $fraud_result = 'positive';
        } else {
            $fraud_score = -1;
            $fraud_result = 'negative';
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreIS_EMAIL_DOMAIN_FREE(OrderModel $order): array
    {

        $email = $order->email;
        $fraud_score = 1;
        $fraud_result = 'positive';

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();

        if ($fraud_domains_free_email_provider_arr = explode(',', $config['fraud_domains_free_email_provider'])) {
            foreach ($fraud_domains_free_email_provider_arr as $k => $v) {
                $domain = '@' . trim($v);
                if (stripos($email, $domain) !== false) {
                    $fraud_score = -1;
                    $fraud_result = 'negative';
                    break;
                }
            }
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreCHECK_EMAIL_VS_NAME(OrderModel $order): array
    {

        $fraud_score = -1;
        $fraud_result = 'negative';
        $email_arr = explode('@', $order->email);
        $email_1 = strtoupper($email_arr[0]);

        if (($firstname_arr = explode(" ", self::correct($order->firstname))) && $email_1) {
            foreach ($firstname_arr as $k => $v) {
                $name = trim($v);
                if ($name && stripos($email_1, $name) !== false) {
                    $fraud_score = 1;
                    $fraud_result = 'positive';
                    break;
                }
            }
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreORDER_FULLNAMES(OrderModel $order): array
    {
        $fraud_score = -1;
        $names = [];

        if ($firstname = self::correct($order->firstname)) {
            $names[] = $firstname;
        }

        if ($b_firstname = self::correct($order->b_firstname)) {
            $names[] = $b_firstname;
        }

        if ($s_firstname = self::correct($order->s_firstname)) {
            $names[] = $s_firstname;
        }
        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            $fraud_score = 1 / $count_names;
            if ($fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreCHECK_STATES(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $geoip_state = $areacode_state = '';

        $s_state = self::correct($order->s_state);
        $b_state = self::correct($order->b_state);

        if ($geo_litecity_location = GeoIpHelper::getGeoipLocation($order->getIp())) {
            $geoip_state = self::correct($geo_litecity_location->region);
        }

        $userinfo_phone = str_replace([' ', '(', ')'], '', $order->phone);
        $userinfo_area_code = trim(substr($userinfo_phone, 0, 3));

        if ($telephoneModel = TelephoneAreaModel::objects()->get(['area_code' => $userinfo_area_code])) {
            $areacode_state = $telephoneModel->state_code;
        }

        if ($s_state === $b_state && $b_state === $geoip_state && $s_state === $areacode_state) {
            $fraud_score = 1;
            $fraud_result = 'positive';
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreGEOIP_CITY_VS_B_S(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';

        $s_city = self::correct($order->s_city);
        $b_city = self::correct($order->b_city);

        if (($customer_ip = $order->getIp()) && $geo_litecity_location = GeoIpHelper::getGeoipLocation($customer_ip)) {
            $geoip_city = self::correct($geo_litecity_location->city);
        }

        $names = array_unique([$s_city, $b_city, $geoip_city ?? null]);

        if (($count_names = count($names)) > 0) {
            $fraud_score = 1 / $count_names;
            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            }
        }

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreCHECK_OK_ORDERS_FOR_EMAIL(OrderModel $order): array
    {
        $fraud_score = 0;
        $fraud_result = 'neutral';

        $orders = OrderModel::objects()->filter(['email' => $order->email, 'groups__dc_status__in' => ['S', 'G'], 'date__lte' => $order->date])->group(['orderid'])->order(['-orderid']);
        if (($total_items = $orders->count()) > 0) {
            $additional_info = $orders->all();
        }

        $time_condition = $order->date - 60 * 60 * 24 * 1;
        $orders = OrderModel::objects()->filter(['email' => $order->email, 'groups__dc_status__in' => ['S', 'G'], 'date__lte' => $time_condition])->group(['orderid'])->order(['-orderid']);

        if (($total_items_min_day = $orders->count()) > 0) {
            if (($fraud_score = $total_items_min_day / $total_items) > 0) {
                $fraud_result = 'positive';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_FULLNAMES_FOR_EMAIL(OrderModel $order): array
    {

        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = [];

        $orders = OrderModel::objects()->select(['firstname', 'orderid', 'order_prefix', 'date'])->distinct()
            ->filter(['email' => $order->email, 'date__lte' => $order->date])->group(['firstname'])->order(['-orderid']);

        if (($total_items = $orders->count()) > 0) {
            $additional_info = $orders->all();
            $fraud_score = 1 / $total_items;
            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_IP(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $customer_ip = $order->getIp();
        $order_date = $order->date;
        $time_condition = $order_date - 60 * 60 * 24 * 7;

        $qs = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date]);

        $additional_info = $names = $full_address_names = [];

        /** @var OrderModel $v */
        foreach ($qs as $k => $v) {
            if (($ip = $v->getIp()) && $customer_ip === $ip) {
                $full_address_s = "{$v->s_address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}";
                $full_address_s = self::correct($full_address_s);
                $names[$v->orderid] = $full_address_s;
                $full_address_names[$v->orderid] = $v;
            }
        }

        $names = array_unique($names);
        $count_names = count($names);

        if ($count_names > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }

            $fraud_score = 1 / $count_names;

            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_BILLINGS_FOR_IP(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $customer_ip = $order->getIp();
        $order_date = $order->date;
        $time_condition = $order_date - 60 * 60 * 24 * 7;

        $qs = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'extra_model__ip__contains' => $customer_ip]);

        $additional_info = $names = $full_address_names = [];

        /** @var OrderModel $v */
        foreach ($qs as $k => $v) {
            if (($ip = $v->getIp()) && $customer_ip === $ip) {
                $full_address_s = "{$v->b_address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}";
                $full_address_s = self::correct($full_address_s);
                $names[$v->orderid] = $full_address_s;
                $full_address_names[$v->orderid] = $v;
            }
        }

        $names = array_unique($names);
        $count_names = count($names);

        if ($count_names > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }

            $fraud_score = 1 / $count_names;

            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_PHONE(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';

        $phone = $order->phone;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $order_date = $order->date;

        $time_condition = $order_date - 60 * 60 * 24 * 180;
        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'phone' => $phone]);
        $additional_info = $names = $full_address_names = [];

        foreach ($orders as $k => $v) {
            if ($phone === preg_replace('/[^0-9]/', '', $v->phone)) {
                $full_address_s = "{$v->s_address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}";
                $full_address_s = self::correct($full_address_s);
                $names[$v->orderid] = $full_address_s;
                $full_address_names[$v->orderid] = $v;
            }
        }

        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            foreach ($names as $k => $v) {
                $s_address_arr = explode("\n", $full_address_names[$k]['s_address']);
                $full_address_names[$k]['s_address1'] = trim($s_address_arr[0]);
                $full_address_names[$k]['s_address2'] = trim($s_address_arr[1]);
                $additional_info[] = $full_address_names[$k];
            }
            $fraud_score = 1 / $count_names;
            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_BILLINGSS_FOR_PHONE(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';

        $phone = $order->phone;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $order_date = $order->date;

        $time_condition = $order_date - 60 * 60 * 24 * 180;
        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'phone' => $phone]);
        $additional_info = $names = $full_address_names = [];

        foreach ($orders as $k => $v) {
            if ($phone === preg_replace('/[^0-9]/', '', $v->phone)) {
                $full_address_s = "{$v->b_address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}";
                $full_address_s = self::correct($full_address_s);
                $names[$v->orderid] = $full_address_s;
                $full_address_names[$v->orderid] = $v;
            }
        }

        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }
            $fraud_score = 1 / $count_names;
            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = $names = $full_address_names = [];
        $time_condition = $order->date - 60 * 60 * 24 * 180;

        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'email' => $order->email])->order(['-orderid']);

        foreach ($orders as $k => $v) {
            $full_address_s = self::correct("{$v->s_address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}");
            $names[$v->orderid] = $full_address_s;
            $full_address_names[$v->orderid] = $v;
        }
        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }

            $fraud_score = 1 / $count_names;

            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_BILLINGS_FOR_EMAIL(OrderModel $order): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = $names = $full_address_names = [];
        $time_condition = $order->date - 60 * 60 * 24 * 180;

        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'email' => $order->email])->order(['-orderid']);

        foreach ($orders as $k => $v) {
            $full_address_s = self::correct("{$v->b_address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}");
            $names[$v->orderid] = $full_address_s;
            $full_address_names[$v->orderid] = $v;
        }
        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }

            $fraud_score = 1 / $count_names;

            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_CARD(OrderModel $order): array
    {
        $fraud_score = 0;
        $fraud_result = 'neutral';
        $additional_info = [];

        return [$fraud_result, $fraud_score, $additional_info];
    }

    protected function scoreCHECK_DIFFERENT_BILLING_FOR_SHIPPING(OrderModel $order)
    {
        global $sql_tbl;

        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = $names = $full_address_names = [];

        $full_address_s = self::correct("{$order->s_address}-{$order->s_city}-{$order->s_state}-{$order->s_country}-{$order->s_zipcode}");

        $time_condition = $order->date - 60 * 60 * 24 * 180;
        $orders = OrderModel::objects()->filter([
            'date__gte' => $time_condition,
            'date__lte' => $order->date,
            's_address' => $order->s_address,
            's_city' => $order->s_city,
            's_state' => $order->s_state,
            's_country' => $order->s_country,
            's_zipcode' => $order->s_zipcode,
        ]);

        foreach ($orders as $k => $v) {
            $tmp_full_address_s = self::correct("{$v->s_address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}");
            if ($full_address_s == $tmp_full_address_s) {
                $tmp_full_address_b = self::correct("{$v->b_address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}");
                $names[$v->orderid] = $tmp_full_address_b;
                $full_address_names[$v->orderid] = $v;
            }
        }

        $names = array_unique($names);

        if (($count_names = count($names)) > 0) {
            foreach ($names as $k => $v) {
                $additional_info[] = $full_address_names[$k];
            }

            $fraud_score = 1 / $count_names;

            if ((int)$fraud_score === 1) {
                $fraud_result = 'positive';
            } elseif ($fraud_score < 1) {
                $fraud_result = 'negative';
            }
        }

        return [$fraud_result, round($fraud_score, 2), $additional_info];
    }

    protected function scoreCHECK_TOTAL(OrderModel $order)
    {
        $order_total_div = 50 / $order->total;

        if ($order_total_div >= 1) {
            $fraud_result = 'positive';
        } else {
            $fraud_result = 'negative';
        }

        $num = 50 - $order->total;

        if (!$num) {
            $num = 1;
        }

        $sign = $num / abs($num);

        $fraud_score = ((max(50, $order->total) / min(50, $order->total)) - 1) * $sign;

        return [$fraud_result, round($fraud_score, 2), null];
    }

    protected function scoreCHECK_SHIPPING_ADDRESS_LINE2(OrderModel $order)
    {

        $fraud_score = 1;
        $fraud_result = 'positive';

        [$shipping] = $order->getAddressInfo();

        if (isset($shipping['address'][1]) || preg_match('/\bApartment\b|\bApt\b|\bSuite\b|\bSte\b|\bUnit\b|#|\d-\d/i', $shipping['address'][0])) {
            $fraud_score = -1;
            $fraud_result = 'negative';
        }

        return [$fraud_result, $fraud_score, null];
    }

    protected function scoreCHECK_PURCHASE_ORDER(OrderModel $order)
    {

        $fraud_score = -1;
        $fraud_result = 'negative';

        if ((int)$order->paymentid === 2) {
            $fraud_score = 1;
            $fraud_result = 'positive';
        }

        return [$fraud_result, $fraud_score, null];
    }

    public function scoreMANUAL_IS_ORDER_ITEMS_EASY_TO_SELL(OrderModel $order)
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;
        $maxOrderPriceAmount = 0;
        foreach ($order->detail_models as $detailModel) {
            $maxOrderPriceAmount = max($detailModel->price * $detailModel->amount, $maxOrderPriceAmount);
            if ($hardResellModel = ProductHardResellModel::objects()->get(['product_id' => $detailModel->productid])) {
                switch ($hts = $hardResellModel->getHardToResellStatus()) {
                    case ProductHardResellModel::HARD_TO_RESELL_UNKNOWN :
                        $hard[] = 'neutral';
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_YES:
                        $hard[] = 'positive';
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_NO:
                        $hard[] = 'negative';
                        break;
                }
            }
        }
        $hard = array_unique($hard);
        if (count($hard) === 1) {
            $fraud_result = reset($hard);
        }
        if (!(($order->total > 50) || ($maxOrderPriceAmount > 10))) {
            $fraud_result = 'positive';
        }
        switch ($fraud_result) {
            case 'neutral':
                $fraud_score = 0;
                break;
            case 'positive':
                $manual = 'Y';
                $fraud_score = 1;
                break;
            case 'negative':
                $manual = 'N';
                $fraud_score = -1;
                break;
        }

        return [$fraud_result, $fraud_score, null, $manual ?? null];
    }

    public function getOrderFraudCheck($orderModel)
    {
        if ($this->order_fraud_model === null) {
            $this->order_fraud_model = OrderFraudCheckModel::objects()->get(['orderid' => $orderModel->orderid, 'question_code' => $this->question_code]);
        }
        return $this->order_fraud_model;
    }

    public function getManualAction(OrderModel $orderModel)
    {
        if ($this->auto !== 'Y') {
            if ($of = $this->getOrderFraudCheck($orderModel)) {
                return $of->manual_action;
            }


        }
        return null;
    }

}