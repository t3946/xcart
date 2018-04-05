<?php


namespace Modules\User\Controllers;

use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\CsTipsModel;
use Xcart\App\Controller\FrontendController;

class CsTipsController extends FrontendController
{
    const PRIVATE_KEY = "y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=";
    const PUBLIC_KEY = "2r7bQsPMLds=";


    public function index()
    {
        echo $this->render('cs_tips.tpl',[
            'order_id' => $_GET['e'],
            'tips' => $_GET['v']
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
        $order_log_model->login = 'order_tips';
        $order_log_model->log = "Customer can get {$_GET['v']} dollars";

        $order_log_model->save();

        echo $this->render('cs_tips.tpl', [
            'order_id' => $order_log_model->login,
            'tips' => $order_log_model->log
        ]);
    }


}