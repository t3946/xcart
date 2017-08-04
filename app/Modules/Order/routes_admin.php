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
    [
        'route' => '/{i:order_id}/manual_transaction',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'manual_transaction'],
        'name' => 'manual_transaction'
    ],
    [
        'route' => '/child_transactions/{i:id}',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'child_transactions'],
        'name' => 'child_transactions'
    ],
    [
        'route' => '/child_transactions',
        'target' => ['\Modules\Order\Controllers\OrderTransactionsController', 'child_transactions_list'],
        'name' => 'child_transactions_list'
    ],
];