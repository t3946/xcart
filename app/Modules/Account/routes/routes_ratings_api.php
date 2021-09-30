<?php

use Modules\Account\Controllers\Api\RatingsApi;

return [
    [
        'route' => '/get-product-ratings',
        'target' => [RatingsApi::class, 'getProductRatings'],
        'name' => 'get-product-ratings'
    ],
];