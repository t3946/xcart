<?php

use Modules\Amazon\Helpers\AmazonFbaOutboundHelper;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Order\Models\OrderGroupModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;

define("CIDEV_CRON_START", "CRON");

require "../top.inc.php";
require "../init.php";

global $config;

$log_category = 'cron_amazon_tracking_number';

if ($config[$log_category] == "Y") {
    func_backprocess_log($log_category, 'Already launched');
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log_category);
    $oMail->body = $log_category . ' already launched';
    $oMail->sendEmail();
   // die("Already launched"); // ################################
}

db_query_param("REPLACE xcart_config SET value='Y', name=:log_category", ['log_category' => $log_category]);

$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

$ogModels = OrderGroupModel::objects()->filter(['amz_fullfilment_order_placed' => 'Y'])->all();
if ($ogModels) {
    $amzPool = new AmazonPoolStore();
    $oClientPack = $amzPool->getFbaOutboundClientPack();
    /** @var OrderGroupModel $ogm */
    foreach ($ogModels as $ogm){
        $tracks = [];
        try {
            $res = AmazonFbaOutboundHelper::getFulfillmentOrderTrackingNumbers($oClientPack->callGetFulfillmentOrder($ogm->getDataModel()->getAmazonShippingOrderId()));
            if (!empty($res)) {
                if (!empty($ogm->tracking)) {
                    $tracks = array_map(function ($a) {
                        return $a['tracknum'];
                    }, $ogm->tracking);
                }
                foreach ($res as $amTrack) {
                    if (!in_array($amTrack['track_number'], $tracks)) {
                        $old = $ogm->tracking;
                        $newTrack = [
                            'tracknum' => $amTrack['track_number'],
                            'ship_date' => $amTrack['shipping_date']->format('m/d/Y'),
                        ];
                        if ($carrierModel = TrackingLinksCarrierModel::objects()->get(['carrier' => $amTrack['carrier_code']])) {
                            $newTrack['carrier_id'] = $carrierModel->carrier_id;
                        }
                        $old[] = $newTrack;
                        $ogm->tracking = $old;

                        $ogm->dc_status = 'S';
                        //func_send_order_status_notification($ogm->orderid, $ogm->dc_status);

                        func_backprocess_log($log_category, "Add tracking number {$amTrack['track_number']} in order {$ogm->orderid}");

                        $ogm->save();
                    }
                }
            }
        } catch(FBAOutboundServiceMWS_Exception $e){
            func_backprocess_log($log_category, "callGetFulfillmentOrder error. OrderId: {$ogm->orderid}. " . $e->getMessage());
        }
    }
}

Xcart\Config::model(['name' => $log_category])->setValue('N')->_update();

$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log($log_category, $log_text);

print "Done.";