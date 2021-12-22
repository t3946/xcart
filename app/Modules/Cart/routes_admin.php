<?php

use Modules\Cart\Controllers\ShoppingCartController;

return [
    [
        'route' => '/api/',
        'path' => 'Modules.Cart.routes_api',
        'namespace' => 'api'
    ],
    [
        'route' => '/show_cart',
        'target' => [ShoppingCartController::class, 'actionView'],
        'name' => 'show'
    ]
];