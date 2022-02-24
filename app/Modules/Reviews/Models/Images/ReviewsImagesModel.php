<?php

namespace Modules\Reviews\Models\Images;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ReviewsImagesModel extends Model
{
    public static function tableName()
    {
        return 'xcart_reviews_images';
    }

    public static function getFields()
    {
        return [
            'review_id' => [
                'class' => IntField::class,
            ],

            'image_id' => [
                'class' => IntField::class,
            ],

            'images' => [
                'class' => ForeignField::class,
                'modelClass' => ImagesModel::class,
                'link' => ['image_id' => 'image_id'],
            ],
        ];
    }

    /**
     * save image and link
     */
    public function saveImage(int $linked_entity_id, array $image_attributes)
    {
        $image = new ImagesModel($image_attributes);
        $image->save();

        $attributes = [
            'review_id' => $linked_entity_id,
            'image_id' => (int)$image->pk,
        ];

        $this->setAttributes($attributes);
        $this->save();
    }
}
