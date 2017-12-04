<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Discounts\Restrictions\CustomerRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;

class CustomerRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return CustomerRestriction::className();
    }

    public function getFields()
    {
        return array_merge(parent::getFields(), [
            'phone' => [
                'class' => NumberField::className(),
                'value' => $this->getDataValue('phone'),
            ],
            'email' => [
                'class' => EmailField::className(),
                'value' => $this->getDataValue('email'),
            ],
        ]);
    }

}