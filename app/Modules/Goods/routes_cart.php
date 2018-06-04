<?php
return [
    /** PRODUCT CART */
    [
        'route' => '/add/product-{slug:key}',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionAdd'],
        'name' => 'add'
    ],
    [
        'route' => '/add/products',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionProductsAdd'],
        'name' => 'products:add'
    ],
    [
        'route' => '/get/products',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionProductsGet'],
        'name' => 'products:get'
    ],
    [
        'route' => '/set/products',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionProductsSet'],
        'name' => 'products:set'
    ],
    [
        'route' => '/del/products',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionProductsDel'],
        'name' => 'products:del'
    ],

    [
        'route' => '',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionList'],
    ],
    [
        'route' => '/',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionList'],
        'name' => 'list'
    ],
    [
        'route' => '/delete/{a:key}',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionDelete'],
        'name' => 'delete'
    ],
//    [
//        'route' => '/add/{a:key}-{i:quantity}',
//        'target' => ['\Modules\Cart\Controllers\CartController', 'actionAdd'],
//        'name' => 'add'
//    ],
    [
        'route' => '/quantity',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionGetQuantity'],
        'methods' => ['GET'],
        'name' => 'quantity:get'
    ],
    [
        'route' => '/quantity',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionSetPostQuantity'],
        'methods' => ['POST'],
        'name' => 'quantity:set:post'
    ],
//    [
//        'route' => '/quantity/{a:key}',
//        'target' => ['\Modules\Goods\Controllers\CartController', 'actionSetQuantity'],
//        'name' => 'quantity:set:post'
//    ],
    [
        'route' => '/quantity/{a:key}-{i:quantity}',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity:set'
    ],
    [
        'route' => '/quantity/inc-{a:key}',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity:inc'
    ],
    [
        'route' => '/quantity/dec-{a:key}',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity:dec'
    ],
    [
        'route' => '/calculate_shipping',
        'target' => ['\Modules\Goods\Controllers\CartController', 'actionCalculateShipping'],
        'name' => 'calculate_shipping'
    ],
];