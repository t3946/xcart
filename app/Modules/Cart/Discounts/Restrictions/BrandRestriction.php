<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Brand\Models\BrandModel;
use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\BrandRestrictionForm;

class BrandRestriction extends AbstractRestriction
{
    private $brandModel;

    public function getFormClass()
    {
        return BrandRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Brand restriction');
    }

    public function getTypeValidationObject()
    {
        return self::VALIDATION_PRODUCT;
    }

    public function validate($item = null)
    {

    }

    public function dataToString()
    {
        $brand = $this->getBrand();

        return "{$brand}";
    }

    public function getBrand()
    {
        if ($this->data && !$this->brandModel) {
            $this->brandModel = BrandModel::objects()->get(['pk' => $this->data['brand']]);
        }

        return $this->brandModel;
    }
}