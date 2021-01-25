<?php


namespace Modules\Order\Controllers;


use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Helpers\OrderLogHelper;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Controller\FrontendController;

class OrderProcessController extends FrontendController
{
    public function cancel($order_id, $slug)
    {
        /** @var OrderModel $order */
        if ($order = OrderModel::objects()->get(['orderid' => $order_id])) {
            if ($order->getOrderHash() === $slug && $order->cb_status === OrderStatusModel::ORDER_STATUS_UNPAID) {
                $this->display('confirmation/confirmation.tpl', [
                    'model' => $order,
                    'h1' => "Order # {$order->getOrderNumber()} has been deleted from our system.",
                    'content' => "You won't receive any further communication from us.<br/>Have a lovely day!"
                ]);
                $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_CANCELED]);
                $order->cb_status = OrderStatusModel::ORDER_STATUS_CANCELED;
                $order->save();
                (new OrderLogModel([
                    'orderid' => $order->orderid,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => 'Abandoned: The order has been canceled',
                ]))->save();
                OrderInvoiceHelper::sendOrderStatusNotification($order, false);
            } else {
                $this->error(404);
            }
        }
    }


    public function continue($order_id, $slug)
    {
        /** @var OrderModel $order */
        if ($order = OrderModel::objects()->get(['orderid' => $order_id])) {
            if ($order->cb_status === OrderStatusModel::ORDER_STATUS_UNPAID && $order->getOrderHash() === $slug) {
                if ($this->getRequest()->getIsPost()) {
                    if ($message = $this->getRequest()->post->get('message')) {
                        OrderLogHelper::sendOrderNote($order, $message, AttentionTagModel::RESUME_ORDER_TAG);
                    }
                    $this->redirect('order:success');
                }

                $this->display('confirmation/confirmation.tpl', [
                    'model' => $order,
                    'sendMessage' => true,
                    'h1' => "Thank you for your decision to continue with your order # {$order->getOrderNumber()}",
                    'content' => "We'll get back to you shortly.<br/>Have a lovely day!"
                ]);
                $message = 'Customer would like to continue with the order!';
                OrderLogHelper::sendOrderNote($order, $message, AttentionTagModel::RESUME_ORDER_TAG);
                $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_QUEUED]);
                $order->cb_status = OrderStatusModel::ORDER_STATUS_QUEUED;
                $order->save();

            } else {
                $this->error(404);
            }
        }
    }

    public function success()
    {
        $this->display('confirmation/success.tpl', [
            'h1' => 'Thank you for your message!',
        ]);
    }
}