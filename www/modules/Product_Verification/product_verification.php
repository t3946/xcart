<?php

use Modules\Order\Models\OrderModel;
use Modules\Goods\Models\VerificationStatusModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\Order;

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

/** @var OrderModel[] $aOrders */
$aOrders = OrderModel::objects()
    ->filter(
        [
            'vn_status__isnt' => Order::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED,
            'order_type' => 'XCART'
        ]
    )->exclude(
        [
            'cb_status__in' => [
                OrderStatusModel::ORDER_STATUS_CANCELED,
                OrderStatusModel::ORDER_STATUS_DECLINED,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
                OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4,
                ]
        ]
    )->all();

if ($aOrders) {

    /** @var OrderModel[] $aManufacturers */
    $aManufacturers = [];
    foreach ($aOrders as $oOrder) {
        if ($aOrderProducts = $oOrder->getProducts())
        foreach ($aOrderProducts as $oProduct) {
            if ($oProduct->forsale === 'Y') {
                $aManufacturers[$oProduct->manufacturerid][$oOrder->orderid] = $oOrder;
            }
        }
    }

    foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {
        $aProducts = [];
        foreach ($aManufacturer as $oOrderManufacturer) {
            $aOrderManufacturerProducts = $oOrderManufacturer->getProducts();
            if (!empty($aOrderManufacturerProducts))
            foreach ($aOrderManufacturerProducts as $oProduct) {
                if ($oProduct->forsale === 'Y') {
                    if ($oProduct->manufacturerid == $iManufacturerId) {
                        if (!\in_array($oProduct->productid, $aProducts)) {
                            $aProducts[] = $oProduct->productid;
                        }
                    }
                }
            }
        }
    }

    $aVerifyStatuses = VerificationStatusModel::objects()->all();

    $smarty->assign('aVerifyStatuses',$aVerifyStatuses);
    $smarty->assign('aManufacturers',$aManufacturers);

}

$smarty->assign("main","product_verification");
