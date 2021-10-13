<?php

use Modules\Editor\Controllers\EditorController;

return [
    [
        'route' => '/index',
        'target' => [EditorController::class, 'index'],
        'name' => 'index'
    ],
    [
        'route' => '/upload',
        'target' => [EditorController::class, 'upload'],
        'name' => 'upload'
    ],
    [
        'route' => '/changed',
        'target' => [EditorController::class, 'changed'],
        'name' => 'changed'
    ],
    [
        'route' => '/api',
        'target' => [EditorController::class, 'api'],
        'name' => 'api'
    ],
];