<?php

use Modules\Help\Controllers\Api\ApiHelpController;

return [
    [
        'route' => '/item-list',
        'target' => [ApiHelpController::class, 'actionGetHelpItems'],
        'name' => 'actionGetHelpItems'
    ],
];