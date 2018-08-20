<?php

namespace Modules\Goods\Forms;


use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\OptionVariantModel;
use Modules\Goods\Models\ProductOptionModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\CharField;
use Xcart\Connection;

class ProductOptionVariantsAdminForm extends ModelForm
{
    public function getFields()
    {
        $variantChoices = [];
        if (($p_option = $this->getInstance()->getField('product_option')->getValue())
            && $po_model = ProductOptionModel::objects()->get(['pk' => $p_option])) {

            foreach ($vars = OptionVariantModel::objects()->getQuerySet()
                ->join('left join', 'xcart_options', ['option_id' => 'oo.id'], 'oo')
                ->join('left join', 'xcart_product_options', ['po.option_id' => 'oo.id'], 'po')
                ->join('left join', 'xcart_product_option_variants', ['po.id' => 'pv.product_option_id', 'id' => 'pv.variant_id'], 'pv')
                ->filter(['po.id' => $p_option, 'pv.id__isnull' => true])->all() as $var) {
               $variantChoices[$var->id] = (string) $var;
           }
       }

       if (!$variantChoices) {
           $variantChoices = ['No variants avail'];
       }

        return [
            'product_option' => [
                'class' => DropDownField::class,
                //'choices' => function() {return [OptionNewModel::objects()->get(['id' => $this->getInstance()->getField('option')->getValue()])];},
                'html' => [
                //    'disabled' => 'disabled',
                ],
                'label' => 'Option'
            ],
            'variant' => [
                'class' => DropDownField::class,
                'choices' => $variantChoices
            ]
        ];
    }

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
}