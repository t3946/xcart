<?php


namespace Modules\Order\Controllers;


use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\FrontendController;

class RMAController extends FrontendController
{
    public function request($order_id)
    {
        if ($order = OrderModel::objects()->get(['orderid' => $order_id])) {
            $this->display('rma/request.tpl', [
                'order' => $order,
            ]);
        }

    }
}