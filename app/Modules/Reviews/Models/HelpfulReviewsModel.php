<?php

namespace Modules\Reviews\Models;

use Xcart\App\Orm\Fields\BigIntField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class HelpfulReviewsModel extends Model
{
    public static function tableName()
    {
        return 'helpful_reviews';
    }

    public static function getFields()
    {
        return [
            'review_id' => [
                'class' => BigIntField::class
            ],
            'user_id' => [
                'class' => IntField::class,
            ],
        ];
    }
}
