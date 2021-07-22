<?php

use Modules\Account\Controllers\AccountDashboard;
use Modules\Account\Controllers\Api\AccountAddressesApi;
use Modules\Forms\Controllers\EmailDashboardAdmin;

return [
    [
        'route' => '/get-addresses',
        'target' => [AccountAddressesApi::class, 'getAddresses'],
        'name' => 'get'
    ],
    [
        'route' => '/change-default-address',
        'target' => [AccountAddressesApi::class, 'changeDefaultAddress'],
        'name' => 'change'
    ],
    [
        'route' => '/remove-address',
        'target' => [AccountAddressesApi::class, 'removeAddress'],
        'name' => 'remove'
    ],
];