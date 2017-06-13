<?php
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Payment\Models\PaymentProcessorModel;
use Omnipay\Common\CreditCard;
use Xcart\Paypal;

if (!defined('XCART_SESSION_START')) { header("Location: ../");  die("Access denied"); }

global $REQUEST_METHOD, $mode, $top_message, $order_transaction_id, $paypal_vt, $transaction_status, $AJAX_SUBMIT, $login;

$gw = $log = $orderTransaction = $result = $countTr =null;
$isAllowed = true;
$params = [];

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array_keys(Gateway::$gatewayMethods))) {

    /** @var OrderModel $orderModel */
    $orderModel = OrderModel::objects()->get(['orderid' => $orderid]);
    if (!empty($order_transaction_id) && $orderTransaction = OrderTransactionModel::objects()->get(['id' => $order_transaction_id])) {
        $pmModel = $orderTransaction->payment_method_model->get();
        $gw = Gateway::getGateway($orderTransaction->payment_method_model);
    }

    switch ($mode) {
        case 'authorize' :
            /** @var PaymentMethodModel $pmModel */
            if ($pmModel = PaymentMethodModel::objects()->get(['payment_method' => $paypal_vt["processor"]])) {
                $gw = Gateway::getGateway($pmModel);
            }
            if ($gw) {
                $countTr = OrderTransactionModel::objects()
                    ->filter(['orderid' => $orderid])
                    ->exclude(['transaction_status' => '', 'transaction_id' => ''])
                    ->count();
                $isAllowed = PaymentHelper::isAuthorizeAllowed($orderModel, $countTr);
                $params = PaymentHelper::prepareAuthorize($paypal_vt, $orderModel);
            }
            break;
        case 'void_transaction' :
            break;
        case 'capture_transaction' :
            $params = [
                'transactionReference' => $orderTransaction->transaction_id,
                'amount' => $transaction_amount[$order_transaction_id],
                'currency' => $orderTransaction->transaction_currency,
            ];
            break;
        case 're_authorize_transaction' :
            break;
        case 'refund_transaction' :
            break;
        case 'look_up_payment' :
            break;
    }
    if ($isAllowed) {
        if ($gw && isset(Gateway::$gatewayMethods[$mode])) {
            $method = Gateway::$gatewayMethods[$mode]['method'];
            $status = Gateway::$gatewayMethods[$mode]['status'];
            $log .= Gateway::$gatewayMethods[$mode]['log'];
            if ($res = $gw->$method($params)) {
                if ($mode == 'authorize') {
                    $orderTransaction = new OrderTransactionModel(['orderid' => $orderModel->orderid]);
                }
                $orderTransaction->setAttributes(
                    [
                        'transaction_id' => $gw->result->getTransactionReference(),
                        'transaction_status' => $status,
                        'transaction_currency' => $params["currency"],
                        'transaction_amount' => $params["amount"],
                        'transaction_response' => $gw->result->getData(),
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
                if ($result['name'] == 'AUTHORIZATION_EXPIRED') {
                    $transaction_status = 'Expired';
                    $orderTransaction->transaction_status = $transaction_status;
                    $orderTransaction->transaction_response = $result;
                    $orderTransaction->save();
                }
                $log .= "<br />{$result['name']}";
                $log .= "<br />{$result['message']}";
            }
        } else {
            if ($mode == 'add_manual_transaction'){
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
            $section_name_top_message = [
                'type' => 'E',
                'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
            ];
            x_session_save("section_name_top_message");
            func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
        }
    }



    $result["xcart_log"] = $log;
    $result["POST_params"] = $data_arr;
    $serialize_result = $result;
    if (empty($paymentid)) {
        $paymentid = 5;
        if ($gw) {
            if ($pmVT = PaymentMethodModel::objects()
                ->filter(['payment_method' => $gw->gateway->getName() . ' VT'])
                ->limit(1)
                ->get()
            ) {
                $paymentid = $pmVT->paymentid;
            }
        }
    }
    $transactionLog = new TransactionLogModel;
    $transactionLog->setAttributes([
        'orderid' => $orderid,
        'paymentid' => $paymentid,
        'transaction_id' => (empty($transaction_id)) ? $orderTransaction->transaction_id : $transaction_id,
        'transaction_status' => (empty($transaction_status)) ? $orderTransaction->transaction_status : $transaction_status,
        'transaction_currency' => (empty($transaction_currency)) ? (empty($orderTransaction->transaction_currency) ? $paypal_vt["currency"] : null) : $transaction_currency,
        'transaction_total' => (empty($transaction_total)) ? (empty($orderTransaction->transaction_amount) ? $paypal_vt["grand_total"] : null) : $transaction_total,
        'date' => time(),
        'login' => $login,
        'transaction_log' => $serialize_result
    ]);
    if ($transactionLog->isValid()) {
        $transactionLog->save();
    }
    func_log_order($orderid, 'PP', $serialize_result, $login);
    if (!empty($transaction_id)) {
        if (in_array($mode, ["authorize", "add_manual_transaction"])) {
            $orderTransactionNew = new OrderTransactionModel;
            if ($orderTransaction) {
                $orderTransactionNew->setAttributes($orderTransaction->getAttributes());
            }
            $orderTransactionNew->setAttributes([
                'transaction_id' => $transaction_id,
                'transaction_response' => $serialize_result,
                'transaction_status' => $transaction_status,
                'login' => $login,
                'date' => time()
            ]);
            $orderTransactionNew->id = null;
            if ($mode == "add_manual_transaction") {
                $orderTransactionNew->manual_transaction = "Y";
            }
            $orderTransactionNew->orderid = $orderid;
            $orderTransactionNew->paymentid = $paymentid;
            $orderTransactionNew->transaction_currency = $transaction_currency;
            $orderTransactionNew->transaction_amount = $transaction_total;
            if ($orderTransactionNew->isValid()) {
                $orderTransactionNew->save();
            }
        } else {
            if ($orderTransaction) {
                if (in_array($mode, ["re_authorize_transaction", "capture_transaction", "refund_transaction"])) {
                    $orderTransaction->transaction_amount = $transaction_amount[$order_transaction_id];
                    $orderTransaction->parent_transaction_id = $orderTransaction->transaction_id;
                }
                $orderTransaction->setAttributes([
                    'transaction_id' => $transaction_id,
                    'transaction_response' => $serialize_result,
                    'transaction_status' => $transaction_status,
                    'login' => $login,
                    'date' => time()
                ]);
                if ($orderTransaction->isValid()) {
                    $orderTransaction->save();
                }
            }
        }
    }
    if (!($mode == "authorize" && $AJAX_SUBMIT == "Y")) {
        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
    }
}
