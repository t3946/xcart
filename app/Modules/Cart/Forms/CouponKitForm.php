<?php
namespace Modules\Cart\Forms;

use Modules\Cart\Admin\DiscountRestrictionAdmin;
use Modules\Cart\Models\CouponKitModel;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\RadioField;
use Xcart\App\Form\ModelForm;

class CouponKitForm extends ModelForm
{
    public array $exclude = ['orders'];

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
            'discount' => [
                'class' => CharField::class,
                'required' => true,
                'hint' => 'Discount by cart position'
            ],
            'max_discount' => [
                'class' => CharField::class,
                'required' => true,
                'hint' => 'Max discount by subtotal cart'
            ],
            'description' => [
                'class' => EditorField::class,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'type' => RadioField::class,
            'restrictions' => [
                'class' => ListViewField::class,
                'adminClass' => DiscountRestrictionAdmin::class,
                'defaultOrder' => [
                    'class'
                ]
            ],
        ];
    }

}