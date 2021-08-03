<?php

use Modules\Account\Controllers\AccountController;

return [
    //страницы
    [
        'route' => '/',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'index'
    ],

    [
        'route' => '/addresses',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'addresses'
    ],

    [
        'route' => '/addresses/add',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'addresses-add'
    ],

    [
        'route' => '/payments/wallet',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'wallet'
    ],

    [
        'route' => '/register',
        'target' => [AccountController::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login',
        'target' => [AccountController::class, 'login'],
        'name' => 'login',
    ],

    [
        'route' => '/logout',
        'target' => [AccountController::class, 'logout'],
        'name' => 'logout',
    ],

    [
        'route' => '/dashboard',
        'target' => [AccountController::class, 'dashboard'],
        'namespace' => 'dashboard'
    ],

    //api
    [
        'route' => '/api/authorization',
        'path' => 'Modules.Account.routes.routes_authorization_api',
        'namespace' => 'authorization_api'
    ],

    [
        'route' => '/api/addresses',
        'path' => 'Modules.Account.routes.routes_addresses_api',
        'namespace' => 'addresses_api'
    ],

    [
        'route' => '/api',
        'path' => 'Modules.Account.routes.routes_api',
        'namespace' => 'api'
    ],

    [
        'route' => '/api/wallet',
        'path' => 'Modules.Account.routes.routes_wallet_api',
        'namespace' => 'api'
    ],
];