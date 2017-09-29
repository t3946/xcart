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
        'route' => '/group_products',
        'target' => ['\Modules\Product\Controllers\GroupController', 'group_products'],
        'name' => 'group_products'
    ],
    [
        'route' => '/group_product/{i:id}',
        'target' => ['\Modules\Product\Controllers\GroupController', 'group_products'],
        'name' => 'group_product'
    ],
    [
        'route' => '/group_categories',
        'target' => ['\Modules\Product\Controllers\GroupController', 'categories'],
        'name' => 'group_categories'
    ],
    [
        'route' => '/group_images',
        'target' => ['\Modules\Product\Controllers\GroupController', 'images'],
        'name' => 'group_images'
    ],
    [
        'route' => '/group/{i:id}',
        'target' => ['\Modules\Product\Controllers\GroupController', 'group'],
        'name' => 'group'
    ],

];