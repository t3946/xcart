<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\BankAccountsForm;
use Modules\Sites\Forms\Corporates\MerchantAccountsForm;
use Modules\Sites\Forms\Corporates\ShareholdersForm;
use Modules\Sites\Models\BankAccountModel;
use Modules\Sites\Models\MerchantAccountModel;
use Modules\Sites\Models\ShareHolderModel;

class MerchantAccountAdmin extends ListViewAdmin
{
    public $ownerField = 'corporate';

    public function getListColumns()
    {
        return ['issuer', 'number'];
    }

    /*public function getAvailableListColumns()
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
    }*/

    public function getForm()
    {
        return new MerchantAccountsForm;
    }

    public function getModel()
    {
        return new MerchantAccountModel;
    }

    public static function getName()
    {
        return 'Merchant Accounts';
    }
}