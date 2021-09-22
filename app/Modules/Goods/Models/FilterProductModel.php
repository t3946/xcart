<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
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
            'filter_val' => [
                'field' => 'fv_id',
                'class' => ForeignField::class,
                'modelClass' => FilterValueModel::class,
                'link' => ['fv_id' => 'fv_id'],
                'null' => false,
                'default' => 0,
                'primary' => true
            ],
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'null' => false,
                'default' => 0,
                'primary' => true
            ],
            'is_feed' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
        ];
    }
}