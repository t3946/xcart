<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BlobField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;

class ImageMModel extends ImageModel
{
    public static function tableName()
    {
        return parent::tableName().'_M';
    }

    public static function getFields()
    {
        return array_merge_recursive(parent::getFields(), [
            'image_path' => [
                'uploadTo' => 'M/%M/%O/%Y-%m-%d',
            ]
        ]);
    }
}