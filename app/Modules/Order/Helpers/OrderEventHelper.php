<?php

namespace Modules\Order\Helpers;


use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\User\Models\SurfMetaModel;
use Modules\User\Models\SurfPathModel;
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

    public static function triggerOrderPaidEvent($owner = null, OrderModel $model, string $status): void
    {
        self::orderPaidEvent($model, $status);
    }

    public static function orderCreateEvent(OrderModel $model): void
    {
        if ($model) {
            /** @var OrderExtraModel $order_extra_model */

            [$order_extra_model] = OrderExtraModel::objects()->getOrNew(['order_id' => $model->orderid]);

            $order_extra_model->submit_operator = OrderHelper::getSubmitOperator();
            $order_extra_model->save();

            $ip = Xcart::app()->request->getUserIP();
            if ($geo_litecity_location = GeoIpHelper::getGeoipLocation($ip)) {
                $ip .= "({$geo_litecity_location})";
            }

            $log_message[] = "<b>Customer IP:</b> {$ip}";

            if (!empty($model->customer_notes)) {
                $log_message[] = "<b>Customer notes:</b>\n{$model->customer_notes}\n\n";
            }

            (new OrderLogModel([
                    'orderid' => $model->orderid,
                    'type' => OrderLogModel::LOG_TYPE_CUSTOMER,
                    'login' => Xcart::app()->user->login,
                    'log' => \nl2br(implode(PHP_EOL, $log_message))
                ])
            )->save();

            $model->updateVerificationStatus();

            $surf_path = SurfPathModel::objects()
                ->filter([
                    'resource_type' => SurfPathModel::GOAL_TYPE_REFERER,
                    'meta_id' =>  SurfMetaModel::getInstance()->id
                ])
                ->order(['-id'])
                ->limit(1)
                ->get();

            if ($surf_path) {
                $model->referer_id = $surf_path->resource_id;
                $model->save();
            }
        }
    }

    private static function initStatuses()
    {
        if (empty(self::$_f_statuses) || empty(self::$_all_statuses))
        {
            self::$_all_statuses = [];
            self::$_f_statuses = [];

            foreach (FraudStatusModel::objects()->cache(3600)->valuesList(['code', 'name']) as $status) {
                self::$_f_statuses[$status['code']] = $status['name'];
            }

            foreach (OrderStatusModel::objects()->cache(3600)->valuesList(['code', 'name']) as $status) {
                self::$_all_statuses[$status['code']] = $status['name'];
            }
        }
    }

    public static function registerAfterSaveEvent($order_id, $attribute, $newValue, $oldValue)
    {
        if ($newValue != $oldValue) {
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

            if (isset($new_status) || isset($old_status))
            {
                Xcart::app()->event->trigger('order:status.changed', [
                    'order_id' => $order_id,
                    'message' => "Order [{$attribute}]: {$old_status} -> {$new_status}"
                ]);
            }
        }
    }

    public static function orderPaidEvent(OrderModel $model, string $status = OrderStatusModel::ORDER_STATUS_AUTHORIZED): void
    {
        if ($cart = $model->cart) {
            $cart->delete();
        }

        $model->setAttributes(['cb_status' => $status, 'cart_number' => null]);
        $model->save();

        $model->groups->update(['cb_status' => $model->cb_status]);
    }
}