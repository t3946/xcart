<?php

use Modules\PBX\Controllers\Admin\PBXController;

return [
    [
        'route' => '/pbxcalls',
        'target' => [PBXController::class, 'index'],
        'name' => 'view'
    ],
    [
        'route' => '/listen',
        'target' => [PBXController::class, 'listen'],
        'name' => 'listen'
    ],
];