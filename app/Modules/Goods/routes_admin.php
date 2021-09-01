<?php

return [
    [
        'route' => '/calculate_shipping/{i:id}',
        'target' => ['\Modules\Goods\Controllers\ShippingController', 'calculate_shipping'],
        'name' => 'calculate_shipping'
    ],
    [
        'route' => '/group_list',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group_list'],
        'name' => 'group_list'
    ],
    [
        'route' => '/group_products',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group_products'],
        'name' => 'group_products'
    ],
    [
        'route' => '/group_product/{i:id}',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group_products'],
        'name' => 'group_product'
    ],
    [
        'route' => '/group_categories',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'categories'],
        'name' => 'group_categories'
    ],
    [
        'route' => '/group_images',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'images'],
        'name' => 'group_images'
    ],
    [
        'route' => '/group/{i:id}',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group'],
        'name' => 'group'
    ],
    [
        'route' => '/group_remove',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group_remove'],
        'name' => 'group_remove'
    ],
    [
        'route' => '/group_add',
        'target' => ['\Modules\Goods\Controllers\GroupController', 'group_add'],
        'name' => 'group_add'
    ],

];