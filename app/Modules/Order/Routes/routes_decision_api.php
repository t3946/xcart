<?php

namespace Modules\Order\Routes;

use Modules\Order\Controllers\Api\DecisionController;

return [
    [
        'route' => '/create',
        'target' => [ DecisionController::class, 'createDecision' ],
        'name' => 'decisions_create',
    ],

    [
        'route' => '/solve',
        'target' => [ DecisionController::class, 'solve' ],
        'name' => 'decisions_make',
    ],

    [
        'route' => '/make-license',
        'target' => [ DecisionController::class, 'makeLicenseDecisionsAction' ],
        'name' => 'make-license',
    ],

    [
        'route' => '/solve-sup',
        'target' => [ DecisionController::class, 'solveSUP' ],
        'name' => 'solve-sup',
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
    ],
];
