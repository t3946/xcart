<?php

return [
    [
        'route' => '/remember_admin_user/{*:slug}/{*:site}',
        'target' => ['\Modules\User\Controllers\UserController', 'remember_admin_user'],
        'name' => 'remember_admin_user'
    ],
];