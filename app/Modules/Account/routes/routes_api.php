<?php

use Modules\Account\Controllers\Api\AccountApi;

return [
    [
        'route' => '/get-territory',
        'target' => [AccountApi::class, 'getTerritory'],
        'name' => 'territory'
    ],
];