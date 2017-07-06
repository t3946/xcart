<?php

return [
    [
        'route' => '/',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionList'],
        'name' => 'list'
    ],
    [
        'route' => '/delete/{slug:key}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDelete'],
        'name' => 'delete'
    ],
    [
        'route' => '/add/{slug:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionAdd'],
        'name' => 'add'
    ],
    [
        'route' => '/quantity/{slug:key}-{i:quantity}',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionQuantity'],
        'name' => 'quantity'
    ],
    [
        'route' => '/quantity/{slug:key}-inc',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionIncrease'],
        'name' => 'quantity_increase'
    ],
    [
        'route' => '/quantity/{slug:key}-dev',
        'target' => ['\Modules\Cart\Controllers\CartController', 'actionDecrease'],
        'name' => 'quantity_decrease'
    ],
];
