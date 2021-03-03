<?php

use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/api/checkout/update',
        'target' => [ OrderProcessController::class, 'checkoutUpdate' ],
        'name' => 'checkout_update_order',
    ],
];