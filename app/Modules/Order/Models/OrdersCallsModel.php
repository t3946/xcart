<?php

namespace Modules\Order\Models;

use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class OrdersCallsModel extends Model
{
    use AutoMetaTrait;

    const TYPE_VIEWING_SAME_OPERATOR = 0;
    const TYPE_VIEWING_OTHER_OPERATOR = 1;
    const ORDER_PHONE_EQUALS_CALLED_PHONE = 2;

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
                'class' => IntField::className(),
                'length' => 1,
                'null' => false,
                'default' => 0,
                'choices' => [
                    0 => 'order viewed during call same operator',
                    1 =>  'order viewed during call by other operator',
                    2 => 'ORDER_PHONE_EQUALS_CALLED_PHONE'
                ],
            ],

            'relevance_order' => [
                'class' => IntField::className(),
                'null' => false
            ],

        ];
    }

}