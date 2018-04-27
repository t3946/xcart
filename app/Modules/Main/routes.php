<?php

use Modules\Main\Controllers\ContactFormController;

return [
    '' => [
        'route' => '',
        'target' => ['\Modules\Main\Controllers\DefaultController', 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/contactus/',
        'target' => [ContactFormController::class, 'actionContactUs'],
        'name' => 'contact_us_form'
    ],

];