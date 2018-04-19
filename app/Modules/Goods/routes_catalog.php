<?php

use Modules\Goods\Controllers\SearchController;
use Modules\Goods\Controllers\CategoryController;
use Modules\Goods\Controllers\DefaultController;
use Modules\Goods\Controllers\PromoController;

return [
    /** PRODUCTS ROUTES */
    [
        'route' => '/product/{slug:sku}',
        'target' => [DefaultController::class, 'actionView'],
//        'name' => 'product:view'
    ],
    [
        'route' => '/product/{i:id}/{slug:slug}/',
        'target' => [DefaultController::class, 'actionViewOld'],
        'name' => 'product:view',
    ],


    /** CATEGORY ROUTES */


    [
        'route' => '/category/bestsellers',
        'target' => [PromoController::class, 'actionBestsellers'],
        'name' => 'bestsellers'
    ],
    [
        'route' => '/category/featured',
        'target' => [PromoController::class, 'actionFeatured'],
        'name' => 'featured'
    ],
    [
        'route' => '/category/new',
        'target' => [PromoController::class, 'actionNew'],
        'name' => 'new'
    ],
    [
        'route' => '/category/viewed',
        'target' => [PromoController::class, 'actionViewed'],
        'name' => 'viewed'
    ],
    [
        'route' => '/category/brands',
        'target' => [PromoController::class, 'actionBrands'],
        'name' => 'brands'
    ],


    [
        'route' => '/category/{i:id}/{slug:slug}/',
        'target' => [CategoryController::class, 'actionViewOld'],
        'name' => 'view'
    ],


    /** SEARCH ROUTES */
    [
        'route' => '/search',
        'target' => [SearchController::class, 'actionSearch'],
        'name' => 'search',
    ],
    [
        'route' => '/search/suggestion',
        'target' => [SearchController::class, 'actionSuggestion'],
        'name' => 'search:suggestion',
    ],

    [
        'route' => '/keyword/{slug:q}',
        'target' => [SearchController::class, 'actionKeywords'],
    ],
    [
        'route' => '/keyword/{slug:q}/',
        'target' => [SearchController::class, 'actionKeywords'],
    ],
];