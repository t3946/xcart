<?php

namespace Modules\Order\Routes;

use Modules\Order\Controllers\Api\DecisionController;

return [
    [
        'route' => '/create',
        'target' => [ DecisionController::class, 'createEstimatedTimeArrivalDecisionAction' ],
        'name' => 'decisions_create',
    ],

    [
        'route' => '/make',
        'target' => [ DecisionController::class, 'makeDecisionsAction' ],
        'name' => 'decisions_make',
    ],

    [
        'route' => '/get',
        'target' => [ DecisionController::class, 'getDecisionsAction' ],
        'name' => 'decisions_get',
    ],

    [
        'route' => '/get-eta-products/{*:order_id}',
        'target' => [ DecisionController::class, 'getEtaProductsAction' ],
        'name' => 'get-eta-products',
    ]
];
