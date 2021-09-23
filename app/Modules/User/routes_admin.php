<?php

use Modules\User\Controllers\Admin\IdentityCheckController;

return [
    [
        'route' => '/check_user',
        'target' => [IdentityCheckController::class, 'actionCallback'],
        'name' => 'checker'
    ],
    [
        'route' => '/start_check',
        'target' => [IdentityCheckController::class, 'actionRequest'],
        'name' => 'start_check'
    ],
    [
        'route' => '/logout',
        'target' => [IdentityCheckController::class, 'logout'],
        'name' => 'logout'
    ]
];