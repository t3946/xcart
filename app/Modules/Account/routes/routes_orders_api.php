<?php

use Modules\Account\Controllers\Api\OrdersApi;

return [
    [
        'route' => '/get-orders/{*:orders_type}/{*:to_date}',
        'target' => [OrdersApi::class, 'getOrders'],
        'name' => 'get-orders',
    ],
];
