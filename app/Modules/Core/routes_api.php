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
		'route' => 'fraud/get/all',
		'target' => [FraudCheckController::class, 'getAll'],
		'name' => 'get_fraud_all'
	],
    [
        'route' => 'fraud/update/weight',
        'target' => [FraudCheckController::class, 'updateWeight'],
        'name' => 'update_fraud_weight'
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
];