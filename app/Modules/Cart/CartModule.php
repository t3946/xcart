<?php

namespace Modules\Cart;

use Modules\Admin\Traits\AdminTrait;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class CartModule extends Module
{
    use AdminTrait;

    /**
     * @var
     */
    public $listRoute;
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

        Xcart::app()->getModule('Cart');
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
}
