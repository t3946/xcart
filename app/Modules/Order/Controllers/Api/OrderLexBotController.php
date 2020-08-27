<?php


namespace Modules\Order\Controllers\Api;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class OrderLexBotController extends Controller
{
    public function index(): void
    {
        $res = 'Order not found';

        $order_number = Xcart::app()->request->get->get('order_number');
        $zip = Xcart::app()->request->get->get('zipcode');
        /** @var OrderModel $orderModel */
        if ($order_number &&
            preg_match('/(\w{2}-)?(\d{6,})/', $order_number, $matches) &&
            ($orderId = $matches[2]) &&
            $orderModel = OrderModel::objects()->get(['orderid' => $orderId, 's_zipcode__startswith' => $zip])) {
            $res = 'Your order is ';
            switch ($orderModel->dc_status) {
                case OrderStatusModel::ORDER_DC_STATUS_SHIPPED :
                case OrderStatusModel::ORDER_DC_STATUS_DELIVERED :
                    if ($tracks = $orderModel->getTrackingNumbers()) {
                        foreach ($tracks as $key => $track) {
                            if ($key) {
                                $res .= "; ";
                            }
                            $res .= $track;
                        }
                    } else {
                        $res .= 'Shipped';
                    }
                    break;
                case OrderStatusModel::ORDER_DA_STATUS_DISPATCHED :
                case OrderStatusModel::ORDER_DC_STATUS_RECEIVED_BY_DISTRIBUTOR :
                case OrderStatusModel::ORDER_DC_STATUS_RECEIVED_BY_AMAZON :
                    $res .= 'Received By Distributor';
                    break;
                default:
                    $res = "Currently our warehouse requires 1-2 days to process your order and prepare the items if there is no additional production time required. 
We'll email you the link to your tracking information once your parcel has left our warehouse. If you haven’t received the tracking number within 2 business days please contact our Customer Care Team";
                    break;
            }
        }
        $this->jsonResponse(['message' => $res ?? '']);
    }
}