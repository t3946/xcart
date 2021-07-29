<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

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
                'adapterName' => 'local',
                //'uploadTo' => '%Y-%m-%d',
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
                'null' => false
            ],
            'hash' => [
                'class' => CharField::class,
                'default' => ''
            ],
            'width' => [
                'class' => IntField::class,
                'default' => null,
            ],
            'height' => [
                'class' => IntField::class,
                'default' => null,
            ]
        ];
    }
}