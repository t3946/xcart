<?php
global $xcart_dir;
require './auth.php';
require '../include/security.php';
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

switch ($_POST['ajax_action']) {
    case "change_verify_product_status" :
        changeVerifyProductStatus($_POST);
        break;
}

function changeVerifyProductStatus($aPostParam = [])
{
    $bResult = [];
    $iProductId = (int)$aPostParam['product_id'];
    $iBatchId = (int)$aPostParam['batch_id'];
    $sStatus = $aPostParam['verify_status_id'];
    $sASIN = $aPostParam['asin'];
    $sNote = $aPostParam['note_text'];
    $aConclusion = $aPostParam['conclusion_buttons'];
    if (!empty($iProductId) && !empty($iBatchId) && !empty($sStatus)) {
        $bResult = classExternalVerificationBatch::model(['batch_id'=>$iBatchId])->updateVerificationStatus(['product_id'=>$iProductId, 'batch_id'=>$iBatchId, 'status'=>$sStatus, 'note'=>$sNote, 'asin'=>$sASIN, 'aConclusion'=>$aConclusion]);
    }
    print(json_encode($bResult));
}