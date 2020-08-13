<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\TaxReturnForm;
use Modules\Sites\Models\TaxReturnModel;

class TaxReturnAdmin extends ListViewAdmin
{
    public $ownerField = 'corporate';

    public function getUserColumns()
    {
        return [];
    }

    public function getListColumns()
    {
        return ['from_date', 'to_date', 'status'];
    }

    public function getAvailableListColumns()
    {
        return [
            'from_date' => [
                'title' => 'From',
                'template' => $this->columnDefaultTemplate,
                'order' => 'from'
            ],
            'to_date' => [
                'title' => 'To',
                'template' => $this->columnDefaultTemplate,
            ],
            'status' => [
                'title' => 'Status',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm()
    {
        return new TaxReturnForm;
    }

    public function getModel()
    {
        return new TaxReturnModel;
    }
}