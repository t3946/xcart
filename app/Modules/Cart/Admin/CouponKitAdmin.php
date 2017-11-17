<?php
namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Cart\Forms\CouponKitForm;
use Modules\Cart\Models\CouponKitModel;

class CouponKitAdmin extends Admin
{
    public static function getName()
    {
        return 'Coupon KIT';
    }

    public function getForm()
    {
        return new CouponKitForm();
    }

    public function getModel()
    {
        return new CouponKitModel();
    }

    public function getQuerySet()
    {
        $qs = parent::getQuerySet();

        $qs->filter(['deleted' => false]);

        return $qs;
    }
}