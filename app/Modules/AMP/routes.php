<?php

return [
    [
        'route' => '/product/{i:id}/{slug:slug}/',
        'target' => ['\Modules\Amp\Controllers\AmpController', 'amp'],
        'name' => 'product'
    ]
];