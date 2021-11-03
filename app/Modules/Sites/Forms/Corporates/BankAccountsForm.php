<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\BankAccountAdmin;
use Xcart\App\Form\Fields\ListViewField;

class BankAccountsForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets() : array
    {
        return [
            ['bank_accounts']
        ];
    }

    public function getName() : string
    {
        return 'Bank accounts';
    }

    public function getFields() : array
    {
        return [
            'bank_accounts' => [
                'class' => ListViewField::class,
                'adminClass' => BankAccountAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Bank accounts'
            ]
        ];
    }
}