<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductOptionsAdminForm extends ModelForm
{
    public function getFields()
    {
        return [
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionVariantsAdmin::class,
                /*'defaultOrder' => [
                    'class'
                ]*/
            ],
        ];
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }
}