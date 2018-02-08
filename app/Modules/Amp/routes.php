<?php

return [
    [
        'route' => '/product/{i:id}/{*:slug}/',
        'target' => ['\Modules\Amp\Controllers\AmpController', 'amp'],
        'name' => 'product'
    ],
    [
        'route' => '/product/{i:id}/{*:slug}',
        'target' => ['\Modules\Amp\Controllers\AmpController', 'index'],
    ],
];