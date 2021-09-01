<?php

use Modules\PBX\Helpers\AnveoAssignCalls;
use Modules\Order\Models\OrderUserActivityModel;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Payment\Helpers\PaymentEventHelper;
use Modules\Order\Models\OrderEventsModel;
use Modules\Order\Helpers\OrderEventHelper;

return [
    'anveo:call' => [
        [
            'callback' => [AnveoAssignCalls::class, 'eventBindCallToOrder']
        ]
    ],
    'order:status.changed' => [
        [
            'callback' => [OrderEventsModel::class, 'newOrderEvent'],
        ],
    ],

    'order:created' => [
        [
            'callback' => [OrderEventHelper::class, 'triggerOrderCreateEvent'],
        ],
    ],

    'order:paid' => [
        [
            'callback' => [OrderEventHelper::class, 'triggerOrderPaidEvent'],
        ],
    ],

    'order:shipped' => [
        [
            'callback' => [OrderEventHelper::class, 'triggerOrderShippedEvent'],
        ],
    ],

    'payment:authorize' => [
        [
            'callback' => [PaymentEventHelper::class, 'triggerPaymentAuthorizeEvent'],
        ],
    ],

    'order:tag' => [
        [
            'callback' => [OrderTagEventHelper::class, 'triggerOrderTagEvent'],
        ],
    ],
    'order:view' => [
        [
            'callback' => [OrderUserActivityModel::class, 'userView'],
        ]
    ],
    'app:end' => []
];