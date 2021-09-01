<?php

use Modules\Account\Controllers\Api\AccountListsApi;

return [
    [
        'route' => '/get-lists',
        'target' => [AccountListsApi::class, 'getLists'],
        'name' => 'get-lists'
    ],
    [
        'route' => '/create-lists',
        'target' => [AccountListsApi::class, 'createList'],
        'name' => 'create-lists'
    ],
    [
        'route' => '/reorder-products',
        'target' => [AccountListsApi::class, 'reorderProducts'],
        'name' => 'reorder-list'
    ],
    [
        'route' => '/delete-list',
        'target' => [AccountListsApi::class, 'deleteList'],
        'name' => 'delete-list'
    ],
];