<?php
namespace Modules\Order\Helpers;

use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderAdditionalTagLinkModel;
use Xcart\App\Main\Xcart;

class OrderTagEventHelper
{
    public static function triggerOrderTagEvent($owner = null, $status_id, $order_id)
    {
        self::orderTagEvent($status_id, $order_id);
    }

    public static function orderTagEvent($status_id, $order_id)
    {
        if ($status_id && $order_id) {

            $model = AttentionTagModel::objects()->filter(['status_id' => $status_id])->get();

            if ($model) {
                OrderAdditionalTagLinkModel::objects()->getOrCreate(['status_id' => $status_id, 'orderid' => $order_id]);

                if ($model->events) {
                    Xcart::app()->event->trigger('order:changed', ['order_id' => $order_id]);
                }
            }
        }
    }
}