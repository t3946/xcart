<?php
return [
    [
        'route' => '',
        'path' => 'Modules.Product.routes_catalog',
        'namespace' => 'catalog',
    ],

    [
        'route' => '/cart',
        'path' => 'Modules.Product.routes_cart',
        'namespace' => 'cart',
        'config' => [
            'cache' => false,
        ]
    ],
];