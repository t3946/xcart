<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Main\Xcart;

class OrderEventHelper
{
    private static $_f_statuses;
    private static $_all_statuses;

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

    private static function initStatuses()
    {
        if (empty(self::$_f_statuses) || empty(self::$_all_statuses))
        {
            self::$_all_statuses = [];
            self::$_f_statuses = [];

            self::$_f_statuses = func_query_hash('SELECT code, name FROM xcart_order_fraud_statuses ORDER BY order_by', 'code', false, true, true);

            foreach (OrderStatusModel::objects()->cache(3600)->valuesList(['code', 'name']) as $status) {
                self::$_all_statuses[$status['code']] = $status['name'];
            }
        }
    }

    public static function registerAfterSaveEvent($order_id, $attribute, $newValue, $oldValue)
    {
        static::initStatuses();

        switch ($attribute) {
            case 'fraud_status' : {
                $old_status = self::$_f_statuses[$oldValue] ?? $oldValue;
                $new_status = self::$_f_statuses[$newValue] ?? $newValue;

                break;
            }

            default: {
                if (strpos($attribute, '_status') !== false) {
                    if ($attribute != $oldValue)
                    {
                        $old_status = self::$_all_statuses[$oldValue] ?? $oldValue;
                        $new_status = self::$_all_statuses[$newValue] ?? $newValue;
                    }
                }
            }
        }

        Xcart::app()->event->trigger('order:status.changed', [
            'order_id' => $order_id,
            'message' => "Order [{$attribute}]: {$old_status} -> {$new_status}"
        ]);
    }
}