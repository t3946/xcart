<?php

namespace Modules\Cart;

use Modules\Admin\Traits\AdminTrait;
use Modules\Cart\Admin\CouponKitAdmin;
use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class CartModule extends Module
{
    use AdminTrait;

    /**
     * @var
     */
    public $listRoute;

    public $couponModel;

    /**
     * @var array
     */
    public $cartConfig = [
        'class' => '\Modules\Cart\Components\Cart',
    ];

    public static function onApplicationRun()
    {
        $tpl = Xcart::app()->template->getRenderer();

        $tpl->addAccessorCallback('cart', function () {
            return Xcart::app()->getModule('Cart')->getCart();
        });

        Xcart::app()->getModule('Cart'); //Small hack for init class;
    }

    public function init()
    {
        if (!Xcart::app()->hasComponent('cart')) {
            $this->setComponent('cart', $this->cartConfig);
        }
    }

    public function getCart()
    {
        return $this->getComponent('cart');
    }

    public static function getAdminMenu()
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
}
