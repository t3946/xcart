<?php

use Modules\Account\Controllers\Api\ResetPasswordApi;

return [
    [
        'route' => '/verify-one-time-password',
        'target' => [ResetPasswordApi::class, 'verifyOneTimePassword'],
        'name' => 'verify-one-time-password',
    ],

    [
        'route' => '/send-email',
        'target' => [ResetPasswordApi::class, 'sendEmail'],
        'name' => 'send-email',
    ],

    [
        'route' => '/reset-password',
        'target' => [ResetPasswordApi::class, 'resetPassword'],
        'name' => 'reset-password',
    ],
];
