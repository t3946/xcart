<?php

namespace Modules\Reviews\Models\Videos;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ReviewsVideosModel extends Model
{
    public static function tableName()
    {
        return 'xcart_reviews_videos';
    }

    public static function getFields()
    {
        return [
            'review_id' => [
                'class' => IntField::class,
            ],

            'video_id' => [
                'class' => IntField::class,
            ],
            'videos' => [
                'class' => ForeignField::class,
                'modelClass' => VideosModel::class,
                'link' => ['video_id' => 'video_id'],
            ],
        ];
    }

    public function saveVideo(int $linked_entity_id, array $video_attributes) {
        $video = new VideosModel($video_attributes);
        $video->save();

        $attributes = [
            'review_id' => $linked_entity_id,
            'video_id' => (int)$video->pk,
        ];

        $this->setAttributes($attributes);
        $this->save();
    }
}