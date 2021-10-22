<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\BankAccountForm;
use Modules\Sites\Forms\Corporates\BankAccountsForm;
use Modules\Sites\Models\BankAccountModel;

class BankAccountAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'corporate';
    public static bool $public = false;

    public function getListColumns(): array
    {
        return ['bank_name', 'account_type', 'account_number'];
    }

    public function getAvailableListColumns(): array
    {
        return [
            'bank_name' => [
                'title' => 'Bank name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'bank_name'
            ],
            'account_type' => [
                'title' => 'Account type',
                'template' => $this->columnDefaultTemplate,
            ],
            'account_number' => [
                'title' => 'Account number',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm(): BankAccountForm
    {
        return new BankAccountForm;
    }

    public function getModel(): BankAccountModel
    {
        return new BankAccountModel;
    }

    public static function getName(): string
    {
        return 'Bank accounts';
    }

    public function getAllUrl(): string
    {
        $admin = new CorporatesAdmin;
        $admin->section = 'bank_accounts';
        if ($this->ownerPk->id) {
            return $admin->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return $admin->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }
}