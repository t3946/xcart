<?php


namespace Modules\Order\Controllers\Api;


use Modules\Order\Helpers\ExportRenderHelper;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;

class ExportController extends Controller
{
    public function export($order_id): void
    {
        /** @var OrderModel $order */
        if ($order = OrderModel::objects()->get(['orderid' => $order_id])) {
            foreach ($order->groups as $group) {
                if ($rows = ExportRenderHelper::export($group)) {
                    $file = implode(',', array_keys($rows[0])) . "\r\n";
                    foreach ($rows as $row) {
                        $file .= implode(',', $row) . "\r\n";
                    }

                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename="' . $order->getOrderNumber() . '.csv' . '";');
                    echo $file;
                }
            }
        }
    }
}