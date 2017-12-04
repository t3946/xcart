<?php

namespace Modules\Product\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class UpdatedProductModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_cidev_updated_products';
    }

    public static function getFields()
    {
        return [
            'type' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
            'time_stamp' => [
                'class' => UnixTimestampField::className(),
                'autoNowAdd' => true,
            ],
            'product' => [
                'field' => 'resourceid',
                'class' => ForeignField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['resourceid' => 'productid'],
            ],
            'resourceid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
            'mask' => [
                'class' => IntField::className(),
                'null' => true,
            ],

        ];
    }
}