<?php

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
];