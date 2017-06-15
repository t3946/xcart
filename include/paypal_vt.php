<?php
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;

if (!defined('XCART_SESSION_START')) { header("Location: ../"); die("Access denied");}
global $REQUEST_METHOD, $mode, $top_message, $order_transaction_id, $paypal_vt, $transaction_status, $AJAX_SUBMIT, $login;
$gw = $log = $orderTransaction = $result = $countTr = $logStatus = $params = null;
$isAllowed = true;

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array_keys(Gateway::$gatewayMethods))) {
    extract(Gateway::$gatewayMethods[$mode]);
    /** @var OrderModel $orderModel */
    /** @var PaymentMethodModel $pmModel */
    $orderModel = OrderModel::objects()->get(['orderid' => $orderid]);
    if ($order_transaction_id && ($orderTransaction = OrderTransactionModel::objects()->get(['id' => $order_transaction_id]))) {
        $pmModel = $orderTransaction->payment_method_model;
        $amount = [
            'amount' => number_format(trim($transaction_amount[$order_transaction_id]),2),
            'currency' => $orderTransaction->transaction_currency
        ];
        $params = PaymentHelper::getPaymentParams($orderTransaction, $amount);
    } else {
        $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $paypal_vt["processor"]]);
        $countTr = OrderTransactionModel::objects()
            ->filter(['orderid' => $orderid])
            ->exclude(['transaction_status' => '', 'transaction_id' => ''])
            ->count();
        $isAllowed = PaymentHelper::isAuthorizeAllowed($orderModel, $countTr);
        $params = PaymentHelper::prepareAuthorize($paypal_vt, $orderModel);
    }
    if (!$orderTransaction) {
        $orderTransaction = new OrderTransactionModel(['orderid' => $orderModel->orderid]);
    }

    try {
        if ($isAllowed) {
            if ($gw = Gateway::getGateway($pmModel)) {
                if ($res = $gw->$method($params)) {
                    $result = $gw->result->getData();
                    if (!$result['amount']) {
                        $result['amount'] = $amount;
                    }
                    if (in_array($mode, ["re_authorize_transaction", "capture_transaction", "refund_transaction"])) {
                        $orderTransaction->parent_transaction_id = $orderTransaction->transaction_id;
                    }
                    $orderTransaction->setAttributes(
                        [
                            'transaction_id' => $gw->result->getTransactionReference(),
                            'transaction_status' => ($logStatus = $gw->getState($mode)),
                            'transaction_currency' => $result['amount']["currency"],
                            'transaction_amount' => abs($result['amount']['total']),
                            'transaction_response' => $result,
                            'login' => $login,
                            'paymentid' => $pmModel->paymentid
                        ]
                    );
                    $orderTransaction->save();

                    $log .= "<br />Transaction:" . $orderTransaction->transaction_id;
                    if (!$countTr && $mode == 'authorize') {
                        list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                        $log .= $o_log;
                        if ($send_notification) {
                            func_send_order_status_notification($orderModel->orderid, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                        }
                    }
                } else {
                    $result = $gw->result->getData();
                    if ($orderTransaction && ($state = $gw->getState($mode))){
                        $orderTransaction->transaction_status = $state;
                        $orderTransaction->transaction_response = $result;
                        $orderTransaction->save();
                    }
                    $log .= "<br/>{$result['name']}";
                    $log .= "<br/>{$result['message']}";
                }
            } else {
                if ($mode == 'add_manual_transaction') {
                    $transaction_id = trim($transaction_id);
                    $transaction_amount = trim($transaction_amount);

                    if ($transaction_status == "authorized") {
                        $transaction_type = "authorization";
                        $set_cb_status_for_first_transaction = "AP";
                    } else {
                        $transaction_type = "capture";
                        $set_cb_status_for_first_transaction = "P";
                    }
                    $count_transactions = OrderTransactionModel::objects()
                        ->filter(['orderid' => $orderid])
                        ->exclude(['transaction_status' => '', 'transaction_id' => ''])
                        ->count();
                    $allowed_statuses_flag = func_check_for_the_allowed_statuses_for_create_payment($order);
                    if (!$allowed_statuses_flag && empty($count_transactions) && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {
                        $top_message = array(
                            'type' => 'E',
                            'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
                        );
                        $section_name_top_message = $top_message;
                        x_session_save("section_name_top_message");
                        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
                    }
                    if ($allowed_statuses_flag && empty($count_transactions) && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {
                        $new_cb_status_flag = false;
                        $new_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$set_cb_status_for_first_transaction'");
                        foreach ($order["shipping_groups"] as $ko => $vo) {
                            if (in_array($vo["cb_status"], array('Q', 'N', 'I'))) {
                                db_query("UPDATE $sql_tbl[order_groups] SET cb_status='$set_cb_status_for_first_transaction' WHERE orderid='$orderid' AND manufacturerid='$ko'");
                                $current_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='" . $vo["cb_status"] . "'");
                                $log .= "<br /><B>" . $vo["all_distributor_info"]["code"] . ":</B> cb_status: " . $current_cb_status_value . " -> " . $new_cb_status_value . "<br />";
                                $new_cb_status_flag = true;
                            }
                        }
                        if ($new_cb_status_flag) {
                            db_query("UPDATE $sql_tbl[orders] SET cb_status='$set_cb_status_for_first_transaction' WHERE orderid='$orderid'");
                            func_send_order_status_notification($orderid, $set_cb_status_for_first_transaction);
                        }
                    }
                    $result = func_paypal_look_up_payment($Access_Token, $transaction_id, $transaction_type);
                    $transaction_total = $transaction_amount;
                    $result["FIELD_manual_transaction"] = "Y";
                    $result["FIELD_avs_code"] = $avs_code;
                    $log .= "'Add transaction' at 'Add manual transaction' section";
                }
            }
        } else {
            if (!$countTr && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {
                $top_message = [
                    'type' => 'E',
                    'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
                ];
                x_session_save("section_name_top_message");
                func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
            }
        }
    } catch (\Exception $e) {
        $log .= "<br/>{$gw->getProcessorName()} Processing Error: {$e->getMessage()}";
        $logStatus = OrderTransactionModel::STATUS_FAILED;
    }
    $result["xcart_log"] = $log;
    $transactionLog = new TransactionLogModel([
        'orderid' => $orderid,
        'paymentid' => $pmModel->paymentid,
        'transaction_id' => isset($orderTransaction) ? $orderTransaction->transaction_id : '',
        'transaction_status' => $logStatus,
        'transaction_currency' => !isset($result['amount']) ? $params['currency'] : $result['amount']['currency'],
        'transaction_total' => !isset($result['amount']) ? $params['amount'] : $result['amount']['total'],
        'login' => $login,
        'transaction_log' => $result
    ]);
    if ($transactionLog->isValid()) {
        $transactionLog->save();
    }
    func_log_order($orderid, 'PP', $log, $login);

    if (!($mode == "authorize" && $AJAX_SUBMIT == "Y")) {
        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
    }
}
