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
    private $forProducts = false;
    private $cart = [];


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
        if (!$this->cart) {
            $this->cart = Xcart::app()->request->session->get('cart');
        }

        return $this->cart;
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

            foreach ($coupon::getDefaultRestrictions() as $restriction) {
                static::$types_restrictions[$restriction::className()] = false;
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
                static::setProducts(ProductModel::objects()->filter(['pk__in' => $pids])->all());
            }
        }

        return static::$products;
    }

    /**
     * @return \Modules\Cart\Interfaces\IDiscountRestriction[]
     */
    public function getRestricts()
    {
        $coupon = $this->getCoupon();
        $restricts = $coupon::getDefaultRestrictions();

        /** @var \Modules\Cart\Models\CouponRestrictionModel $restriction */
        foreach ($this->getRestrictions() as $restriction) {
            $restricts[] = $restriction->getRestrict();
        }

        return $restricts;
    }

    public function checkRestrictions()
    {
        /** @var \Modules\Cart\Discounts\Restrictions\AbstractRestriction $restrict */
        foreach ($this->getRestricts() as $restrict) {
            $restrict->setCoupon($this->getCoupon());
            $valid = false;

            switch ($restrict->getTypeValidation())
            {
                case $restrict::VALIDATION_PRODUCT : {
                    $this->forProducts = true;

                    /** @var ProductModel $product */
                    foreach ($this->getCartProducts() as $product) {
                        if ($tValid = $restrict->validate($product)) {
                            $this->pids_appended[] = $product->pk;
                            $valid = $valid ?: $tValid;
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

            $this->setRestrictTypeValidate($restrict::className(), $valid);
        }

        return $this->isAllTypesValid();
    }

    public function setRestrictTypeValidate($class, $status) {
        if ($status && isset(static::$types_restrictions[$class])) {
            static::$types_restrictions[$class] = true;
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

    public function isForProduct()
    {
        return $this->forProducts == true;
    }

    public function getSummProducts()
    {
        $cost = 0;
        $cart = $this->getCart();

        foreach ($cart['products'] as $item) {
            $cost += $item['subtotal'];
        }

        return $cost;

    }

    public function calcDiscount()
    {
        $coupon = $this->getCoupon();
        $cart = $this->getCart();
        $discount = floatval($coupon->discount);
        $max_dict = floatval($coupon->max_discount);
        $cost = $cart['subtotal'];

        if ($this->isForProduct()) {
            $cost = $this->getSummProducts();
        }

        if ($coupon->isPercentageCalc()) {
            //  Dec to float 10% => 0.1
            $calc = $cost * ($discount / 100);
        }
        else {
            $calc = $discount;
        }


        if ($calc > $max_dict) {
            $calc = $max_dict;
        }

        return $calc;

    }

    public function appendCoupon($cart = [])
    {
        $cart = $this->cart = $cart ?: $this->getCart();

        if ($coupon = $this->getCoupon()) {
            if ($this->getCustomer() && $cart) {
                if ($this->checkRestrictions()) {
                    $discount = $this->calcDiscount();

                    $total = $cart['subtotal'];

                    $cart['coupon'] = $coupon->code;
                    $cart['coupon_discount'] = -1 * $discount;
                    $cart['display_discounted_subtotal'] = $total - $discount;
                    $cart['total_cost'] = $cart['total_cost'] - $discount;

                    $cart['orders'][0]['coupon'] = $cart['coupon'];
                    $cart['orders'][0]['coupon_discount'] =  $cart['coupon_discount'];
                    $cart['orders'][0]['total_cost'] = $cart['total_cost'];
                    $cart['orders'][0]['display_discounted_subtotal'] = $cart['display_discounted_subtotal'];


                }
                else {
                    Xcart::app()->request->session->remove('coupon_code');
                    Xcart::app()->flash->addWithCode('coupon_code', 'Coupon not be used in current cart.', 'error', 15000);
                }
            }
        }

        return $cart;
    }

}