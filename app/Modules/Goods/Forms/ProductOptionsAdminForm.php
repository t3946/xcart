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
    public array $exclude = ['product'];

    public function getFields()
    {
        $field_list = [

            'option' => [
                'class' => DropDownField::class,
                'html' => [
                    'disabled' => 'disabled',
                ],
            ],
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionVariantsAdmin::class,
                'defaultOrder' => 'position'
            ],
        ];
        if ($this->getInstance()->getIsNewRecord()) {
            unset($field_list['option']['html']['disabled']);
        }
        return $field_list;
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }
}