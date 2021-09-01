<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/view',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'view'],
        'name' => 'view'
    ],
    [
        'route' => '/load/{i:id}',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'load'],
        'name' => 'load'
    ],
    [
        'route' => '/create/report',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'create'],
        'name' => 'create_report'
    ],
    [
        'route' => '/update/{i:id}',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'update'],
        'name' => 'update_report'
    ],
    [
        'route' => '/list',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'reports_list'],
        'name' => 'admin_reports'
    ],

];