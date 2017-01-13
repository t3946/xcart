<?php

return [
    [
        'route' => 'controller=dashboard',
        'target' => ['\Modules\Admin\Controllers\DashboardController', 'index'],
        'name' => 'index'
    ],

//    [
//        'route' => '/all/{:module}/{:admin}',
//        'target' => [\Modules\Admin\Controllers\AdminController::class, 'all'],
//        'name' => 'all'
//    ],
];