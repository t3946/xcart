<?php
return [
    /** PRODUCTS ROUTES */
    [
        'route' => '/product/{slug:sku}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'actionView'],
        //        'name' => 'product:view'
    ],
    [
        'route' => '/product/{i:id}/{slug:slug}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'actionViewOld'],
        'name' => 'product:view',
    ],


    /** CATEGORY ROUTES */

    [
        'route' => '/category/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Product\Controllers\CategoryController', 'actionViewOld'],
        'name' => 'view',
//        'meta' => [
//            'cache' => true,
//            'cache_time' => 60
//        ]
    ],


    /** SEARCH ROUTES */
    [
        'route' => '/search',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionSearch'],
        'name' => 'search',
    ],
    [
        'route' => '/search/suggestion',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionSuggestion'],
        'name' => 'search:suggestion',
    ],

    [
        'route' => '/keyword/{slug:q}',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionKeywords'],
    ],
    [
        'route' => '/keyword/{slug:q}/',
        'target' => ['\Modules\Product\Controllers\SearchController', 'actionKeywords'],
    ],

    /** PRODUCT CART */
    [
        'route' => '/cart',
        'path' => 'Modules.Product.routes_cart',
        'namespace' => 'cart',
        'config' => [
            'cache' => false,
        ]
    ],
];