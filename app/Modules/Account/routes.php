<?php

use Modules\Account\Controllers\AccountDashboard;
use Modules\Forms\Controllers\EmailDashboardAdmin;

return [
    [
        'route' => '/*',
        'target' => [AccountDashboard::class, 'actionIndex'],
        'name' => 'home'
    ],
];