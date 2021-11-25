<?php

namespace Modules\Reviews;

use Modules\Reviews\Controllers\Api\ReviewsApi;
use Modules\Reviews\Models\RatingsModel;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Module\Module;

class ReviewsModule extends Module
{
    public const MAX_ATTACHMENTS_NUMBER = 10;
    public const MAX_IMAGE_SIZE_MB = 20;
    public const MAX_VIDEOS_SIZE_MB = 100;
    public const IMAGES_UPLOAD_TO = '';
    public const VIDEOS_UPLOAD_TO = 'media/reviews';

    static function onApplicationRun()
    {
        StorageHelper::push([
            [
                'previewValue' => 'Most recent',
                'viewValue' => 'Most recent',
                'value' => ReviewsApi::SORT_NEW,
            ],
            [
                'previewValue' => 'Top reviews',
                'viewValue' => 'Top reviews',
                'value' => ReviewsApi::SORT_TOP,
            ],
            [
                'previewValue' => 'With images',
                'viewValue' => 'With images',
                'value' => ReviewsApi::SORT_HAS_IMAGES,
            ],
            [
                'previewValue' => 'With videos',
                'viewValue' => 'With videos',
                'value' => ReviewsApi::SORT_HAS_VIDEOS,
            ],
        ], 'orders', 'reviews');

        $ratings_models = RatingsModel::objects()->asArray()->all();
        $ratings = ['overall' => null, 'features' => []];

        foreach ($ratings_models as $i => $model) {
            if ($model['slug'] === 'overall') {
                $ratings['overall'] = $model;
            } else {
                $ratings['features'][] = $model;
            }
        }

        StorageHelper::push($ratings, 'ratings', 'ratings');
    }
}
