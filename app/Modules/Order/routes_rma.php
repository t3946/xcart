<?php

use Modules\Order\Controllers\RMAController;

return [
    [
        'route' => '/{i:order_id}',
        'target' => [RMAController::class, 'request'],
        'name' => 'rma_request'
    ],
];