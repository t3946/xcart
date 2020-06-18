<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Forms\Forms\EmailSorterForm;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailSorterModel;
use Xcart\App\Orm\Model;


class EmailSorterAdmin extends Admin
{

    public function getListColumns()
    {
        return ['type', 'filter_field', 'cond', 'value', 'entity', 'related_value'];
    }

    public function getSearchColumns()
    {
        return [];
    }


    public function getAvailableListColumns()
    {
        return [
            'type' => [
                'title' => 'Type',
                'template' => $this->columnDefaultTemplate,
            ],
            'filter_field' => [
                'title' => 'Field',
                'template' => $this->columnDefaultTemplate,
            ],
            'cond' => [
                'title' => 'Condition',
                'template' => $this->columnDefaultTemplate,
            ],
            'value' => [
                'title' => 'Value',
                'template' => $this->columnDefaultTemplate,
            ],
            'entity' => [
                'title' => 'Entity',
                'template' => $this->columnDefaultTemplate,
            ],
            'related_value' => [
                'title' => 'Related field',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'entity') {
            return $item->getField($property)->toText();
        }
        if ($property === 'cond') {
            return $item->getField($property)->toText();
        }
        if ($property === 'filter_field') {
            $email = new EmailModel;
            return $email->getField($item->getField($property)->getValue())->getVerboseName();
        }

        return parent::getItemProperty($item, $property);
    }

    public function getForm()
    {
        return new EmailSorterForm();
    }

    public function getModel()
    {
        return new EmailSorterModel();
    }

    public static function getName()
    {
        return 'Automatic Email Sorter';
    }

    public function getListGroupActions()
    {
        return [];
    }

}

