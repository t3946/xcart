<?php

use Modules\Forms\Controllers\Api\ApiEmailDashboardAdmin;

return [
    [
        'route' => '/email-list/{i:page}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetEmails'],
        'name' => 'actionGetEmails'
    ],
    [
        'route' => '/email-list-search/{i:page}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionSearchEmails'],
        'name' => 'actionSearchEmails'
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
    [
        'route' => '/set-viewed',
        'target' => [ApiEmailDashboardAdmin::class, 'setViewed'],
        'name' => 'setViewed'
    ],
    [
        'route' => '/get-templates',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetTemplates'],
        'name' => 'actionGetTemplates'
    ],
    [
        'route' => '/send-email',
        'target' => [ApiEmailDashboardAdmin::class, 'actionSendEmail'],
        'name' => 'actionSendEmail'
    ],
];