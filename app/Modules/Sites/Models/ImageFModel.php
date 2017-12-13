<?php
namespace Modules\Sites\Models;


use Modules\Goods\Models\ImageModel;

class ImageFModel extends ImageModel
{
    public static function tableName()
    {
        return parent::tableName().'_F';
    }

    public static function getFields()
    {
        return array_merge_recursive(parent::getFields(), [
            'image_path' => [
                'uploadTo' => 'F/%M/%O/%Y-%m-%d',
            ]
        ]);
    }
}