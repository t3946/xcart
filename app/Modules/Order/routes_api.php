<?php

use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/api/checkout/update',
        'target' => [ OrderProcessController::class, 'checkoutUpdate' ],
        'name' => 'checkout_update_order',
    ],
    [
        'route' => '/api/get-extra',
        'target' => [ OrderProcessController::class, 'getExtra' ],
        'name' => 'get-extra',
    ]
];