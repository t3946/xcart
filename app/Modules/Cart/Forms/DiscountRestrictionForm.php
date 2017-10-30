<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 30.10.17
 * Time: 20:12
 */

namespace Modules\Cart\Forms;

use Modules\Cart\Models\CouponRestrictionModel;
use Xcart\App\Form\ModelForm;

class DiscountRestrictionForm extends ModelForm
{
    public function getModel()
    {
        return new CouponRestrictionModel();
    }
}