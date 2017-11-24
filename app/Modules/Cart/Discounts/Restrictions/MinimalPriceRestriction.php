<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\MinimalPriceRestrictionForm;
use Modules\Cart\Models\CouponOrderModel;

class MinimalPriceRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return MinimalPriceRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Minimal price');
    }

    public function getTypeValidation()
    {
        return self::VALIDATION_OTHER;
    }

    public function getErrorMessage()
    {
        $format = number_format($this->data['min_price']);

        return "Coupon use for minimal price $ {$format}";
    }

    /**
     * @param \Modules\User\Models\UserModel $user
     *
     * @return bool
     */
    public function validate($user = null)
    {

        if ($this->cart) {
            //@TODO: Cahnge for new cart
            return $this->cart['tmp_coupon_total'] > $this->data['min_price'];
        }

        return false;
    }

    public function dataToString()
    {
        return "{$this->data['min_price']}";
    }
}