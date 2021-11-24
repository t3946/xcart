<?php


namespace Modules\Order\Commands;


use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Distributor\Models\DistributorUtilityModel;
use Modules\Mail\Helpers\SendMailHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Commands\Command;

class DispatchOrderCommand extends Command
{
    public const DISPATCH_CB_STATUSES = [
        OrderStatusModel::ORDER_STATUS_COMPLETED,
        OrderStatusModel::ORDER_STATUS_UNPAID_PO,
        OrderStatusModel::ORDER_STATUS_PENDING_PARTIAL_REFUND,
        OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
    ];
    public const DISPATCH_DC_STATUSES = [
        OrderStatusModel::ORDER_DC_STATUS_PENDING_DISPATCH,
    ];

    public function handle($arguments = [])
    {
        $m = OrderGroupModel::objects()->filter([
            'cb_status__in' => self::DISPATCH_CB_STATUSES,
            'dc_status__in' => self::DISPATCH_DC_STATUSES,
            'manufacturer__submit_to_operator' => 'by_email_or_and_fax',
        ]);
        /** @var OrderGroupModel $group */
        foreach ($m as $group) {
            $dx = $group->manufacturer;
            $order = $group->order;
            if (!$dx->allow_dispatch_off_working_hours || !$dx->isGoodTimeToSendEmail()) {
                continue;
            }
            if (!$template = $dx->order_submit_template) {
                func_backprocess_log("Auto_dispatch_cron", "{$dx->code} dispatch template not set");
                continue;
            }
            $template->message_body = (string)$group->off_hours_message ?: $template->message_body;

            $to = DistributorHelper::getDistributorEmails($dx, DistributorUtilityModel::DISPATCH_UTILITY);

            $to[] = 'orders@s3stores.com';
            $to = array_unique(array_map('trim', $to));

            SendMailHelper::sendTemplate($to, $template, $group);

            $new_status = OrderStatusModel::objects()->get(['code' => OrderStatusModel::ORDER_DC_STATUS_RECEIVED_BY_DISPATCHED]);
            $log = "{$dx->code}: Send (Dispatch to distributor) CRON \n dc_status: {$group->dc_status_model} -> {$new_status} \n";

            $group->setAttributes([
                'dc_status' => OrderStatusModel::ORDER_DC_STATUS_RECEIVED_BY_DISPATCHED,
                'dc_dispatched_time' => time()
            ]);
            $group->save();

            OrderLogModel::createLog(
                $group->order->orderid,
                OrderLogModel::LOG_TYPE_XCART,
                nl2br($log)
            );

            $log_text = "<a href='{$order->getAdminUrl()}' target='_blank' style='color: blue;'>{$order->getOrderNumber()}</a>, {$dx->code} - dispatched by cron";
            func_backprocess_log("Auto_dispatch_cron", $log_text);
        }
        print "Done!\n";
    }
}