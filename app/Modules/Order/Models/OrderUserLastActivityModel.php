<?php
namespace Modules\Order\Models;

class OrderUserLastActivityModel extends AbstractOrderUserActivityModel
{
    public static function tableName()
    {
        return 'xcart_order_user_actives_last';
    }
}