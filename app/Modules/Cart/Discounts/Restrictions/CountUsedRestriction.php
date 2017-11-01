<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CountUsesForm;

class CountUsedRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return CountUsesForm::className();
    }

    public function getName()
    {
        return CartModule::t('Count uses');
    }

    public function validate()
    {

    }

    public function dataToString()
    {
        return "Max uses: {$this->data['max_use']}; Max use per user: {$this->data['max_uses_per_user']}; Current uses: {$this->data['uses']}";
    }
}