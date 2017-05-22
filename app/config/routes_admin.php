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
        'route' => '/admin/reports',
        'path' => 'Modules.Reports.routes_admin',
        'namespace' => 'reports'
    ],
    [
        'route' => '/admin/amazon',
        'path' => 'Modules.Amazon.routes_admin',
        'namespace' => 'amazon'
    ],
//    [
//        'route' => '/admin/files',
//        'path' => 'Modules.Files.routes',
//        'namespace' => 'files'
//    ],
//    [
//        'route' => '/admin/editor',
//        'path' => 'Modules.Editor.routes',
//        'namespace' => 'editor'
//    ],
];