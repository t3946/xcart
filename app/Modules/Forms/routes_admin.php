<?php

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

];