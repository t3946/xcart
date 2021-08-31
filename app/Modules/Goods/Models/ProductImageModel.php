<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property int image_id
 */
class ProductImageModel extends Model
{
    public static function tableName()
    {
        return 'xcart_product_images';
    }

    public static function getFields()
    {
        return [
            'image_id' => AutoField::class,
            'path' => [
                'class' => ImageField::class,
                'adapterName' => 's3',
                'uploadTo' => 'images/%Y-%m-%d',
                'sizes' => [
                    'thumb' => [
                        174,
                        'method' => 'adaptiveResize'
                    ],
                    'preview' => [
                        520,
                        'method' => 'adaptiveResize'
                    ],
                    'detail' => [
                        800,
                        'method' => 'adaptiveResize'
                    ]
                ],
                'null' => true,
                'default' => null
            ],
            'hash' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'link' => [
                'class' => CharField::class,
            ],
            'link_uri' => [
                'class' => CharField::class,
            ],
            'width' => [
                'class' => IntField::class,
                'default' => null,
            ],
            'height' => [
                'class' => IntField::class,
                'default' => null,
            ],
            'is_downloaded' => [
                'class' => BooleanField::class,
                'default' => false
            ],
            'is_manual' => [
                'class' => BooleanField::class,
                'default' => false
            ],
            'products' => [
                'class' => ManyToManyField::class,
                'modelClass' => ProductModel::class,
                'through' => ProductImagesModel::class,
            ]
        ];
    }
}