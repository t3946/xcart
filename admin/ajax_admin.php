<?php
require './auth.php';
require '../include/security.php';
require_once '../include/class/classProduct.php';



switch ($_POST['ajax_action']) {
    case "change_verify_product_status" :
        change_verify_product_status($_POST);
        break;
}

function change_verify_product_status($aPostParam = array()) {
    $bResult = [];
    $iProductId = (int) $aPostParam['product_id'];
    $iStatusId = (int) $aPostParam['verify_status_id'];
    $sNote = $aPostParam['note_text'];
    if (!empty($iProductId)) {
        $oProduct = new classProduct($iProductId);
        $bResult = $oProduct->changeVerificationStatus($iStatusId, $sNote);
    }
    print(json_encode($bResult));
}