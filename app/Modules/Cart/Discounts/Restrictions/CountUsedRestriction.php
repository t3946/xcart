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

    public function getErrorMessage()
    {
        return "Coupon can no longer be used.";
    }

    /**
     * @param \Modules\User\Models\UserModel $user
     *
     * @return bool
     */
    public function validate($user = null)
    {
        $result = $this->data['max_use'] > CouponOrderModel::objects()->filter(['coupon_id' => $this->couponModel->id])->count();
        if (!$result) {
            $this->notValidAction();
        }
        return $result;
    }

    public function dataToString()
    {
        return "Max uses: {$this->data['max_use']}";
    }
}