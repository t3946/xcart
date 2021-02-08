<?php

use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/api/shipping-methods',
        'target' => [ OrderProcessController::class, 'getShippingMethods' ],
        'name' => 'get_shipping_methods',
    ],
];