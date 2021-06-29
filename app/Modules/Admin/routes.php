<?php

use Modules\Admin\Controllers\AuthController;
use Modules\Admin\Controllers\AdminController;
use Modules\Admin\Controllers\CommonController;
use Modules\Admin\Controllers\DistributorController;
use Modules\Admin\Controllers\FieldController;

return [
    [
        'route' => '/',
        'target' => [CommonController::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/list/{:module}/{:admin}/owner/{:id}',
        'target' => [AdminController::class, 'all'],
        'name' => 'list_owned'
    ],
    [
        'route' => '/suggestion/{:module}/{:admin}/{:entity}',
        'target' => [AdminController::class, 'suggestion'],
        'name' => 'suggestion'
    ],
    [
        'route' => '/list/{:module}/{:admin}/{:id}',
        'target' => [AdminController::class, 'all'],
        'name' => 'list_nested'
    ],
    [
        'route' => '/list/{:module}/{:admin}',
        'target' => [AdminController::class, 'all'],
        'name' => 'list'
    ],
    [
        'route' => '/create/{:module}/{:admin}/owner/{:id}',
        'target' => [AdminController::class, 'create'],
        'name' => 'create_owned'
    ],
    [
        'route' => '/create/{:module}/{:admin}/{:id}',
        'target' => [AdminController::class, 'create'],
        'name' => 'create_nested'
    ],
    [
        'route' => '/create/{:module}/{:admin}',
        'target' => [AdminController::class, 'create'],
        'name' => 'create'
    ],
    [
        'route' => '/group_action/{:module}/{:admin}',
        'target' => [AdminController::class, 'groupAction'],
        'name' => 'group_action'
    ],
    [
        'route' => '/sort/{:module}/{:admin}',
        'target' => [AdminController::class, 'sort'],
        'name' => 'sort'
    ],
    [
        'route' => '/sort/{:module}/{:admin}/{:id}',
        'target' => [AdminController::class, 'sort'],
        'name' => 'sort_nested'
    ],
    [
        'route' => '/columns/{:module}/{:admin}',
        'target' => [AdminController::class, 'columns'],
        'name' => 'columns'
    ],

    [
        'route' => '/update/{:module}/{:admin}/{:pk}/owner/{:owner}',
        'target' => [AdminController::class, 'update'],
        'name' => 'update_owned'
    ],
    [
        'route' => '/update/{:module}/{:admin}/{:pk}',
        'target' => [AdminController::class, 'update'],
        'name' => 'update'
    ],
    [
        'route' => '/update/{:module}/{:admin}/{:pk}/{:section}',
        'target' => [AdminController::class, 'update_section'],
        'name' => 'update_section'
    ],
    [
        'route' => '/update/{:module}/{:admin}',
        'target' => [AdminController::class, 'updateall'],
        'name' => 'updateall'
    ],
    [
        'route' => '/info/{:module}/{:admin}/{:pk}/{:dx}',
        'target' => [DistributorController::class, 'info'],
        'name' => 'info_dx'
    ],
    [
        'route' => '/info/{:module}/{:admin}/{:pk}',
        'target' => [AdminController::class, 'info'],
        'name' => 'info'
    ],
    [
        'route' => '/remove/{:module}/{:admin}/{:pk}',
        'target' => [AdminController::class, 'remove'],
        'name' => 'remove'
    ],
    [
        'route' => '/login',
        'target' => [AuthController::class, 'login'],
        'name' => 'login'
    ],
    [
        'route' => '/logout',
        'target' => [AuthController::class, 'logout'],
        'name' => 'logout'
    ],
    [
        'route' => '/distributor/{i:mid}/{i:section}',
        'target' => [DistributorController::class, 'index'],
        'name' => 'section'
    ],
    [
        'route' => '/distributor/add',
        'target' => [DistributorController::class, 'index'],
        'name' => 'dx_add'
    ],
    [
        'route' => '/distributor/contact_sort/{i:mid}',
        'target' => [DistributorController::class, 'contact_sort'],
        'name' => 'dx_contact_sort'
    ],
    [
        'route' => '/distributor/contact_create/{i:mid}',
        'target' => [DistributorController::class, 'contact_create'],
        'name' => 'dx_contact_create'
    ],
    [
        'route' => '/field/reload/',
        'target' => [FieldController::class, 'field_reload'],
        'name' => 'field_reload'
    ],
];