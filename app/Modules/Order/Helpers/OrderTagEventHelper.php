<?php
namespace Modules\Order\Helpers;

use Modules\Order\Models\AttentionTagModel;

class OrderTagEventHelper
{
    public static function tagEvent($ids)
    {
        if ($ids) {
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $models = AttentionTagModel::objects()->filter(['status_id__in' => $ids])->all();
            foreach ($models as $model)
            {
                $model->
            }
        }
    }
}