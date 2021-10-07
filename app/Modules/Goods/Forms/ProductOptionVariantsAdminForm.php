<?php

namespace Modules\Goods\Forms;


use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\QueryBuilder;
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
    public array $exclude = ['product_option'];
    public function getFields()
    {
        $variantChoices = [];
        if (($p_option = $this->getInstance()->getField('product_option')->getValue())
            && $po_model = ProductOptionModel::objects()->get(['pk' => $p_option])) {

            foreach (OptionVariantModel::objects()->filter(['option_id' => $po_model->option_id])->order(['name']) as $variant) {
                $variantChoices[$variant->id] = (string) $variant->name;
            }
       }

       if (!$variantChoices) {
           $variantChoices = ['No variants avail'];
       }

        return [
            'variant' => [
                'class' => Select2Field::class,
                'html' => ['style' => 'width: 100%'],
                'choices' => $variantChoices
            ]
        ];
    }

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
}