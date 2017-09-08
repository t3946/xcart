<?php
return [
    [
        'route' => '',
        'path' => 'Modules.Main.routes',
        'namespace' => 'main'
    ],
    [
        'route' => '',
        'path' => 'Modules.Product.routes',
//        'namespace' => 'catalog',
//        'config' => [
//            'cache' => [
//                'time' => 360,
//            ]
//        ]
    ],

    [
        'route' => '/brand',
        'path' => 'Modules.Brand.routes',
        'namespace' => 'brand',
//        'config' => [
//            'cache' => [
//                'time' => 3600,
//            ]
//        ]
    ],
//    [
//        'route' => '/cart',
//        'path' => 'Modules.Cart.routes',
//        'namespace' => 'cart',
//        'config' => [
//            'cache' => false
//        ]
//    ],

    [
        'route' => '/admin/dashboard',
        'path' => 'Modules.Dashboard.routes_admin',
        'namespace' => 'dashboard'
    ],
    [
        'route' => '/admin/reports',
        'path' => 'Modules.Reports.routes_admin',
        'namespace' => 'reports'
    ],
    [
        'route' => '/admin/amazon',
        'path' => 'Modules.Amazon.routes_admin',
        'namespace' => 'amazon'
    ],

    [
        'route' => '/demo',
        'path' => 'Modules.Demo.routes',
        'namespace' => 'demo'
    ],
    [
        'route' => '/payment',
        'path' => 'Modules.Payment.routes',
        'namespace' => 'payment'
    ],
    [
        'route' => '/admin/brand',
        'path' => 'Modules.Brand.routes_admin',
        'namespace' => 'brand'
    ],
    [
        'route' => '/admin/order',
        'path' => 'Modules.Order.routes_admin',
        'namespace' => 'order'
    ],
    [
        'route' => '/admin/product',
        'path' => 'Modules.Product.routes_admin',
        'namespace' => 'product'
    ],

];