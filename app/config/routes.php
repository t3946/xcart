<?php
$admin_routes = include('routes_admin.php');
return array_merge($admin_routes, [
    [
        'route' => '',
        'path' => 'Modules.Main.routes',
        'namespace' => 'main'
    ],
    [
        'route' => '/amp',
        'path' => 'Modules.Amp.routes',
        'namespace' => 'amp',
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
        'route' => '/demo',
        'path' => 'Modules.Demo.routes',
        'namespace' => 'demo'
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
        'route' => '/',
        'path' => 'Modules.Pages.routes',
        'namespace' => 'page'
    ],

]);