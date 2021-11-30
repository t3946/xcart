<?php

use Modules\Account\Controllers\Api\AccountApi;

return [
    [
        'route' => '/get-territory',
        'target' => [AccountApi::class, 'getTerritory'],
        'name' => 'territory'
    ],

    [
        'route' => '/get-routes',
        'target' => [AccountApi::class, 'getRoutesList'],
        'name' => 'get-routes-list',
    ],

    [
        'route' => '/lists',
        'path' => 'Modules.Account.routes.routes_lists_api',
        'namespace' => 'lists',
    ],
    [
        'route' => '/authorization',
        'path' => 'Modules.Account.routes.routes_authorization_api',
        'namespace' => 'user',
    ],

    [
        'route' => '/profile',
        'path' => 'Modules.Account.routes.routes_profile_api',
        'namespace' => 'profile',
    ],

    [
        'route' => '/addresses',
        'path' => 'Modules.Account.routes.routes_addresses_api',
        'namespace' => 'addresses',
    ],

    [
        'route' => '/wallet',
        'path' => 'Modules.Account.routes.routes_wallet_api',
        'namespace' => 'wallet',
    ],

    [
        'route' => '/login-and-security',
        'path' => 'Modules.Account.routes.routes_login-and-securities_api',
        'namespace' => 'login-and-security',
    ],

    [
        'route' => '/tsv',
        'path' => 'Modules.Account.routes.routes_tsv_api',
        'namespace' => 'tsv',
    ],

    [
        'route' => '/reset-password',
        'path' => 'Modules.Account.routes.routes_reset-password_api',
        'namespace' => 'reset-password',
    ],

    [
        'route' => '/review',
        'path' => 'Modules.Account.routes.routes_review_api',
        'namespace' => 'review',
    ],
    [
        'route' => '/orders',
        'path' => 'Modules.Account.routes.routes_orders_api',
        'namespace' => 'orders',
    ],
];
