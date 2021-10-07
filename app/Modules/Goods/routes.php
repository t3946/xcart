<?php

return [
    [
        'route' => '/goods/api/ratings',
        'path' => 'Modules.Goods.Routes.routes_ratings_api',
        'namespace' => 'goods:api:ratings',
    ],

    [
        'route' => '/goods/api/reviews',
        'path' => 'Modules.Goods.Routes.routes_reviews_api',
        'namespace' => 'goods:api:reviews',
    ],

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
