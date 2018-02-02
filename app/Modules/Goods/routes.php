<?php
return [

    [
        'route' => '/getallmpn/',
        'target' => ['\Modules\Goods\Controllers\ProductController', 'getDistributorProductList'],
        'name' => 'getmpn'
    ],

    [
        'route' => '',
        'path' => 'Modules.Goods.routes_catalog',
        'namespace' => 'catalog',
    ],

    [
        'route' => '/cart',
        'path' => 'Modules.Goods.routes_cart',
        'namespace' => 'cart',
        'config' => [
            'cache' => false,
        ]
    ],

];