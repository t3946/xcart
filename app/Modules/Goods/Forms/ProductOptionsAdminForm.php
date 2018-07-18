<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductOptionsAdminForm extends ModelForm
{
    public $exclude = ['product'];

    public function getFields()
    {
        $f =  [

            'option' => [
                'class' => DropDownField::className(),
                /*'choices' => function() {
                        if ($oid = $this->getInstance()->getField('option')->getValue()) {
                            return [OptionNewModel::objects()->get(['id' => $oid])];
                        }
                        return OptionNewModel::objects()->all();
                    },*/
                'html' => [
//                    'disabled' => 'disabled',
                ],
            ],
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionVariantsAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl'
                /*'defaultOrder' => [
                    'class'
                ]*/
            ],
        ];
        return $f;
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }
}