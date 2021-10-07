<?php

use Modules\Account\Controllers\Api\OrdersApi;

return [
    [
        'route' => '/get-orders',
        'target' => [OrdersApi::class, 'getOrders'],
        'name' => 'get-orders',
    ],
];
