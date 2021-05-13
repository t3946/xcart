<?php

use Modules\Goods\Controllers\Api\ApiAnalyticController;
use Modules\Goods\Controllers\Api\ApiImageController;
use Modules\Goods\Controllers\Api\ApiProductController;
use Modules\Order\Controllers\Api\UpsMapController;
use Modules\Goods\Controllers\Api\ApiCategoriesController;

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
        'route' => 'brand/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Brand\Controllers\DefaultController', 'actionViewOld'],
        'name' => 'viewApi'
    ],
    [
        'route' => 'product/{i:id}/{slug:slug}/',
        'target' => [ApiProductController::class, 'actionProductGroup'],
        'name' => 'groupProductApi',
    ],
];
