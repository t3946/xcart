<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OrderStatusModel extends Model
{
    use AutoMetaTrait;

    const ORDER_STATUS_CHECKOUT_STEP1 = 'S1';
    const ORDER_STATUS_CHECKOUT_STEP2 = 'S2';
    const ORDER_STATUS_CHECKOUT_STEP3 = 'S3';
    const ORDER_STATUS_CHECKOUT_STEP4 = 'S4';

    const ORDER_STATUS_AUTHORIZED = 'AP';
    const ORDER_STATUS_COMPLETED = 'P';
    const ORDER_STATUS_QUEUED = 'Q';
    const ORDER_STATUS_UNPAID = 'N';
    const ORDER_STATUS_NOT_FINISHED = 'I';

    const ORDER_DC_STATUS_NOT_SHIPPED = 'T';

    const ORDER_BD_STATUS_UNPAID = 'W';

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