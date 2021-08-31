<?php
namespace Modules\Order\Controllers;

use Modules\Admin\Controllers\BackendController;
use Modules\Order\Models\OrderModel;
use Xcart\App\Main\Xcart;

class FraudCheckController extends BackendController
{
    public function index(int $order_id = null)
    {
        /** @var OrderModel $order_model */
        $order_model = OrderModel::objects()->get(['orderid' => $order_id]);
        Xcart::app()->breadcrumbs->add('Orders Management', '/orders.php');
        Xcart::app()->breadcrumbs->add('Order details', "/admin/order.php?orderid=$order_id");
        Xcart::app()->breadcrumbs->add("Fraud check for order # $order_model->order_prefix$order_id");

        echo $this->renderInSmarty('fraud_check/fraud_base_v2.tpl', [
            'order_id' => $order_id
        ]);
    }
}