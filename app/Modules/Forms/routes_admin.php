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
        'route' => '/email-dashboard',
        'target' => [EmailDashboardAdmin::class, 'index'],
        'name' => 'index'
    ],

];