<?php
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Xcart\Paypal;

global $REQUEST_METHOD, $mode, $top_message, $order_transaction_id, $paypal_vt, $transaction_status, $AJAX_SUBMIT, $login;

$orderTransaction = null;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array("authorize", "void_transaction", "capture_transaction", "re_authorize_transaction", "refund_transaction", "self_transaction", "look_up_payment", "add_manual_transaction"))) {
    $log = "";
    if (func_check_comma_in_field($orderid, $transaction_amount[$order_transaction_id], 'paypal_vt_transaction_amount')) {
        $top_message["content"] .= func_get_langvar_by_name("lbl_error_comma_in_number");
        $top_message["type"] = "I";
        $section_name_top_message = $top_message;
        x_session_save("section_name_top_message");
        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
    }
    if (func_check_comma_in_field($orderid, $paypal_vt["grand_total"], 'paypal_vt_grand_total')) {
        $top_message["content"] .= func_get_langvar_by_name("lbl_error_comma_in_number");
        $top_message["type"] = "I";
        $section_name_top_message = $top_message;
        x_session_save("section_name_top_message");
        func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
    }
    try {
        $oPaypal = new Paypal();
        $Access_Token = $oPaypal->getAccessToken();
    } catch (\Exception $e) {
        $oPaypal = null;
        $Access_Token = null;
    }
//	$Access_Token = func_paypal_get_access_token();
    if (empty($Access_Token)) {
        $log .= "'Access_Token' - failed <br />";
    } else {
        if (!empty($order_transaction_id)) {
            $orderTransaction = OrderTransactionModel::objects()->get(['id' => $order_transaction_id]);
        }
    }

    if ($mode == "authorize") {
        $log .= "'Authorize' at 'Authorization'";
        if (!empty($Access_Token)) {
            switch ($paypal_vt["card_number"]{0}) {
                case '3':
                    $credit_card_typy = "amex";
                    break;
                case '4':
                    $credit_card_typy = "visa";
                    break;
                case '5':
                    $credit_card_typy = "mastercard";
                    break;
                case '6':
                    $credit_card_typy = "discover";
                    break;
                default:
                    $credit_card_typy = "";
            }
            foreach ($paypal_vt as $key => $val) {
                $val = func_stripslashes(func_html_entity_decode($val));
                $val = htmlspecialchars_decode($val, ENT_QUOTES);
                $paypal_vt[$key] = $val;
            }
            $cardholderl_name = trim($paypal_vt["cardholderl_name"]);
            $cardholderl_name_arr = explode(" ", $cardholderl_name);
            $first_name = trim($cardholderl_name_arr[0]);
            unset($cardholderl_name_arr[0]);
            $last_name = implode(" ", $cardholderl_name_arr);
            $last_name = trim($last_name);
            $shipping_address_type = 'residential';
            if (!empty($order["extra"]["additional_fields"]) && is_array($order["extra"]["additional_fields"])) {
                foreach ($order["extra"]["additional_fields"] as $k_ea => $v_ea) {
                    if (!empty($v_ea["value"]) && $v_ea["title"] == "Company" && $v_ea["section"] == "S") {
                        $shipping_address_type = 'business';
                    }
                }
            }
            $data_json = '{
		        "intent":"authorize",
		        "payer":{
                		"payment_method":"credit_card",
		                "funding_instruments":[
                		        {
                                		"credit_card":{
		                                        "number":"' . $paypal_vt["card_number"] . '",
                		                        "type":"' . $credit_card_typy . '",
                                		        "expire_month":"' . $paypal_vt["expiration_month"] . '",
		                                        "expire_year":"' . substr(date("Y"), 0, 2) . $paypal_vt["expiration_year"] . '",
                		                        "cvv2":"' . $paypal_vt["csc"] . '",
		                                        "first_name":"' . ($first_name) . '",
                		                        "last_name":"' . ($last_name) . '",
                                		        "billing_address":{
                                                		"line1":"' . ($paypal_vt["b_address"]) . '",
		                                                "line2":"' . ($paypal_vt["b_address_2"]) . '",
                		                                "city":"' . ($paypal_vt["b_city"]) . '",
                                		                "state":"' . ($paypal_vt["b_state"]) . '",
                                                		"postal_code":"' . ($paypal_vt["b_zipcode"]) . '",
		                                                "country_code":"' . ($paypal_vt["b_country"]) . '"
                		                        }
                                		}
		                        }
                		],
		                "payer_info":{
                		        "email":"' . $order["email"] . '",
		                        "first_name":"' . ($first_name) . '",
                		        "last_name":"' . ($last_name) . '",
		                        "shipping_address":{
                		                "recipient_name":"' . ($order["s_firstname"]) . '",
		                                "type":"' . $shipping_address_type . '",
                		                "line1":"' . ($order["s_address"]) . (!empty($order["s_address_2"]) ? " " . ($order["s_address_2"]) : "") . '",
                		                "city":"' . ($order["s_city"]) . '",
		                                "state":"' . ($order["s_state"]) . '",
		                                "postal_code":"' . ($order["s_zipcode"]) . '",
                		                "country_code":"' . ($order["s_country"]) . '"
                		        }
		                }
		        },
		        "transactions":[
                		{
		                        "amount":{
                		                "total":"' . number_format($paypal_vt["grand_total"], 2) . '",
		                                "currency":"' . $paypal_vt["currency"] . '",
                		                "details":{
		                                        "subtotal":"' . number_format($paypal_vt["grand_total"], 2) . '",
                		                        "tax":"0.00",
                                		        "shipping":"0.00"
		                                }
                		        },
					"invoice_number":"' . $order["order_prefix"] . $order["orderid"] . '",
		                        "description":""
		                }
		        ]
		}';
            //$count_transactions = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_transactions] WHERE transaction_status!='' AND transaction_id!='' AND orderid='$orderid'");
            $count_transactions = OrderTransactionModel::objects()
                ->filter(['orderid' => $orderid])
                ->exclude(['transaction_status' => '', 'transaction_id' => ''])
                ->count();
            $allowed_statuses_flag = func_check_for_the_allowed_statuses_for_create_payment($order);
            if (($allowed_statuses_flag && empty($count_transactions) && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) || $count_transactions >= 1) {
                $result = func_paypal_create_payment($Access_Token, $data_json);
            } else {
                $result = false;
                if (!$allowed_statuses_flag && empty($count_transactions) && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {

                    $top_message = array(
                        'type' => 'E',
                        'content' => func_get_langvar_by_name("lbl_first_transaction_in_order_exception")
                    );
                    $section_name_top_message = $top_message;
                    x_session_save("section_name_top_message");
                    func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
                }
            }

            if (in_array($result["curl_getinfo"]["http_code"], array("200", "201"))) {
                $transaction_id = $result["transactions"][0]["related_resources"][0]["authorization"]["id"];
                if (!empty($transaction_id)) {
                    $result2 = func_paypal_look_up_payment($Access_Token, $transaction_id, "authorization");
                    if (!empty($result2["links"]) && is_array($result2["links"])) {
                        $result["links"] = $result2["links"];
                    }
                    $log .= "<br />Transaction:" . $transaction_id;
                    $transaction_status = $result["transactions"][0]["related_resources"][0]["authorization"]["state"];
                    $transaction_currency = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["currency"];
                    $transaction_total = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["total"];

//				db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");
                    if (empty($count_transactions) && !empty($order["shipping_groups"]) && is_array($order["shipping_groups"])) {
                        $new_cb_status_flag = false;
                        $new_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='AP'");
                        foreach ($order["shipping_groups"] as $ko => $vo) {
                            if (in_array($vo["cb_status"], array('Q', 'N', 'I'))) {
                                db_query("UPDATE $sql_tbl[order_groups] SET cb_status='AP' WHERE orderid='$orderid' AND manufacturerid='$ko'");
                                $current_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='" . $vo["cb_status"] . "'");
                                $log .= "<br /><B>" . $vo["all_distributor_info"]["code"] . ":</B> cb_status: " . $current_cb_status_value . " -> " . $new_cb_status_value;
                                $new_cb_status_flag = true;
                            }
                        }
                        if ($new_cb_status_flag) {
                            db_query("UPDATE $sql_tbl[orders] SET cb_status='AP' WHERE orderid='$orderid'");
                            func_send_order_status_notification($orderid, "AP");
                        }
                    }
                } else {
                    $log .= "<br />Failed. Empty transaction id";
                }
            } else {
                $log .= "<br />Failed. http_code: " . $result["curl_getinfo"]["http_code"];
            }
        } // if (!empty($Access_Token))
    } // if ($mode == "authorize")
    elseif ($mode == "void_transaction" && $orderTransaction && !empty($orderTransaction->transaction_id)) {
        $log .= "'Void authorized transaction' at 'Virtual Terminal'";
        if (!empty($Access_Token)) {
            $result = func_paypal_void($Access_Token, $orderTransaction->transaction_id);
            $transaction_id = $result["id"];
            $transaction_status = $result["state"];
            $transaction_currency = $result["amount"]["currency"];
            $transaction_total = $result["amount"]["total"];
        }
    } elseif ($mode == "capture_transaction" && $orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
        $log .= "'Capture authorized transaction' at 'Virtual Terminal'";
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
    } elseif ($mode == "re_authorize_transaction" && $orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
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
    } elseif ($mode == "refund_transaction" && $orderTransaction && !empty($orderTransaction->transaction_id) && !empty($transaction_amount[$order_transaction_id])) {
        $log .= "'Refund transaction' at 'Virtual Terminal'";
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
    } elseif ($mode == "self_transaction" && $orderTransaction && !empty($orderTransaction->transaction_id)) {
        $log .= "'Self transaction' at 'Virtual Terminal'";
        $transaction_status = $orderTransaction->transaction_status;
        if (!empty($Access_Token)) {
            if (!empty($result["id"])) {
                $transaction_id = $result["id"];
            }
        }
    } elseif ($mode == "look_up_payment" && $orderTransaction && !empty($orderTransaction->transaction_id)) {
        $log .= "'Look up payment (Get links)' at 'Virtual Terminal'";
        $transaction_status = $orderTransaction->transaction_status;
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
                if ($result['state'] == 'expired'){
                    $transaction_status = 'Expired';
                    $orderTransaction->transaction_status = $transaction_status;
                    $orderTransaction->transaction_response = $result;
                    $orderTransaction->save();
                }
            }
        }
    } elseif ($mode == "add_manual_transaction") {
        $transaction_id = trim($transaction_id);
        $transaction_amount = trim($transaction_amount);
        if (func_check_comma_in_field($orderid, $transaction_amount, 'manual_transaction_amount')) {
            $top_message["content"] .= func_get_langvar_by_name("lbl_error_comma_in_number");
            $top_message["type"] = "I";
            $section_name_top_message = $top_message;
            x_session_save("section_name_top_message");
            func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
        }
        if (empty($transaction_amount) || $transaction_amount <= 0 || empty($paymentid) || empty($transaction_id)) {
            $top_message = array(
                'type' => 'I',
                'content' => func_get_langvar_by_name("lbl_manual_transaction_no_required_fileds")
            );
            $section_name_top_message = $top_message;
            x_session_save("section_name_top_message");
            func_header_location("order.php?orderid=" . $orderid . "&tab=y#main_order_tabs-VT");
        }
        if ($transaction_status == "authorized") {
            $transaction_type = "authorization";
            $set_cb_status_for_first_transaction = "AP";
        } else {
            $transaction_type = "capture";
            $set_cb_status_for_first_transaction = "P";
        }
        //$count_transactions = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_transactions] WHERE transaction_status!='' AND transaction_id!='' AND orderid='$orderid'");
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
    $result["xcart_log"] = $log;
    $result["FIELD_transaction_id"] = $transaction_id;
    $result["FIELD_transaction_status"] = $transaction_status;
    $result["FIELD_transaction_currency"] = $transaction_currency;
    $result["FIELD_transaction_total"] = $transaction_total;
    $result["POST_params"] = $data_arr;
    $serialize_result = serialize($result);
    if (empty($paymentid)) {
        $paymentid = "5";
    }
    $transactionLog = new TransactionLogModel;
    $transactionLog->setAttributes([
        'orderid' => $orderid,
        'paymentid' => $paymentid,
        'transaction_id' => empty($transaction_id) ? $orderTransaction->transaction_id : $transaction_id,
        'transaction_status' => empty($transaction_status) ? $orderTransaction->transaction_status : $transaction_status,
        'transaction_currency' => empty($transaction_currency) ? $orderTransaction->transaction_currency : $transaction_currency,
        'transaction_total' => empty($transaction_total) ? $orderTransaction->transaction_amount : $transaction_total,
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
                $orderTransaction->setAttributes([
                    'transaction_id' => $transaction_id,
                    'transaction_response' => $serialize_result,
                    'transaction_status' => $transaction_status,
                    'login' => $login,
                    'date' => time()
                ]);
            }
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
