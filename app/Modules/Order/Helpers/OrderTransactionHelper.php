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
     * @param OrderTransactionModel $model
     * @param Gateway $gw
     * @param OrderModel $orderModel
     * @param PaymentMethodModel $pmModel
     * @param array $params
     * @param string $mode
     * @return OrderTransactionModel
     */
    public static function prepareOrderTransaction($gw, $params = null)
    {

        $response = null;

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
                'paymentid' => $params['payment-method_model']->paymentid,
                'transaction_fee' => isset($result['transaction_fee']) ? $result['transaction_fee']['value'] : null,
            ]
        );

        return $response;
    }

    /**
     * @param string $method
     * @param OrderTransactionModel $model
     * @param array $params
     */
    public static function action($method, $params)
    {
        $model = null;

        if ($gw = Gateway::getGateway($params['processor'])) {
            if ($res = $gw->$method($params)) {
                if ($result = OrderTransactionHelper::prepareOrderTransaction($gw, $params)){
                    if ($result['transaction_id'] != $params['']) {

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
                + $trs[OrderTransactionModel::STATUS_PENDING]
                + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED]
            ),
            'void_total' => floatval($trs[OrderTransactionModel::STATUS_VOIDED]),
            'authorized_total' => floatval($trs[OrderTransactionModel::STATUS_AUTHORIZED] + $trs[OrderTransactionModel::STATUS_PENDING]),
            'captured_total' => floatval($trs[OrderTransactionModel::STATUS_COMPLETED] + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED])
        ];
    }
}