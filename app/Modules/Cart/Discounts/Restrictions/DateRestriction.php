<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\RestrictionDatesForm;

class DateRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return RestrictionDatesForm::className();
    }

    public function getName()
    {
        return CartModule::t('Date restriction');
    }

    public function validate()
    {

    }

    public function dataToString()
    {
        return "{$this->data['start']} - {$this->data['end']}";
    }
}