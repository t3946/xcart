<?php
return [
    [
        'route' => '/{i:id}/{slug:slug}',
        'target' => ['\Modules\Product\Controllers\CategoryController', 'view_old'],
        'name' => 'view:old'
    ],
];