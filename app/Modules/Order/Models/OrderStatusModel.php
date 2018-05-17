<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OrderStatusModel extends Model
{
    use AutoMetaTrait;

    public const ORDER_STATUS_CHECKOUT_STEP1 = 'S1';
    public const ORDER_STATUS_CHECKOUT_STEP2 = 'S2';
    public const ORDER_STATUS_CHECKOUT_STEP3 = 'S3';
    public const ORDER_STATUS_CHECKOUT_STEP4 = 'S4';

    public const ORDER_STATUS_AUTHORIZED = 'AP';
    public const ORDER_STATUS_COMPLETED = 'P';
    public const ORDER_STATUS_QUEUED = 'Q';
    public const ORDER_STATUS_UNPAID = 'N';
    public const ORDER_STATUS_NOT_FINISHED = 'I';

    public const ORDER_DC_STATUS_NOT_SHIPPED = 'T';

    public const ORDER_BD_STATUS_UNPAID = 'W';

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
    public function __toString()
    {
        return $this->name;
    }
}