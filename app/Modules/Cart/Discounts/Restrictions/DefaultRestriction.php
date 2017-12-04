<?php

namespace Modules\Cart\Discounts\Restrictions;

use Mindy\QueryBuilder\Q\QAndNot;
use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CountUsesRestrictionForm;
use Modules\Cart\Models\CouponOrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;

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

    public function getErrorMessage()
    {
        return "Coupon was already used.";
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
            $uQs = UserModel::objects()
                            ->filter(['email' => $user->email])
                            ->orFilter(['phone' => $user->phone]);

            if ( $uQs->count() ) {
                $qs = CouponOrderModel::objects()->filter(['login__in' => $uQs->select(['login']), 'coupon_id' => $this->couponModel->id]);

                if ($this->order_id) {
                    $qs->filter([new QAndNot(['order_id' => $this->order_id])]);
                }

                $uses = $qs->count();
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