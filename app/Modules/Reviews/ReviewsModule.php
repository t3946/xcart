<?php

namespace Modules\Reviews;

use Modules\Reviews\Controllers\Api\ReviewsApi;
use Modules\Reviews\Models\RatingsModel;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Module\Module;

class ReviewsModule extends Module
{
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
                'previewValue' => 'Reviews with images',
                'viewValue' => 'Reviews with images',
                'value' => ReviewsApi::SORT_HAS_ATTACHMENTS,
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