<?php

use Modules\Amazon\Controllers\AmazonController;
use Modules\Amazon\Controllers\AmazonVerificationController;

return [
    [
        'route' => '',
        'target' => [AmazonController::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/batch_processing',
        'target' => [AmazonController::class, 'batch_processing'],
        'name' => 'batch_processing'
    ],[
        'route' => '/batch_delete',
        'target' => [AmazonController::class, 'batch_delete'],
        'name' => 'batch_delete'
    ],
    [
        'route' => '/batch_processing_check',
        'target' => [AmazonController::class, 'batch_processing_check'],
        'name' => 'batch_processing_check'
    ],
    [
        'route' => '/batch/{i:id}',
        'target' => [AmazonController::class, 'batch'],
        'name' => 'batch'
    ],
    [
        'route' => '/verification/{i:id}/{i:oid}',
        'target' => [AmazonVerificationController::class, 'verification'],
        'name' => 'verification'
    ],
    [
        'route' => '/verification/view',
        'target' => [AmazonVerificationController::class, 'view'],
        'name' => 'view'
    ],
    [
        'route' => '/verification/submit',
        'target' => [AmazonVerificationController::class, 'submit'],
        'name' => 'submit'
    ],
];