<?php

use Modules\Payment\Controllers\PaymentController;

return [
    [
        'route' => '/endpoint/{slug:gateway}',
        'target' => [PaymentController::class, 'endpoint'],
        'name' => 'endpoint'
    ],
    [
        'route' => '/process/{slug:gateway}',
        'target' => [PaymentController::class, 'process'],
        'name' => 'process'
    ],
    [
        'route' => '/cancel/{slug:gateway}',
        'target' => [PaymentController::class, 'cancel'],
        'name' => 'cancel'
    ],
    [
        'route' => '/return/{slug:gateway}/{i:order_id}/{slug:slug}',
        'target' => [PaymentController::class, 'return'],
        'name' => 'return'
    ],
    [
        'route' => '/success/{slug:gateway}',
        'target' => [PaymentController::class, 'success'],
        'name' => 'success'
    ],
];