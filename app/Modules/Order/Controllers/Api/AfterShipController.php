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
            $order = OrderModel::objects()->get(['orderid' => $params['msg']['order_id']])))
        {
            if ($group = $order->groups->get(['trackings__tracknum' => $params['msg']['tracking_number']])) {

                $current_dc_status_value = $group->dc_status_model->name;
                $group->dc_status = OrderStatusModel::ORDER_DC_STATUS_DELIVERED;
                $group->save();
                $new_value = $group->dc_status_model->name;
                $log = "<b>{$group->manufacturer->code}:</b> dc_status: {$current_dc_status_value} -> {$new_value}\n";
                (new OrderLogModel([
                    'orderid' => $order->orderid,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => $log,
                ]))->save();

            }
            if ($order->groups->exclude(['dc_status' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED])->count() === 0) {
                $order->dc_status = OrderStatusModel::ORDER_DC_STATUS_DELIVERED;
                $order->save();
            }
        }
    }
}