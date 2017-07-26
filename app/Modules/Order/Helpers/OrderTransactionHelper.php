<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Main\Xcart;

class OrderTransactionHelper
{


    /**
     * @param Gateway $gw
     * @param array $params
     * @return array
     */
    public static function prepareOrderTransaction($gw, $params = [])
    {

        $response = [];

        $result = $gw->result->getData();

        if (!$result['amount']) {
            $result['amount'] =
                [
                    'total' => $params['amount'],
                    'currency' => $params['currency']
                ];
        }

        /*if ($mode == 'add_manual_transaction') {
            $response['manual_transaction'] = 'Y';
        }*/

        if ($params['mode'] == 'refund_transaction') {
            $result['amount']['total'] = -abs($result['amount']['total']);
        }

        if (isset($result['capture_id'])) {
            if ($parent = OrderTransactionModel::objects()->get(['transaction_id' => $result['capture_id']])) {
                $response['parent_id'] = $parent->id;
            }
        }

        $response = array_merge($response,
            [
                'transaction_id' => $gw->result->getTransactionReference(),
                'transaction_status' => $gw->getState($params['mode']),
                'transaction_currency' => $result['amount']["currency"],
                'transaction_amount' => $result['amount']['total'],
                'transaction_response' => $result,
                'login' => Xcart::app()->user->login,
                'paymentid' => $params['payment_method_model']->paymentid,
                'transaction_fee' => isset($result['transaction_fee']) ? $result['transaction_fee']['value'] : null,
            ]
        );

        return $response;
    }


    /**
     * @param $method
     * @param array $params
     * @return array
     */
    public static function action($method, $params)
    {
        $model = null;

        if ($gw = Gateway::getGateway($params['processor'])) {
            if ($gw->$method($params)) {
                if ($result = OrderTransactionHelper::prepareOrderTransaction($gw, $params)){
                    if (empty($params['transactionReference']) || $result['transaction_id'] != $params['transactionReference']) {
                        $model = new OrderTransactionModel($result);
                    } else {
                        $model = OrderTransactionModel::objects()->get(['transaction_id' => $result['transaction_id']]);
                        $model->setAttributes($result);
                    }
                }
            }
        }

        return [$model, $gw];
    }

    public static function getOrderTransactionsGroupsValues(OrderModel $order)
    {
        $trs = [];
        if ($order) {
            foreach ($order->transactions as $transaction) {
                $trs[strtolower($transaction->transaction_status)] += $transaction->transaction_amount;
            }
        }

        return [
            'authorized_PLUS_captured_totals' => floatval(
                $trs[OrderTransactionModel::STATUS_COMPLETED]
                + $trs[OrderTransactionModel::STATUS_AUTHORIZED]
                + $trs[OrderTransactionModel::STATUS_CAPTURED]
                + $trs[OrderTransactionModel::STATUS_PENDING]
                + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED]
            ),
            'void_total' => floatval($trs[OrderTransactionModel::STATUS_VOIDED]),
            'authorized_total' => floatval($trs[OrderTransactionModel::STATUS_AUTHORIZED] + $trs[OrderTransactionModel::STATUS_PENDING]),
            'captured_total' => floatval($trs[OrderTransactionModel::STATUS_COMPLETED] + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED])
        ];
    }
}