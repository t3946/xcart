<?php
return [
    [
        'route' => '/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Product\Controllers\CategoryController', 'actionViewOld'],
        'name' => 'view:old',
//        'meta' => [
//            'cache' => true,
//            'cache_time' => 60
//        ]
    ],
    [
        'route' => '/{i:id}/{slug:slug}',
        'target' => ['\Modules\Product\Controllers\CategoryController', 'actionViewOld'],
        'name' => 'view:old2',
//        'meta' => [
//            'cache' => true,
//            'cache_time' => 60
//        ]
    ],
];