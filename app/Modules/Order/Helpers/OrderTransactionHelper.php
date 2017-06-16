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

            if (in_array($mode, ["re_authorize_transaction", "capture_transaction", "refund_transaction"])) {
                $model->parent_transaction_id = $model->transaction_id;
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
     * @param OrderTransactionModel$model
     */
    public static function action($method, $model)
    {
        if ($model) {
            if ($gw = Gateway::getGateway($model->payment_method_model)) {
                $amount =
                    [
                        'amount' => $model->transaction_amount,
                        'currency' => $model->transaction_currency
                    ];
                $params = PaymentHelper::getPaymentParams($model, $amount);
                if ($res = $gw->$method($params)) {
                    $model = OrderTransactionHelper::prepareOrderTransaction($model, $gw, $orderModel, $pmModel, $amount, $mode);
                }
            }
        }
        return $model;
    }
}