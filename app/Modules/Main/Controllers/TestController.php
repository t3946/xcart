<?php

namespace Modules\Main\Controllers;


use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\FrontendController;

class TestController extends FrontendController
{
    public function actionTest(): void
    {
        $orders = OrderModel::objects()->filter(['paymentid' => 2, 'details__isnt' => ''])->limit(500)->order(['-orderid'])->all();
        foreach ($orders as $order) {
            $po = [];
            $rows = explode( "\n", text_decrypt($order->details));

            foreach ($rows as $row) {
                [$name, $value] = explode( ':', trim($row));
                $name = str_replace(array(" ", "po_fax"), array("_", "purchase_manager_phone"), $name);

                $po[trim(strtolower($name))] = trim($value);
            }

            [$extra] = OrderExtraModel::objects()->getOrCreate(['order_id' => $order->orderid]);
            $extra->purchase_order = $po;
            $extra->save();
            echo $order->orderid. "\n";
        }
    }
}