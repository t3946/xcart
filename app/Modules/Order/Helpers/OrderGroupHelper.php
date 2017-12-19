<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\OrderModule;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
use Xcart\App\Main\Xcart;

class OrderGroupHelper
{
    /**
     * @param array $params
     * @return string
     */
    public static function dispatchGroup($params)
    {
        $log = '';

        /** @var OrderModel $order_model */
        $order_model = OrderModel::objects()->get(['orderid' => $params['orderid']]);

        $transactions = $order_model->transactions->all();

        if ($order_model->groups->count() > 1) {
            if (!OrderTransactionHelper::isPartiallyCaptureEnabled($transactions)) {
                if (OrderTransactionHelper::getCaptured($transactions) >= $order_model->total) {
                    return $log;
                } else {
                    $section_name_top_message["content"] = OrderModule::t("Dispatch of orders with BluePay transactions, having more than one Dx only possible after manual capture of amount enough to cover overall order total adjusted after all Dx's confirmations");
                    $section_name_top_message["type"] = "E";
                    static::dispatchError($order_model, $section_name_top_message, $log);
                }
            }
        }

        /** @var OrderGroupModel $group_model */
        $group_model = $order_model->groups->get(['manufacturerid' => $params['mnf_id']]);

        if ($group_model && $group_model->cb_status == "AP") {

            $toCaptureAmount = round($group_model->total_gross - $group_model->getRefunds(), 2);

            $toCaptureAmountAvail = round(OrderTransactionHelper::getToCapture($transactions), 2);

            if ($toCaptureAmount <= $toCaptureAmountAvail) {

                $auth_transactions = $order_model->transactions->filter(
                    [
                        'type' => OrderTransactionModel::TYPE_AUTHORIZATION,
                        'transaction_status__in' => [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                            OrderTransactionModel::STATUS_PENDING
                        ]
                    ])->all();

                foreach ($auth_transactions as $auth_tr) {

                    $amount =
                        [
                            'amount' => number_format(min($toCaptureAmount, $auth_tr->transaction_amount), 2, '.', ''),
                            'currency' => $auth_tr->transaction_currency,
                        ];
                    $params = array_merge(PaymentHelper::getPaymentParams($auth_tr, $amount),
                        [
                            'mode' => 'capture',
                            'new_method_model' => $auth_tr->payment_method_model,
                            'order' => $order_model,
                            'orderTransaction' => $auth_tr,
                        ]
                    );

                    $trStore = new OrderTransactionStore($params, $auth_tr);
                    $model = $trStore->capture();

                    $log .= "<br />".$trStore->log;

                    if ($model->type == OrderTransactionModel::TYPE_CAPTURE && $model->transaction_status == OrderTransactionModel::STATUS_COMPLETED) {
                        $toCaptureAmount = round ($toCaptureAmount - $model->transaction_amount, 2);
                    }

                    if ($toCaptureAmount <= 0) {
                        break;
                    }
                }
                if ($toCaptureAmount > 0) {

                    $top_message["content"] = func_get_langvar_by_name("txt_capture_failed");
                    $top_message["type"] = "I";

                    static::dispatchError($order_model, $top_message, $log);
                }

                $new_status = OrderStatusModel::objects()->get(['code' => 'P']);

                $log .= "<br /><B>" . $group_model->manufacturer->code . ":</B> cb_status: " . $group_model->cb_status_model->name . " -> " . $new_status->name;

                $group_model->cb_status = $new_status->code;

                $group_model->save();

            } else {

                $section_name_top_message["content"] = func_get_langvar_by_name("lbl_captureamount_not_equal_order_amount");
                $section_name_top_message["type"] = "E";

                static::dispatchError($order_model, $section_name_top_message, $log);

            }
        }
        return $log;
    }

    public static function dispatchError($order_model, $section_name_top_message, $log)
    {
        $log .= "<br />" . $section_name_top_message["content"];
        func_log_order($order_model->orderid, 'X', $log, Xcart::app()->user->login);

        Xcart::app()->request->session->add('section_name_top_message', $section_name_top_message);
        Xcart::app()->request->redirect("/admin/order.php?orderid={$order_model->orderid}");
    }
}