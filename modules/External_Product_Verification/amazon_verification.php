<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
global $xcart_dir, $REQUEST_METHOD, $amazon_verification_maximum_mistakes;
require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationProductsQueue.php";

if ($REQUEST_METHOD == 'POST') {
    if (!empty($test_sku) && is_array($test_sku)) {
        $oProducts = new classProducts();
        foreach ($test_sku as $idx => $sSKU) {
            $iProductId = $oProducts->getProductIdBySKU($sSKU);
            if (!empty($iProductId)) {
                $oProductQueue = new classExternalVerificationProductsQueue(['productid'=>$iProductId]);
                if ($oProductQueue->getProductId()) {
                    if (!empty($correct_answer)) {
                        $oProductQueue->updateStatus($correct_answer[$idx]);
                    }
                } else {
                    if (!empty($correct_answer)) {
                        $oProductQueue->setField('productid', $iProductId)->setStatus($correct_answer[$idx])->_insert();
                    }
                }
            }
        }
    }
    if (!empty($etalon_delete) && is_array($etalon_delete)) {
        foreach ($etalon_delete as $id=>$etalon) {
            $oProductQueue = new classExternalVerificationProductsQueue(['productid'=>$id]);
            if ($oProductQueue->getProductId()) {
                if ($oProductQueue->getCrossVerifyCount()<2) {
                    $oProductQueue->updateStatus(classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_PROGRESS);
                } else
                    $oProductQueue->updateStatus(classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_VERIFIED);
            }
        }
    }
    db_query("UPDATE $sql_tbl[config] SET value='".$amazon_verification_maximum_mistakes."' WHERE name='amazon_verification_maximum_mistakes' AND category='$option'");

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=Amazon_Verification");
}

$aProductsQueue = classExternalVerificationProductsQueue::getProductsQueueEtalon();
$smarty->assign("aProductsQueue", $aProductsQueue);
