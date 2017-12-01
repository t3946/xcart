<?php
namespace Modules\Cart\Helpers;

use Modules\Cart\Models\CouponKitModel;
use Modules\Cart\Models\CouponOrderModel;
use Modules\Product\Models\ProductModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

class CouponOldCart
{
    /**
     * @var CouponKitModel null
     */
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
    private $order_id = null;
    private $cart = [];
    private $errors = [];
    private $login;
    private $code;

    private $balance;
    private $productsDiscount = 0;
    private $force_show_error = false;


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

    public function setCart($cart = [])
    {
        $this->cart = $cart;
        return $this;
    }

    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function setCouponCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }

    public function setIsForceShow($force = false)
    {
        if (!$this->force_show_error) {
            $this->force_show_error = $force;
        }
    }

    /**
     * @param CouponKitModel $coupon
     *
     * @return $this
     */
    public function setCoupon($coupon)
    {
        static::$coupon = $coupon;
        return $this;
    }

    /**
     * @return CouponKitModel
     */
    public function getCoupon()
    {
        if (!static::$coupon) {
            if ($this->order_id) {
                /** @var CouponOrderModel $model */
                $model = CouponOrderModel::objects()->filter(['order_id' => $this->order_id])->get();

                static::$coupon = $model->coupon;
            }
            elseif ($this->code) {
                static::$coupon = CouponKitModel::objects()->filter(['active' => true, 'code' => $this->code])->get();
            }
            else if ( $code = Xcart::app()->request->session->get('coupon_code') ) {
                static::$coupon = CouponKitModel::objects()->filter(['active' => true, 'code' => $code])->get();
            }
        }

        $this->balance = static::$coupon->max_discount;

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
            if ($this->login) {
                static::setCustomer(UserModel::objects()->filter(['login' => $this->login])->get());
            }
            else if( $login = Xcart::app()->request->session->get('login') ) {
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
        if ($this->cart && empty($this->cart['tmp_coupon_total'])) {
            $this->cart['tmp_coupon_total'] = $this->getSumProducts();
        }


        /** @var \Modules\Cart\Discounts\Restrictions\AbstractRestriction $restrict */
        foreach ($this->getRestricts() as $restrict) {
            $restrict->setCoupon($this->getCoupon());
            $restrict->setCart($this->getCart());
            $restrict->setOrderId($this->getOrderId());
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

            $this->setIsForceShow($restrict->isForceShow());
            $this->addError($restrict::className(), $restrict->getErrorMessage());
            $this->setRestrictTypeValidate($restrict::className(), $valid);
        }

        return $this->isAllTypesValid();
    }

    public function setRestrictTypeValidate($class, $status) {
        if ($status && isset(static::$types_restrictions[$class])) {
            static::$types_restrictions[$class] = true;

            $this->cleanError($class);
        }
    }

    public function restrictTypeIsValid($type)
    {
        return isset(static::$types_restrictions[$type]) && static::$types_restrictions[$type];
    }

    public function isAllTypesValid()
    {
        foreach (static::$types_restrictions as $type =>$valid) {
            if (!$valid) {
                return false;
            }
        }

        $this->cleanErrors();

        return true;
    }

    public function isForProduct()
    {
        return $this->forProducts == true;
    }

    private function getValidProductIds()
    {
        if (!$this->pids_appended) {
            $cart = $this->getCart();

            foreach ($cart['products'] as $item) {
                $this->pids_appended[] = $item['productid'];
            }
        }

        return $this->pids_appended;
    }

    public function getProductSubtotal($item)
    {
        return $item['price'] * $item['amount'];
    }

    public function getSumProducts()
    {
        $cost = 0;
        $cart = $this->getCart();

        if (!empty($cart['subtotal'])) {
            return $cart['subtotal'];
        }

        foreach ($cart['products'] as $item) {
            $cost += $this->getProductSubtotal($item);
        }

        return $cost;
    }

    public function getSumValidProducts()
    {
        $cost = 0;
        $cart = $this->getCart();
        $pids = $this->getValidProductIds();

        foreach ($cart['products'] as $item) {
            if (in_array($item['productid'], $pids)) {
                $cost += $this->getProductSubtotal($item);
            }
        }

        return $cost;
    }

    public function calcSumDiscount()
    {
        $cost = $this->getSumProducts();

        if ($this->isForProduct()) {
            $cost = $this->getSumValidProducts();
        }

        return $this->calcDiscount($cost);
    }

    public function calcDiscount($cost)
    {
        $coupon = $this->getCoupon();
        $discount = floatval($coupon->discount);
        $max_dict = floatval($coupon->max_discount);

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

    public function getBalancedDiscount($cost)
    {
        $calc = 0;

        if ($this->balance) {
            $calc = $this->calcDiscount($cost);


            if ($this->balance < $calc) {
                $calc -= $this->balance;
            }

            $this->balance -= $calc;

        }

        return $calc;
    }

    public function addError($type, $message)
    {
        $this->errors[$type] = $message;
        return $this;
    }

    public function cleanError($type) {
        unset($this->errors[$type]);
        return $this;
    }

    public function cleanErrors()
    {
        $this->errors = [];
        return $this;
    }

    public function getErrors()
    {
        return $this->errors ?: [];
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return bool|null
     */
    public function validateCoupon()
    {
        if ($coupon = $this->getCoupon()) {
            if ($this->getCustomer() && $this->getCart()) {
                return $this->checkRestrictions();
            }
        }

        return null;
    }

    private function reCalcProducts()
    {
        $cart = $this->getCart();
        $pids = $this->getValidProductIds();

        foreach ($cart['products'] as $key =>$item) {
            if ( in_array($item['productid'], $pids) ) {
//                $price = $item['price'];
                $total = $this->getProductSubtotal($item);
                $discount = $this->getBalancedDiscount($total);
//                $p_discount = $this->calcDiscount($price);

                $item['coupon_discount'] = $discount;
                $item['coupon_discount_orig'] = $discount;
                $item['discounted_price'] = $total - $discount;
//                $item['discounted_price_orig'] = $p_discount;
//                $item['display_price'] = $p_discount;
                $item['display_subtotal'] = $total - $discount;
                $item['display_discounted_price'] = $total - $discount;

                $cart['products'][$key] = $item;

                $this->productsDiscount += $discount;
            }
        }

        $cart['orders'][0]['products'] = $cart['products'];

        $this->setCart($cart);
    }

    private function reCalcCart()
    {
        $coupon = $this->getCoupon();
        $cart = $this->getCart();

        $total = $this->getSumProducts();

        $cart['coupon'] = $coupon->code;
        $cart['discount_coupon'] = $coupon->code;
        $cart['coupon_discount'] = $this->productsDiscount;
        $cart['coupon_discount_orig'] = $this->productsDiscount;
        $cart['display_discounted_subtotal'] = $total - $this->productsDiscount;
        $cart['total_cost'] = $cart['total_cost'] - $this->productsDiscount;

        $cart['orders'][0]['coupon'] = $cart['coupon'];
        $cart['orders'][0]['coupon_discount'] =  $cart['coupon_discount'];
        $cart['orders'][0]['total_cost'] = $cart['total_cost'];
        $cart['orders'][0]['display_discounted_subtotal'] = $cart['display_discounted_subtotal'];

        $this->setCart($cart);
    }

    public function isValid()
    {
        return $this->validateCoupon();
    }

    public function isForceShow()
    {
        return $this->force_show_error;
    }

    /**
     * @return array|null Old cart structure
     */
    public function appendCoupon()
    {
        if ($this->isValid() || $this->order_id) {
            $this->reCalcProducts();
            $this->reCalcCart();
        }

        return $this->getCart();
    }
}