<?php


namespace Modules\Order\Helpers;


use DateInterval;
use DateTime;
use Modules\Goods\Models\ProductHardResellModel;
use Modules\Order\Models\FraudCheckBaseQuestionModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Manager;
use Xcart\App\QueryBuilder\Q\QAnd;
use Xcart\App\QueryBuilder\Q\QOr;

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
                return "\b$e\b";
            }, $a);
            $search = implode('|', $b);
            $replacement = '(' . implode('|', array_map('strtoupper', $a)) . ')';
            $field = preg_replace("/$search/", $replacement, $field);
        }
        return $field;
    }

    public static function correct($field): string
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace("/[^\w\s\[,.\-\/@_\]]/", '', $field);
        return strtoupper($field);
    }

    public static function correctAddress($field): string
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace('/[\[,.-\/@_\]]/', '', $field);
        return strtoupper($field);
    }

    public static function scoreRF_CB(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'positive';
        $outcome = 0;
        $additional_info = [];
        foreach ($order->getProducts() as $product_model) {
            /** @var OrderDetailModel[]|Manager $order_chargeback */
            $order_chargeback = OrderDetailModel::objects()->filter([
                'productid' => $product_model->pk,
                'order__fraud_status__in' => [FraudStatusModel::STATUS_FRAUD_PURE, FraudStatusModel::STATUS_FRAUD_PROBABLY, FraudStatusModel::STATUS_FRAUD_CHARGEBACK]
            ]);
            if ($order_chargeback->count()) {
                $outcome = 1;
                $fraud_result = 'negative';
                foreach ($order_chargeback as $order_detail) {
                    $additional_info[] = $order_detail->orderid;
                }
                break;
            }
        }
        return [$fraud_result, $fraud->weight, $additional_info, null, $outcome];
    }

    public static function scoreDC_DN(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scoreRF_EM(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'positive';
        $outcome = 0;
        if (strpos($order->email, "@mail.com") !== false) {
            $fraud_result = 'negative';
            $outcome = 1;
        }
        return [$fraud_result, $fraud->weight, null, null, $outcome];

    }

    public static function scoreST_DC(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    private static function checksIsPassByType(?array $transaction_response, string $type = 'address_line1_check'): bool
    {
        if (!$transaction_response) {
            return false;
        }
        foreach ($transaction_response['charges']['data'] as $details) {
            if ($details['payment_method_details']['card']['checks'][$type] === 'pass') {
                return true;
            }
        }
        return false;
    }

    public static function scoreST_CVV(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scoreST_ST(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scoreST_ZIP(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scoreDC_AU(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        return self::scoreDC_DN($order, $fraud);
    }

    public static function scorePP_SASA(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scorePP_SASA_C(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $manual_action = 'N';
        $outcome = 0;
        [$r] = self::scorePP_SASA($order, $fraud);
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
            ['PP_SASA' => $r, 'address_status' => $oTransaction->transaction_response['address_status'] ?? ''],
            $manual_action,
            $outcome
        ];
    }

    public static function scoreRF_MF(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 1;

        return [
            $fraud_result,
            $fraud->weight,
            null,
            null,
            $outcome
        ];
    }

    public static function scorePP_VER(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scorePP_EE(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    /** Проверяет, совпадает ли хотя бы один из 3-х имен указанных в заказе с адресом почты
     * @param OrderModel $order
     * @param FraudCheckBaseQuestionModel $fraud
     * @return array
     */
    public static function scoreDC_EN(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $email_arr = explode('@', $order->email);
        $email_1 = strtoupper($email_arr[0]);
        $name_list = [$order->firstname ?? '', $order->s_firstname ?? '', $order->b_firstname ?? ''];
        foreach ($name_list as $name_client) {
            if ($email_1 && ($firstname_arr = explode(' ', self::correct($name_client)))) {
                foreach ($firstname_arr as $v) {
                    $name = trim($v);
                    if ($name && strlen($name) > 2 && stripos($email_1, $name) !== false) {
                        $fraud_result = 'positive';
                        goto result;
                    }
                }
            }
        }
        $company_list = [$order->b_company ?? '', $order->s_company ?? ''];
        foreach ($company_list as $company) {
            $normalize_company = strtolower($company);
            foreach ($email_arr as $email_attr) {
                if (soundex($email_attr) === soundex($normalize_company)) {
                    $fraud_result = 'positive';
                    goto result;
                }
            }
        }
        result:
        $outcome = $fraud_result === 'positive';

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreDC_PC(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $outcome = 0;
        $date = new DateTime();
        $date->sub(new DateInterval('P3M'));
        $date_receipt = new DateTime();
        $date_receipt->sub(new DateInterval('P21D'));
        // Оплаченный и доставленный заказы по данному email адресу более 3 месяцев(если обычная оплата) ИЛИ 3 недели(если оплата по чеку)
        $total_orders = OrderModel::objects()->filter([
            'email' => $order->email,
            'groups__dc_status__in' => [OrderStatusModel::ORDER_DC_STATUS_SHIPPED, OrderStatusModel::ORDER_DC_STATUS_DELIVERED],
            'groups__cb_status__in' => [OrderStatusModel::ORDER_BD_STATUS_PAID],
            new QOr([
                'date__lte' => $date->getTimestamp(),
                new QAnd([
                    'date__lte' => $date_receipt->getTimestamp(),
                    'groups__po_status__in' => [OrderStatusModel::ORDER_PO_STATUS_CANADIAN_OFFICE, OrderStatusModel::ORDER_PO_STATUS_USA_ADDRESS]
                ])])
        ]);

        if ($total_orders->count() > 0) {
            $additional_info = $total_orders->all();
            $fraud_result = 'positive';
            $outcome = 1;
        }

        return [$fraud_result, $fraud->weight, $additional_info ?? null, null, $outcome];
    }

    public static function scoreDC_GT(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
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

    public static function scoreDC_SA(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {

        $fraud_result = 'positive';
        $outcome = 1;

        [$shipping] = $order->getAddressInfo();

        if (!empty($shipping['address'][1])
            || preg_match('/\bApartment\b|\bPO Box\b|\bApt\b|\bSuite\b|\bSte\b|\bUnit\b|#|\d-\d/i', $shipping['address'][0])
            || preg_match('/-\d/', $shipping['address'][0])) {
            $fraud_result = 'negative';
            $outcome = 0;
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreRF_PO(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'positive';
        $outcome = 0;

        if ($order->isPurchaseOrder() && $extra_model = $order->extra_model) {
            if ($extra_model->purchase_order) {
                $fraud_result = 'negative';
                $outcome = 1;
            }
        }

        return [$fraud_result, $fraud->weight, null, null, $outcome];
    }

    public static function scoreRF_RP(OrderModel $order, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $maxOrderPriceAmount = 0;
        $outcome = 1;
        foreach ($order->detail_models as $detailModel) {
            $maxOrderPriceAmount = max($detailModel->price * $detailModel->amount, $maxOrderPriceAmount);
            if ($hardResellModel = ProductHardResellModel::objects()->get(['product_id' => $detailModel->productid])) {
                switch ($hts = $hardResellModel->getHardToResellStatus()) {
                    case ProductHardResellModel::HARD_TO_RESELL_YES:
                        $hard[] = 'positive';
                        break;
                    case ProductHardResellModel::HARD_TO_RESELL_NO:
                    case ProductHardResellModel::HARD_TO_RESELL_UNKNOWN:
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
        // TODO: Поменял местами
        switch ($fraud_result) {
            case 'positive':
                $manual = 'N';
                $outcome = 0;
                break;
            case 'negative':
                $manual = 'Y';
                $outcome = 1;
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
<span>{$detailModel->productcode}</span> HARD TO RESELL
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

    public static function scoreGC_CT(OrderModel $order_model, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $type_card = "Couldn't determine";
        if (($oTransaction = $order_model->getFirstTransaction())) {
            switch ($oTransaction->payment_method_model->processor->processor_name) {
                case 'BluePay':
                    $card_type = $oTransaction->transaction_response['card_type'];
                    if (empty($card_type)) {
                        $card_type = $oTransaction->transaction_response['maskedCardData']['type'];
                    }
                    $type_card = $card_type;
                    if ($card_type !== 'AMEX') {
                        $fraud_result = 'positive';
                    }
                    break;
                case 'Stripe':
                    foreach ($oTransaction->transaction_response['charges']['data'] as $details) {
                        $type_card = $details['payment_method_details']['card']['brand'];

                        if ($details['payment_method_details']['card']['brand'] !== 'amex') {
                            $fraud_result = 'positive';
                        }
                    }
                    break;
            }
        }
        $outcome = $fraud_result === 'positive';
        return [$fraud_result, $fraud->weight, ['card_type' => $type_card], null, $outcome];
    }

    public static function scoreGC_AVS(OrderModel $order_model, FraudCheckBaseQuestionModel $fraud): array
    {
        $fraud_result = 'negative';
        $code = self::getAVSCodeByOrderModel($order_model);
        $outcome = 0;
        switch ($code) {
            case 'Y':
            case 'X':
            case 'M':
            case 'D':
                $outcome = 1;
                break;
            case 'B':
                $outcome = 5 / 6;
                break;
            case 'P':
                $outcome = 4 / 6;
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