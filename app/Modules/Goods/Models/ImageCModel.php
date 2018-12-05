<?php
namespace Modules\Goods\Models;


class ImageCModel extends ImageModel
{
    public static function tableName()
    {
        return parent::tableName().'_C';
    }

    public static function getFields()
    {
        return array_merge_recursive(parent::getFields(), [
            'image_path' => [
                'uploadTo' => 'T/%M/%O/%Y-%m-%d',
            ]
        ]);
    }
}