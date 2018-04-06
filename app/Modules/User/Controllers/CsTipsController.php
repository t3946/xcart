<?php


namespace Modules\User\Controllers;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\CsTipsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class CsTipsController extends FrontendController
{
    public function index()
    {
        $request = $this->getRequest();

        $csTipsModel = new CsTipsModel();

        $csTipsModel->order_id = $request->get->get('order');
        $csTipsModel->calculateOrderTips();

        echo $this->render('cs_tips.tpl',[
            'order_id' => $request->get->get('order'),
            'hash' => $request->get->get('hash'),
            'capture_amount' => $csTipsModel->capture_amount,
            'tips' => $csTipsModel->order_tips
        ]);

    }

    public function tipsLog()
    {
        $request = $this->getRequest();

        $csTipsModel = new CsTipsModel();
        $csTipsModel->order_id = $request->post->get('order');
        $csTipsModel->getHash();

        if ($request->post->get('hash') != $csTipsModel->hash){
            $this->getRequest()->redirect("/");
        }

        $globalConfigModel = GlobalConfigModel::objects()->get(['name' => 'tag_customer_tips']);
        $ststus_id = $globalConfigModel->value;
        OrderTagEventHelper::orderTagEvent($ststus_id, $request->post->get('order'));

        $order_log_model = new OrderLogModel();

        $order_log_model->orderid = $csTipsModel->order_id;
        $order_log_model->type = 'C';
        $order_log_model->date = time();
        $order_log_model->login = Xcart::app()->user->login;
        $order_log_model->log = "Customer can get {$request->post->get('cash')} dollars";

        $order_log_model->save();

        echo $this->render('cs_tips_done.tpl', [
            'order_id' => $order_log_model->login,
            'tips' => $order_log_model->log
        ]);
    }


}