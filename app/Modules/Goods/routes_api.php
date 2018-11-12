<?php

use Modules\Goods\Controllers\Api\ApiAnalyticController;
use Modules\Goods\Controllers\Api\ApiProductController;

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
];