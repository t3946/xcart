<?php

return [

    [
        'route' => '/send/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'sendMessage'],
        'namespace' => 'send_message'
    ],

    [
        'route' => '/add/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'getSubscribe'],
        'namespace' => 'set'
    ],

    [
        'route' => '/delete/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'getUnsubscribe'],
        'namespace' => 'unset'
    ],
];