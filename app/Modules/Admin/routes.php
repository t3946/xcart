<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Admin\Controllers\CommonController', 'index'],
        'name' => 'index'
    ],

    [
        'route' => '/all/{:module}/{:admin}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'all'],
        'name' => 'all'
    ],
    [
        'route' => '/all/{:module}/{:admin}/{:id}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'all'],
        'name' => 'list_nested'
    ],
    [
        'route' => '/create/{:module}/{:admin}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'create'],
        'name' => 'create'
    ],
    [
        'route' => '/create/{:module}/{:admin}/{:id}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'create'],
        'name' => 'create_nested'
    ],
    [
        'route' => '/group_action/{:module}/{:admin}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'groupAction'],
        'name' => 'group_action'
    ],
    [
        'route' => '/sort/{:module}/{:admin}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'sort'],
        'name' => 'sort'
    ],
    [
        'route' => '/columns/{:module}/{:admin}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'columns'],
        'name' => 'columns'
    ],
    [
        'route' => '/update/{:module}/{:admin}/{:pk}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'update'],
        'name' => 'update'
    ],
    [
        'route' => '/info/{:module}/{:admin}/{:pk}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'info'],
        'name' => 'info'
    ],
    [
        'route' => '/remove/{:module}/{:admin}/{:pk}',
        'target' => ['\Modules\Admin\Controllers\AdminController', 'remove'],
        'name' => 'remove'
    ],
    [
        'route' => '/login',
        'target' => ['\Modules\Admin\Controllers\AuthController', 'login'],
        'name' => 'login'
    ],
    [
        'route' => '/logout',
        'target' => ['\Modules\Admin\Controllers\AuthController', 'logout'],
        'name' => 'logout'
    ],
];