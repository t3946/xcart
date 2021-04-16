<?php

use Modules\Help\Controllers\HelpController;

return [
    [
        'route' => '/*',
        'target' => [HelpController::class, 'actionIndex'],
        'name' => 'actionIndex'
    ],
];