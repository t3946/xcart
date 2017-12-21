<?php

return [
    [
        'route' => '/check_user',
        'target' => ['\Modules\User\Controllers\Admin\IdentityCheckController', 'actionCallback'],
        'name' => 'view'
    ]
];