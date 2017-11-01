<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\BrandRestrictionForm;
use Modules\Cart\Forms\CouponRestrictions\DatesRestrictionForm;

class BrandRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return BrandRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Brand restriction');
    }

    public function validate()
    {

    }

    public function dataToString()
    {
        return "";
    }
}