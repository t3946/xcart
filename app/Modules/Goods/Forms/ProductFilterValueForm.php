<?php

namespace Modules\Goods\Forms;

use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class ProductFilterValueForm extends ModelForm
{
    public function getFields()
    {
        return [
            'fv_name' => [
                'class' => CharField::class,
            ],
            'filter' => [
                'class' => Select2Field::class,
                'choices' => function () {
                    $ar_choices = [];
                    $product = ProductModel::objects()->get(['pk' => $this->admin->ownerPk]);
                    if ($product instanceof ProductModel) {
                        $site_model = $product->sites->limit(1)->get();
                        foreach (FilterModel::objects()->filter(['storefrontid' => $site_model->pk, 'f_active' => 'Y'])->order(['f_name']) as $filter) {
                            $ar_choices[$filter->f_id] = (string)$filter->f_name;
                        }
                    }
                    return $ar_choices;
                },
            ]
        ];
    }
    public function getFieldsets()
    {
        return [[
            'filter',
            'fv_name',
        ]];
    }
    public function getModel()
    {
        return new FilterValueModel();
    }
}