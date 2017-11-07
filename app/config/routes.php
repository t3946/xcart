<?php
$admin_routes = include('routes_admin.php');
return array_merge($admin_routes, [
    [
        'route' => '/amp',
        'path' => 'Modules.Amp.routes',
        'namespace' => 'amp',
    ],
    [
        'route' => '/brand',
        'path' => 'Modules.Brand.routes',
        'namespace' => 'brand',
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