<?php

namespace Modules\Account\Controllers\Api;

use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class OrdersApi extends FrontendController
{
    public function getOrders()
    {
        $user = Xcart::app()->auth->getUser(true);

        $mass = [];
        foreach ($user->orders as $key => $order  )
        {
            $mass[$key] = $order->getAttributes();
        }

        dd($mass);
    }
}