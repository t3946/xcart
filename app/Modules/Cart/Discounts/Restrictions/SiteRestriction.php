<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Forms\CouponRestrictions\SiteRestrictionForm;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

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

    public function getTypeValidation()
    {
        return self::VALIDATION_OTHER;
    }


    public function validate($object = null)
    {
        /** @var \Modules\Sites\SitesModule $module */
        $module = Xcart::app()->getModule('Sites');
        $site = $module->getSite();

        if ($site && $model = $this->getModel()) {
            if ($this->data['not_in']) {
                return $site->pk != $model->pk;
            }
            else {
                return $site->pk == $model->pk;
            }
        }

        return true;
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