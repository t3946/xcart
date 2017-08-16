<?php

return [
    [
        'route' => '/calculate_shipping/{i:id}',
        'target' => ['\Modules\Product\Controllers\ShippingController', 'calculate_shipping'],
        'name' => 'calculate_shipping'
    ],
    [
        'route' => '/group_list',
        'target' => ['\Modules\Product\Controllers\GroupController', 'group_list'],
        'name' => 'group_list'
    ],
    [
        'route' => '/group/{i:id}',
        'target' => ['\Modules\Product\Controllers\GroupController', 'group'],
        'name' => 'group'
    ],

];