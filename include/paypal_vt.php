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

global $REQUEST_METHOD, $mode, $top_message, $order_transaction_id, $paypal_vt, $transaction_status, $AJAX_SUBMIT, $login;

$orderTransaction = null;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, ["authorize", "void_transaction", "capture_transaction", "re_authorize_transaction", "refund_transaction", "self_transaction", "look_up_payment", "add_manual_transaction"])) {
    $gw = $log = null;
    $result = false;
    /** @var OrderModel $orderModel */
    $orderModel = OrderModel::objects()->get(['orderid' => $orderid]);
    if (!empty($order_transaction_id) && $orderTransaction = OrderTransactionModel::objects()->get(['id' => $order_transaction_id])) {
        $gw = Gateway::getGateway($orderTransaction->payment_method_model);
    }
    switch ($mode) {
        case 'authorize' :
            $log .= "'Authorize' at 'Authorization'";
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
                if ($isAllowed) {
                    $paymentid = $gw->model->paymentid;
                    if ($res = $gw->authorize(PaymentHelper::prepareAuthorize($paypal_vt, $orderModel))) {
                        if (!$orderTransaction) {
                            $orderTransaction = new OrderTransactionModel(['orderid' => $orderModel->orderid]);
                        }
                        $orderTransaction->setAttributes(
                            [
                                'transaction_id' => $gw->result->getTransactionReference(),
                                'transaction_status' => OrderTransactionModel::STATUS_AUTHORIZED,
                                'transaction_currency' => $paypal_vt["currency"],
                                'transaction_total' => $paypal_vt["grand_total"],
                                'transaction_response' => $gw->result->getData(),
                            ]
                        );
                        $orderTransaction->save();
                        $log .= "<br />Transaction:" . $orderTransaction->transaction_id;
                        if (!$countTr) {
                            list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                            $log .= $o_log;
                            if ($send_notification) {
                                func_send_order_status_notification($orderModel->orderid, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                            }
                        }
                    } else {
                        //$orderTransaction->transaction_status = "failed";
                        $log .= "<br />Failed. " . $gw->result->getMessage();
                    }
                } else {
                    if (!$isAllowed && !$countTr && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {
                        $section_name_top_message = [
                            'type' => 'E',
                            'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
                        ];
                        x_session_save("section_name_top_message");
                        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
                    }
                }
            }
            break;
        case 'void_transaction' :
            $log .= "'Void authorized transaction' at 'Virtual Terminal'";
            if ($orderTransaction && !empty($orderTransaction->transaction_id)) {
                $log .= "'Void authorized transaction' at 'Virtual Terminal'";
                if ($gw) {
                    switch ($gw->gateway->getName()) {
                        case 'BluePay' :
                            if ($res = $gw->void([
                                'transaction_id' => $orderTransaction->transaction_id,
                            ])
                            ) {
                                $transaction_status = "voided";
                                $transaction_id = $gw->result->getTransactionReference();
                            } else {
                                $transaction_status = "failed";
                            }

                            $transaction_currency = $orderTransaction->transaction_currency;
                            $transaction_total = $transaction_amount[$order_transaction_id];

                            $result = $gw->result->getData();
                            $orderTransaction->transaction_response = $result;

                            break;
                        default :
                            if (!empty($Access_Token)) {
                                $result = func_paypal_void($Access_Token, $orderTransaction->transaction_id);
                                $transaction_id = $result["id"];
                                $transaction_status = $result["state"];
                                $transaction_currency = $result["amount"]["currency"];
                                $transaction_total = $result["amount"]["total"];
                            }
                            break;
                    }
                }
            }
            break;
        case 'capture_transaction' :
            if ($orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
                $log .= "'Capture authorized transaction' at 'Virtual Terminal'";
                if ($gw) {
                    if ($res = $gw->capture(
                            [
                                'transaction_id' => $orderTransaction->transaction_id,
                                'amount' => $transaction_amount[$order_transaction_id]
                            ]
                        )
                    ){
                        $log .= "<br />Transaction: {$orderTransaction->transaction_id} -> {$gw->result->getTransactionReference()}";
                        $log .= "<br />state: " . $result["state"];

                        $orderTransaction->setAttributes(
                            [
                                'transaction_id' => $gw->result->getTransactionReference(),
                                'transaction_status' => OrderTransactionModel::STATUS_COMPLETED,
                                'transaction_currency' => $paypal_vt["currency"],
                                'transaction_total' => $transaction_amount[$order_transaction_id],
                                'transaction_response' => $gw->result->getData(),
                            ]
                        );
                        $orderTransaction->save();
                        func_send_order_status_notification($orderid, OrderStatusModel::ORDER_STATUS_COMPLETED);
                        $log .= "<br />state: " . $orderTransaction->transaction_status;

                    } else {
                       /* if ($result['name'] == 'AUTHORIZATION_EXPIRED') {
                            $transaction_status = 'Expired';
                            $orderTransaction->transaction_status = $transaction_status;
                            $orderTransaction->transaction_response = $result;
                            $orderTransaction->save();
                        }
                        $log .= "<br />{$result['name']}";*/
                        $log .= "<br />{$gw->result->getMessage()}";
                    }


                    switch ($gw->gateway->getName()) {
                        case 'BluePay' :
                            if ($res = $gw->capture([
                                'transaction_id' => $orderTransaction->transaction_id,
                                'amount' => $transaction_amount[$order_transaction_id]
                            ])
                            ) {
                                $result = $gw->result->getData();
                                $orderTransaction->transaction_response = $result;
                                $result["state"] = 'completed';
                                $result['id'] = $gw->result->getTransactionReference();
                                $result["amount"]["currency"] = $orderTransaction->transaction_currency;
                                $result["amount"]["total"] = $transaction_amount[$order_transaction_id];
                                $transaction_id = $result["id"];
                                $transaction_status = $result["state"];
                                $transaction_currency = $result["amount"]["currency"];
                                $transaction_total = $result["amount"]["total"];
                                $orderTransaction->transaction_response =
                                    array_merge(
                                        $orderTransaction->transaction_response,
                                        ['links' => [
                                            ['rel' => 'refund']
                                        ]]);
                                func_send_order_status_notification($orderid, "P");
                                $log .= "<br />Transaction: {$orderTransaction->transaction_id} -> {$result['id']}";
                                $log .= "<br />state: " . $result["state"];
                            } else {
                                $log .= "<br />{$gw->result->getMessage()}";
                            }
                            $orderTransaction->save();
                            break;
                        default:
                            if (!empty($Access_Token)) {
                                $data_arr["amount"]["currency"] = $orderTransaction->transaction_currency;
                                $data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
                                $data_arr["is_final_capture"] = false; // true
                                //$result = func_paypal_capture($Access_Token, $transaction_info["transaction_id"], $data_arr);
                                $result = $oPaypal->captureTransaction($orderTransaction->transaction_id, $data_arr);
                                $aResultStates = array('pending', 'completed', 'refunded', 'partially_refunded');
                                if (!empty($result['state'])) {
                                    switch ($result['state']) {
                                        case  'completed' :
                                            $log .= "<br />Transaction: {$orderTransaction->transaction_id} -> {$result['id']}";
                                            $transaction_id = $result["id"];
                                            $transaction_status = $result["state"];
                                            $transaction_currency = $result["amount"]["currency"];
                                            $transaction_total = $result["amount"]["total"];
                                            func_send_order_status_notification($orderid, "P");
                                            break;
                                        default :
                                            $log .= "<br />Transaction: {$orderTransaction->transaction_id} -> {$result['id']}";
                                            $log .= "<br />state: " . $result["state"];
                                    }
                                } else {
                                    if ($result['name'] == 'AUTHORIZATION_EXPIRED') {
                                        $transaction_status = 'Expired';
                                        $orderTransaction->transaction_status = $transaction_status;
                                        $orderTransaction->transaction_response = $result;
                                        $orderTransaction->save();
                                    }
                                    $log .= "<br />{$result['name']}";
                                    $log .= "<br />{$result['message']}";
                                }
                            }
                    }

                }
            }
            break;
        case 're_authorize_transaction' :
            if ($orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
                $log .= "'RE-authorize transaction' at 'Virtual Terminal'";
                if (!empty($Access_Token)) {
                    $data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
                    $data_arr["amount"]["currency"] = $orderTransaction->transaction_currency;
                    $result = func_paypal_reauthorize($Access_Token, $orderTransaction->transaction_id, $data_arr);
                    if ($result["state"] == "authorized") {
                        $transaction_id = $result["id"];
                        $transaction_status = $result["state"];
                        $transaction_currency = $result["amount"]["currency"];
                        $transaction_total = $result["amount"]["total"];
                    }
                }
            }
            break;
        case 'refund_transaction' :
            if ($orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
                $log .= "'Refund transaction' at 'Virtual Terminal'";
                if ($gw) {
                    switch ($gw->model->module_name) {
                        case 'BluePay' :
                            if ($res = $gw->refund([
                                'transaction_id' => $orderTransaction->transaction_id,
                                'amount' => $transaction_amount[$order_transaction_id]
                            ])
                            ) {
                                $transaction_status = "refunded";
                                $transaction_id = $gw->result->getTransactionReference();
                            } else {
                                $transaction_status = "failed";
                            }

                            $transaction_currency = $orderTransaction->transaction_currency;
                            $transaction_total = $transaction_amount[$order_transaction_id];

                            $result = $gw->result->getData();
                            $orderTransaction->transaction_response = $result;

                            break;
                        default :
                            if (!empty($Access_Token)) {
                                $data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
                                $data_arr["amount"]["currency"] = $orderTransaction->transaction_currency;
                                $result = func_paypal_refund($Access_Token, $orderTransaction->transaction_id, $data_arr);
                                if (!empty($result["id"])) {
                                    $transaction_id = $result["id"];
                                    if ($result["state"] == "completed") {
                                        $transaction_status = "refunded";
                                    } else {
                                        $transaction_status = $result["state"];
                                    }
                                    $transaction_currency = $result["amount"]["currency"];
                                    $transaction_total = $result["amount"]["total"];
                                }
                            }
                            break;
                    }
                }
            }
            break;
        case 'self_transaction' :
            if ($orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
                $log .= "'Self transaction' at 'Virtual Terminal'";
                $transaction_status = $orderTransaction->transaction_status;
                if (!empty($Access_Token)) {
                    if (!empty($result["id"])) {
                        $transaction_id = $result["id"];
                    }
                }
            }
            break;
        case 'look_up_payment' :
            if ($orderTransaction && !empty($orderTransaction->transaction_id)) {
                $log .= "'Look up payment (Get links)' at 'Virtual Terminal'";
                $transaction_status = $orderTransaction->transaction_status;
                if ($gw) {
                    switch ($gw->gateway->getName()) {
                        case 'BluePay':
                            $orderTransaction->transaction_response =
                                $gw->lookup([
                                    'transaction_id' => $orderTransaction->transaction_id,
                                    'transaction_status' => $orderTransaction->transaction_status
                                ]);
                            $transaction_total = $orderTransaction->transaction_amount;
                            $orderTransaction->save();
                            break;
                        default:
                            if (!empty($Access_Token)) {
                                $transaction_type = "authorization";
                                if (in_array(strtolower($transaction_status), array('completed', 'p'))) {
                                    $transaction_type = "capture";
                                } elseif (in_array(strtolower($transaction_status), array('refunded', 'refund'))) {
                                    $transaction_type = "refund";
                                }
                                $result = func_paypal_look_up_payment($Access_Token, $orderTransaction->transaction_id, $transaction_type);
                                if (!empty($result["id"])) {
                                    $transaction_id = $result["id"];
                                    $transaction_total = (empty($result["amount"]["total"])) ? $orderTransaction->transaction_amount : $result["amount"]["total"];
                                    switch ($result['state']) {
                                        case 'expired':
                                            $transaction_status = 'Expired';
                                            break;
                                        case 'pending':
                                            $transaction_status = 'Pending';
                                            $transaction_currency = $orderTransaction->transaction_currency;
                                            break;
                                        case 'completed':
                                        case 'refunded':
                                            $transaction_status = $result['state'];
                                            $transaction_currency = $orderTransaction->transaction_currency;
                                            break;
                                    }
                                    $orderTransaction->transaction_status = $transaction_status;
                                    $orderTransaction->transaction_response = $result;
                                    $orderTransaction->transaction_amount = $transaction_total;
                                    $orderTransaction->save();
                                }
                            }
                            break;

                    }
                }
            }
            break;
        case 'add_manual_transaction' :
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
            break;
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
