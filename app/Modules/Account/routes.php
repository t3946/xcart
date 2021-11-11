<?php

use Modules\Account\Controllers\AccountController;

return [
    //страницы
    [
        'route' => '/your-lists/add-product-to-list/{*:is_already_in_list}/{*:list_id}/{*:sku}',
        'target' => [AccountController::class, 'actionProductIndex'],
        'name' => 'add-product',
    ],
    [
        'route' => '/your-lists/add-list/{*:sku}',
        'target' => [AccountController::class, 'actionProductIndex'],
        'name' => 'add-list',
    ],
    [
        'route' => '/',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'index'
    ],

    [
        'route' => '/review',
        'target' => [AccountController::class, 'actionReview'],
        'name' => 'review'
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
        'route' => '/addresses/edit',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'addresses-edit'
    ],

    [
        'route' => '/payments/wallet',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'wallet'
    ],
    [
        'route' => '/payments/wallet/add',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'wallet-add'
    ],
    [
        'route' => '/payments/wallet/edit',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'wallet-edit'
    ],
    [
        'route' => '/payments/transactions',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'transactions'
    ],
    [
        'route' => '/your-lists/invite/{*:tag}/{*:code}',
        'target' => [AccountController::class, 'listInvite'],
        'name' => 'list-invite'
    ],
    [
        'route' => '/your-lists/{*:id}',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'your-lists'
    ],
    [
        'route' => '/your-lists',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'your-lists-shipping'
    ],
    [
        'route' => '/orders',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'orders'
    ],
    [
        'route' => '/orders/open-orders',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'open-orders'
    ],
    [
        'route' => '/orders/canceled-orders',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'canceled-orders'
    ],
    [
        'route' => '/orders/completed-orders',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'completed-orders'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/order-tracking',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-tracking'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/products-ordered',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-products'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/order-actions',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-actions'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/addresses',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-addresses'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/communication',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-communication'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/order-info/log',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-info-log'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/email-info/{*:email_id}',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-email'
    ],
    [
        'route' => '/orders/{*:id}/{*:orderType}/change-address',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-change-address'
    ],
    [
        'route' => '/orders/decisions-required',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'order-decisions-required',
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
        'route' => '/login-and-security/two-step-verification/recovery',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'two-step-verification-recovery',
    ],

    [
        'route' => '/login-and-security/two-step-verification/password-assistance',
        'target' => [AccountController::class, 'actionIndex'],
        'name' => 'two-step-verification-recovery-password-assistance',
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

    [
        'route' => '/api/reset-password',
        'path' => 'Modules.Account.routes.routes_reset-password_api',
        'namespace' => 'api',
    ],

    [
        'route' => '/api/review',
        'path' => 'Modules.Account.routes.routes_review_api',
        'namespace' => 'api',
    ],
    [
        'route' => '/api/orders',
        'path' => 'Modules.Account.routes.routes_orders_api',
        'namespace' => 'api',
    ],
];