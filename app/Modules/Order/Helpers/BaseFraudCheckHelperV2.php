<?php


namespace Modules\Order\Helpers;


use Modules\Goods\Models\ProductHardResellModel;
use Modules\Order\Models\BaseFraudCheckModelV2;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderBaseFraudCheckModelV2;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Manager;

class BaseFraudCheckHelperV2
{
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
    public static function scoreCHECK_PRODUCTS_ON_CHARGEBACK(OrderModel $order, BaseFraudCheckModelV2 $fraud) : array
    {
        $fraud_result = 'positive';
        $outcome = 1;
        $additional_info = [];
        foreach ($order->getProducts() as $product_model) {
            /** @var OrderDetailModel[]|Manager $order_chargeback */
            $order_chargeback = OrderDetailModel::objects()->filter([
                'productid' => $product_model->pk,
                'order__fraud_status__in' => [FraudStatusModel::STATUS_FRAUD_PURE, FraudStatusModel::STATUS_FRAUD_PROBABLY, FraudStatusModel::STATUS_FRAUD_CHARGEBACK]
            ]);
            if ($order_chargeback->count()) {
                $outcome = 0;
                $fraud_result = 'negative';
                foreach ($order_chargeback as $order_detail) {
                    $additional_info[] = $order_detail->orderid;
                }
                break;
            }
        }
        return [$fraud_result, $fraud->weight, $additional_info, null, $outcome];
    }

    public static function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'positive';
        $outcome = 1;
        $manual_action = 'Y';
        /** @var SiteModel $site */
        if (($app = Xcart::app()) && $site = $app->getModule('Sites')->getSite()) {
            $config = $site->getGlobalConfig();
            if (stripos($config['fraud_domains_free_email_provider'], $order->getEmailDomain()) !== false) {
                $fraud_result = 'negative';
                $manual_action = 'N';
                $outcome = 0;
            }
        }

        return [$fraud_result, $fraud->weight, null, $manual_action, $outcome];
    }

    public static function scoreCHECK_EMAIL_ADDRESS_DOMAIN(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'positive';
        $outcome = 1;
        if (strpos($order->email, "@mail.com") !== false) {
            $fraud_result = 'negative';
            $outcome = 0;
        }
        return [$fraud_result, $fraud->weight, null, null, $outcome];

    }

    public static function scoreCHECK_STRIPE_DEBIT_OR_CREDIT_CARD(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        /** @var OrderTransactionModel $transaction_model */
        $transaction_model = $order->getFirstTransaction();
        $data_response = $transaction_model->transaction_response;
        $name_card = '';
        foreach ($data_response['charges']['data'] as $details) {
            $name_card = $details['payment_method_details']['card']['funding'];
            if ($details['payment_method_details']['card']['funding'] === 'debit') {
                $fraud_result = 'positive';
                $outcome = 1;
            }
        }
        return [$fraud_result, $fraud->weight, ['name_card' => $name_card], null, $outcome];
    }

    private static function checksIsPassByType(array $transaction_response, string $type = 'address_line1_check'): bool
    {
        foreach ($transaction_response['charges']['data'] as $details) {
            if ($details['payment_method_details']['card']['checks'][$type] === 'pass') {
                return true;
            }
        }
        return false;
    }

    public static function scoreCHECK_STRIPE_CVC(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        $transaction_model = $order->getFirstTransaction();
        /** @var OrderTransactionModel $transaction_model */
        $data_response = $transaction_model->transaction_response;
        if (self::checksIsPassByType($data_response, 'cvc_check')) {
            $fraud_result = 'positive';
            $outcome = 1;
        }
        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_STRIPE_STREET(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        $transaction_model = $order->getFirstTransaction();
        /** @var OrderTransactionModel $transaction_model */
        $data_response = $transaction_model->transaction_response;
        if (self::checksIsPassByType($data_response)) {
            $outcome = 1;
            $fraud_result = 'positive';
        }
        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_STRIPE_ZIP(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        $transaction_model = $order->getFirstTransaction();
        /** @var OrderTransactionModel $transaction_model */
        $data_response = $transaction_model->transaction_response;
        if (self::checksIsPassByType($data_response, 'address_postal_code_check')) {
            $fraud_result = 'positive';
            $outcome = 1;
        }
        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE_FOR_SHIPPING_ADDRESS(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        return self::scoreMANUAL_CHECK_EMAIL_DOMAIN_WEBSITE($order, $fraud);
    }

    public static function scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        /** @var OrderTransactionModel $oTransaction */
        $oTransaction = $fraud->getFirstTransaction($order);
        $manual_action = 'N';
        $outcome = 0;
        $o_address = self::addressAbbreviationsPrepare(self::correctAddress($order->s_address));
        $p_address = self::correctAddress($oTransaction->transaction_response['address_street']);
        $o_address_replaces = str_replace(' ', '\s?', $o_address);
        if ($oTransaction->transaction_response['address_country_code'] === $order->s_country &&
            $oTransaction->transaction_response['address_state'] === $order->s_state &&
            self::correctAddress($oTransaction->transaction_response['address_city']) === self::correctAddress($order->s_city) &&
            self::correctAddress($oTransaction->transaction_response['address_zip']) === self::correctAddress($order->s_zipcode) &&
            preg_match("/{$o_address_replaces}/", $p_address, $mm)) {
            $fraud_result = 'positive';
            $manual_action = 'Y';
            $outcome = 1;
        }
        return [$fraud_result, $fraud->weight, ['o_address' => $o_address, 'p_address' => $p_address], $manual_action, $outcome];
    }

    public static function scoreMANUAL_PAYPAL_SHIPPING_CONFIRMED(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $manual_action = 'N';
        $outcome = 0;
        [$r] = self::scoreMANUAL_PAYPAL_SHIPPING_EQUAL_BILLING($order, $fraud);
        if ($r === 'positive' && $oTransaction = $fraud->getFirstTransaction($order)) {
            if ($oTransaction->transaction_response['address_status'] === 'confirmed') {
                $fraud_result = 'positive';
                $manual_action = 'Y';
                $outcome = 1;
            }
        }
        return [
            $fraud_result,
            $fraud->weight,
            ['MANUAL_PAYPAL_SHIPPING_EQUAL_BILLING' => $r, 'address_status' => $oTransaction->transaction_response['address_status'] ?? ''],
            $manual_action,
            $outcome
        ];
    }

    public static function scoreMANUAL_IS_FREIGHT_FORWARDER_FOUND(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $manual_action = 'N';
        $outcome = 0;

        /** @var OrderBaseFraudCheckModelV2 $orderFraudShipping */
        if ($orderFraudShipping = OrderBaseFraudCheckModelV2::objects()->get(['order_id' => $order->orderid, 'question__question_code' => 'MANUAL_GOOGLE_SHIPPING_1'])) {
            $r = $orderFraudShipping->fraud_result;
            if ($r === 'positive') {
                $fraud_result = $r;
                $manual_action = 'Y';
            }
        }
        /** @var OrderBaseFraudCheckModelV2 $orderFraudBilling */
        if ($orderFraudBilling = OrderBaseFraudCheckModelV2::objects()->get(['order_id' => $order->orderid, 'question__question_code' => 'MANUAL_GOOGLE_BILLING_1'])) {
            $r2 = $orderFraudBilling->fraud_result;
            if ($r2 === 'positive') {
                $fraud_result = $r2;
                $manual_action = 'Y';
            }
        }

        if (($info = $orderFraudShipping->additional_info) && $info['Commercial_Mail_Receiving_Agency']) {
            $fraud_result = 'negative';
            $manual_action = 'N';
        }

        if ($fraud_result !== 'negative' && ($info = $orderFraudBilling->additional_info) && $info['Commercial_Mail_Receiving_Agency']) {
            $fraud_result = 'negative';
            $manual_action = 'N';
        }
        if ($fraud_result === 'positive') {
            $outcome = 1;
        }

        return [
            $fraud_result,
            $fraud->weight,
            [
                'MANUAL_GOOGLE_SHIPPING_1' => $r ?? '',
                'MANUAL_GOOGLE_BILLING_1' => $r2 ?? '',
                'Commercial_Mail_Receiving_Agency' => $info['Commercial_Mail_Receiving_Agency']
            ],
            $manual_action,
            $outcome
        ];
    }

    public static function scoreMANUAL_PAYPAL_FULLNAME_VERIFIED(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        $manual_action = 'N';
        $payer_status = '';

        if ($fraud->isPaypalPayment($order)) {
            if ($oTransaction = $fraud->getFirstTransaction($order)) {
                $payer_status = $oTransaction->transaction_response['payer_status'];
                if ($payer_status === 'verified') {
                    $fraud_result = 'positive';
                    $manual_action = 'Y';
                    $outcome = 1;
                }
            }
        }
        return [$fraud_result, $fraud->weight, ['Payer status' => $payer_status], $manual_action, $outcome];
    }

    public static function scoreMANUAL_PAYPAL_EMAIL_EQUAL_TO_ORDER(OrderModel $order, BaseFraudCheckModelV2 $fraud)
    {
        $fraud_result = 'negative';
        $manual_action = 'N';
        $payer_email = '';
        $outcome = 0;
        if ($oTransaction = $fraud->getFirstTransaction($order)) {
            $payer_email = $oTransaction->transaction_response['payer_email'];
            if ($oTransaction->transaction_response['payer_email'] === $order->email) {
                $fraud_result = 'positive';
                $manual_action = 'Y';
                $outcome = 1;
            }
        }

        return [$fraud_result, $fraud->weight, ['Payer email' => $payer_email], $manual_action, $outcome];
    }

    public static function scoreIS_EMAIL_DOMAIN_FREE(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {

        $email = $order->email;
        $fraud_result = 'positive';
        $outcome = 1;

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();

        if ($fraud_domains_free_email_provider_arr = explode(',', $config['fraud_domains_free_email_provider'])) {
            foreach ($fraud_domains_free_email_provider_arr as $k => $v) {
                $domain = '@' . trim($v);
                if (stripos($email, $domain) !== false) {
                    $fraud_result = 'negative';
                    $outcome = 0;
                    break;
                }
            }
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_EMAIL_VS_NAME(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $email_arr = explode('@', $order->email);
        $email_1 = strtoupper($email_arr[0]);
        $outcome = 0;

        if ($email_1 && ($firstname_arr = explode(' ', self::correct($order->firstname)))) {
            foreach ($firstname_arr as $k => $v) {
                $name = trim($v);
                if ($name && stripos($email_1, $name) !== false) {
                    $fraud_result = 'positive';
                    $outcome = 1;
                    break;
                }
            }
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_OK_ORDERS_FOR_EMAIL(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;

        $orders = OrderModel::objects()->filter(['email' => $order->email, 'groups__dc_status__in' => ['S', 'G'], 'date__lte' => $order->date])->group(['orderid'])->order(['-orderid']);
        if (($total_items = $orders->count()) > 0) {
            $additional_info = $orders->all();
        }

        $time_condition = $order->date - 60 * 60 * 24 * 1;
        $orders = OrderModel::objects()->filter(['email' => $order->email, 'groups__dc_status__in' => ['S', 'G'], 'date__lte' => $time_condition])->group(['orderid'])->order(['-orderid']);

        if (($total_items_min_day = $orders->count()) > 0) {
            if (($total_items_min_day / $total_items) > 0) {
                $fraud_result = 'positive';
                $outcome = 1;
            }
        }

        return [$fraud_result, $fraud->weight, $additional_info ?? null, null, $outcome];
    }

    public static function scoreCHECK_TOTAL(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $total = $order->total;
        $order_total_div = 50 / $total;

        if ($order_total_div >= 1) {
            $fraud_result = 'positive';
            $outcome = 1;
        } else {
            $fraud_result = 'negative';
            $outcome = 0;
        }

        $num = 50 - $total;

        if (!$num) {
            $num = 1;
        }

        $sign = $num / abs($num);

        $fraud_score = ((max(50, $total) / min(50, $total)) - 1) * $sign;

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_SHIPPING_ADDRESS_LINE2(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {

        $fraud_result = 'positive';
        $outcome = 1;

        [$shipping] = $order->getAddressInfo();

        if (isset($shipping['address'][1]) || preg_match('/\bApartment\b|\bApt\b|\bSuite\b|\bSte\b|\bUnit\b|#|\d-\d/i', $shipping['address'][0])) {
            $fraud_result = 'negative';
            $outcome = 0;
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreCHECK_PURCHASE_ORDER(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;

        if ((int)$order->paymentid === 2) {
            $fraud_result = 'positive';
            $outcome = 1;
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreMANUAL_IS_ORDER_ITEMS_EASY_TO_SELL(OrderModel $order, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $maxOrderPriceAmount = 0;
        $outcome = 0;
        foreach ($order->detail_models as $detailModel) {
            $maxOrderPriceAmount = max($detailModel->price * $detailModel->amount, $maxOrderPriceAmount);
            if ($hardResellModel = ProductHardResellModel::objects()->get(['product_id' => $detailModel->productid])) {
                switch ($hts = $hardResellModel->getHardToResellStatus()) {
                    case ProductHardResellModel::HARD_TO_RESELL_YES:
                        $hard[] = 'positive';
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_NO:
                        $hard[] = 'negative';
                        break;
                }
            }
        }
        $hard = array_unique($hard ?? []);
        if (count($hard) === 1) {
            $fraud_result = reset($hard);
        }
        if (!(($order->total > 50) || ($maxOrderPriceAmount > 10))) {
            $fraud_result = 'positive';
        }
        switch ($fraud_result) {
            case 'positive':
                $manual = 'Y';
                $outcome = 1;
                break;
            case 'negative':
                $manual = 'N';
                break;
        }

        return [$fraud_result, $fraud->weight, null, $manual ?? null, $outcome];
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

    public static function scoreCHECK_BRAND_CARD(OrderModel $order_model, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'positive';
        $type_card = "Couldn't determine";
        $outcome = 1;
        if (($oTransaction = $order_model->getFirstTransaction())) {
            switch ($processor = $oTransaction->payment_method_model->processor->processor_name) {
                case 'BluePay':
                    if ($card_type = $oTransaction->transaction_response['card_type'] ?? null) {
                        $type_card = $card_type;
                        if (in_array($card_type, ['AMEX'])) {
                            $fraud_result = 'negative';
                        }
                    }
                    break;
                case 'Stripe':
                    foreach ($oTransaction->transaction_response['charges']['data'] as $details) {
                        $type_card = $details['payment_method_details']['card']['brand'];
                        if (in_array($details['payment_method_details']['card']['brand'], ['amex'])) {
                            $fraud_result = 'negative';
                        }
                    }
                    break;
                case 'PayPal':
                    $fraud_result = 'negative';
                    break;
            }
        }
        if ($fraud_result === 'negative') {
            $outcome = 0;
        }
        return [$fraud_result, $fraud->weight, ['card_type' => $type_card], null, $outcome];
    }

    public static function scoreCHECK_AVS_ADDRESS(OrderModel $order_model, BaseFraudCheckModelV2 $fraud): array
    {
        $fraud_result = 'negative';
        $code = self::getAVSCodeByOrderModel($order_model);
        $outcome = 0;
        switch ($code)
        {
            case 'Y':
            case 'X':
            case 'M':
            case 'D':
                $outcome = 1;
                break;
            case 'B':
                $outcome = 5/6;
                break;
            case 'P':
                $outcome = 4/6;
                break;
        }
        if (in_array($code, ['Y', 'X', 'M', 'D'])) {
            $fraud_result = 'positive';
        }
        return [$fraud_result, $fraud->weight, ['AVS' => $code], null, $outcome];
    }

    private static function getAVSCodeByOrderModel(OrderModel $order_model): string
    {
        $code = 'S';
        if (($oTransaction = $order_model->getFirstTransaction())) {
            switch ($processor = $oTransaction->payment_method_model->processor->processor_name) {
                case 'Stripe':
                    foreach ($oTransaction->transaction_response['charges']['data'] as $details) {
                        $check_details = $details['payment_method_details']['card']['checks'];
                        if (self::isPass($check_details['address_line1_check']) && self::isPass($check_details['address_postal_code_check'])) {
                            $code = 'M';
                        } else if (self::isPass($check_details['address_line1_check']) && !self::isPass($check_details['address_postal_code_check'])) {
                            $code = 'B';
                        } else if (self::isPass($check_details['address_postal_code_check']) && !self::isPass($check_details['address_line1_check'])) {
                            $code = 'P';
                        }
                    }
                    break;
                case 'BluePay':
                    if ($cv = $oTransaction->transaction_response['advinfo'] ?? null) {
                        if ($cv['AVS'] === 'Street and Zip match') {
                            $code = 'M';
                        } else if ($cv['AVS'] === 'Zip match 5, street match') {
                            $code = 'Y';
                        }
                    }
                    break;
                case 'PayPal':
                    if ($cv = $oTransaction->transaction_response['cardValidation'] ?? null) {
                        if ((int)$cv['avs_z'] === 1 && (int)$cv['avs_c'] === 1 && (int)$cv['avs_a'] && $cv['cvv_code'] === 'M') {
                            $code = 'M';
                        }
                    }
            }
        }
        return $code;
    }

    private static function isPass(string $check): bool
    {
        return $check === 'pass';
    }
}