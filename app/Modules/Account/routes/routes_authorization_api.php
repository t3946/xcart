<?php

use Modules\Account\Controllers\Api\AccountAuthorizationApi;

return [
    [
        'route' => '/register',
        'target' => [AccountAuthorizationApi::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login',
        'target' => [AccountAuthorizationApi::class, 'login'],
        'name' => 'login',
    ],

    [
        'route' => '/check-login',
        'target' => [AccountAuthorizationApi::class, 'checkLogin'],
        'name' => 'check-login',
    ],

    [
        'route' => '/logout',
        'target' => [AccountAuthorizationApi::class, 'logout'],
        'name' => 'logout',
    ],
];
