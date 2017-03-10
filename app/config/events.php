<?php
return [
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