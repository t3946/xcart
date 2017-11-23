<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\DatesRestrictionForm;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;

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

    public function getTypeValidation()
    {
        return self::VALIDATION_OTHER;
    }
    public function getErrorMessage()
    {
        return "Coupon is currently non valid";
    }

    public function validate($object = null)
    {
        $start = strtotime($this->data['start']);
        $end = strtotime($this->data['end']);
        $time = time();

        $result = ($start <= $time && $time <= $end);
        if (!$result) {
            $this->notValidAction();
        }
        return $result;
    }

    public function dataToString()
    {
        return "{$this->data['start']} - {$this->data['end']}";
    }
}