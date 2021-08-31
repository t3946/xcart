<?php

use Modules\Core\Controllers\Api\FraudCheckController;
use Modules\Core\Controllers\Api\GeneralSettingsController;
use Modules\Order\Controllers\Api\OrderFraudCheckController;

return [
	[
		'route' => 'config/get/all',
		'target' => [GeneralSettingsController::class, 'getAllConfig'],
		'name' => 'get_config'
	],
	[
		'route' => 'fraud/get/full_name',
		'target' => [FraudCheckController::class, 'getFraudFullName'],
		'name' => 'get_fraud_full_name'
	],
	[
		'route' => 'fraud/get/address',
		'target' => [FraudCheckController::class, 'getFraudAddress'],
		'name' => 'get_fraud_address'
	],
	[
		'route' => 'question/fa/get/all',
		'target' => [FraudCheckController::class, 'getAllFAQuestions'],
		'name' => 'get_fa_questions'
	],
    [
        'route' => 'fraud-check/settings/all',
        'target' => [FraudCheckController::class, 'getFraudCheckSettings'],
        'name' => 'get_fraud_check_settings'
    ],
    [
        'route' => 'question/base/get/all',
        'target' => [FraudCheckController::class, 'getAllBaseQuestions'],
        'name' => 'get_base_questions'
    ],
    [
        'route' => 'fraud/settings/get',
        'target' => [FraudCheckController::class, 'getBaseSettings'],
        'name' => 'get_fraud_settings'
    ],
    [
        'route' => 'fraud/settings/save',
        'target' => [FraudCheckController::class, 'updateFraudSettings'],
        'name' => 'update_fraud_settings'
    ],
    [
        'route' => 'question/fa/update',
        'target' => [FraudCheckController::class, 'updateFAQuestion'],
        'name' => 'update_fa_question'
    ]
];