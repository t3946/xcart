<?php


namespace Modules\User\Controllers;

use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\CsTipsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class CsTipsController extends FrontendController
{
    const PRIVATE_KEY = "y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=";
    const PUBLIC_KEY = "2r7bQsPMLds=";


    public function index()
    {
        $csTipsModel = new CsTipsModel();
        $csTipsModel->encrypt = $_GET['e'];
        $csTipsModel->decryptOrderId();
        $csTipsModel->calculateOrderTips();



        echo $this->render('cs_tips.tpl',[
            'order_id' => $_GET['e'],
            'tips' => $_GET['v'],
            'capture_amount' => $csTipsModel->capture_amount
        ]);

    }

    public function tipsLog()
    {
        $csTipsModel = new CsTipsModel();
        $csTipsModel->encrypt = $_GET['e'];
        $csTipsModel->decryptOrderId();

        $order_log_model = new OrderLogModel();

        $order_log_model->orderid = $csTipsModel->order_id;
        $order_log_model->type = 'C';
        $order_log_model->date = time();
        /** @var TODO Доработать. Логин указывать у клиента (Взять из сессии) login */
        $order_log_model->login = Xcart::app()->user->login;
        $order_log_model->log = "Customer can get {$_GET['v']} dollars";

        $order_log_model->save();

        echo $this->render('cs_tips_done.tpl', [
            'order_id' => $order_log_model->login,
            'tips' => $order_log_model->log
        ]);
    }


}