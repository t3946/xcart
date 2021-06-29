<?php

return [
    [
        'route' => '/check_user',
        'target' => ['\Modules\User\Controllers\Admin\IdentityCheckController', 'actionCallback'],
        'name' => 'checker'
    ],
    [
        'route' => '/start_check',
        'target' => ['\Modules\User\Controllers\Admin\IdentityCheckController', 'actionRequest'],
        'name' => 'start_check'
    ]
];