<?php
namespace Modules\Order\Helpers;

use Modules\Order\Models\AttentionTagModel;
use Xcart\App\Main\Xcart;

class OrderTagEventHelper
{
    public static function tagEvent($tag_id, $order_id)
    {
        if ($tag_id && $order_id) {
            if (!is_array($tag_id)) {
                $tag_id = [$tag_id];
            }

            $models = AttentionTagModel::objects()->filter(['status_id__in' => $tag_id])->all();
            foreach ($models as $model)
            {
                if ($model->events) {
                    Xcart::app()->event->trigger('order:changed', ['order_id' => $order_id]);
                }
            }
        }
    }
}