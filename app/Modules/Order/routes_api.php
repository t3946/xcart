<?php

use Modules\Order\Controllers\Api\OrderFraudCheckController;
use Modules\Order\Controllers\OrderProcessController;

return [
    [
        'route' => '/api/checkout/update',
        'target' => [OrderProcessController::class, 'checkoutUpdate'],
        'name' => 'checkout_update_order',
    ],
    [
        'route' => '/api/order/fraud-check/settings/{:order_id}',
        'target' => [OrderFraudCheckController::class, 'getBaseSettings'],
        'name' => 'order_fraud_settings',
    ],
    [
        'route' => '/api/order/fraud-check/unlock/{:order_id}',
        'target' => [OrderFraudCheckController::class, 'unlockOrder'],
        'name' => 'order_fraud_unlock',
    ],
    [
        'route' => '/api/order/fraud-status/update',
        'target' => [OrderFraudCheckController::class, 'saveOrderFraudStatus'],
        'name' => 'order_fraud_status_change'
    ],
    [
        'route' => '/api/order/fraud-check/force-check/{:order_id}',
        'target' => [OrderFraudCheckController::class, 'forceFraudCheck'],
        'name' => 'order_force_check',
    ],
    [
        'route' => '/api/order/fraud-check/change-result',
        'target' => [OrderFraudCheckController::class, 'changeFraudCheckResult'],
        'name' => 'order_change_result',
    ],
    [
        'route' => '/api/order/fraud-check/unlock-all',
        'target' => [OrderFraudCheckController::class, 'unlockOrders'],
        'name' => 'orders_fraud_unlock',
    ],
    [
        'route' => '/api/order/related-info/{:order_id}',
        'target' => [OrderFraudCheckController::class, 'getOrderInformation'],
        'name' => 'related_order_info',
    ]
];