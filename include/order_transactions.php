<?php

global $smarty, $order;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

$order_transactions = func_query("SELECT $sql_tbl[order_transactions].*, $sql_tbl[payment_methods].payment_method, $sql_tbl[payment_methods].transaction_id_link, $sql_tbl[payment_methods].transaction_link_anchor, $sql_tbl[customers].firstname, $sql_tbl[customers].usertype FROM $sql_tbl[order_transactions] LEFT JOIN $sql_tbl[payment_methods] ON $sql_tbl[payment_methods].paymentid=$sql_tbl[order_transactions].paymentid LEFT JOIN $sql_tbl[customers] ON $sql_tbl[customers].login=$sql_tbl[order_transactions].login WHERE $sql_tbl[order_transactions].orderid='$orderid' ORDER BY $sql_tbl[order_transactions].date DESC");

if (!empty($order_transactions)) {
    $captured_total = 0;
    $authorized_total = 0;
    $void_total = 0;
    foreach ($order_transactions as $k_order_transaction => $v_order_transaction) {
        if (empty($v_order_transaction["transaction_response"]) && !empty($v_order_transaction["transaction_id"])) {
            $transaction_type = "";
            if (!isset($Access_Token)) {
                $Access_Token = func_paypal_get_access_token();
            }
            if (!empty($Access_Token)) {
                if (in_array(strtolower($v_order_transaction["transaction_status"]), array('completed', 'p'))) {
                    $transaction_type = "capture";
                } elseif (in_array(strtolower($v_order_transaction["transaction_status"]), array('authorized', 'pending', 'ap'))) {
                    $transaction_type = "authorization";
                }
            }
            if (!empty($transaction_type)) {
                $result = func_paypal_look_up_payment($Access_Token, $v_order_transaction["transaction_id"], $transaction_type);
                $v_order_transaction["transaction_response"] = $order_transactions[$k_order_transaction]["transaction_response"] = serialize($result);
                db_query($qqq = "UPDATE $sql_tbl[order_transactions] SET transaction_response='" . addslashes($v_order_transaction["transaction_response"]) . "' WHERE id='$v_order_transaction[id]'");
            }
        }
        if (!empty($v_order_transaction["transaction_response"])) {
            $unserialized_transaction_response = unserialize($v_order_transaction["transaction_response"]);
            if (is_array($unserialized_transaction_response)) {
                $order_transactions[$k_order_transaction]["unserialized_transaction_response"] = $unserialized_transaction_response;

                if (!empty($unserialized_transaction_response["details"][0]["issue"])) {
                    $order_transactions[$k_order_transaction]["issue"] = $unserialized_transaction_response["details"][0]["issue"];
                }
            }
        }
        if (in_array(strtolower($v_order_transaction["transaction_status"]), array('completed', 'p'))) {
            $captured_total += $v_order_transaction["transaction_amount"];
        } elseif (in_array(strtolower($v_order_transaction["transaction_status"]), array('authorized', 'pending', 'ap'))) {
            $authorized_total += $v_order_transaction["transaction_amount"];
        } elseif (in_array(strtolower($v_order_transaction["transaction_status"]), array('voided'))) {
            $void_total += $v_order_transaction["transaction_amount"];
        }
    }
    $order_transactions_totals["captured_total"] = price_format($captured_total);
    $order_transactions_totals["authorized_total"] = price_format($authorized_total);
    $order_transactions_totals["void_total"] = price_format($void_total);
    $order_transactions_totals["authorized_PLUS_captured_totals"] = price_format($captured_total + $authorized_total);
    $smarty->assign("order_transactions_totals", $order_transactions_totals);
}
$count_shipping_groups = count($order["shipping_groups"]);
$smarty->assign("count_shipping_groups", $count_shipping_groups);
$smarty->assign("order_transactions", $order_transactions);