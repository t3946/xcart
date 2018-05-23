<?php

use Modules\Order\Api\Controllers\InvoiceConventerController;

return [
    [
        'route' => '/{i:order_id}/authorise',
        'target' => [InvoiceConventerController::class, 'convertToPdf'],
        'name' => 'pdf'
    ],
];