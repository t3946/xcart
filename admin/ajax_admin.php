<?php
require './auth.php';
require '../include/security.php';
require_once '../include/class/classProducts.php';
require_once '../include/class/classOrder.php';


switch ($_POST['ajax_action']) {
    case "change_verify_product_status" :
        changeVerifyProductStatus($_POST);
        break;
    case "change_verify_order_status" :
        changeVerifyOrderStatus($_POST);
        break;
    case "ship_order_by_amazon" :
        shipOrderByAmazon($_POST);
}

function changeVerifyProductStatus($aPostParam = [])
{
    $bResult = [];
    $iProductId = (int)$aPostParam['product_id'];
    $aOrders = explode(',', $aPostParam['order_id']);
    $iStatusId = (int)$aPostParam['verify_status_id'];
    $sNote = $aPostParam['note_text'];
    if (!empty($iProductId)) {
        $oProduct = new classProduct($iProductId);
        $bResult = $oProduct->changeVerificationStatus($iStatusId, $sNote);
        if (!empty($aOrders)) {
            foreach ($aOrders as $iOrderId) {
                $oOrder = new classOrder($iOrderId);
                $oOrder->updateVerificationStatus();
            }
        }
    }
    print(json_encode($bResult));
}

function changeVerifyOrderStatus($aPostParam = [])
{
    $bResult = [];
    $iOrderId = (int)$aPostParam['order_id'];
    $sOrderStatus = $aPostParam['order_verify_status'];
    if (!empty($iOrderId)) {
        $oOrder = new classOrder($iOrderId);
        $bResult = $oOrder->changeVerificationStatus($sOrderStatus);
    }
    print(json_encode($bResult));
}

function shipOrderByAmazon($aPostParam = [])
{
    $iOrderId = (int) $aPostParam['orderid'];
    $iManufacturerid = (int) $aPostParam['manufacturerid'];
    $oOrderGroup = new classOrderGroup(['orderid'=>$iOrderId,'manufacturerid'=>$iManufacturerid]);
    $sAmazonShipmentNotesSend = $aPostParam['submit_amazon_shipment_with_notes'];
    if ($sAmazonShipmentNotesSend) {
        $sAmazonShipmentNotes = $aPostParam['submit_amazon_shipment_notes'];
        $oOrderGroup->updateAmazonShipmentWithNotes('Y');
        $oOrderGroup->updateAmazonShipmentNotes($sAmazonShipmentNotes);
    }
    //$oOrderGroup->shipOrderGroupByAmazon();
}