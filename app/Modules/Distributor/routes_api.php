<?php


use Modules\Distributor\Controllers\Api\ApiDxController;

return [
    [
        'route' => 'dx/{slug:code}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_info'
    ],
];