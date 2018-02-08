<?php

return [
    [
        'route' => '/',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionList'],
        'name' => 'list'
    ],
    [
        'route' => '/delete/{a:key}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDelete'],
        'name' => 'delete'
    ],
    [
        'route' => '/add/{a:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionAdd'],
        'name' => 'add'
    ],
    [
        'route' => '/quantity',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionGetQuantity'],
        'name' => 'quantity:get'
    ],
    [
        'route' => '/quantity/{a:key}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionSetQuantity'],
        'name' => 'quantity:set:post'
    ],
    [
        'route' => '/quantity/{a:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity:set'
    ],
    [
        'route' => '/quantity/inc-{a:key}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity:inc'
    ],
    [
        'route' => '/quantity/dec-{a:key}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity:dec'
    ],
];
