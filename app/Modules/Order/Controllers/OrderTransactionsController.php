<?php

namespace Modules\Order\Controllers;


use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\OrderModule;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;

class OrderTransactionsController extends PrototypeAdminController
{
    public function transaction_process($order_id, $mode, $id)
    {
        $order_log = $type = null;

        $method = $mode;

        /** @var OrderModel $orderModel */
        if ($orderModel = OrderModel::objects()->get(['orderid' => $order_id])) {

            if ($id && $orderTransaction = $orderModel->transactions->get(['id' => $id])) {

                $amount =
                    [
                        'amount' => number_format(trim($_POST['transaction_amount'][$id]), 2, '.', ''),
                        'currency' => $orderTransaction->transaction_currency
                    ];

                $params = array_merge(
                    PaymentHelper::getPaymentParams($orderTransaction, $amount),
                    [
                        'mode' => $mode,
                        'payment_method_model' => $orderTransaction->payment_method_model,
                        'new_method_model' => $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $orderTransaction->payment_method_model->processor->processor_name . ' VT']),
                        'order' => $orderModel,
                        'orderTransaction' => $orderTransaction,
                    ]
                );

                $store = new OrderTransactionStore($params);
                $store->$method();
                $order_log .= $store->log;
            }

            func_log_order($order_id, 'PP', $order_log, Xcart::app()->user->login);
        }

        Xcart::app()->request->redirect("/admin/order.php?orderid={$order_id}&tab=y#main_order_tabs-VT");
    }

    public function authorise($order_id)
    {
        $method = $order_log = $type = null;
        $result = [];

        /** @var OrderModel $orderModel */
        if (isset($_POST['paypal_vt']) && $order_id && $orderModel = OrderModel::objects()->get(['orderid' => $order_id])) {

            $count = $orderModel->transactions->count();

            if (!($isAllowed = PaymentHelper::isAuthorizeAllowed($orderModel, $count))) {
                if (!$count && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {

                    $order_log .= $f_order = OrderModule::t("Error: First transaction in order exception");
                    func_log_order($order_id, 'PP', $order_log, Xcart::app()->user->login);

                    $top_message = [
                        'type' => 'E',
                        'content' => $f_order
                    ];

                    Xcart::app()->request->session->add('top_message', $top_message);
                    Xcart::app()->request->redirect("/admin/order.php?orderid={$orderModel->orderid}&tab=y#main_order_tabs-VT");

                }
            }

            $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $_POST['paypal_vt']['processor']]);

            $params = array_merge(PaymentHelper::prepareAuthorize($_POST['paypal_vt'], $orderModel),
                [
                    'processor' => $pmModel->processor,
                    'payment_method_model' => $pmModel,
                    'order' => $orderModel,
                ]
            );

            /** @var OrderTransactionModel $transaction_model */
            $store = new OrderTransactionStore($params);
            $transaction_model = $store->authorize();
            $order_log .= $store->log;

            list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
            $order_log .= "<br />Transaction:" . $transaction_model->transaction_id . $o_log;

            if (!$count && $send_notification) {
                func_send_order_status_notification($orderModel->orderid, OrderStatusModel::ORDER_STATUS_AUTHORIZED, true);
            }
        }

        func_log_order($order_id, 'PP', $order_log, Xcart::app()->user->login);

        Xcart::app()->request->redirect("/admin/order.php?orderid={$order_id}&tab=y#main_order_tabs-VT");
    }

    public function child_transactions($id)
    {
        if ($id && ($orderTransaction = OrderTransactionModel::objects()->filter(['parent_id' => $id])->all())) {
            echo $this->renderSmarty('admin/main/transactions_table.tpl',
                [
                    'order_transactions' => $orderTransaction,
                    'main_transaction' => true,
                    'user_login' => Xcart::app()->user->login,
                ]
            );
        }
    }
}