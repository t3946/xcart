<?php


namespace Modules\Order\Commands;


use Mindy\QueryBuilder\Expression;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class AbandonedOrderCommand extends Command
{

    public function handle($arguments = [])
    {
        /** @var OrderModel $order */
        foreach (OrderModel::objects()->filter([
            'date__lte' => new Expression('UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 22 HOUR))'),
            'order_type' => OrderModel::ORDER_TYPE_XCART,
            'cb_status__in' => [
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4,
                OrderStatusModel::ORDER_STATUS_NOT_FINISHED,
                OrderStatusModel::ORDER_STATUS_FAILED,
            ]])->order(['-orderid']) as $order) {

            if ($order->total < 40 || OrderHelper::hasCustomerSiblingsOrders($order)) {
                $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_DECLINED]);
                $order->cb_status = OrderStatusModel::ORDER_STATUS_DECLINED;
                $order->save();
                (new OrderLogModel([
                    'orderid' => $order->orderid,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => 'Abandoned: The order has been declined',
                ]))->save();
                echo "Abandoned: The order {$order->getOrderNumber()} has been declined\n";
                continue;
            }

            $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_UNPAID]);
            $order->cb_status = OrderStatusModel::ORDER_STATUS_UNPAID;
            $order->save();
            (new OrderLogModel([
                'orderid' => $order->orderid,
                'type' => OrderLogModel::LOG_TYPE_XCART,
                'log' => 'Abandoned: Unpaid notification sent to Cx',
            ]))->save();
            echo "Abandoned: Unpaid notification sent to Cx {$order->getOrderNumber()}\n";

            OrderInvoiceHelper::sendOrderStatusNotification($order, false);
        }
    }
}