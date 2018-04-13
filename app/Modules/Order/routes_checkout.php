<?php

return [
    [
        'route' => '/shipping/',
        'target' => ['\Modules\Order\Controllers\CheckoutController', 'actionShipping'],
        'name' => 'shipping'
    ]
];