<?php


namespace Modules\Order\Commands;


use DateTime;
use DateTimeZone;
use Xcart\App\QueryBuilder\Expression;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTrackingModel;
use Xcart\App\Commands\Command;

class TrackingConvertCommand extends Command
{

    public function handle($arguments = [])
    {
        foreach (OrderGroupModel::objects()->filter([
            'order__date__gte' => new Expression('UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 3 YEAR))'),
            'dc_status__in' => [OrderStatusModel::ORDER_DC_STATUS_SHIPPED]])->order(['orderid']) as $group) {
            foreach ($group->tracking as $track) {
                if (!$t_shipdate = $track['shipping_date']) {
                    $t_shipdate = DateTime::createFromFormat('m/d/Y H:i:s', "{$track['ship_date']} 00:00:00", new DateTimeZone('EST'));
                }
                $tri = [
                    'linkid' => $track['linkid'] ?: null,
                    'tracknum' => $track['tracknum'],
                    'shipping_date' => $t_shipdate ?? $group->order->date,
                    'carrier_id' => $track['carrier_id'] ?? 1,
                    'order_group_id' => $group->order_group_id,
                    'send_to_amazon' => $track['send_to_amazon'] === 'Y' ? 1 : 0
                ];
                $trackingModel = new OrderTrackingModel($tri);
                $trackingModel->save();
            }
        }
    }
}