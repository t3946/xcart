<?php

namespace Modules\Account\Models;

use Modules\Order\Models\OrderDetailModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class OrderCancelItemsModel extends Model
{
    public static function tableName()
    {
        return 'account_order_cancel_items';
    }

    public static function getFields()
    {
        return [
            'request_id' => [
                'class' => AutoField::class,
            ],
            'order_item' => [
                'field' => 'order_item_id',
                'class' => ForeignField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['order_item_id' => 'itemid'],
            ],
            'amount' => [
                'class' => IntField::class,
            ],
        ];
    }
}