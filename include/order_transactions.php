<?php

global $smarty, $order;

use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
global $orderid;
$order_transactions = null;
$orderModel = OrderModel::objects()->get(['orderid' => $orderid]);
if ($orderModel && $orderModel->transactions) {
    $captured_total = 0;
    $authorized_total = 0;
    $void_total = 0;

    foreach ($orderModel->transactions as $k_order_transaction => $transactionModel) {
        $order_transactions[$k_order_transaction]['model'] = $transactionModel;
        if (empty($transactionModel->transaction_response) && !empty($transactionModel->transaction_id)) {
            if ($transactionModel = OrderTransactionHelper::action('lookup', $transactionModel)) {
                $transactionModel->save();
            }
        }
        if (!empty($transactionModel->transaction_response)) {
            $unserialized_transaction_response = $transactionModel->transaction_response;
            if (is_array($unserialized_transaction_response)) {
                $order_transactions[$k_order_transaction]["unserialized_transaction_response"] = $unserialized_transaction_response;

                if (!empty($unserialized_transaction_response["details"][0]["issue"])) {
                    $order_transactions[$k_order_transaction]["issue"] = $unserialized_transaction_response["details"][0]["issue"];
                }
            }
        }
        if (in_array(strtolower($transactionModel->transaction_status), array('completed', 'p'))) {
            $captured_total += $transactionModel->transaction_amount;
        } elseif (in_array(strtolower($transactionModel->transaction_status), array('authorized', 'pending', 'ap'))) {
            $authorized_total += $transactionModel->transaction_amount;
        } elseif (in_array(strtolower($transactionModel->transaction_status), array('voided'))) {
            $void_total += $transactionModel->transaction_amount;
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