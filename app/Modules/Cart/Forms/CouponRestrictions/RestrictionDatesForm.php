<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Discounts\Restrictions\DateRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\DateField;

class RestrictionDatesForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return DateRestriction::className();
    }

    public function getFields()
    {
        $data = $this->getInstance()->data;

        return array_merge(parent::getFields(), [
            'start' => [
                'class' => DateField::className(),
                'required' => true,
                'value' => empty($data['start']) ? '': $data['start'],
            ],
            'end' => [
                'class' => DateField::className(),
                'required' => true,
                'value' => empty($data['end']) ? '': $data['end'],
            ],
        ]);
    }


}