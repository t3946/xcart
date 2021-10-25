<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\MerchantAccountForm;
use Modules\Sites\Models\MerchantAccountModel;

class MerchantAccountAdmin extends ListViewAdmin
{
    public static bool $public = false;
    public ?string $ownerField = 'corporate';

    public function getListColumns() : array
    {
        return ['issuer', 'number'];
    }

    public function getAvailableListColumns(): array
    {
        return [
            'issuer' => [
                'title' => 'Merchant account issuer',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
            'number' => [
                'title' => 'Merchant account #',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm() : MerchantAccountForm
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

    public function getAllUrl()
    {
        $admin = new CorporatesAdmin;
        $admin->section = 'merchant_accounts';
        if ($this->ownerPk->id) {
            return $admin->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return $admin->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }
}