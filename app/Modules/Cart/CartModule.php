<?php

namespace Modules\Cart;

use Modules\Admin\Traits\AdminTrait;
use Modules\Cart\Admin\CouponKitAdmin;
use Modules\Cart\Helpers\CouponOldCart;
use Modules\Cart\Helpers\StagesOfOrdering;
use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;
use Modules\Cart\Components\Cart;

class CartModule extends Module
{
    use AdminTrait;

    public $listRoute;

    public $couponModel;

    private $isCouponsActive;

    private $validate_component;

    /**
     * @var StagesOfOrdering
     */
    private static $_stagesOfOrdering;

    /**
     * @var array
     */
    public $cartConfig = [
        'class' => Cart::class,
    ];

    /**
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function onApplicationRun(): void
    {
        $tpl = Xcart::app()->template->getRenderer();

        $tpl->addAccessorCallback('cart', function () {
            return Xcart::app()->getModule('Cart')->getCart();
        });

        // Small hack for init class;
        Xcart::app()->getModule('Cart');

        // receive information about stages of ordering
       static::$_stagesOfOrdering = new StagesOfOrdering();
      //  static::$_stagesOfOrdering->setActive('shopping_cart');
        static::$_stagesOfOrdering->setActive('shipping_address');
//        static::$_stagesOfOrdering->setActive('shipping_payment_options');
//        static::$_stagesOfOrdering->setActive('order_review');
       // static::$_stagesOfOrdering->setActive('payment');

        $tpl->addAccessorCallback('getCartBreadcrumbs', function () {
            return static::$_stagesOfOrdering->getStages();
        });

        $tpl->addAccessorCallback('getCartBreadcrumbsBackEnabled', function () {
            return !static::$_stagesOfOrdering->getFirstStage();
        });


    }

    public function init(): void
    {
        if (!Xcart::app()->hasComponent('cart')) {
            static::setComponent('cart', $this->cartConfig);
        }
    }

    public function getCart()
    {
        return static::getComponent('cart');
    }

    public static function getAdminMenu(): array
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        return [
            [
                'name' => CouponKitAdmin::getName(),
                'route' => $router->url('admin:list', [
                    'module' => static::getModuleName(),
                    'admin' => CouponKitAdmin::classNameShort()
                ]),
            ],
        ];
    }

    public function setValidateComponent($component):void
    {
        $this->validate_component = $component;
    }

    public function getValidateComponent()
    {
        if (!$this->validate_component) {
            $this->validate_component = CouponOldCart::getInstance();
        }

        return $this->validate_component;
    }

    public function getCouponModel()
    {
        if ( Xcart::app()->request->session->has('coupon_code') ) {
            $code = Xcart::app()->request->session->get('coupon_code');

            if (!$this->couponModel || $this->couponModel->code != $code) {
                $this->couponModel = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get();
            }

            return $this->couponModel;
        }

        return null;
    }

    public function isCouponActive(): bool
    {
        if (null === $this->isCouponsActive) {
            $this->isCouponsActive = (bool)CouponKitModel::objects()->filter(['active' => true, 'deleted' => false])->count();
        }

        return $this->isCouponsActive;
    }
}
