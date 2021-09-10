<?php

use Modules\PBX\Controllers\PBXController;

return [
        [
            'route' => '/call/',
            'target' => [PBXController::class, 'actionCallback'],
            'name' => 'pbx_call'
        ]
    ];