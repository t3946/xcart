<?php
return [
    [
        'route' => 'products/getallmpn/',
        'target' => ['\Modules\Goods\Controllers\Api\ApiProductController', 'getDistributorProductList'],
        'name' => 'getmpn'
    ],
    [
        'route' => 'analytics',
        'target' => ['\Modules\Goods\Controllers\Api\ApiAnalyticController', 'index'],
        'name' => 'analytic'
    ],
];