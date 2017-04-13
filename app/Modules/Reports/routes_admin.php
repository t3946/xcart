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
        'route' => '/create/report',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'create'],
        'name' => 'create_report'
    ],

];