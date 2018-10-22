<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderLogModel;

class OrderLogHelper
{
    public static function getOrderLogs($order_id): array
    {
        $result = [];
        $logs = OrderLogModel::objects()->filter(['orderid' => $order_id])->order(['-id'])->asArray(true);
        foreach ($logs as $log) {
            $result[(new \DateTime())->setTimestamp($log['date'])->format('Y-m-d H:i:s').$log['id']] = $log;
        }

        $calls_log_data = \Modules\PBX\Helpers\AnveoAssignCalls::getResource($order_id);
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