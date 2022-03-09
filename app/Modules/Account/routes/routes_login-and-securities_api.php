<?php

use Modules\Account\Controllers\Api\LoginAndSecuritiesApi;

return [
    [
        'route' => '/edit-name',
        'target' => [LoginAndSecuritiesApi::class, 'editName'],
        'name' => 'edit-name',
    ],
    [
        'route' => '/edit-email',
        'target' => [LoginAndSecuritiesApi::class, 'editEmailAddress'],
        'name' => 'edit-email',
    ],
    [
        'route' => '/edit-phone',
        'target' => [LoginAndSecuritiesApi::class, 'editPhoneNumber'],
        'name' => 'edit-phone',
    ],
    [
        'route' => '/edit-password',
        'target' => [LoginAndSecuritiesApi::class, 'editPassword'],
        'name' => 'edit-password',
    ],
];
