<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class ProductOptionsAdminForm extends ModelForm
{
    public $exclude = ['product'];

    public function getFields()
    {
        return  [

            'option' => [
                'class' => DropDownField::class,
//                'ajaxUrl' => (new ProductOptionsAdmin)->getSuggestionUrl('option'),
                'html' => [
//                    'disabled' => 'disabled',
                ],
            ],
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionVariantsAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'defaultOrder' => 'position'
            ],
        ];
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }
}