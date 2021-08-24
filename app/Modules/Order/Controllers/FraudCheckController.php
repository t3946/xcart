<?php
namespace Modules\Order\Controllers;

use Modules\Admin\Controllers\BackendController;

class FraudCheckController extends BackendController
{
    public function index(int $order_id = null)
    {
        echo $this->renderInSmarty('fraud_check/fraud_base_v2.tpl', [
            'order_id' => $order_id
        ]);
    }
}