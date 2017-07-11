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
        'route' => '/quantity/{a:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity:set'
    ],
    [
        'route' => '/quantity/{a:key}-inc',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity:inc'
    ],
    [
        'route' => '/quantity/{a:key}-deс',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity:dec'
    ],
];
