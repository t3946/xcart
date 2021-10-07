<?php

use Modules\Goods\Controllers\Api\ReviewsApi;

return [
    [
        'route' => '/create',
        'target' => [ReviewsApi::class, 'createReview'],
        'name' => 'create'
    ],

    [
        'route' => '/get-reviews',
        'target' => [ReviewsApi::class, 'getReviewsAction'],
        'name' => 'get-reviews'
    ],

    [
        'route' => '/get-ratings-and-reviews',
        'target' => [ReviewsApi::class, 'getReviewsAndRatingsAction'],
        'name' => 'get-ratings-and-reviews'
    ],
];
