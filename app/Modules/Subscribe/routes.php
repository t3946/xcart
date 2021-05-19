<?php

return [
    [
        'route' => '/add/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'getSubscribe'],
        'name' => 'set'
    ],

    [
        'route' => '/delete/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'getUnsubscribe'],
        'name' => 'unset'
    ],
];