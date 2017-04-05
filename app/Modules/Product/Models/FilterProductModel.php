<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FilterProductModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cidev_filter_products';
    }

    public static function getFields()
    {
        return [
            'fv_id' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
                'primary' => true
            ],
            'productid' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
                'primary' => true
            ],
            'is_feed' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
        ];
    }
}