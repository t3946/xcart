<?php

use Modules\Reviews\Controllers\Api\ReviewsApi;

return [
    [
        'route' => '/create',
        'target' => [ReviewsApi::class, 'createReview'],
        'name' => 'create',
    ],

    [
        'route' => '/get-reviews',
        'target' => [ReviewsApi::class, 'getReviewsAction'],
        'name' => 'get-reviews',
    ],

    [
        'route' => '/mark-helpful',
        'target' => [ReviewsApi::class, 'markHelpfulAction'],
        'name' => 'mark-helpful',
    ],

    [
        'route' => '/unmark-helpful',
        'target' => [ReviewsApi::class, 'unmarkHelpfulAction'],
        'name' => 'unmark-helpful',
    ],

    [
        'route' => '/get-ratings',
        'target' => [ReviewsApi::class, 'getRatingsAction'],
        'name' => 'get-ratings'
    ],

    [
        'route' => '/get-ratings-and-reviews',
        'target' => [ReviewsApi::class, 'getReviewsAndRatingsAction'],
        'name' => 'get-ratings-and-reviews',
    ],

    [
        'route' => '/check-video-file',
        'target' => [ReviewsApi::class, 'checkVideoFileAction'],
        'name' => 'check-video-file',
    ],
];
