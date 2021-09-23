<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

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
        ];
    }
}