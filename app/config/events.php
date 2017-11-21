<?php
return [
    'anveo:call' => [
        [
            'callback' => ['\\Modules\\PBX\\Helpers\\AnveoAssignCalls', 'eventBindCallToOrder']
        ]
    ],
    'order:changed' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderEventsModel', 'newOrderEvent'],
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