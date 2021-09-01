<?php

return [
        [
            'route' => '/call/',
            'target' => ['\Modules\PBX\Controllers\PBXController', 'actionCallback'],
            'name' => 'pbx_call'
        ]
    ];