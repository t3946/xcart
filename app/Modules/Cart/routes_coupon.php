<?php
return [
    [
        'route' => '/{:code}',
        'target' => ['\Modules\Cart\Controllers\CouponController', 'actionView'],
        'name' => 'view'
    ],
];