<?php
return [
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