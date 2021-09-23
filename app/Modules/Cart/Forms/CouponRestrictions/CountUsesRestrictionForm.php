<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 31.10.17
 * Time: 17:57
 */

namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Discounts\Restrictions\CountUsedRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\NumberField;

class CountUsesRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return CountUsedRestriction::className();
    }

    public function getFields()
    {
        return array_merge(parent::getFields(), [
            'max_use' => [
                'class' => NumberField::className(),
                'required' => true,
                'value' => $this->getDataValue('max_use', 1),
                'label' => 'Maximum coupon uses'
            ],
        ]);
    }
}