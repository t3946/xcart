<?php


use Modules\Distributor\Controllers\Api\ApiDxController;

return [
    [
        'route' => 'dx/schedule',
        'target' => [ApiDxController::class, 'schedule'],
        'name' => 'dx_schedule'
    ],
    [
        'route' => 'dx/scheduletest',
        'target' => [ApiDxController::class, 'scheduleDynamic'],
        'name' => 'dx_schedule_test'
    ],
    [
        'route' => 'dx/{slug:code}/{i:sfId}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_sf_info'
    ],
    [
        'route' => 'dx/{slug:code}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_info'
    ],

];