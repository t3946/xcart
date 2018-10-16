<?php

use CaponicaAmazonMwsComplete\AmazonClient\FbaInboundClient;
use Modules\Amazon\Models\AmazonListInboundShipment;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;

define('CIDEV_CRON_START', 'CRON');
session_start();
set_time_limit(0);

require __DIR__ . DIRECTORY_SEPARATOR . '../www/top.inc.php';
require __DIR__ . DIRECTORY_SEPARATOR . '../www/init.php';

global $config;

const LOG_CATEGORY = 'cron_amazon_list_inbound_shipment_items';

$start_time = new DateTime('now');
$log_text = ' * * *  Cron started  * * * ';
func_backprocess_log(LOG_CATEGORY, $log_text);

$cl_ver = FbaInboundClient::MWS_CLIENT_VERSION; //use for autoload Amazon library
$oAmazon = new \Xcart\AmazonMWS('FBAInboundServiceMWS_Client', '/FulfillmentInboundShipment/2010-10-01');
$oAmazon
    ->_Request('GetListInboundItems')
    ->_Request('GetListInboundShipments');

/** @var AmazonListInboundShipment[] $aShipments */
$aShipments = AmazonListInboundShipment::objects()->filter(['shipment_status__raw' => "NOT IN ('DELETED', 'ERROR')"])->order(['shipment_name']);
foreach ($aShipments as $shipment) {
    $log = '';

    echo "Processing {$shipment->shipment_name} shipment - {$shipment->shipment_status}\n";

    if (!$warehouse = $shipment->warehouse) {
        continue;
    }
    if (!$items = $shipment->items->all()) {
        continue;
    }

    /** @var OrderModel $order */
    $order = $shipment->order_id ? OrderModel::objects()->get(['orderid' => $shipment->order_id]) : new OrderModel;
    $is_new_order = $order->getIsNewRecord();


    switch ($shipment->shipment_status) {
        case AmazonListInboundShipment::SHIPMENT_STATUS_WORKING:
            $d2a_status = OrderStatusModel::ORDER_DA_STATUS_PENDING_ORDER_ENTRY;
            break;
        case AmazonListInboundShipment::SHIPMENT_STATUS_CANCELLED:
        case AmazonListInboundShipment::SHIPMENT_STATUS_DELETED:
        case AmazonListInboundShipment::SHIPMENT_STATUS_ERROR:
            $d2a_status = OrderStatusModel::ORDER_DA_STATUS_NOT_SHIPPED;
            break;
        default:
            $d2a_status = OrderStatusModel::ORDER_DA_STATUS_SHIPPED;
            break;
    }

    $order->setAttributes([
        'order_prefix' => 'FB-',
        's_address' => "{$warehouse->address}\n({$shipment->destination_fulfillment_center_id})",
        's_city' => $warehouse->city,
        's_state' => $warehouse->state_model->code,
        's_country' => $warehouse->country_model->code,
        's_zipcode' => $warehouse->zipcode_model->zip,
        's_firstname' => 'Amazon.com',
        'firstname' => 'Amazon.com',
        'fraud_status' => 'S',
        'order_type' => OrderModel::ORDER_TYPE_FB,
        'bd_status' => $order->bd_status ?? OrderStatusModel::ORDER_BD_STATUS_UNPAID,
        'd2a_status' => $d2a_status,
        'phone' => '1-800-929-2431',
        'email' => 'orders@s3stores.com',
        'vn_status' => OrderStatusModel::ORDER_VN_STATUS_VERIFIED,
    ]);
    $order->save();

    if ($is_new_order) {
        echo "Create order # {$order->getOrderNumber()}\n";
        $log_message = "<a style=\"color: #1411FF;\" href=\"https://sellercentral.amazon.com/gp/fba/inbound-shipment-workflow/index.html/ref=ag_fbaisw_name_fbasqs#{$shipment->shipment_id}\" target=\"_blank\">Amazon FBA Shipment # {$shipment->shipment_name}</a>";
        (new OrderLogModel([
            'orderid' => $order->orderid,
            'type' => OrderLogModel::LOG_TYPE_CUSTOMER,
            'login' => '',
            'log' => $log_message
        ])
        )->save();
    }

    $groups = [];

    foreach ($items as $item) {
        /** @var ProductModel $product */
        if ($product = $item->product) {
            if ($order->storefrontid === null && $site = $product->sites->limit(1)->get()) {
                $order->storefrontid = $site->storefrontid;
            }

            if (!$groups[$product->manufacturerid]) {
                [$groups[$product->manufacturerid], $is_new] = OrderGroupModel::objects()->getOrNew(['orderid' => $order->orderid, 'manufacturerid' => $product->manufacturerid]);
                $groups[$product->manufacturerid]->bd_status = $order->bd_status;
                $groups[$product->manufacturerid]->d2a_status = $order->d2a_status;
                $groups[$product->manufacturerid]->total_gross = $groups[$product->manufacturerid]->total_net = 0;

                if ($is_new) {
                    $groups[$product->manufacturerid]->save();
                }
            }

            /** @var OrderDetailModel $detail */
            [$detail] = OrderDetailModel::objects()->getOrNew(['orderid' => $order->orderid, 'order_group_id' => $groups[$product->manufacturerid]->order_group_id, 'productid' => $product->productid]);
            $detail->setAttributes([
                'price' => $product->cost_to_us,
                'amount' => $shipment->shipment_status === 'CLOSED' ? $item->quantity_received : $item->quantity_shipped,
                'provider' => $shipment->shipment_name,
                'productcode' => $product->productcode,
                'product' => $product->getFrontendName(),
                'original_provider' => $product->original_provider,
                'item_cost_to_us' => $product->cost_to_us,
            ]);

            if (($amount = (float)$detail->amount) != ($old_amount = (float)$detail->getOldAttribute('amount'))) {
                $log .= "<b>{$detail->productcode}</b>: amount: {$old_amount} -> {$amount}\n";
            }

            $detail->save();
            $groups[$product->manufacturerid]->total_gross += $detail->price * $item->quantity_shipped;
            $groups[$product->manufacturerid]->total_net = $groups[$product->manufacturerid]->total_gross;
        }
    }

    $order->total = $order->subtotal = 0;
    foreach ($groups as $group) {
        $group->total_gross = (string) $group->total_gross;
        $group->total_net = (string) $group->total_net;
        $group->save();
        $order->subtotal += $group->total_gross;
        $order->total = $order->subtotal;
    }
    $order->save();

    $shipment->order_id = $order->orderid;
    $shipment->save();

    if ($log) {
        (new OrderLogModel([
            'orderid' => $order->orderid,
            'type' => OrderLogModel::LOG_TYPE_XCART,
            'log' => nl2br($log),
        ]))->save();
    }
}

//Xcart\Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");
