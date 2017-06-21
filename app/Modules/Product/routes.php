<?php
return [
    [
        'route' => '/{slug:sku}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'actionView'],
        'name' => 'view'
    ],
    [
        'route' => '/{i:id}/{slug:slug}',
        'target' => ['\Modules\Product\Controllers\DefaultController', 'actionViewOld'],
        'name' => 'view:old'
    ],
];