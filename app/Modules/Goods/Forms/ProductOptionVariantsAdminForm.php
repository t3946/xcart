<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductOptionModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductOptionVariantsAdminForm extends ModelForm
{
    public function getFields()
    {
        return [
            'option' => [
                'class' => DropDownField::className(),
                'choices' => function() {return [OptionNewModel::objects()->get(['id' => $this->getInstance()->getField('option')->getValue()])];},
                'html' => [
                    'disabled' => 'disabled',
                ],
            ],
            'variant' => [
                'class' => DropDownField::className(),
            ]
        ];
    }

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
}