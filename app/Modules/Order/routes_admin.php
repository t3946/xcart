<?php
return [
    [
        'route' => '/order/{i:order_id}/{slug:mode}/{slug:transaction_id}',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'transaction_process'],
        'name' => 'transaction_process'
    ],
];