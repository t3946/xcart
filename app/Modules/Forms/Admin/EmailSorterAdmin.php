<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Forms\Forms\EmailSorterForm;
use Modules\Forms\Models\EmailSorterModel;
use Xcart\App\Orm\Model;


class EmailSorterAdmin extends Admin
{

    public function getListColumns()
    {
        return ['filter_field', 'condition', 'value', 'entity'];
    }

    public function getSearchColumns()
    {
        return [];
    }


    public function getAvailableListColumns()
    {
        return [
            'entity' => [
                'title' => 'entity',
                'template' => $this->columnDefaultTemplate,
            ],
            'filter_field' => [
                'title' => 'filter_field',
                'template' => $this->columnDefaultTemplate,
            ],
            'condition' => [
                'title' => 'contains',
                'template' => $this->columnDefaultTemplate,
            ],
            'value' => [
                'title' => 'value',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
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

    public function getItemProperty(Model $item, $property)
    {
        return parent::getItemProperty($item, $property);
    }

    public function getListItemActions()
    {
        return [
            'edit',
            'delete',
        ];
    }
    public function getListGroupActions()
    {
        return [];
    }

}

