<?php

use Modules\Sites\Controllers\SitesController;

return [
    [
        'route' => '/set-site/{i:site_id}',
        'target' => [SitesController::class, 'setSite'],
        'name' => 'setSite',
    ],
];
