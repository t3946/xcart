<?php

use Modules\Order\Controllers\Admin\OrderRelatedMessagesController;
use Modules\Order\Controllers\Api\ActivityController;
use Modules\Order\Controllers\Api\ExportController;
use Modules\Order\Controllers\Api\ReconciliationController;
use Modules\Order\Controllers\FraudCheckController;

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
    [
        'route' => '/api/tag/add/{i:order_id}/{i:status_id}',
        'target' => ['\Modules\Order\Controllers\Admin\ApiTagsController', 'actionAdd'],
        'name' => 'api:tag:add'
    ],
    [
        'route' => '/api/tag/del/{i:order_id}/{i:status_id}',
        'target' => ['\Modules\Order\Controllers\Admin\ApiTagsController', 'actionDel'],
        'name' => 'api:tag:del'
    ],
    [
        'route' => '/api/payable_manufacturers',
        'target' => [ReconciliationController::class, 'actionPayableManufacturers'],
        'name' => 'api:payable_manufacturers'
    ],
    [
        'route' => '/api/payable_orders',
        'target' => [ReconciliationController::class, 'actionPayableOrders'],
        'name' => 'api:payable_orders'
    ],
    [
        'route' => '/api/payable_orders/prereconcile',
        'target' => [ReconciliationController::class, 'actionPayableOrdersPreReconcile'],
        'name' => 'api:payable_prereconcile'
    ],
    [
        'route' => '/api/payable_orders/tentatively',
        'target' => [ReconciliationController::class, 'actionPayableOrdersTentatively'],
        'name' => 'api:payable_tentatively'
    ],
    [
        'route' => '/order_note_tag_settings',
        'target' => [OrderRelatedMessagesController::class, 'actionSetOrderNoteTag'],
        'name' => 'order_note_tag_settings'
    ],
    [
        'route' => '/api/activity/{i:order_id}/{slug:action}',
        'target' => [ActivityController::class, 'hook'],
        'name' => 'activity'
    ],
    [
        'route' => '/api/export/{i:order_id}',
        'target' => [ExportController::class, 'export'],
        'name' => 'api:export'
    ],
    [
        'route' => '/fraud_check_v2/{:order_id}',
        'target' => [FraudCheckController::class, 'index'],
        'name' => 'order_test'
    ]
];