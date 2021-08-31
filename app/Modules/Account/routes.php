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
        'route' => '/payments/transactions',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'transactions'
    ],
    [
        'route' => '/your-lists',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'your-lists'
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
        'name' => 'dashboard'
    ],

    [
        'route' => '/login-and-security',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'login-and-security'
    ],

    [
        'route' => '/login-and-security/edit-name',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'edit-name',
    ],

    [
        'route' => '/login-and-security/edit-email',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'edit-email',
    ],

    [
        'route' => '/login-and-security/edit-phone',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'edit-phone',
    ],

    [
        'route' => '/login-and-security/edit-password',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'edit-password',
    ],

    [
        'route' => '/login-and-security/two-step-verification/settings',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'two-step-verification-settings',
    ],

    [
        'route' => '/login-and-security/two-step-verification/settings/disable',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'two-step-verification-settings-disable',
    ],

    [
        'route' => '/login-and-security/two-step-verification/settings/add-new',
        'target' => [AccountController::class, 'actionTSVAddNew'],
        'name' => 'two-step-verification-add-new',
    ],

    [
        'route' => '/login-and-security/two-step-verification/settings/preferred-method',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'two-step-verification-settings-preferred-method',
    ],

    [
        'route' => '/public-profile',
        'target' => [AccountController::class, 'publicProfile'],
        'name' => 'public-profile'
    ],

    //api
    [
        'route' => '/api/authorization',
        'path' => 'Modules.Account.routes.routes_authorization_api',
        'namespace' => 'authorization_api'
    ],

    [
        'route' => '/api/profile',
        'path' => 'Modules.Account.routes.routes_profile_api',
        'namespace' => 'profile_api'
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

    [
        'route' => '/api/login-and-security',
        'path' => 'Modules.Account.routes.routes_login-and-securities_api',
        'namespace' => 'api',
    ],

    [
        'route' => '/api/tsv',
        'path' => 'Modules.Account.routes.routes_tsv_api',
        'namespace' => 'api',
    ],

    [
        'route' => '/api/lists',
        'path' => 'Modules.Account.routes.routes_lists_api',
        'namespace' => 'api',
    ],
];