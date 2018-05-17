<?php

return [

    [
        'route' => '/send/',
        'target' => ['\Modules\Subscribe\Controllers\SubscribeController', 'sendMessage'],
        'name' => 'send_message'
    ],

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