<?php

use Modules\Account\Controllers\Api\TSVApi;

return [
    [
        'route' => '/confirm-code',
        'target' => [TSVApi::class, 'confirmCode'],
        'name' => 'confirm-code'
    ],

    [
        'route' => '/disable',
        'target' => [TSVApi::class, 'disable'],
        'name' => 'disable'
    ],
];