<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/create/filter',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'create'],
        'name' => 'create_filter'
    ],
    [
        'route' => '/update/filter/{i:id}',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'update'],
        'name' => 'update_filter'
    ],
    [
        'route' => '/admin/filters',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'settings'],
        'name' => 'admin_filters'
    ],
    [
        'route' => '/admin/filters/sort',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'sort'],
        'name' => 'sort_filters'
    ],
    [
        'route' => '/create/group',
        'target' => ['\Modules\Dashboard\Controllers\DashboardGroupController', 'create'],
        'name' => 'create_group'
    ],
    [
        'route' => '/update/group/{i:id}',
        'target' => ['\Modules\Dashboard\Controllers\DashboardGroupController', 'update'],
        'name' => 'update_group'
    ],
    [
        'route' => '/admin/groups',
        'target' => ['\Modules\Dashboard\Controllers\DashboardGroupController', 'settings'],
        'name' => 'admin_groups'
    ],
    [
        'route' => '/filter/{i:id}',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'filter'],
        'name' => 'filter'
    ],
    [
        'route' => '/search',
        'target' => ['\Modules\Dashboard\Controllers\SearchController', 'index'],
        'name' => 'search'
    ],
    [
        'route' => '/search_suggestion',
        'target' => ['\Modules\Dashboard\Controllers\SearchController', 'search_ajax_suggestion'],
        'name' => 'search_suggestion'
    ],
];