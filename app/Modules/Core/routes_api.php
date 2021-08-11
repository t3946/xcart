<?php

use Modules\Core\Controllers\Api\FraudCheckController;
use Modules\Core\Controllers\Api\GeneralSettingsController;

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
		'route' => 'fraud/get/all',
		'target' => [FraudCheckController::class, 'getAll'],
		'name' => 'get_fraud_all'
	],
    [
        'route' => 'fraud/update/weight',
        'target' => [FraudCheckController::class, 'updateWeight'],
        'name' => 'update_fraud_weight'
    ]
];