<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'index'],
        'name' => 'index'
    ],

    [
        'route' => '/search',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'search'],
        'name' => 'search'
    ],
    [
        'route' => '/search_suggestion',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'search_ajax_suggestion'],
        'name' => 'search_suggestion'
    ],
];