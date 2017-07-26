<?php
return [
    [
        'route' => '/{i:order_id}/{slug:mode}/{i:id}',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'transaction_process'],
        'name' => 'transaction_process'
    ],
    [
        'route' => '/{i:order_id}/authorise',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'authorise'],
        'name' => 'authorise'
    ],
];