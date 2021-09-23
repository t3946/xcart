<?php
return [
    [
        'route' => '',
        'target' => ['\Modules\Demo\Controllers\DefaultController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/brand',
        'target' => ['\Modules\Demo\Controllers\DefaultController', 'catalogBrand'],
        'name' => 'catalog:brand'
    ],
    [
        'route' => '/catalog',
        'target' => ['\Modules\Demo\Controllers\DefaultController', 'catalogIndex'],
        'name' => 'catalog:index'
    ],
    [
        'route' => '/search',
        'target' => ['\Modules\Demo\Controllers\DefaultController', 'catalogSearch'],
        'name' => 'catalog:search'
    ],
    [
        'route' => '/product',
        'target' => ['\Modules\Demo\Controllers\DefaultController', 'product'],
        'name' => 'product'
    ],
];