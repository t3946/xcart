<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\PurchaseOrderModel;
use Modules\PBX\Helpers\AnveoAssignCalls;

class OrderLogHelper
{
    public static function getOrderLogs($order_id): array
    {
        $result = [];
        $logs = OrderLogModel::objects()->filter(['orderid' => $order_id])->order(['-id'])->asArray(true);
        foreach ($logs as $log) {
            $result[(new \DateTime())->setTimestamp($log['date'])->format('Y-m-d H:i:s').$log['id']] = $log;
        }

        if ($pos = PurchaseOrderModel::objects()->filter(['order_id' =>$order_id])->all()) {
            foreach ($pos as $po) {
                foreach ($po->logs->all() as $log) {
                    $result[$log->date] = [
                        'date' => $log->date,
                        'login' => $log->login,
                        'type' => 'X',
                        'log' => $log->log,
                    ];
                }
            }
        }

        $calls_log_data = AnveoAssignCalls::getResource($order_id);
        foreach ($calls_log_data as $call) {
            $result[$call['start_at']] = [
                'date' => $call['start_at'],
                'login' => $call['account'],
                'type' => 'CA',
                'log' => $call['direction']. "<audio controls preload='none' style='width: 100%'><source src=\"{$call['url']}\" type='audio/mp3'></audio>",
            ];
        }
        krsort($result);

        return $result;
    }
}