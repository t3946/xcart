<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property int image_id
 * @property ImageField path
 * @property string hash
 */
class ProductImageModel extends Model
{
    public const IMAGE_SIZE_THUMB = 'thumb';
    public const IMAGE_SIZE_PREVIEW = 'preview';
    public const IMAGE_SIZE_DETAIL = 'detail';

    private const CDN_DOMAIN = [
        'https://i1.s3stores.com/',
        'https://i2.s3stores.com/',
        'https://i3.s3stores.com/',
        'https://i4.s3stores.com/'
    ];

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

    public function __toString()
    {
        return $this->getCdnURL(self::IMAGE_SIZE_DETAIL);
    }

    public function getCdnURL(string $size = ''): string
    {
        $parsed_path = pathinfo($this->path->getValue());

        $filename = $parsed_path['dirname'] . '/' . ($size ? $size . '_' : '') . $parsed_path['basename'];

        $idx = $this->pk % 4;

        return self::CDN_DOMAIN[$idx] . $filename;
    }
}