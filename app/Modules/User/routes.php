<?php

use Modules\User\Controllers\UserController;
use Modules\User\Controllers\CsTipsController;

return [
    [
        'route' => '/thankyoufororder/',
        'target' => [CsTipsController::class, 'index'],
        'name' => 'cs_tips'
    ],

    [
        'route' => '/thank_you/',
        'target' => [CsTipsController::class, 'tipsLog'],
        'name' => 'tips_log'
    ],

    [
        'route' => '/register/',
        'target' => [UserController::class, 'register'],
        'name' => 'register',
    ],

    [
        'route' => '/login/',
        'target' => [UserController::class, 'login'],
        'name' => 'account-login',
    ],

    [
        'route' => '/user-info/',
        'target' => [UserController::class, 'info'],
        'name' => 'user-info',
    ],

    [
        'route' => '/logout/',
        'target' => [UserController::class, 'logout'],
        'name' => 'user-logout',
    ],
];
