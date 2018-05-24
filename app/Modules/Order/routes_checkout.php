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
    [
        'route' => '/auto_complete_country/',
        'target' => [CheckoutController::class, 'actionAutoCompleteCountry'],
        'name' => 'auto_complete_country'
    ],
    [
        'route' => '/auto_complete_zip_code/',
        'target' => [CheckoutController::class, 'actionAutoCompleteZipCode'],
        'name' => 'auto_complete_zip_code'
    ],
    [
        'route' => '/auto_complete_state/',
        'target' => [CheckoutController::class, 'actionAutoCompleteState'],
        'name' => 'auto_complete_state'
    ],
    [
        'route' => '/auto_complete_city/',
        'target' => [CheckoutController::class, 'actionAutoCompleteCity'],
        'name' => 'auto_complete_city'
    ],
];