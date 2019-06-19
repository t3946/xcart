<?php


namespace Modules\Order\Controllers\Api;


use Modules\Order\Helpers\OrderReconciliationHelper;
use Xcart\App\Controller\Controller;

class ReconciliationController extends Controller
{
    public function actionPayableManufacturers()
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableManufacturers($request->post->all());
        $this->jsonResponse($data);
    }

    public function actionPayableOrders()
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableOrders($request->post->all());
        if ($data) {
            echo $this->render('reconciliation/order_payable.tpl', ['orders' => $data]);
        }
    }
}