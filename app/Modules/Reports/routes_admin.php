<?php

return [
    [
        'route' => '',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/create/report',
        'target' => ['\Modules\Reports\Controllers\ReportsController', 'create'],
        'name' => 'create_report'
    ],

];