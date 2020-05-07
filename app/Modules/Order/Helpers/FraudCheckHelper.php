<?php


namespace Modules\Order\Helpers;


use GuzzleHttp\Client;
use Modules\Core\Models\TelephoneAreaModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductHardResellModel;
use Modules\Order\Models\FraudCheckModel;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class FraudCheckHelper
{
    private const MELISSA_KEY = 'lBRkrbaK8DVVghZCwkUO2k**nSAcwXpxhQ0PC2lXxuDAZ-**';

    public static function addressAbbreviationsPrepare($field)
    {
        $arr = [
            'Apt' => 'Apartment',
            'Ave' => 'Avenue',
            'Blvd' => 'Boulevard',
            'Bldg' => 'Building',
            'Ctr' => ['Center', 'Centers'],
            'Cir' => ['Circle', 'Circles'],
            'Ct' => 'Court',
            'Dr' => 'Drive',
            'E' => 'East',
            'Expy' => 'Expressway',
            'Ext' => 'Extension',
            'Ft' => 'Fort',
            'Fwy' => 'Freeway',
            'Hts' => ['Height', 'Heights'],
            'Hwy' => 'Highway',
            'Is' => 'Island',
            'Jct' => 'Junction',
            'Ln' => 'Lane',
            'Mt' => ['Mount', 'Mountain'],
            'N' => 'North',
            'NE' => 'Northeast',
            'NW' => 'Northwest',
            'Pky' => 'Parkway',
            'Pl' => 'Place',
            'PO' => 'Post Office',
            'Rd' => 'Road',
            'RD' => 'Rural Delivery',
            'RR' => 'Rural Route',
            'St' => ['Saint', 'Street'],
            'S' => 'South',
            'SE' => 'Southeast',
            'SW' => 'Southwest',
            'Spg' => 'Spring',
            'Spgs' => 'Springs',
            'Sq' => ['Square', 'Squares'],
            'Ste' => 'Suite',
            'Ter' => 'Terrace',
            'Tpke' => 'Turnpike',
            'W' => 'West',
        ];
        foreach ($arr as $key => $val) {
            $a = [$key];
            if (!is_array($val)) {
                $a[] = $val;
            } else {
                array_push($a, ...$val);
            }
            $b = array_map(static function ($e) {
                $e = strtoupper($e);
                return "\b{$e}\b";
            }, $a);
            $search = implode('|', $b);
            $replacement = '(' . implode('|', array_map('strtoupper', $a)) . ')';
            $field = preg_replace("/{$search}/", $replacement, $field);
        }
        return $field;
    }

    public static function correct($field): string
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace("/[^\w\s\[,.\-\/@_\]]/", '', $field);
        $field = strtoupper($field);
        return $field;
    }

    public static function correctAddress($field): string
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace('/[\[,.-\/@_\]]/', '', $field);
        $field = strtoupper($field);
        return $field;
    }

    public static function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE(OrderModel $order, FraudCheckModel $fraud): array
    {
        /** @var SiteModel $site */
        if (($app = Xcart::app()) && $site = $app->getModule('Sites')->getSite()) {
            $config = $site->getGlobalConfig();
            if (stripos($config['fraud_domains_free_email_provider'], $order->getEmailDomain()) !== false) {
                $fraud_score = 1;
                $fraud_result = 'negative';
                $manual_action = 'N';
            }
        }

        return [$fraud_result, round($fraud_score, 2), null, $manual_action];
    }

    public static function scoreMANUAL_XPAY_AVS(OrderModel $order, FraudCheckModel $fraud): array
    {
        $score = -1;
        $fraud_result = 'negative';
        $manual_action = 'N';
        $processor = '';

        if (($oTransaction = $fraud->getFirstTransaction($order))) {
            switch ($processor = $oTransaction->payment_method_model->processor->processor_name) {
                case 'PayPal':
                    if ($cv = $oTransaction->transaction_response['cardValidation'] ?? null) {
                        if ((int)$cv['avs_z'] === 1 && (int)$cv['avs_c'] === 1 && (int)$cv['avs_a'] && $cv['cvv_code'] === 'M') {
                            $score = 1;
                            $fraud_result = 'positive';
                            $manual_action = 'Y';
                        }
                    }
                    /** PayPal VT */
                    if ($txns = $oTransaction->transaction_response['transactions'] ?? null) {
                        $txn = reset($txns);
                        if ($rtxns = $txn['related_resources']) {
                            $rtxn = reset($rtxns);
                            if (($cv = $rtxn['authorization']['processor_response'] ?? null) && $cv['avs_code'] === 'D' && $cv['cvv_code'] === 'M') {
                                $score = 1;
                                $fraud_result = 'positive';
                                $manual_action = 'Y';
                            }
                        }
                    }
                    if (isset($oTransaction->transaction_response['advinfo'])) {
                        $cv = array_merge($cv, $oTransaction->transaction_response['advinfo']);
                    }


                    break;
                case 'BluePay':
                    if ($cv = $oTransaction->transaction_response['advinfo'] ?? null) {
                        if (/*$cv['CVV'] === 'CVV2 - Match' && */ in_array($cv['AVS'], ['Street and Zip match', 'Zip match 5, street match'], true)) {
                            $score = 1;
                            $fraud_result = 'positive';
                            $manual_action = 'Y';
                        }
                    }
                    break;
            }
            if (isset($oTransaction->transaction_response['maskedCardData'])) {
                $cv = array_merge($cv, $oTransaction->transaction_response['maskedCardData']);
            }
        }
        return [$fraud_result, $score, $cv ?? ['Payment processor' => $processor], $manual_action];
    }

    public static function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE_FOR_SHIPPING_ADDRESS(OrderModel $order, FraudCheckModel $fraud): array
    {
        return self::scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE($order, $fraud);
    }

    public static function scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;
        /** @var OrderTransactionModel $oTransaction */
        if ($fraud->isPaypalPayment($order) && $oTransaction = $fraud->getFirstTransaction($order)) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
            $o_address = self::addressAbbreviationsPrepare(self::correctAddress($order->s_address));
            $p_address = self::correctAddress($oTransaction->transaction_response['address_street']);
            $o_address_replaces = str_replace(' ', '\s?', $o_address);
            if ($oTransaction->transaction_response['address_country_code'] === $order->s_country &&
                $oTransaction->transaction_response['address_state'] === $order->s_state &&
                self::correctAddress($oTransaction->transaction_response['address_city']) === self::correctAddress($order->s_city) &&
                self::correctAddress($oTransaction->transaction_response['address_zip']) === self::correctAddress($order->s_zipcode) &&
                preg_match("/{$o_address_replaces}/", $p_address, $mm)) {
                $fraud_result = 'positive';
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        return [$fraud_result, $fraud_score, ['o_address' => $o_address, 'p_address' => $p_address], $manual_action];
    }

    public static function scoreMANUAL_PAYPAL_SHIPPING_CONFIRMED(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;

        if ($fraud->isPaypalPayment($order)) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
            [$r] = self::scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING($order, $fraud);
            if ($r === 'positive' && $oTransaction = $fraud->getFirstTransaction($order)) {
                if ($oTransaction->transaction_response['address_status'] === 'confirmed') {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
            }
        }
        return [
            $fraud_result,
            $fraud_score,
            ['MANUAL_PAYPAL_SHIPPING_EQUAL_BILLING' => $r, 'address_status' => $oTransaction->transaction_response['address_status'] ?? ''],
            $manual_action
        ];
    }

    public static function scoreMANUAL_IS_FREIGHT_FORWARDER_FOUND(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;

        /** @var OrderFraudCheckModel $orderFraud */
        if ($orderFraudShipping = OrderFraudCheckModel::objects()->get(['orderid' => $order->orderid, 'question_code' => 'MANUAL_GOOGLE_SHIPPING_1'])) {
            $r = $orderFraudShipping->fraud_result;
            if ($r === 'positive') {
                $fraud_result = $r;
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }
        if ($orderFraudBilling = OrderFraudCheckModel::objects()->get(['orderid' => $order->orderid, 'question_code' => 'MANUAL_GOOGLE_BILLING_1'])) {
            $r2 = $orderFraudBilling->fraud_result;
            if ($r2 === 'positive') {
                $fraud_result = $r2;
                $fraud_score = 1;
                $manual_action = 'Y';
            }
        }

        if (($info = $orderFraudShipping->additional_info) && $info['Commercial_Mail_Receiving_Agency']) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
        }

        if ($fraud_result !== 'negative' && ($info = $orderFraudBilling->additional_info) && $info['Commercial_Mail_Receiving_Agency']) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
        }

        return [
            $fraud_result,
            $fraud_score,
            [
                'MANUAL_GOOGLE_SHIPPING_1' => $r ?? '',
                'MANUAL_GOOGLE_BILLING_1' => $r2 ?? '',
                'Commercial_Mail_Receiving_Agency' => $info['Commercial_Mail_Receiving_Agency']
            ],
            $manual_action
        ];
    }

    public static function scoreMANUAL_PAYPAL_FULLNAME_VERIFIED(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;

        if ($fraud->isPaypalPayment($order)) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
            $payer_status = '';
            if ($oTransaction = $fraud->getFirstTransaction($order)) {
                $payer_status = $oTransaction->transaction_response['payer_status'];
                if ($payer_status === 'verified') {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
            }
        }
        return [$fraud_result, $fraud_score, ['Payer status' => $payer_status], $manual_action];
    }

    public static function scoreMANUAL_PAYPAL_EMAIL_EQUAL_TO_ORDER(OrderModel $order, FraudCheckModel $fraud)
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;

        if ($fraud->isPaypalPayment($order)) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
            $payer_email = '';
            if ($oTransaction = $fraud->getFirstTransaction($order)) {
                $payer_email = $oTransaction->transaction_response['payer_email'];
                if ($oTransaction->transaction_response['payer_email'] === $order->email) {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
            }
        }

        return [$fraud_result, $fraud_score, ['Payer email' => $payer_email], $manual_action];
    }

    public static function scoreMANUAL_PAYPAL_FULLNAME_EQUAL_TO_ORDER(OrderModel $order, FraudCheckModel $fraud)
    {
        $fraud_result = 'neutral';
        $fraud_score = 0;

        if ($fraud->isPaypalPayment($order)) {
            $fraud_result = 'negative';
            $fraud_score = 1;
            $manual_action = 'N';
            $first_name = $last_name = '';

            if ($oTransaction = $fraud->getFirstTransaction($order)) {
                $first_name = $oTransaction->transaction_response['first_name'];
                $last_name = $oTransaction->transaction_response['last_name'];
                $ar = array_unique([$order->s_firstname, $order->b_firstname, $order->firstname]);
                if (count($ar) === 1) {
                    $name = reset($ar);
                    if (stripos($name, $first_name) !== false &&
                        stripos($name, $last_name) !== false) {
                        $fraud_result = 'positive';
                        $fraud_score = 1;
                        $manual_action = 'Y';
                    }
                }
                if (stripos($order->s_company, $first_name) !== false &&
                    stripos($order->s_company, $last_name) !== false) {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
                if (stripos($order->b_company, $first_name) !== false &&
                    stripos($order->b_company, $last_name) !== false) {
                    $fraud_result = 'positive';
                    $fraud_score = 1;
                    $manual_action = 'Y';
                }
            }
        }

        return [$fraud_result, $fraud_score, ['Payer full name' => "{$first_name} {$last_name}"], $manual_action];
    }

    public static function scoreCHECK_B_S(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreIS_EMAIL_DOMAIN_FREE(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreCHECK_EMAIL_VS_NAME(OrderModel $order, FraudCheckModel $fraud): array
    {

        $fraud_score = -1;
        $fraud_result = 'negative';
        $email_arr = explode('@', $order->email);
        $email_1 = strtoupper($email_arr[0]);

        if (($firstname_arr = explode(' ', FraudCheckHelper::correct($order->firstname))) && $email_1) {
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

    public static function scoreORDER_FULLNAMES(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $names = [];

        if ($firstname = FraudCheckHelper::correct($order->firstname)) {
            $names[] = $firstname;
        }

        if ($b_firstname = FraudCheckHelper::correct($order->b_firstname)) {
            $names[] = $b_firstname;
        }

        if ($s_firstname = FraudCheckHelper::correct($order->s_firstname)) {
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

    public static function scoreCHECK_STATES(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $geoip_state = $areacode_state = '';

        $s_state = FraudCheckHelper::correct($order->s_state);
        $b_state = FraudCheckHelper::correct($order->b_state);

        if ($geo_litecity_location = GeoIpHelper::getGeoipLocation($order->getIp())) {
            $geoip_state = FraudCheckHelper::correct($geo_litecity_location->region);
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

    public static function scoreGEOIP_CITY_VS_B_S(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';

        $s_city = FraudCheckHelper::correct($order->s_city);
        $b_city = FraudCheckHelper::correct($order->b_city);

        if (($customer_ip = $order->getIp()) && $geo_litecity_location = GeoIpHelper::getGeoipLocation($customer_ip)) {
            $geoip_city = FraudCheckHelper::correct($geo_litecity_location->city);
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

    public static function scoreCHECK_OK_ORDERS_FOR_EMAIL(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreCHECK_FULLNAMES_FOR_EMAIL(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_IP(OrderModel $order, FraudCheckModel $fraud): array
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
                $full_address_s = FraudCheckHelper::correct($full_address_s);
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

    public static function scoreCHECK_DIFFERENT_BILLINGS_FOR_IP(OrderModel $order, FraudCheckModel $fraud): array
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
                $full_address_s = FraudCheckHelper::correct($full_address_s);
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

    public static function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_PHONE(OrderModel $order, FraudCheckModel $fraud): array
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
                $address = trim($v->s_address);
                $full_address_s = "{$address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}";
                $full_address_s = FraudCheckHelper::correct($full_address_s);
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

    public static function scoreCHECK_DIFFERENT_BILLINGSS_FOR_PHONE(OrderModel $order, FraudCheckModel $fraud): array
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
                $address = trim($v->b_address);
                $full_address_s = "{$address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}";
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

    public static function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = $names = $full_address_names = [];
        $time_condition = $order->date - 60 * 60 * 24 * 180;

        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'email' => $order->email])->order(['-orderid']);

        foreach ($orders as $k => $v) {
            $address = trim($v->s_address);
            $full_address_s = self::correct("{$address}-{$v->s_city}-{$v->s_state}-{$v->s_country}-{$v->s_zipcode}");
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

    public static function scoreCHECK_DIFFERENT_BILLINGS_FOR_EMAIL(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $additional_info = $names = $full_address_names = [];
        $time_condition = $order->date - 60 * 60 * 24 * 180;

        $orders = OrderModel::objects()->filter(['date__gte' => $time_condition, 'date__lte' => $order->date, 'email' => $order->email])->order(['-orderid']);

        foreach ($orders as $k => $v) {
            $address = trim($v->b_address);
            $full_address_s = self::correct("{$address}-{$v->b_city}-{$v->b_state}-{$v->b_country}-{$v->b_zipcode}");
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

    public static function scoreCHECK_DIFFERENT_SHIPPINGS_FOR_CARD(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = 0;
        $fraud_result = 'neutral';
        $additional_info = [];

        return [$fraud_result, $fraud_score, $additional_info];
    }

    public static function scoreCHECK_DIFFERENT_BILLING_FOR_SHIPPING(OrderModel $order): array
    {
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

    public static function scoreCHECK_TOTAL(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreCHECK_SHIPPING_ADDRESS_LINE2(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function scoreCHECK_PURCHASE_ORDER(OrderModel $order, FraudCheckModel $fraud): array
    {

        $fraud_score = -1;
        $fraud_result = 'negative';

        if ((int)$order->paymentid === 2) {
            $fraud_score = 1;
            $fraud_result = 'positive';
        }

        return [$fraud_result, $fraud_score, null];
    }

    private static function fetchMelissaAddress($address)
    {
        $client = new Client(['verify' => false, 'timeout' => 10]);
        $params = [
            'freeForm' => self::correct("{$address['address']} {$address['city']} {$address['state']} {$address['country']} {$address['zipcode']}"),
            'fmt' => 'json',
            'id' => self::MELISSA_KEY
        ];
        $url = 'https://www.melissa.com/v2/lookups/addresscheck/address/';
        if ($response = $client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return [];
    }

    private static function fetchMellissaPhone($phone)
    {
        $client = new Client(['verify' => false, 'timeout' => 10]);
        $params = [
            'phone' => $phone,
            'fmt' => 'json',
            'id' => self::MELISSA_KEY
        ];
        $url = 'https://www.melissa.com/v2/lookups/phonecheck/';
        if ($response = $client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
    }

    private static function fetchMellissaEmail($email)
    {
        $client = new Client(['verify' => false, 'timeout' => 10]);
        $params = [
            'emailAddress' => $email,
            'fmt' => 'json',
            'id' => self::MELISSA_KEY
        ];
        $url = 'https://www.melissa.com/v2/lookups/personator/';
        if ($response = $client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
    }

    public static function scoreMANUAL_IS_GOOGLE_PHONE_1(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $callerId = '';
        if ($res = self::fetchMellissaPhone($order->phone)) {
            if (($callerId = $res['CallerID'])) {
                $names = array_merge(self::getAllVariations($order->firstname), self::getAllVariations($order->s_firstname), self::getAllVariations($order->b_firstname));
                $names = array_map(static function ($a) {
                    return soundex($a);
                }, $names);

                if (in_array(soundex($callerId), $names, true)) {
                    $fraud_score = 1;
                    $fraud_result = 'positive';
                    $manual_action = 'Y';
                }
            }
        }
        return [$fraud_result, $fraud_score, [
            'CallerID' => $callerId,
            'Phone' => $res['Phone'] ?? '',
        ], $manual_action ?? 'N'];
    }

    public static function scoreMANUAL_IS_GOOGLE_EMAIL_1(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        if ($res = self::fetchMellissaEmail($order->email)) {
            if (($fullName = $res['NameFull'])) {
                $names = array_merge(self::getAllVariations($order->firstname), self::getAllVariations($order->s_firstname), self::getAllVariations($order->b_firstname));
                $names = array_map(static function ($a) {
                    return soundex($a);
                }, $names);
                if (in_array(soundex($fullName), $names, true)) {
                    $fraud_score = 1;
                    $fraud_result = 'positive';
                    $manual_action = 'Y';
                }
            }
        }
        return [$fraud_result, $fraud_score, [
            'NameFull' => $fullName ?? '',
        ], $manual_action ?? 'N'];

    }

    private static function depthPicker($arr, $temp_string, &$collect)
    {
        if ($temp_string !== '')
            $collect [] = $temp_string;

        for ($i = 0, $iMax = sizeof($arr); $i < $iMax; $i++) {
            $arrcopy = $arr;
            $elem = array_splice($arrcopy, $i, 1); // removes and returns the i'th element
            if (count($arrcopy) > 0) {
                self::depthPicker($arrcopy, "{$temp_string} {$elem[0]}", $collect);
            } else {
                $collect [] = "{$temp_string} {$elem[0]}";
            }
        }
    }

    private static function getAllVariations($string): array
    {
        $array = explode(' ', $string);
        self::depthPicker($array, "", $collect);
        return array_filter($collect, static function ($a) {
            return strpos(trim($a), ' ') !== false;
        });
    }

    public static function scoreMANUAL_GOOGLE_SHIPPING_1(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $fullName = '';
        $addressVerified = false;
        $addressCMRA = false;

        if ($res = self::fetchMelissaAddress([
            'address' => $order->s_address,
            'city' => $order->s_city,
            'state' => $order->s_state,
            'country' => $order->s_country,
            'zipcode' => $order->s_zipcode,
        ])) {
            if ($fullName = $res['NameFull']) {
                $names = array_merge(self::getAllVariations($fullName), self::getAllVariations($res['PartyOwner1NameFull'] ?? ''));
                $names = array_map(static function ($a) {
                    return soundex($a);
                }, $names);

                if (in_array(soundex($order->s_firstname), $names, true)) {
                    $fraud_score = 1;
                    $fraud_result = 'positive';
                    $manual_action = 'Y';
                }
            }
            if ($resultsArr = explode(',', $res['Results'] ?? '')) {
                if (in_array('AS01', $resultsArr, 'true')) {
                    $addressVerified = true;
                }
                if (in_array('AS10', $resultsArr, 'true')) {
                    $addressCMRA = true;
                }
            }
        }

        return [$fraud_result, $fraud_score, [
            'NameFull' => $fullName,
            'PhoneNumber' => $res['PhoneNumber'] ?? '',
            'PartyOwner1NameFull' => $res['PartyOwner1NameFull'] ?? '',
            'AddressVerified' => $addressVerified ? 'Verified' : 'Not verified',
            'AddressTypeCode' => $res['AddressTypeCode'] ?? '',
            'Commercial_Mail_Receiving_Agency' => $addressCMRA,
        ], $manual_action ?? 'N'];
    }

    public static function scoreMANUAL_GOOGLE_BILLING_1(OrderModel $order, FraudCheckModel $fraud): array
    {
        $fraud_score = -1;
        $fraud_result = 'negative';
        $fullName = '';
        $addressVerified = false;
        $addressCMRA = false;

        if ($res = self::fetchMelissaAddress([
            'address' => $order->b_address,
            'city' => $order->b_city,
            'state' => $order->b_state,
            'country' => $order->b_country,
            'zipcode' => $order->b_zipcode,
        ])) {
            if ($fullName = $res['NameFull']) {
                $names = array_merge(self::getAllVariations($fullName), self::getAllVariations($res['PartyOwner1NameFull'] ?? ''));
                $names = array_map(static function ($a) {
                    return soundex($a);
                }, $names);

                if (in_array(soundex($order->b_firstname), $names, true)) {
                    $fraud_score = 1;
                    $fraud_result = 'positive';
                    $manual_action = 'Y';
                }
            }
            if ($resultsArr = explode(',', $res['Results'] ?? '')) {
                if (in_array('AS01', $resultsArr, 'true')) {
                    $addressVerified = true;
                }
                if (in_array('AS10', $resultsArr, 'true')) {
                    $addressCMRA = true;
                }
            }
        }

        return [$fraud_result, $fraud_score, [
            'NameFull' => $fullName,
            'PhoneNumber' => $res['PhoneNumber'] ?? '',
            'PartyOwner1NameFull' => $res['PartyOwner1NameFull'] ?? '',
            'AddressVerified' => $addressVerified ? 'Verified' : 'Not verified',
            'AddressTypeCode' => $res['AddressTypeCode'] ?? '',
            'Commercial_Mail_Receiving_Agency' => $addressCMRA,
        ], $manual_action ?? 'N'];
    }

    public static function scoreMANUAL_IS_ORDER_ITEMS_EASY_TO_SELL(OrderModel $order, FraudCheckModel $fraud): array
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

    public static function getProductList(OrderModel $orderModel): array
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

}