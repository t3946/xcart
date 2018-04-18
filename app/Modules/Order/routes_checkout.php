<?php

return [
    [
        'route' => '/shipping/',
        'target' => ['\Modules\Order\Controllers\CheckoutController', 'actionShipping'],
        'name' => 'shipping'
    ],
    [
        'route' => '/options/',
        'target' => ['\Modules\Order\Controllers\CheckoutController', 'actionOptions'],
        'name' => 'options'
    ],
    [
        'route' => '/review/',
        'target' => ['\Modules\Order\Controllers\CheckoutController', 'actionReview'],
        'name' => 'review'
    ],
];