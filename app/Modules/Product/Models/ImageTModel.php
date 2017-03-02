<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class ImageTModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_images_T';
    }

    public static function getFields()
    {
        return [
            'imageid' => [
                'class' => AutoField::className(),
            ]
        ];
    }

    public function getURL()
    {
        return ltrim($this->image_path, '.');
    }
}