<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderModel;

class OrderEventHelper
{
    /**
     * @param null $owner
     * @param OrderModel $model
     */
    public static function triggerOrderCreateEvent($owner = null, OrderModel $model): void
    {
        self::orderCreateEvent($model);
    }

    public static function orderCreateEvent(OrderModel $model): void
    {
        if ($model) {
            /** @var OrderExtraModel $order_extra_model */

            [$order_extra_model] = OrderExtraModel::objects()->getOrNew(['order_id' => $model->orderid]);

            $order_extra_model->submit_operator = OrderHelper::getSubmitOperator();
            $order_extra_model->save();
        }
    }
}