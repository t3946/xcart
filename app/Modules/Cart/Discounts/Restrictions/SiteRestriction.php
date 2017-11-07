<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\SiteRestrictionForm;
use Modules\Sites\Models\SiteModel;

class SiteRestriction extends AbstractRestriction
{
    private $model;

    public function getFormClass()
    {
        return SiteRestrictionForm::className();
    }

    public function getName()
    {
        return CartModule::t('Site restriction');
    }

    public function validate()
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
            $this->model = SiteModel::objects()->get(['pk' => $this->data['site']]);
        }

        return $this->model;
    }
}