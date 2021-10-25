<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\TaxReturnForm;
use Modules\Sites\Models\TaxReturnModel;

class TaxReturnAdmin extends ListViewAdmin
{
    public static bool $public = false;
    public ?string $ownerField = 'corporate';

    public function getUserColumns()
    {
        return [];
    }

    public function getListColumns(): array
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

    public function getForm(): TaxReturnForm
    {
        return new TaxReturnForm;
    }

    public function getModel()
    {
        return new TaxReturnModel;
    }

    public function getAllUrl()
    {
        $admin = new CorporatesAdmin;
        $admin->section = 'tax_returns_outstanding';
        if ($this->ownerPk->id) {
            return $admin->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return $admin->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }
}