<?php

namespace Modules\Order\Controllers;


use Modules\Order\Models\OrderModel;
use Modules\Payment\Gateways\Gateway;
use Xcart\App\Controller\Controller;

class OrderTransactionsController extends Controller
{
    public function transaction_process($order_id, $mode, $transaction_id)
    {
        if ($orderModel = OrderModel::objects()->get(['orderid' => $order_id])) {

            extract(Gateway::$gatewayMethods[$mode]);

        }
    }
}