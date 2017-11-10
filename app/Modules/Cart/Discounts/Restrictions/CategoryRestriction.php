<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CategoryRestrictionForm;
use Modules\Product\Models\CategoryModel;

class CategoryRestriction extends AbstractRestriction
{
    private $model;

    public function getFormClass()
    {
        return CategoryRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Category restriction');
    }

    public function getTypeValidationObject()
    {
        return self::VALIDATION_PRODUCT;
    }

    public function validate($product = null)
    {

    }

    public function dataToString()
    {
        $model = $this->getModel();

        return "{$model}";
    }

    public function getModel()
    {
        if ($this->data && !$this->model) {
            $this->model = CategoryModel::objects()->get(['pk' => $this->data['category']]);
        }

        return $this->model;
    }
}