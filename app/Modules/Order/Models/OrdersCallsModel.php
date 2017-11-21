<?php

namespace Modules\Order\Models;

use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;

class OrdersCallsModel extends AutoMetaModel
{
    const TYPE_VIEWING_SAME_OPERATOR = 0;
    const TYPE_VIEWING_OTHER_OPERATOR = 1;


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
                ],
            ],

            'relevance_order' => [
                'class' => IntField::className(),
                'null' => false
            ],

        ];
    }

}