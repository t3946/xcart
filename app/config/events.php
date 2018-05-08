<?php

use Modules\Order\Helpers\OrderEventHelper;

return [
    'anveo:call' => [
        [
            'callback' => ['\\Modules\\PBX\\Helpers\\AnveoAssignCalls', 'eventBindCallToOrder']
        ]
    ],
    'order:status.changed' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderEventsModel', 'newOrderEvent'],
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

    'payment:authorize' => [
        [
            'callback' => ['\\Modules\\Payment\\Helpers\\PaymentEventHelper', 'triggerPaymentAuthorizeEvent'],
        ],
    ],

    'order:tag' => [
        [
            'callback' => ['\\Modules\\Order\\Helpers\\OrderTagEventHelper', 'triggerOrderTagEvent'],
        ],
    ],
    'order:view' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderUserActivityModel', 'userView'],
        ]
    ],
    'app:end' => []
];