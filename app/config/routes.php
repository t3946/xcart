<?php
$admin_routes = include 'routes_admin.php';
return array_merge($admin_routes, [
    [
        'route' => '',
        'path' => 'Modules.Main.routes',
        'namespace' => 'main'
    ],
    [
        'route' => '',
        'path' => 'Modules.Goods.routes',
    ],
    [
        'route' => '/product',
        'path' => 'Modules.Landing.routes',
        'namespace' => 'landing',
    ],
    [
        'route' => '/amp',
        'path' => 'Modules.Amp.routes',
        'namespace' => 'amp',
    ],
    [
        'route' => '/pbx',
        'path' => 'Modules.PBX.routes',
        'namespace' => 'pbx',
    ],
    [
        'route' => '/user',
        'path' => 'Modules.User.routes',
        'namespace' => 'user',
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
        'route' => '/demo',
        'path' => 'Modules.Demo.routes',
        'namespace' => 'demo'
    ],
    [
        'route' => '/coupon',
        'path' => 'Modules.Cart.routes_coupon',
        'namespace' => 'coupon',
    ],
    [
        'route' => '/images',
        'path' => 'Modules.Image.routes',
        'namespace' => 'images'
    ],
    [
        'route' => '/payment',
        'path' => 'Modules.Payment.routes',
        'namespace' => 'payment'
    ],
    [
        'route' => '/checkout',
        'path' => 'Modules.Order.routes_checkout',
        'namespace' => 'checkout'
    ],
    [
        'route' => '',
        'path' => 'Modules.Pages.routes',
        'namespace' => 'page'
    ],

    [
        'route' => '/sub',
        'path' => 'Modules.Subscribe.routes',
        'namespace' => 'sub'
    ],

]);