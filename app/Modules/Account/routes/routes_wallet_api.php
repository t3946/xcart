<?php

use Modules\Account\Controllers\AccountDashboard;
use Modules\Account\Controllers\Api\AccountWalletApi;
use Modules\Forms\Controllers\EmailDashboardAdmin;

return [
    [
        'route' => '/get-cards',
        'target' => [AccountWalletApi::class, 'getCards'],
        'name' => 'cards'
    ],
    [
        'route' => '/change-default',
        'target' => [AccountWalletApi::class, 'changeDefault'],
        'name' => 'default'
    ],
    [
        'route' => '/add-card',
        'target' => [AccountWalletApi::class, 'addNewCard'],
        'name' => 'add'
    ],
];