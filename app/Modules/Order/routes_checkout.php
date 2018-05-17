<?php

use Modules\Order\Controllers\CheckoutController;

return [
    [
        'route' => '/shipping/',
        'target' => [CheckoutController::class, 'actionShipping'],
        'name' => 'shipping'
    ],
    [
        'route' => '/options/',
        'target' => [CheckoutController::class, 'actionOptions'],
        'name' => 'options'
    ],
    [
        'route' => '/review/',
        'target' => [CheckoutController::class, 'actionReview'],
        'name' => 'review'
    ],
    [
        'route' => '/payment/',
        'target' => [CheckoutController::class, 'actionPayment'],
        'name' => 'payment'
    ],
    [
        'route' => '/complete/{i:order_id}/{slug:slug}',
        'target' => [CheckoutController::class, 'actionComplete'],
        'name' => 'complete'
    ],
    [
        'route' => '/invoice/',
        'target' => [CheckoutController::class, 'actionInvoice'],
        'name' => 'invoice'
    ],
    [
        'route' => '/invoicepdf/',
        'target' => [CheckoutController::class, 'actionInvoicePdf'],
        'name' => 'invoicepdf'
    ],
];