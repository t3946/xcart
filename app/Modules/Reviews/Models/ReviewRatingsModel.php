<?php

namespace Modules\Reviews\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ReviewRatingsModel extends Model
{
    public static function tableName()
    {
        return 'review_ratings';
    }

    public static function getFields()
    {
        return [
            'review_id' => [
                'class' => IntField::class,
            ],
            'rating_id' => [
                'class' => IntField::class,
            ],
            'rating' => [
                'class' => IntField::class,
            ],
        ];
    }

    public function toArray(): array
    {
        return $this->getAttributes();
    }
}
