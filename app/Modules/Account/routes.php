<?php

use Modules\Account\Controllers\AccountController;

return [
    [
        'route' => '/',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'index'
    ],

    [
        'route' => '/register/',
        'target' => [AccountController::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login/',
        'target' => [AccountController::class, 'login'],
        'name' => 'login',
    ],

    [
        'route' => '/user-info/',
        'target' => [AccountController::class, 'info'],
        'name' => 'info',
    ],

    [
        'route' => '/logout/',
        'target' => [AccountController::class, 'logout'],
        'name' => 'logout',
    ],
    [
        'route' => '/api/addresses',
        'path' => 'Modules.Account.routes.routes_addresses_api',
        'namespace' => 'addresses_api'
    ],
];