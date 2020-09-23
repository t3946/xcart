<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\MerchantAccountAdmin;
use Xcart\App\Form\Fields\ListViewField;

class MerchantAccountsForm extends CorporatesForm
{
    public $exclude = ['storefronts', 'taxes'];

    public function getFieldsets()
    {
        return [[
            'merchant_accounts',
        ]];
    }

    public function getFields()
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

    public function getName()
    {
        return 'Merchant accounts';
    }
}