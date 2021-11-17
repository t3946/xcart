<?php

use Modules\Account\Controllers\Api\AccountListsApi;

return [
    [
        'route' => '/get-lists',
        'target' => [AccountListsApi::class, 'actionGetLists'],
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
    [
        'route' => '/move-product',
        'target' => [AccountListsApi::class, 'moveProduct'],
        'name' => 'move-product'
    ],
    [
        'route' => '/get-url-encrypt',
        'target' => [AccountListsApi::class, 'getUrlEncrypt'],
        'name' => 'encrypt-url'
    ],
    [
        'route' => '/accept-invite',
        'target' => [AccountListsApi::class, 'acceptInvitation'],
        'name' => 'accept-invite'
    ],
    [
        'route' => '/edit-user-rights',
        'target' => [AccountListsApi::class, 'editUsersInList'],
        'name' => 'edit-rights'
    ],
    [
        'route' => '/add-product-on-list',
        'target' => [AccountListsApi::class, 'addProductOnList'],
        'name' => 'add-product'
    ],
    [
        'route' => '/edit-name-in-idea',
        'target' => [AccountListsApi::class, 'editIdeaName'],
        'name' => 'edit-idea-name'
    ],
    [
        'route' => '/edit-comment',
        'target' => [AccountListsApi::class, 'editComment'],
        'name' => 'edit-comment'
    ],
    [
        'route' => '/manage-list',
        'target' => [AccountListsApi::class, 'manageList'],
        'name' => 'manage-list'
    ],
    [
        'route' => '/delete-product',
        'target' => [AccountListsApi::class, 'deleteProduct'],
        'name' => 'delete-products'
    ],
    [
        'route' => '/undo-delete-product',
        'target' => [AccountListsApi::class, 'undoDeleteProduct'],
        'name' => 'undo-delete-products'
    ],

];