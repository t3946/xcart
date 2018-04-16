<?php

namespace Modules\Order\Controllers;

use Modules\Core\Models\CountryModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\User\Models\AddressModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class CheckoutController extends FrontendController
{
    /**
     * @return \Modules\Cart\Components\Cart
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    protected function getCart()
    {
        return Xcart::app()->getModule('Cart')->getComponent('cart');
    }

    public function actionShipping()
    {
        $app = Xcart::app();
        $user = $app->user;
        $cart = $app->cart;

        if (!$user) {}

        /** @var OrderModel $order */

        [$order, $is_created] = OrderModel::objects()->getOrCreate([
            'user_id' => $user->id,
            'cart_number' => $cart->getCartNumber(),
            'order_prefix' => $app->getModule('Sites')->getSite()->getOrderPrefix()
        ]);

        if ($app->request->getIsPost()) {
            $data = $app->request->post->get('customer');
            if (1==1) { //validation

                [$address] = AddressModel::objects()->getOrCreate([
                    'user_id' => $user->id,
                    'full_name' => $data['s_firstname'],
                    'company' => $data['s_company'],
                    'address' => $data['s_address'],
                    'address_2' => $data['s_address_2'],
                    'country' => $data['s_country'],
                    'zip' => $data['s_zipcode'],
                    'state' => $data['s_statename'],
                    'city' => $data['s_city'],
                ]);
                $address->save();

                $order->setAttributes([
                    's_firstname' => $address->full_name,
                    's_company' => $address->company,
                    's_address' => $address->address . PHP_EOL . $address->address_2,
                    's_country' => $address->country,
                    's_zipcode' => $address->zip,
                    's_state' => $address->state,
                    's_city' => $address->city,
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'firstname' => $data['firstname'],
                ]);

                if ($order->save()) {
                    $this->redirect('checkout:options');
                }
            }
        }

        [$s_address, $s_address_2] = $order->getAddress();

        echo $this->render('checkout/shipping.tpl', [
            'order' => $order,
            'countries' => Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql())
        ]);
    }

    public function actionOptions()
    {
        $app = Xcart::app();
        $user = $app->user;
        $cart = $app->cart;

        if (!$user) {}

        [$order, $is_created] = OrderModel::objects()->getOrCreate([
            'user_id' => $user->id,
            'cart_number' => $cart->getStorage()->getCartNumber(),
            'order_prefix' => $app->getModule('Sites')->getSite()->getOrderPrefix()
        ]);

        echo $this->render('checkout/options.tpl', [
            'order' => $order,
            'countries' => Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql())
        ]);
    }
}