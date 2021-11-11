<?php

use Modules\Order\Controllers\Api\AfterShipController;
use Modules\Order\Controllers\Api\InvoiceConventerController;
use Modules\Order\Controllers\Api\OrderLexBotController;
use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/pdf/',
        'target' => [InvoiceConventerController::class, 'convertToPdf'],
        'name' => 'pdf'
    ],

    [
        'route' => '/invoice/',
        'target' => [InvoiceConventerController::class, 'printInvoice'],
        'name' => 'print'
    ],
    [
        'route' => '/api/webhook/',
        'target' => [AfterShipController::class, 'webHook'],
        'name' => 'webhook'
    ],
    [
        'route' => '/cancel/{i:order_id}/{slug:slug}',
        'target' => [OrderProcessController::class, 'cancel'],
        'name' => 'cancel'
    ],
    [
        'route' => '/continue/{i:order_id}/{slug:slug}',
        'target' => [OrderProcessController::class, 'continue'],
        'name' => 'continue'
    ],
    [
        'route' => '/continue/success',
        'target' => [OrderProcessController::class, 'success'],
        'name' => 'success'
    ],
    [
        'route' => '/api/bot',
        'target' => [OrderLexBotController::class, 'index'],
        'name' => 'order_bot'
    ],
    [
        'route' => '/api/decisions',
        'path' => 'Modules.Order.Routes.routes_decision_api',
        'namespace' => 'api',
    ],
];