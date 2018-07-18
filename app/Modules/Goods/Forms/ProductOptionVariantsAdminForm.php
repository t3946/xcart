<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\OptionVariantModel;
use Modules\Goods\Models\ProductOptionModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\CharField;

class ProductOptionVariantsAdminForm extends ModelForm
{
    public function getFields()
    {
        $choices = [];
        if (($p_option = $this->getInstance()->getField('product_option')->getValue()) && $po_model = ProductOptionModel::objects()->get(['pk' => $p_option])) {
           foreach ($vars = $po_model->option->variants->order(['position', 'name'])->all() as $var) {
               $choices[$var->id] = (string) $var;
           }
       }
        return [
            'product_option' => [
                'class' => DropDownField::class,
                //'choices' => function() {return [OptionNewModel::objects()->get(['id' => $this->getInstance()->getField('option')->getValue()])];},
                'html' => [
                //    'disabled' => 'disabled',
                ],
            ],
            'variant' => [
                'class' => DropDownField::class,
                'choices' => $choices
            ]
        ];
    }

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
}