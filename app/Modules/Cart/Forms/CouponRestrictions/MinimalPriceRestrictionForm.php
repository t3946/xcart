<?php
namespace Modules\Cart\Forms\CouponRestrictions;

use Modules\Cart\Discounts\Restrictions\MinimalPriceRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\NumberField;

class MinimalPriceRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return MinimalPriceRestriction::className();
    }

    public function getFields()
    {
        $data = $this->getInstance()->data;

        return array_merge(parent::getFields(), [
            'min_price' => [
                'class' => NumberField::className(),
                'required' => true,
                'value' => empty($data['min_price']) ? 1: $data['min_price'],
                'label' => 'Minimal price'
            ],
        ]);
    }
}