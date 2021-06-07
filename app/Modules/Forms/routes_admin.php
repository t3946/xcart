<?php

use Modules\Forms\Controllers\EmailDashboardAdmin;
use Modules\Forms\Controllers\SnippetController;

return [
    [
        'route' => '/snippets',
        'target' => [SnippetController::class, 'index'],
        'name' => 'snippets'
    ],
    [
        'route' => '/snippet/{i:id}',
        'target' => [SnippetController::class, 'edit'],
        'name' => 'edit'
    ],
    [
        'route' => '/email-dashboard/page/{i:page}',
        'target' => [EmailDashboardAdmin::class, 'index'],
        'name' => 'page'
    ],
    [
        'route' => '/email-dashboard',
        'target' => [EmailDashboardAdmin::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/api',
        'path' => 'Modules.Forms.routes_admin_api',
        'namespace' => 'api'
    ],

];