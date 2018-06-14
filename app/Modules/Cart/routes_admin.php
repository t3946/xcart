<?php

use Modules\Cart\Controllers\ShoppingCartController;

return [
    [
        'route' => '/show_cart',
        'target' => [ShoppingCartController::class, 'actionView'],
        'name' => 'show'
    ]
];