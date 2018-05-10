<?php
namespace Modules\Shipping;

use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\GoodsModule;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;
use Xcart\Cart;
use Xcart\Shipping;

class ShippingModule extends Module
{
    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorSmart('get_shipping', self::class."::getShipping", $template::ACCESSOR_CALL);
    }

    public static function getShipping(int $id, OrderModel $order, array $group) : array
    {
        $result = [];

        $user = new UserModel([
            's_zipcode' => $order->s_zipcode,
            's_state' => $order->s_state,
            's_city' => !empty($order->s_city) ? $order->s_city : 'New City',
            's_country' => $order->s_country,
        ]);

        /** @var DistributorModel $distributor */
        $distributor = DistributorModel::objects()->get(['pk' => $id]);

        $cart = new Cart();
        foreach ($group['items'] as $key=>$position) {
            $_product = $position->object;
            $_product->setPrice($position->getPrice()); //calculate regarding cart product price
            $cart->addObjectToCart(new \Xcart\CartElement($_product, $position->quantity));
        }

        try {
            $shipping_rates = Shipping::model()->getShippingRates($user, $distributor, $cart)[0];
        } catch (\Exception $e) {
            $shipping_rates = [];
        }

        if ($shipping_rates) {
            foreach ($shipping_rates as $rate) {
                $result[$rate->rateid] = $rate;
            }
        }

        return $result;
    }
}