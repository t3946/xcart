<?php
namespace Modules\Sites\Models;


use Modules\Product\Models\ImageModel;

class ImageSModel extends ImageModel
{
    public static function tableName()
    {
        return parent::tableName().'_S';
    }

    public static function getFields()
    {
        return array_merge_recursive(parent::getFields(), [
            'image_path' => [
                'uploadTo' => 'S/%M/%O/%Y-%m-%d',
            ]
        ]);
    }
}