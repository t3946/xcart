<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/create_shipping',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'create_shipping'],
        'name' => 'create_shipping'
    ],
    [
        'route' => '/batch/{i:id}',
        'target' => ['\Modules\Amazon\Controllers\AmazonController', 'batch'],
        'name' => 'batch'
    ],
];