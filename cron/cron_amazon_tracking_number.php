<?php

use Modules\Amazon\Helpers\AmazonFbaFeedHelper;
use Modules\Amazon\Helpers\AmazonFbaOutboundHelper;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTrackingModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;

define('CIDEV_CRON_START', 'CRON');

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

global $config;

$log_category = 'cron_amazon_tracking_number';

$start_time = new DateTime('now');
$log_text = ' * * *  Cron started  * * * ';
func_backprocess_log($log_category, $log_text);

print $log_text . PHP_EOL;

$ogModels = OrderGroupModel::objects()->filter(['amz_fullfilment_order_placed' => 'Y'])->all();
if ($ogModels) {
    func_backprocess_log($log_category, $log = sprintf("Processing %d Send by Amazon orders", count($ogModels)));

    print $log . PHP_EOL;

    $amzPool = new AmazonPoolStore();
    $oClientPack = $amzPool->getFbaOutboundClientPack();
    /** @var OrderGroupModel $ogm */
    foreach ($ogModels as $ogm){
        $tracks = [];
        try {
            $res = AmazonFbaOutboundHelper::getFulfillmentOrderTrackingNumbers($oClientPack->callGetFulfillmentOrder($ogm->getAmazonShippingOrderId()));
            if ($res) {
                $tracks = $ogm->trackings->valuesList('tracknum', true);

                foreach ($res as $amTrack) {
                    if (!in_array($amTrack['track_number'], $tracks, true)) {
                        if ($carrierModel = TrackingLinksCarrierModel::objects()->get(['carrier' => $amTrack['carrier_code']])) {
                            $sh = $carrierModel->carrier_id;
                        }
                        $tri = [
                            'tracknum' => $amTrack['track_number'],
                            'shipping_date' => $amTrack['shipping_date'],
                            'carrier_id' => $sh,
                            'order_group_id' => $ogm->order_group_id
                        ];
                        [$trackingModel, $isNew] = OrderTrackingModel::objects()->getOrCreate($tri);
                        if ($isNew) {
                            func_send_order_status_notification($ogm->orderid, OrderStatusModel::ORDER_DC_STATUS_SHIPPED, true);
                        }
                    }
                }
            }
        } catch(FBAOutboundServiceMWS_Exception $e){
            print($e->getMessage() . PHP_EOL) ;
            func_backprocess_log($log_category, "callGetFulfillmentOrder error. OrderId: {$ogm->orderid}. " . $e->getMessage());
        }
    }
}

$ogModels = OrderModel::objects()
    ->exclude(['groups__trackings__send_to_amazon' => 1])
    ->filter(['amazon_fulfillment_channel' => 'MFN'])
    ->all();
if ($ogModels) {
    func_backprocess_log($log_category, $log = sprintf('Processing %d MFN orders', count($ogModels)));

    print $log . PHP_EOL;

    foreach($ogModels as $order) {
        if ($groups = $order->groups){
            /** @var OrderGroupModel $group */
            foreach($groups as $group){
                try {
                        foreach ($group->trackings as $key => $track) {
                            if (!$track->send_to_amazon) {
                                $feedResult = AmazonFbaFeedHelper::sendTrackingCodeToAmazon($track);
                                $track->send_to_amazon = true;
                                $track->save();
                            }
                        }
                } catch (MarketplaceWebService_Exception $e) {
                    print($e->getMessage());
                    func_backprocess_log($log_category, $e->getMessage());
                }
            }
        }
    }
}



//Xcart\Config::model(['name' => $log_category])->setValue('N')->_update();

$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log($log_category, $log_text);

print $log_text . PHP_EOL;
print "Done." . PHP_EOL;