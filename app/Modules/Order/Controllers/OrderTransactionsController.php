<?php

namespace Modules\Order\Controllers;


use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\OrderModule;
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
        $method = $order_log = $type = null;
        $result = [];

        /** @var OrderModel $orderModel */
        if ($orderModel = OrderModel::objects()->get(['orderid' => $order_id])) {

            extract(Gateway::$gatewayMethods[$mode]);

            if ($id && $orderTransaction = $orderModel->transactions->get(['id' => $id])) {

                $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $orderTransaction->payment_method_model->processor->processor_name . ' VT']);

                $amount =
                    [
                        'amount' => number_format(trim($_POST['transaction_amount'][$id]), 2, '.', ''),
                        'currency' => $orderTransaction->transaction_currency
                    ];

                $params = array_merge(PaymentHelper::getPaymentParams($orderTransaction, $amount), ['mode' => $mode, 'payment_method_model' => $orderTransaction->payment_method_model]);

                try {
                    /** @var OrderTransactionModel $model */
                    list($model, $gw) = OrderTransactionHelper::action($method, $params);

                    if ($model) {

                        if ($model->getIsNewRecord()) {
                            $model->setAttributes(
                                [
                                    'orderid' => $orderModel->orderid,
                                    'parent_id' => $orderTransaction->id,
                                    'type' => $type
                                ]
                            );
                            $order_log .= "<br />".OrderModule::t('Transaction:')." $orderTransaction->transaction_id --> $model->transaction_id";
                        }

                        $model->save();

                        $result = $model->transaction_response;

                        list ($o_log) = OrderHelper::changeOrderCBStatus($orderModel, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                        $order_log .= "<br />". OrderModule::t('Transaction:')." {$model->transaction_id} {$o_log}";

                        $logStatus = $model->transaction_status;

                        $parent = $model;
                        while ($parent->parent_id && $parent = $parent->parent) {
                            list($model_o) = OrderTransactionHelper::action(Gateway::$gatewayMethods['lookup']['method'], PaymentHelper::getPaymentParams($parent));
                            if ($model_o) {
                                $model_o->save();
                            }
                        }
                    } else {

                        $state = $gw->getState($mode);
                        $result = $gw->result->getData();

                        if ($gw->result->isSuccessful()) {

                            if ($orderTransaction && $state) {
                                $orderTransaction->transaction_status = $state;
                                $orderTransaction->transaction_response = $result;
                                $orderTransaction->save();
                            }

                            $logStatus = $orderTransaction->transaction_status;

                            $order_log .= "<br/>{$result['name']}<br/>{$result['message']}";
                        } else {
                            $logStatus =  $state;
                        }

                    }

                } catch (\Exception $e) {
                    $order_log .= "<br/>{$pmModel->payment_method} Processing Error: {$e->getMessage()}";
                    $logStatus = OrderTransactionModel::STATUS_FAILED;
                }

                $transactionLog = new TransactionLogModel(
                    [
                        'orderid' => $orderModel->orderid,
                        'paymentid' => $pmModel->paymentid,
                        'order_transaction_id' => isset($model) ? $model->id : (isset($orderTransaction) ? $orderTransaction->id : null),
                        'transaction_id' => isset($model) ? $model->transaction_id : (isset($orderTransaction) ? $orderTransaction->transaction_id : ''),
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
            func_log_order($order_id, 'PP', $order_log, Xcart::app()->user->login);
        }

        Xcart::app()->request->redirect("/admin/order.php?orderid={$order_id}&tab=y#main_order_tabs-VT");
    }

    public function authorise($order_id)
    {
        $method = $order_log = $type = null;
        $result = [];

        /** @var OrderModel $model */
        if (isset($_POST['paypal_vt']) && $order_id && $model = OrderModel::objects()->get(['orderid' => $order_id])) {

            extract(Gateway::$gatewayMethods['authorize']);

            $count = $model->transactions->count();

            if (!($isAllowed = PaymentHelper::isAuthorizeAllowed($model, $count))) {
                if (!$count && (empty($AJAX_SUBMIT) || $AJAX_SUBMIT != "Y")) {

                    $order_log .= $f_order = OrderModule::t("Error: First transaction in order exception");

                    $top_message = [
                        'type' => 'E',
                        'content' => $f_order
                    ];

                    Xcart::app()->request->session->add('top_message', $top_message);
                    Xcart::app()->request->redirect("/admin/order.php?orderid={$model->orderid}&tab=y#main_order_tabs-VT");

                }
            }

            $pmModel = PaymentMethodModel::objects()->get(['payment_method' => $_POST['paypal_vt']['processor']]);

            $params = array_merge(PaymentHelper::prepareAuthorize($_POST['paypal_vt'], $model), ['processor' => $pmModel->processor, 'payment_method_model' => $pmModel]);

            try {

                /** @var OrderTransactionModel $transaction_model */
                list($transaction_model, $gw) = OrderTransactionHelper::action($method, $params);

                if ($transaction_model) {

                    $transaction_model->setAttributes(
                        [
                            'orderid' => $model->orderid,
                            'type' => $type,
                        ]);

                    $transaction_model->save();

                    $result = $transaction_model->transaction_response;

                    list ($o_log, $send_notification) = OrderHelper::changeOrderCBStatus($model, OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                    $order_log .= "<br />Transaction:" . $transaction_model->transaction_id . $o_log;

                    if (!$count && $send_notification) {
                        func_send_order_status_notification($model->orderid, OrderStatusModel::ORDER_STATUS_AUTHORIZED, true);
                    }

                    $logStatus = $transaction_model->transaction_status;

                } else {

                    $result = $gw->result->getData();

                    $order_log .= "<br/>{$result['name']}<br/>{$result['message']}";

                    $logStatus = OrderTransactionModel::STATUS_FAILED;
                }

            } catch (\Exception $e) {
                $order_log .= "<br/>{$pmModel->payment_method} Processing Error: {$e->getMessage()}";
                $logStatus = OrderTransactionModel::STATUS_FAILED;
            }

            $transactionLog = new TransactionLogModel(
                [
                    'orderid' => $model->orderid,
                    'paymentid' => $pmModel->paymentid,
                    'order_transaction_id' => isset($transaction_model) ? $transaction_model->id : null,
                    'transaction_id' => isset($transaction_model) ? $transaction_model->transaction_id : '',
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