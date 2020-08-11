<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\BankAccountsForm;
use Modules\Sites\Forms\Corporates\ShareholdersForm;
use Modules\Sites\Models\BankAccountModel;
use Modules\Sites\Models\ShareHolderModel;

class ShareHolderAdmin extends ListViewAdmin
{
    public $ownerField = 'corporate';

    public function getListColumns()
    {
        return ['name', 'shares', 'percent'];
    }

    public function getAvailableListColumns()
    {
        return [
            'name' => [
                'title' => 'Company/Person name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
            'percent' => [
                'title' => 'Percentage',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm()
    {
        return new ShareholdersForm;
    }

    public function getModel()
    {
        return new ShareHolderModel;
    }

    public static function getName()
    {
        return 'Shareholders';
    }
}