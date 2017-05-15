<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/batch_processing',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'batch_processing'],
        'name' => 'batch_processing'
    ],
    [
        'route' => '/batch_processing_check',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'batch_processing_check'],
        'name' => 'batch_processing_check'
    ],
    [
        'route' => '/batch/{i:id}',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'batch'],
        'name' => 'batch'
    ],
];