<?php

namespace Modules\Order\Controllers;

use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class RetrieveOrderController extends FrontendController
{
    public function retrieveOrder()
    {
        $request = $this->getRequest();

        $email = $request->post->get('email');

        /** @var OrderModel $order_model */
        if ($order_model = OrderModel::objects()->get(['email' => $email]) ) {
            Xcart::app()->flash->success("Check your email");

            OrderInvoiceHelper::sendOrderStatusNotification($order_model, false);

        }
        else {
            Xcart::app()->flash->error("You haven't invoice");
        }

        $this->redirect('/');
    }
}