<?php

use Modules\Order\Controllers\RetrieveOrderController;

return [
    [
        'route' => '/inv/',
        'target' => [RetrieveOrderController::class, 'retrieveOrder'],
        'name' => 'retrieve_order'
    ],
];