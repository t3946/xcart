<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CountUsesRestrictionForm;
use Modules\Cart\Models\CouponOrderModel;
use Modules\User\Models\UserModel;

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
        return $this->data['max_use'] > CouponOrderModel::objects()->filter(['code' => $this->couponModel->code])->count();


//        if ($user) {

//            if ( $logins = UserModel::objects()->filter(['email' => $this->email])->orFilter(['phone' => $this->phone])->valuesList('login', true) ) {

//            }
//        }


        return false;
    }

    public function dataToString()
    {
        return "Max uses: {$this->data['max_use']}";
    }
}