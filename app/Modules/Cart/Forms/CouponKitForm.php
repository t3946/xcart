<?php
namespace Modules\Cart\Forms;

use Modules\Cart\Admin\DiscountRestrictionAdmin;
use Modules\Cart\Models\CouponKitModel;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\RadioField;
use Xcart\App\Form\ModelForm;

class CouponKitForm extends ModelForm
{
    public function getModel()
    {
        return new CouponKitModel();
    }

    public function getFieldsets()
    {
        return [
            'Main' => [
                'active','code', 'name', 'uses_per_user', 'type', 'discount', 'max_discount', 'description'
            ],
            'Restrictions' => [
                'restrictions'
            ]
        ];
    }

    public function getFields()
    {
        return [
            'description' => EditorField::className(),
            'type' => RadioField::className(),
            'restrictions' => [
                'class' => ListViewField::className(),
                'adminClass' => DiscountRestrictionAdmin::className(),
                'defaultOrder' => [
                    'class'
                ]
            ],
        ];
    }

}