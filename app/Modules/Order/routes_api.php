<?php

use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/api/shipping-methods',
        'target' => [ OrderProcessController::class, 'getShippingMethods' ],
        'name' => 'get_shipping_methods',
    ],
    [
        'route' => '/api/payment-methods',
        'target' => [ OrderProcessController::class, 'getPaymentMethods' ],
        'name' => 'get_payment_methods',
    ],
    [
        'route' => '/api/checkout/update',
        'target' => [ OrderProcessController::class, 'checkoutUpdate' ],
        'name' => 'checkout_update_order',
    ],
];