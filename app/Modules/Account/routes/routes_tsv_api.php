<?php

use Modules\Account\Controllers\Api\TSVApi;

return [
    [
        'route' => '/confirm-code',
        'target' => [TSVApi::class, 'confirmCodeAction'],
        'name' => 'confirm-code'
    ],

    [
        'route' => '/disable',
        'target' => [TSVApi::class, 'disableAction'],
        'name' => 'disable'
    ],

    [
        'route' => '/get',
        'target' => [TSVApi::class, 'getAction'],
        'name' => 'get'
    ],
];
