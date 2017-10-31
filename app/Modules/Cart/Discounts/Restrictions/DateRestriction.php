<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Cart\Forms\RestrictionDatesForm;
use Modules\Cart\Interfaces\IDiscountRestriction;

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

    public function toString()
    {
        return "From: {$this->data['start']}, To: {$this->data['end']}";
    }
}