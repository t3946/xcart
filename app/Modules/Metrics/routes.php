<?php

use Modules\Metrics\Controllers\MetricsController;

return [
    [
        'route' => '/oauth2callback',
        'target' => [MetricsController::class, 'generateRefresh'],
        'name' => 'google_refresh'
    ],
];