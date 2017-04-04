<?php
namespace Modules\Product\Models;

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
            'fp_id' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
            'fv_id' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
            'productid' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
        ];
    }
}