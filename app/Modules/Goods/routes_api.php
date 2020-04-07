<?php

use Modules\Goods\Controllers\Api\ApiAnalyticController;
use Modules\Goods\Controllers\Api\ApiProductController;
use Modules\Order\Controllers\Api\UpsMapController;

return [
    [
        'route' => 'products/getallmpn/',
        'target' => [ApiProductController::class, 'getDistributorProductList'],
        'name' => 'getmpn'
    ],
    [
        'route' => 'products/mpn/{i:mnf_id}/',
        'target' => [ApiProductController::class, 'getMpn'],
        'name' => 'gmpn'
    ],
    [
        'route' => 'analytics',
        'target' => [ApiAnalyticController::class, 'index'],
        'name' => 'analytic'
    ],

    [
        'route' => 'upsmap/{slug:zipcode}',
        'target' => [UpsMapController::class, 'index'],
        'name' => 'upsmap'
    ],
];