<?php

use Modules\Cart\Controllers\ShoppingCartController;

return [
    [
        'route' => 'cart-items/{:page}',
        'target' => [ShoppingCartController::class, 'getListCart'],
        'name' => 'get_list_shopping_cart'
    ],
    [
        'route' => 'cart-item/{:id}',
        'target' => [ShoppingCartController::class, 'getCartItem'],
        'name' => 'get_shopping_cart_item'
    ]
];