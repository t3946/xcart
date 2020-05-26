<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\NestedAdmin;
use Modules\Forms\Forms\SnippetsForm;
use Modules\Forms\Models\SnippetModel;
use Modules\Pages\Forms\PagesForm;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;
use Xcart\App\Orm\Model;


class SnippetsAdmin extends Admin
{
//    public $linkColumn = 'name';

    public function getListColumns()
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
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
            'code' => [
                'title' => 'Code',
                'template' => $this->columnDefaultTemplate,
                'order' => 'code'
            ],
            'name' => [
                'title' => 'Name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
            'description' => [
                'title' => 'Description',
                'template' => $this->columnDefaultTemplate,
                'order' => 'code'
            ],
        ];
    }

    public static function getItemName()
    {
        return 'Snippet';
    }

    public function getForm()
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

}

