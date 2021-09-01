<?php

use Modules\Dashboard\Controllers\SearchController;
use Modules\Dashboard\Controllers\DashboardGroupController;
use Modules\Dashboard\Controllers\DashboardController;

return [
    [
        'route' => '',
        'target' => [DashboardController::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/operators',
        'target' => [DashboardController::class, 'operators'],
        'name' => 'operators'
    ],
    [
        'route' => '/assignments',
        'target' => [DashboardController::class, 'assignments'],
        'name' => 'assignments'
    ],
    [
        'route' => '/subscription/{i:id}',
        'target' => [DashboardController::class, 'subscription'],
        'name' => 'filter_subscription'
    ],
    [
        'route' => '/create/filter',
        'target' => [DashboardController::class, 'create'],
        'name' => 'create_filter'
    ],
    [
        'route' => '/update/filter/{i:id}',
        'target' => [DashboardController::class, 'update'],
        'name' => 'update_filter'
    ],
    [
        'route' => '/admin/filters',
        'target' => [DashboardController::class, 'settings'],
        'name' => 'admin_filters'
    ],
    [
        'route' => '/admin/filters/sort',
        'target' => [DashboardController::class, 'sort'],
        'name' => 'sort_filters'
    ],
    [
        'route' => '/my/filters/sort',
        'target' => [DashboardController::class, 'mySort'],
        'name' => 'sort_my_filters'
    ],
    [
        'route' => '/create/group',
        'target' => [DashboardGroupController::class, 'create'],
        'name' => 'create_group'
    ],
    [
        'route' => '/update/group/{i:id}',
        'target' => [DashboardGroupController::class, 'update'],
        'name' => 'update_group'
    ],
    [
        'route' => '/admin/groups',
        'target' => [DashboardGroupController::class, 'settings'],
        'name' => 'admin_groups'
    ],
    [
        'route' => '/filter/{i:id}',
        'target' => [DashboardController::class, 'filter'],
        'name' => 'filter'
    ],
    [
        'route' => '/search',
        'target' => [SearchController::class, 'index'],
        'name' => 'search'
    ],
    [
        'route' => '/fast-search',
        'target' => [SearchController::class, 'fastSearch'],
        'name' => 'fast-search'
    ],
    [
        'route' => '/search_suggestion',
        'target' => [SearchController::class, 'search_ajax_suggestion'],
        'name' => 'search_suggestion'
    ],
];