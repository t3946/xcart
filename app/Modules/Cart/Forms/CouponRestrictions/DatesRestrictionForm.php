<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Discounts\Restrictions\DateRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DateTimeField;

class DatesRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return DateRestriction::class;
    }

    public function getFields()
    {
        return array_merge(parent::getFields(), [
            'start' => [
                'class' => DateTimeField::class,
                'required' => true,
                'value' => $this->getDataValue('start'),
            ],
            'end' => [
                'class' => DateField::class,
                'required' => true,
                'value' => $this->getDataValue('end'),
            ],
        ]);
    }


}