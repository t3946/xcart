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

}

function changeVerifyProductStatus($aPostParam = array())
{
    $bResult = [];
    $iProductId = (int)$aPostParam['product_id'];
    $aOrders = explode(',', $aPostParam['order_id']);
    $iStatusId = (int)$aPostParam['verify_status_id'];
    $sNote = $aPostParam['note_text'];
    if (!empty($iProductId)) {
        $oProduct = new classProduct($iProductId);
        $bResult = $oProduct->changeVerificationStatus($iStatusId, $sNote, true, $aOrders);
        if (!empty($aOrders)) {
            foreach ($aOrders as $iOrderId) {
                $oOrder = new classOrder($iOrderId);
                $oOrder->updateVerificationStatus($sNote);
            }
        }
    }
    print(json_encode($bResult));
}

function changeVerifyOrderStatus($aPostParam = array())
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