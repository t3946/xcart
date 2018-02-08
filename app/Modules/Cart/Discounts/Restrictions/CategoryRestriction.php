<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\CategoryRestrictionForm;
use Modules\Goods\Models\CategoryModel;

class CategoryRestriction extends AbstractRestriction
{
    private $model;
    private $ids;

    public function getFormClass()
    {
        return CategoryRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Category restriction');
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
     * @param \Modules\Goods\Models\ProductModel $product
     *
     * @return bool
     */
    public function validate($product = null)
    {
        if ($product && $ids = $this->getCategoriesIds()) {
            $cids = $product->categories->valuesList('categoryid', true);

            foreach ($cids as $cid) {
                if (in_array($cid, $ids)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getCategoriesIds()
    {
        if (!$this->ids && $model = $this->getModel()) {

            $this->ids = CategoryModel::objects()->filter(['categoryid_path__startswith' => $model->categoryid_path])->valuesList(['categoryid'], true);
        }

        return $this->ids;
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