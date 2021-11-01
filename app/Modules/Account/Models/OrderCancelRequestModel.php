<?php

namespace Modules\Account\Models;

use Modules\Order\Models\OrderDetailModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

class OrderCancelRequestModel extends Model
{
    public static function tableName()
    {
        return 'account_order_cancel_requests';
    }

    public static function getFields()
    {
        return [
            'request_id' => [
                'class' => AutoField::class,
            ],
            'request_open_time' => [
                'class' => TimeStampField::class,
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['order_id' => 'orderid'],
            ],
            'cancel_text' => [
                'class' => CharField::class,
            ],
        ];
    }
}