<?php

use Modules\Account\Controllers\Api\AccountApi;

return [
    [
        'route' => '/get-territory',
        'target' => [AccountApi::class, 'getTerritory'],
        'name' => 'territory'
    ],

    [
        'route' => '/get-site-properties',
        'target' => [AccountApi::class, 'getSiteProperties'],
        'name' => 'get-site-properties',
    ],

    [
        'route' => '/get-routes',
        'target' => [AccountApi::class, 'getRoutesList'],
        'name' => 'get-routes-list',
    ],
];