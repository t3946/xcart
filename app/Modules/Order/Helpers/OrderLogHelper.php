<?php

namespace Modules\Order\Helpers;


use Exception;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\PurchaseOrderModel;
use Modules\PBX\Helpers\AnveoAssignCalls;
use Xcart\App\Main\Xcart;

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
                'log' => "{$call['direction']} from {$call['e164']} <audio controls preload='none' style='width: 100%'><source src=\"{$call['url']}\" type='audio/mp3'></audio>",
            ];
        }
        krsort($result);

        return $result;
    }

    public static function sendOrderNote(OrderModel $order, $message)
    {
        /** @var OrderModel $order */
        /** @var AttentionTagModel $model */

        $subj = "{$order->getOrderNumber()} note: {$message}";
        (new OrderLogModel([
            'orderid' => $order->orderid,
            'type' => OrderLogModel::LOG_TYPE_XCART,
            'log' => $message,
            'login' => Xcart::app()->user->login
        ]))->save();

        $site_model = $order->site;
        $config = $site_model->getGlobalConfig();

        try {
            Xcart::app()->mail->raw(
                'orders@s3stores.com',
                $subj,
                $message,
                [
                    'from' => 'helpdesk@s3stores.com',
                    'headers' => [
                        'X-Xcart-Label' => 'order-logs'
                    ]
                ]
            );
        } catch (Exception $exception) {
            Xcart::app()->logger->error($exception->getMessage(), $config ?? [], 'email');
        }

        if ($config && $config['order_note_tag']) {
            OrderHelper::setOrderTag($order->orderid, $config['order_note_tag']);
        }
    }
}