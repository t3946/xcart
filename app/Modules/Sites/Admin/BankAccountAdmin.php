<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\BankAccountForm;
use Modules\Sites\Forms\Corporates\BankAccountsForm;
use Modules\Sites\Models\BankAccountModel;

class BankAccountAdmin extends ListViewAdmin
{
    public $ownerField = 'corporate';

    public function getListColumns()
    {
        return ['bank_name', 'account_number'];
    }

    public function getAvailableListColumns()
    {
        return [
            'bank_name' => [
                'title' => 'Bank name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'bank_name'
            ],
            'account_number' => [
                'title' => 'Account number',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm()
    {
        return new BankAccountForm;
    }

    public function getModel()
    {
        return new BankAccountModel;
    }

    public static function getName()
    {
        return 'Bank accounts';
    }
}