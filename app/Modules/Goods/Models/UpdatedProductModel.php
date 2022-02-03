<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

/**
 * @property int mask
 * @property string type
 * @property int resourceid
 * @property ProductModel product
 * @property CategoryModel $category
 */
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
                'class' => IntField::class,
                'primary' => true,
                'null' => false,
            ],
            'time_stamp' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'product' => [
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['resourceid' => 'productid'],
            ],
            'category' => [
                'class' => ForeignField::class,
                'modelClass' => CategoryModel::class,
                'link' => ['resourceid' => 'categoryid'],
            ],
            'resourceid' => [
                'class' => IntField::class,
                'primary' => true,
                'null' => false,
            ],
            'mask' => [
                'class' => IntField::class,
                'null' => true,
            ],

        ];
    }
}