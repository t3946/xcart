<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CountUsesRestrictionForm;
use Modules\Cart\Models\CouponOrderModel;
use Modules\User\Models\UserModel;

class DefaultRestriction extends AbstractRestriction
{

    public function getFormClass()
    {
        return null;
    }

    public function getName()
    {
        return CartModule::t('Defaults validation');
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
        $return = true;

        $return = !$return?: $this->validatePerUserUses($user);

        return $return;
    }

    private function validatePerUserUses($user)
    {
        if ($user && $this->couponModel) {
            $logins = UserModel::objects()
                               ->filter(['email' => $user->email])
                               ->orFilter(['phone' => $user->phone])
                               ->valuesList('login', true);

            if ( $logins ) {
                $uses = CouponOrderModel::objects()->filter(['login__in' => $logins, 'code' => $this->couponModel->code])->count();
                return  $uses < $this->couponModel->uses_per_user;
            }
        }

        return true;
    }

    public function dataToString()
    {
        return 'Default checks';
    }
}