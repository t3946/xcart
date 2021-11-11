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
        'route' => '/get',
        'target' => [ DecisionController::class, 'getDecisionsAction' ],
        'name' => 'decisions_get',
    ],
];
