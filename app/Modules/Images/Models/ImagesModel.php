<?php

namespace Modules\Images\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * do not use this class instantly. Instead, use LinkImage classes
 */
class ImagesModel extends Model
{
    public static string $upload_to = '';
    public static string $max_size = '';

    static function tableName()
    {
        return 'xcart_images';
    }

    static function getFields()
    {
        return [
            'image_id' => [
                'class' => AutoField::class,
            ],
            'path' => [
                'class' => FileField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => self::$upload_to . '/%Y/%m/%d',
                'maxSize' => self::$max_size,
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
