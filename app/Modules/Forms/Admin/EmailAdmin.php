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
    public $infoTemplate = '/admin/email_info.tpl';

    public function getListColumns()
    {
        return ['date', 'from_address', 'subject'];
    }

    public function getSearchColumns()
    {
        return ['subject', 'snippet', 'from_address'];
    }

    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                $this->sort
            ]);
        } else {
            $qs->order([
                '-date'
            ]);
        }
        return $qs;
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
            ],
            'from_address' => [
                'title' => 'From',
                'template' => $this->columnDefaultTemplate,
            ],
            'subject' => [
                'title' => 'Subject',
                'template' => $this->columnDefaultTemplate,
            ],
            'body' => [
                'title' => 'Body',
                'template' => $this->columnDefaultTemplate,
            ],
            'snippet' => [
                'title' => 'Sippet',
                'template' => $this->columnDefaultTemplate,
            ],
            'type' => [
                'title' => 'Type',
                'template' => $this->columnDefaultTemplate,
            ],
            'date' => [
                'title' => 'Date',
                'template' => $this->columnDefaultTemplate,
                'order' => 'date'
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
        return 'Inbox/Sorting dashboard';
    }

    public function getItemProperty(Model $item, $property)
    {
        /** @var EmailModel $item */
        if ($property === 'from_address') {
            return $item->getFrom();
        }
        if ($property === 'to_address') {
            return $item->getTo();
        }
        if ($property === 'subject') {
            return $item->getSubject();
        }
        return parent::getItemProperty($item, $property);
    }

    public function getListItemActions()
    {
        return [
            'info',
        ];
    }
    public function getListGroupActions()
    {
        return [];
    }

    public function info($pk)
    {
        $object = $this->getModelOr404($pk);

        $this->setBreadcrumbs('Information');
        $this->renderInternal($this->infoTemplate, [
            'object' => $object,
            'fields' => $this->getForm()->getFields(),
        ]);
    }

}

