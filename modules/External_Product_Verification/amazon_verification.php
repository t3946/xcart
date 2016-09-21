<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
global $xcart_dir, $REQUEST_METHOD, $amazon_verification_maximum_mistakes;
require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationProductsQueue.php";

$top_message = [];
if ($REQUEST_METHOD == 'POST') {

    if (!empty($position)) {
        foreach ($position as $ikey => $sPosition) {
            $sAsin = '';
            $oProductQueue = new classExternalVerificationProductsQueue(['productid' => $ikey]);
            if ($oProductQueue->getProductId()) {
                if (!empty($answerasin[$ikey])) {
                    $sAsin = implode(',', $answerasin[$ikey]);
                }
                if (empty($sAsin) && in_array($answer[$ikey], [classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH, classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH])) {
                    $top_message["content"] = func_get_langvar_by_name("lbl_ASIN_not_entered");
                    $top_message["type"] = "E";
                } else {
                    $oProductQueue->updateFields(['status' => $answer[$ikey], 'asin' => $sAsin]);
                }
                $oProductQueue->updateField('position', $position[$ikey]);
            }
        }
    }

    if (!empty($test_sku) && is_array($test_sku)) {
        $oProducts = new classProducts();
        foreach ($test_sku as $idx => $sSKU) {
            $sAsin = '';
            $iProductId = $oProducts->getProductIdBySKU($sSKU);
            if (!empty($iProductId)) {
                $oProductQueue = new classExternalVerificationProductsQueue(['productid' => $iProductId]);
                if (!empty($test_asin[$idx])) {
                    $sAsin = implode(',', $test_asin[$idx]);
                    $oProductQueue->updateField('asin', $sAsin);
                }
                $oProductQueue->updateField('position', $test_position[$idx]);
                if ($oProductQueue->getProductId()) {
                    $oProductQueue->updateStatus($correct_answer[$idx]);
                } else {
                    if (!empty($correct_answer)) {
                        if (empty($sAsin) && in_array($correct_answer[$idx], [classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH, classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH])) {
                            $top_message["content"] = func_get_langvar_by_name("lbl_ASIN_not_entered");
                            $top_message["type"] = "E";
                        } else {
                            $oProductQueue->setField('productid', $iProductId)->setStatus($correct_answer[$idx])->_insert();
                        }
                    }
                }
            }
        }
    }
    if (!empty($etalon_delete) && is_array($etalon_delete)) {
        foreach ($etalon_delete as $id => $etalon) {
            $oProductQueue = new classExternalVerificationProductsQueue(['productid' => $id]);
            if ($oProductQueue->getProductId()) {
                if ($oProductQueue->getCrossVerifyCount() < 2) {
                    $oProductQueue->updateStatus(classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_PROGRESS);
                } else
                    $oProductQueue->updateStatus(classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_VERIFIED);
            }
        }
    }
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_maximum_mistakes . "' WHERE name='amazon_verification_maximum_mistakes' AND category='$option'");

    if (empty($top_message)) {
        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
    }
    func_header_location("configuration.php?option=Amazon_Verification");
}

$aProductsQueue = classExternalVerificationProductsQueue::getProductsQueueEtalon();
$smarty->assign("aProductsQueue", $aProductsQueue);
