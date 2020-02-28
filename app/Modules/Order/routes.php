<?php

use Modules\Order\Controllers\Api\AfterShipController;
use Modules\Order\Controllers\Api\InvoiceConventerController;

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
];