<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/create',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'create'],
        'name' => 'create'
    ],
    [
        'route' => '/settings',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'settings'],
        'name' => 'settings'
    ],
    [
        'route' => '/edit/{i:id}',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'edit'],
        'name' => 'edit'
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