<?php

namespace Modules\Order\Controllers;


use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class OrderTransactionsController extends Controller
{
    public function transaction_process($order_id, $mode, $transaction_id)
    {
        $method = $order_log = $result = null;

        /** @var OrderModel $orderModel */
        if ($orderModel = OrderModel::objects()->get(['orderid' => $order_id])) {

            extract(Gateway::$gatewayMethods[$mode]);

            if ($transaction_id && $orderTransaction = $orderModel->transactions->get(['transaction_id' => $transaction_id])) {

                $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $orderTransaction->payment_method_model->processor->processor_name . ' VT']);

                $amount =
                    [
                        'amount' => number_format(trim($_POST["transaction_amount[{$transaction_id}]"]), 2, '.', ''),
                        'currency' => $orderTransaction->transaction_currency
                    ];

                $params = array_merge(PaymentHelper::getPaymentParams($orderTransaction, $amount), ['mode' => $mode, 'payment_method_model' => $orderTransaction->payment_method_model]);

                try {
                    if ($model = OrderTransactionHelper::action(Gateway::$gatewayMethods['look_up_payment']['method'], $orderTransaction, PaymentHelper::getPaymentParams($orderTransaction))) {
                        $model->save();
                    }

                    list($model, $gw) = OrderTransactionHelper::action($method, $params);

                    if ($model){

                            $result = $model->transaction_response;

                            list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                            $order_log .= "<br />Transaction:" . $model->transaction_id . $o_log;

                        } else {

                         $result = $gw->result->getData();

                         if ($orderTransaction && ($state = $gw->getState($mode))) {
                             $orderTransaction->transaction_status = $state;
                             $orderTransaction->transaction_response = $result;
                             $orderTransaction->save();
                         }

                         $order_log .= "<br/>{$result['name']}<br/>{$result['message']}";
                     }

                        $logStatus = $orderTransaction->transaction_status;


                } catch (\Exception $e) {
                    $order_log .= "<br/>{$pmModel->payment_method} Processing Error: {$e->getMessage()}";
                    $logStatus = OrderTransactionModel::STATUS_FAILED;
                }

                $transactionLog = new TransactionLogModel(
                    [
                        'orderid' => $orderModel->orderid,
                        'paymentid' => $pmModel->paymentid,
                        'order_transaction_id' => isset($orderTransaction) ? $orderTransaction->id : null,
                        'transaction_id' => isset($orderTransaction) ? $orderTransaction->transaction_id : '',
                        'transaction_status' => $logStatus,
                        'transaction_currency' => !isset($result['amount']) ? $params['currency'] : $result['amount']['currency'],
                        'transaction_total' => !isset($result['amount']) ? $params['amount'] : $result['amount']['total'],
                        'login' => Xcart::app()->user->login,
                        'transaction_log' => array_merge($result, ['xcart_log' => $order_log])
                    ]
                );

                if ($transactionLog->isValid()) {
                    $transactionLog->save();
                }
            }
        }
    }
}