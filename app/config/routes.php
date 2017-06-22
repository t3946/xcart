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
        'namespace' => 'catalog'
    ],

    [
        'route' => '/brand',
        'path' => 'Modules.Brand.routes',
        'namespace' => 'brand'
    ],

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
];