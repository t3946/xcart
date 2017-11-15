<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CustomerRestrictionForm;
use Modules\Cart\Models\CouponOrderModel;

class CustomerRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return CustomerRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Customer phone or email');
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
        $result = true;

        if ($user) {
            if (!empty($this->data['phone'])) {
                preg_match_all('!\d+!', $user->phone, $matches);
                $phone = implode('', $matches);

                preg_match_all('!\d+!', $this->data['phone'], $matches);
                $cp = implode('', $matches);

                $result = !$result?: ($phone == $cp);
            }
            if (!empty($this->data['email'])) {
                $result = !$result?: ($user->email == $this->data['email']);
            }
        }

        return $result;
    }

    public function dataToString()
    {
        $str = '';

        if (!empty($this->data['phone'])) {
                $str .= " phone = {$this->data['phone']};";
        }
        if (!empty($this->data['email'])) {
                $str .= " email = {$this->data['email']};";
        }

        return $str;
    }
}