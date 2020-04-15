<?php


namespace Modules\Order\Controllers\Api;


use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AfterShipController extends Controller
{
    public function webHook()
    {
        $json = file_get_contents('php://input');
        Xcart::app()->logger->debug($json, [], 'afterShip');
        if (($params = json_decode($json, true)) &&
            isset($params['msg']) &&
            $params['msg']['order_id'] &&
            $params['msg']['tag'] === 'Delivered' &&
            $order = OrderModel::objects()->get(['orderid' => $params['msg']['order_id']]))
        {
            $order->groups->update([
                'dc_status' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED,
            ]);
            $order->dc_status = OrderStatusModel::ORDER_DC_STATUS_DELIVERED;
            $order->save();
            (new OrderLogModel([
                'orderid' => $order->orderid,
                'type' => OrderLogModel::LOG_TYPE_XCART,
                'log' => 'Order has been delivered',
            ]))->save();
        }
    }
}