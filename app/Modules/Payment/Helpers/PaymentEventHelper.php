<?php

namespace Modules\Payment\Helpers;


use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Xcart\App\Main\Xcart;

class PaymentEventHelper
{
    /**
     * @param null $owner
     * @param OrderModel $model
     */
    public static function triggerPaymentAuthorizeEvent($owner = null, OrderModel $model, OrderModel $order_before, OrderTransactionModel $transaction): void
    {
        self::paymentAuthorizeEvent($model, $order_before, $transaction);
    }

    public static function paymentAuthorizeEvent(OrderModel $model, OrderModel $order_before, OrderTransactionModel $transaction)
    {
        if ($model && ($model->transactions->count() === 1)) {
            if (in_array($order_before->cb_status,
                [
                    OrderStatusModel::ORDER_STATUS_NOT_FINISHED,
                    OrderStatusModel::ORDER_STATUS_QUEUED,
                    OrderStatusModel::ORDER_STATUS_UNPAID,
                ]) && in_array($model->cb_status,
                [
                    OrderStatusModel::ORDER_STATUS_AUTHORIZED,
                    OrderStatusModel::ORDER_STATUS_COMPLETED,
                ]))
            {
                /** @var OrderExtraModel $order_extra_model */

                [$order_extra_model] = OrderExtraModel::objects()->getOrNew(['order_id' => $model->orderid]);

                $order_extra_model->payment_operator = Xcart::app()->user;
                $order_extra_model->save();
            }
        }
    }
}