<?php

namespace Modules\Images\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * do not use this class instantly. Instead, use LinkImage classes
 */
class ImagesModel extends Model
{
    private const UPLOAD_PATH = '';
    private const MAX_UPLOAD_SIZE_MB = 20;

    public static string $upload_to = '';
    public static string $max_size = '';

    public const IMAGE_SIZE_THUMB = 'thumb';
    public const IMAGE_SIZE_PREVIEW = 'preview';
    public const IMAGE_SIZE_DETAIL = 'detail';

    static function tableName()
    {
        return 'xcart_images';
    }

    /**
     * override this for change size
     */
    protected static function getMaxUploadSizeMB(): int
    {
        return self::MAX_UPLOAD_SIZE_MB;
    }

    /**
     * override this for change path
     */
    protected static function getUploadPath(): string
    {
        return self::UPLOAD_PATH;
    }

    static function getFields()
    {
        return [
            'image_id' => [
                'class' => AutoField::class,
            ],
            'path' => [
                'class' => ImageField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 's3',
                'uploadTo' => rtrim(static::getUploadPath(), '/') . '/%Y/%m/%d',
                'maxSize' => static::getMaxUploadSizeMB() . 'M',
                'sizes' => [
                    self::IMAGE_SIZE_THUMB => [
                        174,
                        'method' => 'adaptiveResize',
                    ],
                    self::IMAGE_SIZE_PREVIEW => [
                        520,
                        'method' => 'adaptiveResize',
                    ],
                    self::IMAGE_SIZE_DETAIL => [
                        800,
                        'method' => 'adaptiveResize',
                    ],
                ]
            ],
            'width' => [
                'class' => IntField::class,
            ],
            'height' => [
                'class' => IntField::class,
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
}
