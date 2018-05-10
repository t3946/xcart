<?php

//use \Modules\Main\Controllers\ContactFormController;

return [
    '' => [
        'route' => '',
        'target' => ['\Modules\Main\Controllers\DefaultController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/contactus/',
        'target' => ['\Modules\Main\Controllers\ContactFormController', 'actionContactUs'],
        'name' => 'contact_us_form'
    ],
    [
        'route' => '/about-us/',
        'target' => ['\Modules\Main\Controllers\DefaultController', 'actionAboutUs'],
        'name' => 'about_us'
    ],

];