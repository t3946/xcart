<?php
return [
    [
        'route' => '/api/',
        'path' => 'Modules.Goods.routes_api',
        'namespace' => 'api'
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