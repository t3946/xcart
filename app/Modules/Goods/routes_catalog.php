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
        'route' => '/category/bestsellers',
        'target' => ['\Modules\Goods\Controllers\PromoController', 'actionBestsellers'],
        'name' => 'bestsellers'
    ],
    [
        'route' => '/category/featured',
        'target' => ['\Modules\Goods\Controllers\PromoFeaturedController', 'actionFeatured'],
        'name' => 'featured'
    ],
    [
        'route' => '/category/new',
        'target' => ['\Modules\Goods\Controllers\PromoController', 'actionNew'],
        'name' => 'new'
    ],
    [
        'route' => '/category/viewed',
        'target' => ['\Modules\Goods\Controllers\PromoController', 'actionViewed'],
        'name' => 'viewed'
    ],
    [
        'route' => '/category/brands',
        'target' => ['\Modules\Goods\Controllers\PromoController', 'actionBrands'],
        'name' => 'brands'
    ],


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