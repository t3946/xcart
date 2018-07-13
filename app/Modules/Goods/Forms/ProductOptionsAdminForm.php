<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductOptionsAdminForm extends ModelForm
{
    public $exclude = [
        'product'
    ];
    public function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'required' => true,
            ],
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