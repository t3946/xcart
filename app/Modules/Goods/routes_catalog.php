<?php
return [
    /** PRODUCTS ROUTES */
    [
        'route' => '/product/{slug:sku}',
        'target' => ['\Modules\Goods\Controllers\DefaultController', 'actionView'],
        //        'name' => 'product:view'
    ],
    [
        'route' => '/product/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Goods\Controllers\DefaultController', 'actionViewOld'],
        'name' => 'product:view',
    ],


    /** CATEGORY ROUTES */

    [
        'route' => '/category/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Goods\Controllers\CategoryController', 'actionViewOld'],
        'name' => 'view',
//        'meta' => [
//            'cache' => true,
//            'cache_time' => 60
//        ]
    ],


    /** SEARCH ROUTES */
    [
        'route' => '/search',
        'target' => ['\Modules\Goods\Controllers\SearchController', 'actionSearch'],
        'name' => 'search',
    ],
    [
        'route' => '/search/suggestion',
        'target' => ['\Modules\Goods\Controllers\SearchController', 'actionSuggestion'],
        'name' => 'search:suggestion',
    ],

    [
        'route' => '/keyword/{slug:q}',
        'target' => ['\Modules\Goods\Controllers\SearchController', 'actionKeywords'],
    ],
    [
        'route' => '/keyword/{slug:q}/',
        'target' => ['\Modules\Goods\Controllers\SearchController', 'actionKeywords'],
    ],
];