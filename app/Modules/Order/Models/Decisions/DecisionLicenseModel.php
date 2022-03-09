<?php

namespace Modules\Order\Models\Decisions;

use Modules\Order\OrderModule;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class DecisionLicenseModel extends Model
{
    public static function tableName()
    {
        return 'decision_licenses';
    }

    public static function getUploadPath(): string
    {
        return OrderModule::DECISIONS_LICENSE_UPLOAD_TO;
    }

    public static function getMaxUploadSizeMB(): int
    {
        return OrderModule::DECISIONS_LICENSE_UPLOAD_MAX_SIZE_MB;
    }

    public static function getFields()
    {
        return [
            'decision_license_id' => [
                'class' => AutoField::class,
            ],

            'decision_id' => [
                'class' => IntField::class,
            ],

            'path' => [
                'class' => ImageField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => rtrim(static::getUploadPath(), '/') . '/%Y/%m/%d',
                'maxSize' => static::getMaxUploadSizeMB() . 'M',
            ],
        ];
    }
}