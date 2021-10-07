<?php

use Modules\Goods\Controllers\Api\ReviewsApi;

return [
    [
        'route' => '/get-ratings',
        'target' => [ReviewsApi::class, 'getRatingsAction'],
        'name' => 'get-ratings'
    ],
];