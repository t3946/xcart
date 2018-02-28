<?php
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
            'callback' => ['\\Modules\\Order\\Helpers\\OrderEventHelper', 'triggerOrderCreateEvent'],
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