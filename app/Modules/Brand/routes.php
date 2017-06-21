<?php
return [
    [
        'route' => '/{slug:sku}',
        'target' => ['\Modules\Brand\Controllers\DefaultController', 'actionView'],
        'name' => 'view'
    ],
    [
        'route' => '/{i:id}/{slug:slug}',
        'target' => ['\Modules\Brand\Controllers\DefaultController', 'actionViewOld'],
        'name' => 'view:old'
    ],
];