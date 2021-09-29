<?php

use Modules\Account\Controllers\Api\ReviewApi;

return [
    [
        'route' => '/create',
        'target' => [ReviewApi::class, 'createReview'],
        'name' => 'create'
    ],
    [
        'route' => '/get-product-reviews',
        'target' => [ReviewApi::class, 'getProductReviews'],
        'name' => 'get-product-reviews'
    ],
];