<?php

use Modules\Forms\Controllers\Api\ApiEmailDashboardAdmin;

return [
    [
        'route' => '/email-list/{i:page}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetEmails'],
        'name' => 'actionGetEmails'
    ],
    [
        'route' => '/edit-favorite',
        'target' => [ApiEmailDashboardAdmin::class, 'editFavorite'],
        'name' => 'editFavorite'
    ],
    [
        'route' => '/edit-action',
        'target' => [ApiEmailDashboardAdmin::class, 'editAction'],
        'name' => 'editAction'
    ],
];