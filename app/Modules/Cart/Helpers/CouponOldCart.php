<?php
namespace Modules\Cart\Helpers;

use Modules\Cart\Models\CouponKitModel;
use Modules\Product\Models\ProductModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

class CouponOldCart
{
    static $coupon = null;
    static $products = null;
    static $customer = null;
    static $instance = null;
    /**
     * @var \Modules\Cart\Models\CouponRestrictionModel null
     */
    static $restrictions = null;
    static $types_restrictions = [];

    private $pids_appended = [];


    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    /**
     * @param null $customer
     */
    public static function setCustomer($customer)
    {
        self::$customer = $customer;
    }

    /**
     * @param null $products
     */
    public static function setProducts($products)
    {
        self::$products = $products;
    }

    public function getCart()
    {
        return Xcart::app()->request->session->get('cart');
    }

    /**
     * @return CouponKitModel
     */
    public function getCoupon()
    {
        if (!static::$coupon) {
            if ( $code = Xcart::app()->request->session->get('coupon_code') ) {
                static::$coupon = CouponKitModel::objects()->filter(['active' => true, 'code' => $code])->get();
            }
        }

        return static::$coupon;
    }

    public function getRestrictions()
    {
        if (!static::$restrictions && $coupon = $this->getCoupon() ) {
            static::$restrictions = $coupon->restrictions->all();

            foreach (static::$restrictions as $restriction) {
                /** @var  \Modules\Cart\Models\CouponRestrictionModel $restriction*/
                static::$types_restrictions[$restriction->class] = false;
            }
        }

        return static::$restrictions;
    }

    public function getCustomer()
    {
        if (!static::$customer) {
            if( $login = Xcart::app()->request->session->get('login') ) {
                static::setCustomer(UserModel::objects()->filter(['login' => $login])->get());
            }
        }

        return static::$customer;
    }

    public function getCartProductsIds()
    {
        $pids = [];
        $cart = $this->getCart();

        foreach ($cart['products'] as $product) {
            $pids[] = $product['productid'];
        }

        return $pids;
    }

    /**
     * @return ProductModel|null
     */
    public function getCartProducts()
    {
        if (!static::$products) {
            if ( $pids = $this->getCartProductsIds() ) {
                static::setProducts(ProductModel::objects()->filter(['avail' => 'Y', 'pk__in' => $pids]));
            }
        }

        return static::$products;
    }

    public function checkRestrictions()
    {
        $restrictions = $this->getRestrictions();

        /** @var \Modules\Cart\Models\CouponRestrictionModel $restriction */
        foreach ($restrictions as $restriction) {
            $restrict = $restriction->getRestrict();
            $type = $restrict->getTypeValidationObject();
            $valid = false;

            switch ($type)
            {
                case $restrict::VALIDATION_PRODUCT : {
                    /** @var ProductModel $product */
                    foreach ($this->getCartProducts() as $product) {
                        if ($tValid = $restrict->validate($product)) {
                            $this->pids_appended[] = $product->pk;
                            $valid = $valid ?: $tValid;
                            break;
                        }
                    }
                    break;
                }
                case $restrict::VALIDATION_CUSTOMER: {
                    $valid = $restrict->validate($this->getCustomer());
                    break;
                }
                case $restrict::VALIDATION_OTHER:
                default:
                    $valid = $restrict->validate();
            }

            $this->setRestrictTypeValidate($type, $valid);
        }

        return $this->isAllTypesValid();
    }

    public function setRestrictTypeValidate($type, $status) {
        if ($status && isset(static::$types_restrictions[$type])) {
            static::$types_restrictions[$type] = true;
        }
    }

    public function restrictTypeIsValid($type)
    {
        return isset(static::$types_restrictions[$type]) && static::$types_restrictions[$type];
    }

    public function isAllTypesValid()
    {
        foreach (static::$types_restrictions as $valid) {
            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    public function appendCoupon()
    {
        $cart = $this->getCart();

        d($cart);

        if ($this->getCoupon()) {
            if ($this->getCustomer() && $cart) {
                if ($this->checkRestrictions()) {

                }
            }
        }


        return $cart;
    }

}