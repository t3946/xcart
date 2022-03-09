<?php

namespace Modules\Order\Models\RMA;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class RMAImagesModel extends Model
{
    public static function tableName()
    {
        return 'xcart_rma_images';
    }

    public static function getFields()
    {
        return [
            'rma' => [
                'field' => 'rma_id',
                'class' => ForeignField::class,
                'modelClass' => RMAModel::class,
                'link' => ['rma_id' => 'rma_id'],
            ],
            'image' => [
                'field' => 'image_id',
                'class' => ForeignField::class,
                'modelClass' => ImagesModel::class,
                'link' => ['image_id' => 'image_id'],
            ],
        ];
    }
}