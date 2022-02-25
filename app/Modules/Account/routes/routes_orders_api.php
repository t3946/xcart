<?php

use Modules\Account\Controllers\Api\OrdersApi;

return [
    [
        'route' => '/get-order-taxes',
        'target' => [OrdersApi::class, 'getOrderTaxes'],
        'name' => 'get-order-taxes',
    ],
    [
        'route' => '/get-order-group-taxes',
        'target' => [OrdersApi::class, 'getOrderGroupTaxes'],
        'name' => 'get-order-group-taxes',
    ],
    [
        'route' => '/get/{*:orders_type}/{*:to_date}',
        'target' => [OrdersApi::class, 'getOrders'],
        'name' => 'get-orders',
    ],
    [
        'route' => '/get-one/{*:order_id}',
        'target' => [OrdersApi::class, 'getOrder'],
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
    [
        'route' => '/edit-shipping-address',
        'target' => [OrdersApi::class, 'editShippingAddress'],
        'name' => 'edit-shipping-address',
    ],
    [
        'route' => '/get-problem-statuses',
        'target' => [OrdersApi::class, 'getProblemStatuses'],
        'namespace' => 'get-problem-statuses',
    ],
];
