<?php


use Modules\Distributor\Controllers\Api\ApiDxController;

return [
    [
        'route' => 'dx/schedule',
        'target' => [ApiDxController::class, 'schedule'],
        'name' => 'dx_schedule'
    ],
    [
        'route' => 'dx/{slug:code}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_info'
    ],
];