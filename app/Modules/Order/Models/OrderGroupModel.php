<?php
namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\IntField;

class OrderGroupModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_order_groups';
    }

    public static function getPrimaryKeyName($asArray = false)
    {
        return ['orderid', 'manufacturerid'];
    }

    public static function getFields()
    {
        return [
            'orderid' => [
                'class' => IntField::className(),
                'null' => false,
            ],
            'manufacturerid' => [
                'class' => IntField::className(),
                'null' => false,
            ],

        ];
    }
}