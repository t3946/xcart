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

    public function getTypeValidation()
    {
        return self::VALIDATION_PRODUCT;
    }

    public function getErrorMessage()
    {
        return "No products in your cart eligible for a discount";
    }

    /**
     * @param \Modules\Product\Models\ProductModel $item
     *
     * @return bool
     */
    public function validate($item = null)
    {
        if ($item && $brand = $this->getBrand()) {
            return $item->brandid == $brand->pk;
        }

        return false;
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