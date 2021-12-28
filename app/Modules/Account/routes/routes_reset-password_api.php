<?php

use Modules\Account\Controllers\Api\ResetPasswordApi;

return [
    [
        'route' => '/verify-one-time-password',
        'target' => [ResetPasswordApi::class, 'verifyOneTimePassword'],
        'name' => 'verify-one-time-password',
    ],

    [
        'route' => '/send-one-time-password',
        'target' => [ResetPasswordApi::class, 'sendOneTimePassword'],
        'name' => 'send-one-time-password',
    ],

    [
        'route' => '/reset-password',
        'target' => [ResetPasswordApi::class, 'resetPassword'],
        'name' => 'reset',
    ],
];
