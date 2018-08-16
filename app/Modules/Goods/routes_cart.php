<?php

use Modules\Goods\Controllers\CartController;

return [
    /** PRODUCT CART */
    [
        'route' => '/add/product-{slug:key}',
        'target' => [CartController::class, 'actionAdd'],
        'name' => 'add'
    ],
    [
        'route' => '/add/products',
        'target' => [CartController::class, 'actionProductsAdd'],
        'name' => 'products:add'
    ],
    [
        'route' => '/get/products',
        'target' => [CartController::class, 'actionProductsGet'],
        'name' => 'products:get'
    ],
    [
        'route' => '/set/products',
        'target' => [CartController::class, 'actionProductsSet'],
        'name' => 'products:set'
    ],
    [
        'route' => '/del/products',
        'target' => [CartController::class, 'actionProductsDel'],
        'name' => 'products:del'
    ],

    [
        'route' => '',
        'target' => [CartController::class, 'actionList'],
    ],
    [
        'route' => '/',
        'target' => [CartController::class, 'actionList'],
        'name' => 'list'
    ],
    [
        'route' => '/delete/{slug:key}',
        'target' => [CartController::class, 'actionDelete'],
        'name' => 'delete'
    ],
//    [
//        'route' => '/add/{a:key}-{i:quantity}',
//        'target' => ['\Modules\Cart\Controllers\CartController', 'actionAdd'],
//        'name' => 'add'
//    ],
    [
        'route' => '/quantity',
        'target' => [CartController::class, 'actionGetQuantity'],
        'methods' => ['GET'],
        'name' => 'quantity:get'
    ],
    [
        'route' => '/quantity',
        'target' => [CartController::class, 'actionSetPostQuantity'],
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
        'target' => [CartController::class, 'actionQuantity'],
        'name' => 'quantity:set'
    ],
    [
        'route' => '/quantity/inc-{a:key}',
        'target' => [CartController::class, 'actionIncrease'],
        'name' => 'quantity:inc'
    ],
    [
        'route' => '/quantity/dec-{a:key}',
        'target' => [CartController::class, 'actionDecrease'],
        'name' => 'quantity:dec'
    ],
    [
        'route' => '/calculate_shipping',
        'target' => [CartController::class, 'actionCalculateShipping'],
        'name' => 'calculate_shipping'
    ],
];