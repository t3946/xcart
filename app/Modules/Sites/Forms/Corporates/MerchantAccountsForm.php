<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\MerchantAccountAdmin;
use Xcart\App\Form\Fields\ListViewField;

class MerchantAccountsForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets() : array
    {
        return [[
            'merchant_accounts',
        ]];
    }

    public function getFields() : array
    {
        return [
            'merchant_accounts' => [
                'class' => ListViewField::class,
                'adminClass' => MerchantAccountAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Merchant accounts'
            ]
        ];
    }

    public function getName(): string
    {
        return 'Merchant accounts';
    }
}