<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class CustomerFilesModel extends Model
{
    public const UPLOAD_PATH = "user_files";
    public const UPLOAD_MAX_SIZE = "";

    public static function tableName()
    {
        return 'user_files';
    }

    public static function getFields()
    {
        return [
            'user_file_id' => [
                'class' => IntField::class,
            ],

            'path' => [
                'class' => FileField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 's3',
                'uploadTo' => rtrim(self::UPLOAD_PATH, '/') . '/%Y/%m/%d',
                'maxSize' => self::UPLOAD_MAX_SIZE . 'M',
            ],

            'original_name' => [
                'class' => CharField::class,
            ],

            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
}
