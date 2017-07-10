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
        'name' => 'quantity_decrease'
    ],
    [
        'route' => '/quantity/{a:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity'
    ],
    [
        'route' => '/quantity/{a:key}-inc',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity_increase'
    ],
    [
        'route' => '/quantity/{a:key}-deс',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity_decrease'
    ],
];
