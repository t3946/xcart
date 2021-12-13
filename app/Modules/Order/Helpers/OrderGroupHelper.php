<?php

namespace Modules\Order\Helpers;


use DateTime;
use DateTimeZone;
use Modules\Order\Models\OrderLogModel;
use Xcart\App\QueryBuilder\Q\QOrNot;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTrackingModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\OrderModule;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
use Xcart\App\Main\Xcart;

class OrderGroupHelper
{
    /**
     * @param array $params
     * @return void
     * @throws \Xcart\App\Exceptions\InvalidConfigException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function dispatchGroup(array $params): void
    {
        /** @var OrderModel $order_model */
        $order_model = OrderModel::objects()->get(['orderid' => $params['orderid']]);

        $transactions = $order_model->transactions->all();

        if (($order_model->groups->count() > 1) && !OrderTransactionHelper::isPartiallyCaptureEnabled($transactions)) {
            if (OrderTransactionHelper::getCaptured($transactions) >= $order_model->total) {
                return;
            }

            $section_name_top_message['content'] = OrderModule::t("Dispatch of orders with BluePay transactions, having more than one Dx only possible after manual capture of amount enough to cover overall order total adjusted after all Dx's confirmations");
            $section_name_top_message['type'] = 'E';
            static::dispatchError($order_model, $section_name_top_message);
        }

        /** @var OrderGroupModel $group_model */
        $group_model = $order_model->groups->get(['manufacturerid' => $params['mnf_id']]);

        if ($group_model && $group_model->cb_status === OrderStatusModel::ORDER_STATUS_AUTHORIZED) {

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

                    OrderLogModel::createLog(
                        $order_model->orderid,
                        OrderLogModel::LOG_TYPE_XCART,
                        $trStore->log
                    );

                    if ($model->type === OrderTransactionModel::TYPE_CAPTURE && $model->transaction_status === OrderTransactionModel::STATUS_COMPLETED) {
                        $toCaptureAmount = round ($toCaptureAmount - $model->transaction_amount, 2);
                    }

                    if ($toCaptureAmount <= 0) {
                        break;
                    }
                }
                if ($toCaptureAmount > 0) {

                    $top_message['content'] = func_get_langvar_by_name('txt_capture_failed');
                    $top_message['type'] = 'I';

                    static::dispatchError($order_model, $top_message);
                }

                if ($new_status = OrderStatusModel::objects()->get(['code' => OrderStatusModel::ORDER_STATUS_COMPLETED])) {
                    /** @var OrderStatusModel $new_status */

                    $group_model->cb_status_model = $new_status;
                    $group_model->save();

                    OrderLogModel::createLog(
                        $order_model->orderid,
                        OrderLogModel::LOG_TYPE_XCART,
                        "<b>{$group_model->manufacturer->code}:</b> cb_status: {$group_model->cb_status_model->name} -> $new_status->name"
                    );
                }

            } else {

                $section_name_top_message['content'] = func_get_langvar_by_name('lbl_captureamount_not_equal_order_amount');
                $section_name_top_message['type'] = 'E';

                static::dispatchError($order_model, $section_name_top_message);

            }
        }
    }

    /**
     * @param $order_model
     * @param $section_name_top_message
     * @throws \Xcart\App\Exceptions\InvalidConfigException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function dispatchError($order_model, $section_name_top_message): void
    {
        OrderLogModel::createLog(
            $order_model->orderid,
            OrderLogModel::LOG_TYPE_XCART,
            $section_name_top_message['content']
        );

        Xcart::app()->request->session->add('section_name_top_message', $section_name_top_message);
        Xcart::app()->request->redirect("/admin/order.php?orderid={$order_model->orderid}");
    }

    public static function addTrackingNumbers($group, $params): array
    {
        $d_filter = ['order_group_id' => $group->order_group_id];
        if ($params['tracking_id']) {
            $d_filter[] = new QOrNot(['id__in' => $params['tracking_id']]);
        }
        /** @var OrderTrackingModel $dm */
        foreach (OrderTrackingModel::objects()->filter($d_filter) as $dm) {
            $dm->delete();
        }

        foreach ($params['tracking_carrier'] as $_k => $sh) {
            if ($sh && !$params['tracking_id'][$_k]) {
                $tracking_number = trim($params['tracking_number'][$_k]);
                $t_shipdate = trim($params['tracking_ship_date'][$_k]);
                $t_shipdate = $t_shipdate ?: (new DateTime())->format('m/d/Y');
                $tracking_number  = $tracking_number ?: null;
                $tr_params = [
                    'linkid' => $params['tracking_shipper'][$_k] ?: null,
                    'tracknum' => $tracking_number,
                    'shipping_date' => $t_shipdate ? DateTime::createFromFormat('m/d/Y H:i:s', "{$t_shipdate} 00:00:00", new DateTimeZone('EST')) : null,
                    'carrier_id' => $sh,
                    'order_group_id' => $group->order_group_id
                ];
                $tri = [
                    'order_group_id' => $group->order_group_id
                ];

                if ($tracking_number) {
                    $tri['tracknum'] = $tracking_number;
                }

                [$trackingModel, $is_new] = OrderTrackingModel::objects()->getOrNew($tri);
                if ($is_new){
                    $trackingModel->setAttributes($tr_params);
                    $trackingModel->save();
                    $tracking[] = $trackingModel;
                }
            }
        }
        return $tracking ?? [];
    }
}