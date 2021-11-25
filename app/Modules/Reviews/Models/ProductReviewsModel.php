<?php

namespace Modules\Reviews\Models;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BigIntField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Modules\Reviews\Models\Images\ReviewsImagesModel;
use Modules\Reviews\Models\Videos\ReviewsVideosModel;

class ProductReviewsModel extends Model
{
    public static function tableName()
    {
        return 'product_reviews';
    }

    public static function getFields()
    {
        return [
            'product_review_id' => [
                'class' => AutoField::class,
            ],
            'user_id' => [
                'class' => BigIntField::class,
            ],
            'product_id' => [
                'class' => BigIntField::class,
            ],
            'header' => [
                'class' => CharField::class,
            ],
            'body' => [
                'class' => CharField::class,
            ],
            'location' => [
                'class' => CharField::class,
            ],
            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
            'rating' => [
                'class' => ForeignField::class,
                'modelClass' => ReviewRatingsModel::class,
                'link' => ['product_review_id' => 'review_id'],
            ],
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'user_id'],
            ],
            'helpful' => [
                'class' => ForeignField::class,
                'modelClass' => HelpfulReviewsModel::class,
                'link' => ['product_review_id' => 'review_id'],
            ],
            'helpful_total' => [
                'class' => IntField::class,
                'default' => 0,
            ],
            'images' => [
                'class' => ForeignField::class,
                'modelClass' => ReviewsImagesModel::class,
                'link' => ['product_review_id' => 'review_id'],
            ],
            'videos' => [
                'class' => ForeignField::class,
                'modelClass' => ReviewsVideosModel::class,
                'link' => ['product_review_id' => 'review_id'],
            ],
        ];
    }
}
