<?php

use Modules\Main\Controllers\ContactFormController;
use Modules\Main\Controllers\DefaultController;
use Modules\Main\Controllers\TestController;
use Modules\Main\Controllers\RobotsController;

return [
    '' => [
        'route' => '',
        'target' => [DefaultController::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/contactus/',
        'target' => [ContactFormController::class, 'actionContactUs'],
        'name' => 'contact_us_form'
    ],
    [
        'route' => '/about-us/',
        'target' => [DefaultController::class, 'actionAboutUs'],
        'name' => 'about_us'
    ],
    [
        'route' => '/robots.txt',
        'target' => [RobotsController::class, 'actionIndex'],
        'name' => 'robots'
    ],
//    [
//        'route' => '/test/',
//        'target' => [TestController::class, 'actionTest'],
//        'name' => 'test'
//    ]

];