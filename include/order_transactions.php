<?php

global $smarty, $order, $orderid;

use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Helpers\PaymentHelper;

if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
/** @var OrderModel $orderModel */
if ($orderModel = OrderModel::objects()->get(['orderid' => $orderid])) {

    foreach ($orderModel->transactions->order(['-id']) as $transactionModel) {
        if (empty($transactionModel->transaction_response)) {

            if ($transactionModel = OrderTransactionHelper::action('lookup', $transactionModel, PaymentHelper::getPaymentParams($transactionModel))) {
                $transactionModel->save();
            }
        }
    }

    $smarty->assign("order_transactions", $orderModel->transactions);
    $smarty->assign("transactions_log", $orderModel->transactions_log->order(['-id']));
    $smarty->assign("order_transactions_totals", OrderTransactionHelper::getOrderTransactionsGroupsValues($orderModel));
    $smarty->assign("count_shipping_groups", $orderModel->groups->count());

}


