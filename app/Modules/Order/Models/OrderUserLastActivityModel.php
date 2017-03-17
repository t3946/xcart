<?php
namespace Modules\Order\Models;

class OrderUserLastActivityModel extends OrderUserActivityModel
{
    public static function tableName()
    {
        return 'xcart_order_user_actives_last';
    }

    public static function getPrimaryKeyName($asArray = false)
    {
        return ['user_id', 'order_id'];
    }

    public function afterSave($owner, $isNew)
    {

    }
}