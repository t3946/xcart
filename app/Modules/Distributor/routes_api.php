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
        'route' => 'dx/price/save',
        'target' => [ApiDxController::class, 'savePriceListFile'],
        'name' => 'save_price'
    ],
    [
        'route' => 'dx/scheduletest',
        'target' => [ApiDxController::class, 'scheduleDynamic2'],
        'name' => 'dx_schedule_test'
    ],
    [
        'route' => 'dx/column/get/{:dx}',
        'target' => [ApiDxController::class, 'getColumnByDx'],
        'name' => 'dx_column'
    ],
    [
        'route' => 'dx/products-price/save',
        'target' => [ApiDxController::class, 'updateProductsFromPriceList'],
        'name' => 'save_products_price'
    ],
    [
        'route' => 'dx/get-file-list/{:dx}',
        'target' => [ApiDxController::class, 'getFilesList'],
        'name' => 'get_dx_file_List'
    ],
    [
        'route' => 'dx/load-file',
        'target' => [ApiDxController::class, 'loadFile'],
        'name' => 'load_dx_file'
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