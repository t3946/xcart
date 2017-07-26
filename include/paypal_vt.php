<?php
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
global $REQUEST_METHOD, $mode, $top_message, $order_transaction_id, $paypal_vt, $transaction_status, $AJAX_SUBMIT;
global $paymentid, $login, $transaction_currency, $transaction_amount;

$gw = $log = $orderTransaction = $countTr = $logStatus = $params = null;
$result = [];

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array_keys(Gateway::$gatewayMethods))) {
    /** @var OrderModel $orderModel */
    /** @var PaymentMethodModel $pmModel */
    if ($orderModel = OrderModel::objects()->get(['orderid' => $orderid])) {

        extract(Gateway::$gatewayMethods[$mode]);

        $orderModel->transactions->exclude(['transaction_status' => '', 'transaction_id' => ''])->count();

        $isAllowed = PaymentHelper::isAuthorizeAllowed($orderModel, $countTr);

        if ($order_transaction_id && ($orderTransaction = OrderTransactionModel::objects()->get(['id' => $order_transaction_id]))) {

            $isAllowed = true;
            $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $orderTransaction->payment_method_model->processor->processor_name . ' VT']);

            $amount =
                [
                    'amount' => number_format(trim($transaction_amount[$order_transaction_id]), 2, '.', ''),
                    'currency' => $orderTransaction->transaction_currency
                ];

            $params = PaymentHelper::getPaymentParams($orderTransaction, $amount);

        } elseif (!empty($transaction_id) && $paymentid) { //manual transaction

            $orderTransaction = new OrderTransactionModel(
                [
                    'orderid' => $orderModel->orderid,
                    'transaction_id' => trim($transaction_id),
                    'transaction_status' => $transaction_status
                ]);

            $pmModel = PaymentMethodModel::objects()->get(['paymentid' => $paymentid]);

            $amount =
                [
                    'amount' => number_format(trim($transaction_amount), 2, '.', ''),
                    'currency' => $transaction_currency
                ];
            $params = PaymentHelper::getPaymentParams($orderTransaction, $amount);
        } else {  //authorize

            $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $paypal_vt["processor"]]);
            $params = PaymentHelper::prepareAuthorize($paypal_vt, $orderModel);

        }

        if (!$isAllowed) {
            if (!$countTr && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {

                $top_message = [
                    'type' => 'E',
                    'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
                ];

                x_session_save("section_name_top_message");
                func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");

            }
        }

        try {
            if ($gw = Gateway::getGateway($pmModel->processor)) {

                if ($gw->$method($params)) {

                    if ($orderTransaction = OrderTransactionHelper::action('lookup', $orderTransaction, PaymentHelper::getPaymentParams($orderTransaction))) {
                        $orderTransaction->save();
                    }

                    if ($orderTransaction->transaction_status != OrderTransactionModel::STATUS_REFUNDED) {
                        $orderTransaction = OrderTransactionHelper::prepareOrderTransaction($orderTransaction, $gw, $orderModel, $pmModel, $amount, $mode);
                        $orderTransaction->login = $login;
                        $orderTransaction->save();
                    }

                    $result = $orderTransaction->transaction_response;

                    list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                    $log .= "<br />Transaction:" . $orderTransaction->transaction_id;
                    $log .= $o_log;

                    if (!$countTr && $send_notification) {
                        func_send_order_status_notification($orderModel->orderid, OrderStatusModel::ORDER_STATUS_AUTHORIZED, true);
                    }
                } else {

                    $result = $gw->result->getData();
                    if ($orderTransaction && ($state = $gw->getState($mode))) {
                        $orderTransaction->transaction_status = $state;
                        $orderTransaction->transaction_response = $result;
                        $orderTransaction->save();
                    }

                    $log .= "<br/>{$result['name']}<br/>{$result['message']}";

                }
                $logStatus = $orderTransaction->transaction_status;
            }


        } catch (\Exception $e) {
            $log .= "<br/>{$gw->getProcessorName()} Processing Error: {$e->getMessage()}";
            $logStatus = OrderTransactionModel::STATUS_FAILED;
        }

        $transactionLog = new TransactionLogModel(
            [
                'orderid' => $orderid,
                'paymentid' => $pmModel->paymentid,
                'order_transaction_id' => isset($orderTransaction) ? $orderTransaction->id : null,
                'transaction_id' => isset($orderTransaction) ? $orderTransaction->transaction_id : '',
                'transaction_status' => $logStatus,
                'transaction_currency' => !isset($result['amount']) ? $params['currency'] : $result['amount']['currency'],
                'transaction_total' => !isset($result['amount']) ? $params['amount'] : $result['amount']['total'],
                'login' => $login,
                'transaction_log' => array_merge($result, ['xcart_log' => $log])
            ]
        );

        if ($transactionLog->isValid()) {
            $transactionLog->save();
        }

        func_log_order($orderid, 'PP', $log, $login);

        if (!($mode == "authorize" && $AJAX_SUBMIT == "Y")) {
            func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
        }
    }
}
