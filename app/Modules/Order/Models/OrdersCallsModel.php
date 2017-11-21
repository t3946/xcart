<?php

namespace Modules\Order\Models;

use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class OrdersCallsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'orders_calls';
    }

    public static function getFields()
    {
        return [

            'call' => [
                'field' => 'call_id',
                'class' => ForeignField::className(),
                'modelClass' => PbxAnveoCallModel::className(),
                'link' => ['call_id' => 'id'],
                'null' => false,
                'primary' => true
            ],

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['order_id' => 'orderid'],
                'null' => false,
            ],

            'relevance_type' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],

            'relevance_order' => [
                'class' => IntField::className(),
                'null' => false
            ],

        ];
    }

}