<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Forms\Forms\SnippetsForm;
use Modules\Forms\Models\SnippetModel;


class SnippetsAdmin extends Admin
{

    public function getListColumns(): array
    {
        return ['id', 'code', 'name', 'description'];
    }

    public function getSearchColumns()
    {
        return ['name'];
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'order' => 'id'
            ],
            'code' => [
                'title' => 'Code',
                'order' => 'code'
            ],
            'name' => [
                'title' => 'Name',
                'order' => 'name',
                'class' => 'nowrap'
            ],
        ];
    }

    public static function getItemName()
    {
        return 'Snippet';
    }

    public function getForm(): SnippetsForm
    {
        return new SnippetsForm();
    }

    public function getModel()
    {
        return new SnippetModel();
    }

    public static function getName()
    {
        return 'Snippets';
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function getListItemActions()
    {
        return [
            'update',
        ];
    }
}

