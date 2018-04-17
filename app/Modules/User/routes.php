<?php

return [
    [
        'route' => '/thankyoufororder/',
        'target' => ['\Modules\User\Controllers\CsTipsController', 'index'],
        'name' => 'cs_tips'
    ],

    [
        'route' => '/thank_you/',
        'target' => ['\Modules\User\Controllers\CsTipsController', 'tipsLog'],
        'name' => 'tips_log'
    ]
];