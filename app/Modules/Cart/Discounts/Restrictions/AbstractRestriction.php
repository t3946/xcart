<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 31.10.17
 * Time: 20:52
 */

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\Interfaces\IDiscountRestriction;
use Xcart\App\Helpers\ClassNames;

abstract class AbstractRestriction implements IDiscountRestriction
{
    use ClassNames;

    public $data = [];
    /**
     * @var \Modules\Cart\Models\CouponRestrictionModel|null
     */
    public $restrictModel = null;
    /**
     * @var \Modules\Cart\Models\CouponKitModel
     */
    public $couponModel = null;
    public $cart = null;

    /**
     * AbstractRestriction constructor.
     *
     * @param \Modules\Cart\Models\CouponRestrictionModel $restrictModel
     */
    public function __construct($restrictModel = null) {
        $this->restrictModel = $restrictModel;
    }

    public function setCoupon($model) {
        $this->couponModel = $model;
    }

    public function setCart($cart) {
        $this->cart = $cart;
    }

    abstract public function getFormClass();

    abstract public function getTypeValidation();

    public function setData($data) {
        $this->data = $data;
    }
}