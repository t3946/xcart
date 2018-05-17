<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OrderStatusNotificationModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_status_notifications';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::class,
                'null' => false,
                'primary' => true,
            ],

        ];
    }
}