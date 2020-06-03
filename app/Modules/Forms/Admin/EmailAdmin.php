<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\NestedAdmin;
use Modules\Forms\Forms\EmailForm;
use Modules\Forms\Forms\SnippetsForm;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\SnippetModel;
use Modules\Pages\Forms\PagesForm;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;
use Xcart\App\Orm\Model;


class EmailAdmin extends Admin
{

    public function getListColumns()
    {
        return ['id', 'subject', 'body', 'snippet', 'type'];
    }

    public function getSearchColumns()
    {
        return ['subject', 'body'];
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
            'subject' => [
                'title' => 'Subject',
                'template' => $this->columnDefaultTemplate,
                'order' => 'subject'
            ],
            'body' => [
                'title' => 'Body',
                'template' => $this->columnDefaultTemplate,
                'order' => 'body'
            ],
            'snippet' => [
                'title' => 'Sippet',
                'template' => $this->columnDefaultTemplate,
                'order' => 'snippet'
            ],
        ];
    }

    public static function getItemName()
    {
        return 'Email';
    }

    public function getForm()
    {
        return new EmailForm();
    }

    public function getModel()
    {
        return new EmailModel();
    }

    public static function getName()
    {
        return 'Emails';
    }

}

