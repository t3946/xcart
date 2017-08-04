<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
use Xcart\App\Main\Xcart;

class OrderGroupHelper
{
    public static function dispatchGroup($params)
    {
        $log = '';

        /** @var OrderModel $order_model */
        $order_model = OrderModel::objects()->get(['orderid' => $params['orderid']]);

        /** @var OrderGroupModel $group_model */
        $group_model = $order_model->groups->get(['manufacturerid' => $params['mnf_id']]);

        if ($group_model && $group_model->cb_status == "AP") {

            $groupRefunds = $group_model->getRefunds();
            $toCaptureAmount = $group_model->total_gross - $groupRefunds;
            $toCaptureAmountAvail = OrderTransactionHelper::getCaptureAmountAvail($order_model);

            if ($toCaptureAmount <= $toCaptureAmountAvail) {

                $auth_transactions = array_filter($order_model->transactions->all(), function ($a) {
                    return ($a->type == OrderTransactionModel::TYPE_AUTHORIZATION && in_array($a->transaction_status,
                            [
                                OrderTransactionModel::STATUS_AUTHORIZED,
                                OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                                OrderTransactionModel::STATUS_PENDING
                            ]
                        ));
                });
                foreach ($auth_transactions as $auth_tr) {

                    $amount = [
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
                        $toCaptureAmount -= $model->transaction_amount;
                    }

                    if ($toCaptureAmount <= 0) {
                        break;
                    }
                }
                if ($toCaptureAmount > 0) {

                    $top_message["content"] = func_get_langvar_by_name("txt_capture_failed");
                    $top_message["type"] = "I";

                    $log .= "<br />" . $top_message["content"];
                    func_log_order($order_model->orderid, 'X', $log, Xcart::app()->user->login);

                    Xcart::app()->request->session->add('top_message', $top_message);
                    Xcart::app()->request->redirect("/admin/order.php?orderid={$order_model->orderid}");

                }

                $new_status = OrderStatusModel::objects()->get(['code' => 'P']);

                $log .= "<br /><B>" . $group_model->manufacturer->code . ":</B> cb_status: " . $group_model->status_cb->name . " -> " . $new_status->name;

                $group_model->cb_status = $new_status->code;

                $group_model->save();

            } else {

                $section_name_top_message["content"] = func_get_langvar_by_name("lbl_captureamount_not_equal_order_amount");
                $section_name_top_message["type"] = "E";

                $log .= "<br />" . $section_name_top_message["content"];
                func_log_order($order_model->orderid, 'X', $log, Xcart::app()->user->login);

                Xcart::app()->request->session->add('section_name_top_message', $section_name_top_message);
                Xcart::app()->request->redirect("/admin/order.php?orderid={$order_model->orderid}");

            }
        }
        return $log;
    }
}