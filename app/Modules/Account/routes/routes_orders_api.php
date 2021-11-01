<?php

use Modules\Account\Controllers\Api\OrdersApi;

return [
    [
        'route' => '/get-orders/{*:orders_type}/{*:to_date}',
        'target' => [OrdersApi::class, 'getOrders'],
        'name' => 'get-orders',
    ],
    [
        'route' => '/get-one-order/{*:order_id}',
        'target' => [OrdersApi::class, 'getOneOrder'],
        'name' => 'get-order',
    ],
    [
        'route' => '/send-problem-message',
        'target' => [OrdersApi::class, 'sendProblemMessage'],
        'name' => 'send-problem',
    ],
    [
        'route' => '/open-cancel-request',
        'target' => [OrdersApi::class, 'openCancelItemsRequest'],
        'name' => 'open-cancel-request',
    ],
    [
        'route' => '/open-rma-request',
        'target' => [OrdersApi::class, 'openRmaRequest'],
        'name' => 'open-rma-request',
    ],
];
