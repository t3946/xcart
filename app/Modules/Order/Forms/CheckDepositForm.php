<?php


namespace Modules\Order\Forms;


use Modules\Order\Admin\CheckDepositOrderAdmin;
use Modules\Order\Models\CheckDepositedModel;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class CheckDepositForm extends ModelForm
{
    public $exclude = ['date'];

    public function getModel()
    {
        return new CheckDepositedModel;
    }

    public function getFields()
    {
        $check = $this->getInstance();
        return [
            'check_date' => [
                'class' => DateField::class,
                'required' => true,
                'fieldTemplate' => 'deposited/form/_field.tpl',
                'html' => array_merge(['size' => 9], array_filter(['disabled' => 'disabled'],
                        static fn($a) => $check->status === CheckDepositedModel::STATUS_DEPOSITED))
            ],
            'currency' => [
                'class' => DropDownField::class,
                'fieldTemplate' => 'deposited/form/_field.tpl',
                'choices' => fn() => array_map(static fn($c) => $c->currency_code, CurrencyModel::objects()->all()),
                'html' => array_filter(['disabled' => 'disabled'],
                    static fn($a) => $check->currency_locked || $check->status === CheckDepositedModel::STATUS_DEPOSITED)
            ],
            'orders' => [
                'class' => ListViewField::class,
                'adminClass' => CheckDepositOrderAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => ' '
            ]
        ];
    }

    public function getName()
    {
        return 'Deposit';
    }
}