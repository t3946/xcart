<?php
namespace Modules\Order\Helpers;

use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderAdditionalTagLinkModel;
use Xcart\App\Main\Xcart;
use Xcart\Logs;

class OrderTagEventHelper
{
    /**
     * For trigger
     *
     * @param null $owner
     * @param $status_id
     * @param $order_id
     */
    public static function triggerOrderTagEvent($owner = null, $status_id, $order_id)
    {
        self::orderTagEvent($status_id, $order_id);
    }

    /**
     * For manual execute
     *
     * @param $status_id
     * @param $order_id
     */
    public static function orderTagEvent($status_id, $order_id, $save_log = true)
    {
        if ($status_id && $order_id) {

            $model = AttentionTagModel::objects()->filter(['status_id' => $status_id])->get();

            if ($model) {
                $link = OrderAdditionalTagLinkModel::objects()->getOrCreate(['status_id' => $status_id, 'orderid' => $order_id]);

                if ($save_log && $link->getIsNewRecord()) {
                    Logs::_log('orders', $order_id, 'X', "Attention tag added: " . $model->status . "\n", Xcart::app()->user->login);
                }

                if ($model->events) {
                    Xcart::app()->event->trigger('order:changed', ['order_id' => $order_id]);
                }
            }
        }
    }
}