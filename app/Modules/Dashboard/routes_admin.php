<?php

return [
    [
        'route' => 'controller=dashboard',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'index'],
        'name' => 'index'
    ],

    [
        'route' => 'controller=dashboard&action=search',
        'target' => ['\Modules\Dashboard\Controllers\DashboardController', 'search'],
        'name' => 'search'
    ],
];