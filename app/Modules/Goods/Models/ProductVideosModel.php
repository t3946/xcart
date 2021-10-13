<?php


namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ProductVideosModel extends Model
{
    public static function tableName()
    {
        return 'xcart_product_videos';
    }

    public static function getFields()
    {
        return [

            'id' => [
                'class' => AutoField::class,
            ],

            'product' => [
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'null' => false,
            ],

            'is_local' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],

            'active' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => true,
            ],

            'video' => [
                'class' => CharField::class,
                'null' => false,
            ],

            'image' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'provider' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],

            'description' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ];
    }
}