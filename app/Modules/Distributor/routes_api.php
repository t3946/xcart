<?php


use Modules\Distributor\Controllers\Api\ApiDxController;
use Modules\Distributor\Controllers\Api\VrsController;

return [
    [
        'route' => 'dx/schedule',
        'target' => [ApiDxController::class, 'scheduleDynamic'],
        'name' => 'dx_schedule'
    ],
    [
        'route' => 'dx/scheduletest',
        'target' => [ApiDxController::class, 'scheduleDynamic2'],
        'name' => 'dx_schedule_test'
    ],
    [
        'route' => 'dx/{slug:code}/{i:sfId}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_sf_info'
    ],
    [
        'route' => 'dx/{slug:code}/{slug:sfCode}',
        'target' => [ApiDxController::class, 'getDxInfoSfCode'],
        'name' => 'dx_sf_info_code'
    ],
    [
        'route' => 'dx/{slug:code}',
        'target' => [ApiDxController::class, 'getDxInfo'],
        'name' => 'dx_info'
    ],
    [
        'route' => "vrs/get-status/{*:url}",
        'target' => [VrsController::class, 'getSiteStatus'],
        'name' => 'vrs_status'
    ],
    [
        'route' => "vrs/get-messages/{*:domain}",
        'target' => [VrsController::class, 'getMessages'],
        'name' => 'vrs_messages'
    ],
    [
        'route' => "vrs/send-message",
        'target' => [VrsController::class, 'sendMessage'],
        'name' => 'vrs_send_message'
    ],
    [
        'route' => "vrs/account-login",
        'target' => [VrsController::class, 'userAuthorization'],
        'name' => 'vrs_login'
    ],
    [
        'route' => "vrs/jwt-account-login",
        'target' => [VrsController::class, 'userJWTAuthorization'],
        'name' => 'vrs_login_jwt'
    ],
];