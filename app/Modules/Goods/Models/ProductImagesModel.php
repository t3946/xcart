<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property ProductModel product
 * @property ProductImageModel image
 */
class ProductImagesModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_images';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'primary' => true
            ],
            'image' => [
                'field' => 'image_id',
                'class' => ForeignField::class,
                'modelClass' => ProductImageModel::class,
                'link' => ['image_id' => 'image_id'],
                'primary' => true
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => true,
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 100000
            ],

        ];
    }
}