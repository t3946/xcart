<?php
namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\TimestampField;

class OrderModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_orders';
    }

    public static function getPrimaryKeyName($asArray = false)
    {
        return ['orderid'];
    }

    public static  function getFields()
    {
        return [
            'orderid' => [
                'class' => AutoField::className()
            ],
            'groups' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupModel::className(),
                'link' => ['orderid', 'orderid']
            ],
            'date' => [
                'class' => TimestampField::className()
            ]
        ];
    }
}