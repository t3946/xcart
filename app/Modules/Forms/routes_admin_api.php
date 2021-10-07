<?php

use Modules\Forms\Controllers\Api\ApiEmailDashboardAdmin;

return [
    [
        'route' => '/email-list/{i:page}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetEmails'],
        'name' => 'actionGetEmails'
    ],
    [
        'route' => '/email-info/{i:id}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetEmailInfo'],
        'name' => 'actionGetEmailInfo'
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
    [
        'route' => '/mail/remove-label',
        'target' => [ApiEmailDashboardAdmin::class, 'actionRemoveMailLabel'],
        'name' => 'actionRemoveMailLabel'
    ],
    [
        'route' => '/mail/create-label',
        'target' => [ApiEmailDashboardAdmin::class, 'actionCreateMailLabel'],
        'name' => 'actionCreateMailLabel'
    ],
    [
        'route' => '/add-label-email',
        'target' => [ApiEmailDashboardAdmin::class, 'actionAddLabelMail'],
        'name' => 'actionAddLabelMail'
    ],
    [
        'route' => '/email/children/{:id}',
        'target' => [ApiEmailDashboardAdmin::class, 'actionGetEmailChildren'],
        'name' => 'actionGetEmailChildren'
    ],
    [
        'route' => '/email/template/render',
        'target' => [ApiEmailDashboardAdmin::class, 'actionRenderTemplateBody'],
        'name' => 'actionRenderTemplateBody'
    ]
];