<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;

class OrderTransactionHelper
{

    /**
     * @param OrderTransactionModel $model
     * @param $gw
     * @param OrderModel $orderModel
     * @param PaymentMethodModel $pmModel
     * @param array $amount
     * @param string $mode
     * @return OrderTransactionModel
     */
    public static function prepareOrderTransaction($model, $gw, $orderModel = null, $pmModel, $amount = null, $mode = '')
    {
        if (!$model && $orderModel) {
            $model = new OrderTransactionModel(['orderid' => $orderModel->orderid]);
        }
        if ($model) {
            $result = $gw->result->getData();
            if (!$result['amount']) {
                $result['amount'] = $amount;
            }

            if ($mode == 'add_manual_transaction') {
                $model->manual_transaction = 'Y';
            }

            $model->setAttributes(
                [
                    'transaction_id' => $gw->result->getTransactionReference(),
                    'transaction_status' => ($logStatus = $gw->getState($mode)),
                    'transaction_currency' => $result['amount']["currency"],
                    'transaction_amount' => abs($result['amount']['total']),
                    'transaction_response' => $result,
                    'paymentid' => $pmModel->paymentid
                ]
            );
        }
        return $model;
    }

    /**
     * @param string $method
     * @param OrderTransactionModel $model
     */
    public static function action($method, $model)
    {
        if ($model) {
            if ($gw = Gateway::getGateway($model->payment_method_model->processor)) {
                $amount =
                    [
                        'amount' => $model->transaction_amount,
                        'currency' => $model->transaction_currency
                    ];
                $params = PaymentHelper::getPaymentParams($model, $amount);
                if ($res = $gw->$method($params)) {
                    $model = OrderTransactionHelper::prepareOrderTransaction($model, $gw, null, $model->payment_method_model, $amount);
                }
            }
        }
        return $model;
    }

    public static function getOrderTransactionsGroupsValues(OrderModel $order)
    {
        $trs = [];
        if ($order) {
            foreach ($order->transactions as $transaction) {
                $trs[$transaction->transaction_status] += $transaction->transaction_amount;
            }
        }

        return [
            'authorized_PLUS_captured_totals' => floatval($trs[OrderTransactionModel::STATUS_COMPLETED]
                + $trs[OrderTransactionModel::STATUS_AUTHORIZED]
                + $trs[OrderTransactionModel::STATUS_PENDING]),
            'void_total' => floatval($trs[OrderTransactionModel::STATUS_VOIDED]),
            'authorized_total' => floatval($trs[OrderTransactionModel::STATUS_AUTHORIZED]),
            'captured_total' => floatval($trs[OrderTransactionModel::STATUS_COMPLETED])
        ];
    }
}