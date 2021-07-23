<?php

use Modules\Account\Controllers\Api\AuthorizationAddressesApi;

return [
    [
        'route' => '/register',
        'target' => [AuthorizationAddressesApi::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login',
        'target' => [AuthorizationAddressesApi::class, 'login'],
        'name' => 'login',
    ],

    [
        'route' => '/logout',
        'target' => [AuthorizationAddressesApi::class, 'logout'],
        'name' => 'logout',
    ],
];
