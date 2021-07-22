<?php

use Modules\Account\Controllers\AccountDashboard;
use Modules\Forms\Controllers\EmailDashboardAdmin;

return [
    [
        'route' => '/*',
        'target' => [AccountDashboard::class, 'actionIndex'],
        'name' => 'home'
    ],
    [
        'route' => '/api/addresses',
        'path' => 'Modules.Account.routes.routes_addresses_api',
        'namespace' => 'addresses_api'
    ],
];