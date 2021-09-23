<?php
return [
    [
        'route' => '/{a:type}/{i:id}.{a:ext}',
        'target' => ['\Modules\Image\Controllers\DefaultController', 'actionGet'],
        'name' => 'get:base'
    ],
    [
        'route' => '/{a:type}/{i:id}_{i:size}.{a:ext}',
        'target' => ['\Modules\Image\Controllers\DefaultController', 'actionGet'],
        'name' => 'get:size'
    ],
    [
        'route' => '/{a:type}/{i:id}_{i:size}_{a:method}.{a:ext}',
        'target' => ['\Modules\Image\Controllers\DefaultController', 'actionGet'],
        'name' => 'get:size:crop'
    ],
    [
        'route' => '/{a:type}/{i:id}_{i:w}x{i:h}.{a:ext}',
        'target' => ['\Modules\Image\Controllers\DefaultController', 'actionGet'],
        'name' => 'get:size:wh'
    ],
    [
        'route' => '/{a:type}/{i:id}_{i:w}x{i:h}_{a:method}.{a:ext}',
        'target' => ['\Modules\Image\Controllers\DefaultController', 'actionGet'],
        'name' => 'get:size:wh:crop'
    ],
];