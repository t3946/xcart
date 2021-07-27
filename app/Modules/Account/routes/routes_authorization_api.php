<?php

use Modules\Account\Controllers\Api\AuthorizationApi;

return [
    [
        'route' => '/register',
        'target' => [AuthorizationApi::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login',
        'target' => [AuthorizationApi::class, 'login'],
        'name' => 'login',
    ],

    [
        'route' => '/logout',
        'target' => [AuthorizationApi::class, 'logout'],
        'name' => 'logout',
    ],
];
