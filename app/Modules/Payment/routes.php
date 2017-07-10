<?php

return [
    [
        'route' => '/endpoint/{slug:gateway}',
        'target' => ['\Modules\Payment\Controllers\PaymentController', 'endpoint'],
        'name' => 'endpoint'
    ],
    [
        'route' => '/process/{slug:gateway}',
        'target' => ['\Modules\Payment\Controllers\PaymentController', 'process'],
        'name' => 'process'
    ],
    [
        'route' => '/cancel/{slug:gateway}',
        'target' => ['\Modules\Payment\Controllers\PaymentController', 'cancel'],
        'name' => 'cancel'
    ],
    [
        'route' => '/return/{slug:gateway}',
        'target' => ['\Modules\Payment\Controllers\PaymentController', 'ret'],
        'name' => 'return'
    ],
    [
        'route' => '/success/{slug:gateway}',
        'target' => ['\Modules\Payment\Controllers\PaymentController', 'success'],
        'name' => 'success'
    ],
];