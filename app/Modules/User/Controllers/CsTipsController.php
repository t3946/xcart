<?php


namespace Modules\User\Controllers;

use Modules\Order\Models\OrderModel;
use Modules\User\Models\CsTipsModel;
use Xcart\App\Controller\FrontendController;

class CsTipsController extends FrontendController
{
    const PRIVATE_KEY = "y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=";
    const PUBLIC_KEY = "2r7bQsPMLds=";


    public function index()
    {

        echo $this->render('layout/thank_for_order.tpl',[
            'meta' => self::PUBLIC_KEY
        ]);

        $CsTipsModel = new CsTipsModel();

        $CsTipsModel->encrypt = base64_decode($_GET['e']);
        $orderid = $CsTipsModel->getOrderId();

        $order_model = OrderModel::objects()->get(['orderid' => $orderid]);

    }

    public function tipsLog()
    {

    }


}