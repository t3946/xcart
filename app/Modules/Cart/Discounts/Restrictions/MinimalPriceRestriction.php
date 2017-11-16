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
        return self::VALIDATION_CUSTOMER;
    }

    /**
     * @param \Modules\User\Models\UserModel $user
     *
     * @return bool
     */
    public function validate($user = null)
    {

        if ($this->cart) {
            return $this->cart['subtotal'] > $this->data['min_price'];
        }

        return false;
    }

    public function dataToString()
    {
        return "Minimal price: {$this->data['min_price']}";
    }
}