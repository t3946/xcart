<?php

use Modules\Account\Controllers\Api\TSVApi;

return [
    [
        'route' => '/generate',
        'target' => [TSVApi::class, 'generate'],
        'name' => 'generate'
    ],
    [
        'route' => '/check-code',
        'target' => [TSVApi::class, 'checkCode'],
        'name' => 'check-code'
    ],
];
