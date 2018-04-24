<?php

namespace Modules\Order\Models;


use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

class OrderExtraModel extends Model
{
    public static function tableName()
    {
        return 'order_extra';
    }

    public static function getFields()
    {
        return [

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
                'primary' => true,
            ],

            'submit_operator' => [
                'field' => 'submit_operator_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['submit_operator_id' => 'id'],
                'null' => true,
            ],

            'payment_operator' => [
                'field' => 'payment_operator_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['payment_operator_id' => 'id'],
                'null' => true,
            ],

            'purchase_order' => [
                'class' => SerializeField::className(),
                'null' => false,
            ],
        ];
    }
}