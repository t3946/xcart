<?php

namespace Modules\Reviews\Models;

use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ReviewFileModel extends Model {
    public static function tableName()
    {
        return 'review_images';
    }

    public static function getFields()
    {
        return [
            'review_id' => [
                'class' => IntField::class,
            ],
            'image_path' => [
                'class' => FileField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => 'images/review_images/',
                'maxSize' => '20M',
            ],
        ];
    }
}
