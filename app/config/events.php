<?php
return [
    'order:change' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderEventsModel', 'newOrderEvent'],
            'sender' => '\\Modules\\Order\\Models\\Order', //Class name or null
            'priority' => 0 // 1-3 or not determine
        ]
    ],
    'order:view' => [
        [
            'callback' => ['\\Modules\\Order\\Models\\OrderUserRecentlyActiveModel', 'userView'],
        ]
    ]
];