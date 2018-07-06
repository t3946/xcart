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

        if (!$email || trim($email) == ''){
            Xcart::app()->flash->error("No orders found");
        }

        /** @var OrderModel $order_model */
        if ($order_models = OrderModel::objects()->filter(['email' => $email])->all() ) {
            Xcart::app()->flash->success("Please, check your email");

            foreach ($order_models as $order_model) {
                OrderInvoiceHelper::sendOrderStatusNotification($order_model, false);
            }
        }
        else {
            Xcart::app()->flash->error("No orders found");
        }

        $this->redirect('/');
    }
}