<?php

use Modules\Account\Controllers\Api\AccountApi;

return [
    [
        'route' => '/cancel-transaction',
        'target' => [AccountApi::class, 'cancelTransaction'],
        'name' => 'cancel-transaction'
    ],

    [
        'route' => '/get-territory',
        'target' => [AccountApi::class, 'getTerritory'],
        'name' => 'territory'
    ],

    [
        'route' => '/send-sms',
        'target' => [AccountApi::class, 'sendSMS'],
        'name' => 'send-sms'
    ],

    [
        'route' => '/get-payment-methods',
        'target' => [AccountApi::class, 'getPaymentMethodsAction'],
        'name' => 'get-payment-methods'
    ],

    [
        'route' => '/get-initial-data',
        'target' => [AccountApi::class, 'getInitialDataAction'],
        'name' => 'get-initial-data'
    ],

    [
        'route' => '/get-invoice-pdf',
        'target' => [AccountApi::class, 'getInvoicePdf'],
        'name' => 'get-invoice-pdf'
    ],

    [
        'route' => '/get-site-data',
        'target' => [AccountApi::class, 'getSiteDataAction'],
        'name' => 'get-site-data'
    ],

    [
        'route' => '/get-product-info',
        'target' => [AccountApi::class, 'getProductInfo'],
        'name' => 'get-product-info'
    ],
    [
        'route' => '/lists',
        'path' => 'Modules.Account.routes.routes_lists_api',
        'namespace' => 'lists',
    ],

    [
        'route' => '/profile',
        'path' => 'Modules.Account.routes.routes_profile_api',
        'namespace' => 'profile',
    ],

    [
        'route' => '/wallet',
        'path' => 'Modules.Account.routes.routes_wallet_api',
        'namespace' => 'wallet',
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
