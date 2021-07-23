<?php

use Modules\User\Controllers\CsTipsController;

return [
    [
        'route' => '/thankyoufororder/',
        'target' => [CsTipsController::class, 'index'],
        'name' => 'cs_tips'
    ],

    [
        'route' => '/thank_you/',
        'target' => [CsTipsController::class, 'tipsLog'],
        'name' => 'tips_log'
    ],
];
