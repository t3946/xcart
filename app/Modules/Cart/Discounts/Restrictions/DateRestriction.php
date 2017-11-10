<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\DatesRestrictionForm;

class DateRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return DatesRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Date restriction');
    }

    public function getTypeValidationObject()
    {
        return self::VALIDATION_OTHER;
    }


    public function validate($object = null)
    {

    }

    public function dataToString()
    {
        return "{$this->data['start']} - {$this->data['end']}";
    }
}