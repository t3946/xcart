<?php

use Modules\Account\Controllers\AccountDashboard;
use Modules\Account\Controllers\Api\AccountApi;
use Modules\Forms\Controllers\EmailDashboardAdmin;

return [
    [
        'route' => '/get-territory',
        'target' => [AccountApi::class, 'getTerritory'],
        'name' => 'territory'
    ],
];