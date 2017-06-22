<?php
return [
    [
        'route' => '',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionSearch'],
        'name' => 'view',
    ],
    [
        'route' => '/',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionSearch'],
        'name' => 'view1',
    ],
];