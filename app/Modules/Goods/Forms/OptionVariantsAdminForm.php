<?php

namespace Modules\Goods\Forms;


use Modules\Core\Fields\ColorPickerField;
use Modules\Goods\Models\OptionVariantModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class OptionVariantsAdminForm extends ModelForm
{
    public $exclude = ['option'];

    public function getModel()
    {
        return new OptionVariantModel();
    }

    public function getFields()
    {
        //$value_field =
        switch ($this->getInstance()->option->type) {
            case 'color' :
                $v_arr = ['value' => [
                    'class' => ColorPickerField::class,
                    'required' => true,
                ]];
                break;
            default :
                $v_arr = [];
        }

        return array_merge([
            'name' => [
                'class' => CharField::class,
                'required' => true,
            ],
        ],$v_arr);
    }
}