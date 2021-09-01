<?php

use Modules\Help\Controllers\HelpController;

return [
    [
        'route' => '/api',
        'path' => 'Modules.Help.routes_api',
        'namespace' => 'api'
    ],
    [
        'route' => '/*',
        'target' => [HelpController::class, 'actionIndex'],
        'name' => 'actionIndex'
    ],
];