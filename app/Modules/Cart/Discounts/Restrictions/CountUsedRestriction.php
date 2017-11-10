<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CountUsesRestrictionForm;

class CountUsedRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return CountUsesRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Count uses');
    }

    public function getTypeValidationObject()
    {
        return self::VALIDATION_CUSTOMER;
    }

    public function validate($user = null)
    {

    }

    public function dataToString()
    {
        return "Max uses: {$this->data['max_use']}; Max use per user: {$this->data['max_uses_per_user']}; Current uses: {$this->data['uses']}";
    }
}