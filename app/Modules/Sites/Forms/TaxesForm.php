<?php


namespace Modules\Sites\Forms;


use Modules\Goods\Admin\OptionVariantsAdmin;
use Modules\Sites\Admin\TaxRatesAdmin;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class TaxesForm extends ModelForm
{
    public array $exclude = ['position'];

    public function getModel()
    {
        return new TaxModel;
    }

    public function getName()
    {
        return 'Tax details';
    }

    public function getFields()
    {
        return [...parent::getFields(),
            'rates' => [
                'class' => ListViewField::class,
                'adminClass' => TaxRatesAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl'
            ]];
    }
}