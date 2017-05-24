<?php
return [
//    [
//        'route' => '',
//        'path' => 'Modules.Main.routes',
//        'namespace' => 'main'
//    ],
    [
        'route' => '/admin/dashboard',
        'path' => 'Modules.Dashboard.routes_admin',        
        'namespace' => 'dashboard'
    ],
    [
        'route' => '/demo',
        'path' => 'Modules.Demo.routes',
        'namespace' => 'demo'
    ],
    [
        'route' => '/product',
        'path' => 'Modules.Product.routes',
        'namespace' => 'product'
    ],
    [
        'route' => '/category',
        'path' => 'Modules.Product.routes_category',
        'namespace' => 'category'
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
];