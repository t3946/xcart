<?php
return [
    [
        'route' => '/{slug:sku}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'view'],
        'name' => 'view'
    ],
    [
        'route' => '/{i:id}/{slug:slug}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'view_old'],
        'name' => 'view:old'
    ],
];