<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OrderStatusModel extends Model
{
    use AutoMetaTrait;

    const ORDER_STATUS_AUTHORIZED = 'AP';
    const ORDER_STATUS_COMPLETED = 'P';

    public static function tableName()
    {
        return 'xcart_order_statuses';
    }
    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::className(),
                'primary' => true
            ],
        ];
    }
}