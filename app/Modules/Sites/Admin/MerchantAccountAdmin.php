<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\MerchantAccountForm;
use Modules\Sites\Models\MerchantAccountModel;

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
        return new MerchantAccountForm;
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