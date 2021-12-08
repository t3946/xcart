<?php


namespace Modules\Order\Commands;


use Modules\Distributor\Models\DistributorUtilityModel;
use Modules\Mail\Helpers\SendMailHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Commands\Command;
use Modules\Distributor\Helpers\DistributorHelper;

class RequestAvailabilityCommand extends Command
{
    public const REQUEST_AVAILABILITY_CB_STATUSES = [
        OrderStatusModel::ORDER_STATUS_COMPLETED,
        OrderStatusModel::ORDER_STATUS_QUEUED,
        OrderStatusModel::ORDER_STATUS_UNPAID_PO,
        OrderStatusModel::ORDER_STATUS_AUTHORIZED
    ];
    public const REQUEST_AVAILABILITY_DC_STATUSES = [
        OrderStatusModel::ORDER_DC_STATUS_NOT_SHIPPED,
    ];
    public const REQUEST_AVAILABILITY_ORDER_TYPES = [
        OrderModel::ORDER_TYPE_XCART,
        OrderModel::ORDER_TYPE_MFN,
    ];

    public function handle($arguments = [])
    {
        $m = OrderGroupModel::objects()->filter([
            'cb_status__in' => self::REQUEST_AVAILABILITY_CB_STATUSES,
            'dc_status__in' => self::REQUEST_AVAILABILITY_DC_STATUSES,
            'manufacturer__d_availability_must_be_checked' => 'Y',
            'order__order_type__in' => self::REQUEST_AVAILABILITY_ORDER_TYPES,
            'notify_sent' => 'N'
        ]);

        /** @var OrderGroupModel $group */
        foreach ($m as $group) {
            $manufacturer = $group->manufacturer;
            if (($template = $manufacturer->request_avail_template)
                && $manufacturer->isGoodTimeToSendEmail()
            ) {
                $to = DistributorHelper::getDistributorEmails($manufacturer, DistributorUtilityModel::REQUEST_AVAIL_UTILITY);

                $to[] = 'orders@s3stores.com';

                SendMailHelper::sendTemplate($to, $template, $group);

                $current_notify = $group->notify_sent ? 'Y' : 'N';
                $new_status = OrderStatusModel::objects()->get(['code' => OrderStatusModel::ORDER_DC_STATUS_PENDING_AVAIL_CHECK]);

                $log = "Request availability email was sent automatically to {$manufacturer} distributor by CRON \n";
                $log .= "<b>{$manufacturer->code}:</b> dc_status: {$group->dc_status_model} -> {$new_status} \n";
                $log .= "<b>{$manufacturer->code}:</b> notify_sent: {$current_notify} -> Y \n";

                $group->setAttributes(['notify_sent' => true, 'dc_status' => OrderStatusModel::ORDER_DC_STATUS_PENDING_AVAIL_CHECK]);
                $group->save();

                OrderLogModel::createLog(
                    $group->order->orderid,
                    OrderLogModel::LOG_TYPE_SYSTEM,
                    $log
                );
            }
        }
        print"Done!\n";
    }
}