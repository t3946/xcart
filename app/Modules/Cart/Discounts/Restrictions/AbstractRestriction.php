<?php
namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\Interfaces\IDiscountRestriction;
use Xcart\App\Cli\Cli;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Main\Xcart;

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
    public $order_id = null;

    /**
     * AbstractRestriction constructor.
     *
     * @param \Modules\Cart\Models\CouponRestrictionModel $restrictModel
     */
    public function __construct($restrictModel = null) {
        $this->restrictModel = $restrictModel;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
        return $this;
    }

    public function setCoupon($model) {
        $this->couponModel = $model;
        return $this;
    }

    public function setCart($cart) {
        $this->cart = $cart;
        return $this;
    }

    abstract public function getErrorMessage();

    abstract public function getFormClass();

    abstract public function getTypeValidation();

    public function setData($data) {
        $this->data = $data;
    }

    public function notValidAction()
    {
        if (!Cli::isCli()) {
            Xcart::app()->request->session->remove('coupon_code');
        }
    }
}