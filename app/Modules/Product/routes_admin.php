<?php

return [
    [
        'route' => '/calculate_shipping/{i:id}',
        'target' => ['\Modules\Product\Controllers\ShippingController', 'calculate_shipping'],
        'name' => 'calculate_shipping'
    ],

];