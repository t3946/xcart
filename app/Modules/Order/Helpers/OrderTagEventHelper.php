<?php
namespace Modules\Order\Helpers;

use Xcart\App\Main\Xcart;

class OrderTagEventHelper
{
    /**
     * For trigger
     *
     * @param null $owner
     * @param $status_id
     * @param $order_id
     *
     * @throws \Xcart\App\Orm\Exception\MultipleObjectsReturned
     */
    public static function triggerOrderTagEvent($owner = null, $status_id, $order_id)
    {
        self::orderTagEvent($status_id, $order_id);
    }

    /**
     * For manual execute
     *
     * @param int $status_id Status pk
     * @param int $order_id  Order pk
     * @param bool $save_log Save action in log or not
     *
     * @throws
     */
    public static function orderTagEvent($status_id, $order_id, $save_log = true): void
    {
        if ($status_id &&
            $order_id &&
            ($model = OrderHelper::setOrderTag($order_id, $status_id, $save_log)) &&
            $model->events)
        {
            $message = "Attention tag added: " . $model->status;
            Xcart::app()->event->trigger('order:status.changed', ['order_id' => $order_id, 'message' => $message]);
        }
    }
}