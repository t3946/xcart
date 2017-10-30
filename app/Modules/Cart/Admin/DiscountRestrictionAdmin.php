<?php
namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Cart\Forms\CouponKitForm;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Cart\Models\CouponKitModel;
use Modules\Cart\Models\CouponRestrictionModel;

class DiscountRestrictionAdmin extends Admin
{
    public static function getName()
    {
        return 'Discount Restriction';
    }

    public function getForm()
    {
        return new DiscountRestrictionForm();
    }

    public function getModel()
    {
        return new CouponRestrictionModel();
    }
}