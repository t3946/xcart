<?php
return [
    'order:changed' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderEventsModel', 'newOrderEvent'],
        ],
    ],
    'order:view' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderUserActivityModel', 'userView'],
        ]
    ],
    'app:end' => []
];