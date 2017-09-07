<?php
return [
    /** PRODUCT CART */
    [
        'route' => '/add/product-{slug:key}',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionAdd'],
        'name' => 'add'
    ],
    [
        'route' => '/add/products',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionProductsAdd'],
        'name' => 'products:add'
    ],
    [
        'route' => '/',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionList'],
        'name' => 'list'
    ],
    [
        'route' => '/delete/{a:key}',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionDelete'],
        'name' => 'delete'
    ],
//    [
//        'route' => '/add/{a:key}-{i:quantity}',
//        'target' => ['\Modules\Cart\Controllers\CartController', 'actionAdd'],
//        'name' => 'add'
//    ],
    [
        'route' => '/quantity',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionGetQuantity'],
        'methods' => ['GET'],
        'name' => 'quantity:get'
    ],
    [
        'route' => '/quantity',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionSetPostQuantity'],
        'methods' => ['POST'],
        'name' => 'quantity:set:post'
    ],
//    [
//        'route' => '/quantity/{a:key}',
//        'target' => ['\Modules\Product\Controllers\CartController', 'actionSetQuantity'],
//        'name' => 'quantity:set:post'
//    ],
    [
        'route' => '/quantity/{a:key}-{i:quantity}',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity:set'
    ],
    [
        'route' => '/quantity/inc-{a:key}',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity:inc'
    ],
    [
        'route' => '/quantity/dec-{a:key}',
        'target' => ['\Modules\Product\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity:dec'
    ],
];