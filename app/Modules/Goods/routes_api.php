<?php

use Modules\Brand\Controllers\DefaultController;
use Modules\Goods\Controllers\Api\ApiAnalyticController;
use Modules\Goods\Controllers\Api\ApiImageController;
use Modules\Goods\Controllers\Api\ApiProductController;
use Modules\Order\Controllers\Api\UpsMapController;
use Modules\Goods\Controllers\Api\ApiCategoriesController;
use Modules\Goods\Controllers\SearchController;

return [
    [
        'route' => 'products/getallmpn/',
        'target' => [ApiProductController::class, 'getDistributorProductList'],
        'name' => 'getmpn'
    ],
    [
        'route' => 'products/mpn/{i:mnf_id}/',
        'target' => [ApiProductController::class, 'getMpn'],
        'name' => 'gmpn'
    ],
    [
        'route' => 'products/verify',
        'target' => [ApiProductController::class, 'verify'],
        'name' => 'verify'
    ],
    [
        'route' => 'analytics',
        'target' => [ApiAnalyticController::class, 'index'],
        'name' => 'analytic'
    ],
    [
        'route' => 'upsmap/{slug:zipcode}',
        'target' => [UpsMapController::class, 'index'],
        'name' => 'upsmap'
    ],
    [
        'route' => 'i/{i:image_id}/{i:width}/{**:filename}',
        'target' => [ApiImageController::class, 'view'],
        'name' => 'image_resize'
    ],
    [
        'route' => 'i/{i:image_id}/{**:filename}',
        'target' => [ApiImageController::class, 'view'],
        'name' => 'image'
    ],
    [
        'route' => 'goods/get/{i:product_id}/',
        'target' => [ApiProductController::class, 'getById'],
        'name' => 'getById'
    ],

    /** PRODUCT SLIDERS */
    [
        'route' => 'category/bestsellers',
        'target' => [ApiCategoriesController::class, 'actionSliderBestsellers'],
        'name' => 'bestsellersApi'
    ],
    [
        'route' => 'category/featured',
        'target' => [ApiCategoriesController::class, 'actionSliderFeatured'],
        'name' => 'featuredApi'
    ],
    [
        'route' => 'category/new',
        'target' => [ApiCategoriesController::class, 'actionSliderNew'],
        'name' => 'newApi'
    ],
    [
        'route' => 'category/viewed-{i:id}',
        'target' => [ApiCategoriesController::class, 'actionSliderViewed'],
        'name' => 'viewedApiProduct'
    ],
    [
        'route' => 'category/viewed',
        'target' => [ApiCategoriesController::class, 'actionSliderViewed'],
        'name' => 'viewedApi'
    ],
    [
        'route' => 'category/also-bound-{i:id}',
        'target' => [ApiCategoriesController::class, 'actionSliderAlsoBought'],
        'name' => 'also_boundApi'
    ],
    [
        'route' => 'category/related-{i:id}',
        'target' => [ApiCategoriesController::class, 'actionSliderRelatedProducts'],
        'name' => 'relatedApi'
    ],
    [
        'route' => 'category/{i:id}/{slug:slug}/',
        'target' => [ApiCategoriesController::class, 'actionCatalogCategory'],
        'name' => 'categoryPaginatedApi',
    ],
    [
        'route' => 'category/bought-products',
        'target' => [ApiCategoriesController::class, 'getBuyAgainProducts'],
        'name' => 'bought-products',
    ],
    [
        'route' => 'brand/{i:id}/{slug:slug}/',
        'target' => [DefaultController::class, 'actionViewOld'],
        'name' => 'viewApi'
    ],
    [
        'route' => 'product/{i:id}/{slug:slug}/',
        'target' => [ApiProductController::class, 'actionProductGroup'],
        'name' => 'groupProductApi',
    ],
    [
        'route' => 'product/update_image/{i:id}/',
        'target' => [ApiProductController::class, 'actionUpdateImages'],
        'name' => 'updateImages',
    ],

    /** SEARCH ROUTES */
    [
        'route' => 'search',
        'target' => [SearchController::class, 'actionSearch'],
        'name' => 'search',
    ],
    [
        'route' => 'search/suggestion',
        'target' => [SearchController::class, 'actionApiSuggestion'],
        'name' => 'search:suggestion',
    ],
    [
        'route' => 'keyword/{slug:q}',
        'target' => [SearchController::class, 'actionKeywords'],
    ],
    [
        'route' => 'keyword/{slug:q}/',
        'target' => [SearchController::class, 'actionKeywords'],
    ],
];
