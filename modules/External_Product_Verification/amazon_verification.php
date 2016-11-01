<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
global $xcart_dir, $REQUEST_METHOD, $amazon_verification_maximum_mistakes, $product_names, $product_description, $pack_qty_amazon, $pack_qty_website,
       $test_position, $correct_answer, $amazon_verification_make_conclusion_popup_message, $amazon_verification_product_quantity_popup_message,
       $amazon_verification_product_names_popup_message, $amazon_verification_product_images_popup_message;

$top_message = [];
if ($REQUEST_METHOD == 'POST') {

    if (!empty($position)) {
        foreach ($position as $ikey => $sPosition) {
            $sAsin = '';
            /** @var ExternalVerificationProductsQueue $oProductQueue */
            $oProductQueue = ExternalVerificationProductsQueue::model(['productid' => $ikey]);
            if ($oProductQueue->getProductId()) {
                if (!empty($answerasin[$ikey])) {
                    $sAsin = implode(',', $answerasin[$ikey]);
                }
                if (empty($sAsin) && in_array($answer[$ikey], [ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH, ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH])) {
                    $top_message["content"] = func_get_langvar_by_name("lbl_ASIN_not_entered");
                    $top_message["type"] = "E";
                } else {
                    $aUpdateArray = ['status' => $answer[$ikey], 'asin' => $sAsin];
                    $oProductQueue->setField('status', $answer[$ikey])->setField('asin', $sAsin);
                }

                $oProductQueue->setField('product_image', $product_image[$ikey])->
                setField('product_names', $product_names[$ikey])->
                setField('product_description', $product_description[$ikey])->
                setField('pack_qty_amazon', $pack_qty_amazon[$ikey])->
                setField('pack_qty_website', $pack_qty_website[$ikey])->
                setField('position', $position[$ikey])->_update();
            }
        }
    }

    if (!empty($test_sku) && is_array($test_sku)) {
        $oProducts = new Xcart\Products();
        foreach ($test_sku as $idx => $sSKU) {
            $sAsin = '';
            $iProductId = $oProducts->getProductIdBySKU($sSKU);
            if (!empty($iProductId)) {
                $oProductQueue = ExternalVerificationProductsQueue::model(['productid' => $iProductId]);
                if (!empty($test_asin[$idx])) {
                    $sAsin = implode(',', $test_asin[$idx]);
                    $oProductQueue->setField('asin', $sAsin);
                }
                $oProductQueue->setField('position', $test_position[$idx])->
                setField('product_image', $product_image[$idx])->
                setField('product_names', $product_names[$idx])->
                setField('product_description', $product_description[$idx])->
                setField('pack_qty_amazon', $pack_qty_amazon[$idx])->
                setField('pack_qty_website', $pack_qty_website[$idx]);
                if ($oProductQueue->getProductId()) {
                    $oProductQueue->setStatus($correct_answer[$idx])->_update();
                } else {
                    if (!empty($correct_answer)) {
                        if (empty($sAsin) && in_array($correct_answer[$idx], [ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH, ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH])) {
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
            $oProductQueue = new ExternalVerificationProductsQueue(['productid' => $id]);
            if ($oProductQueue->getProductId()) {
                if ($oProductQueue->getCrossVerifyCount() < 2) {
                    $oProductQueue->updateStatus(ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_PROGRESS);
                } else
                    $oProductQueue->updateStatus(ExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_VERIFIED);
            }
        }
    }
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_maximum_mistakes . "' WHERE name='amazon_verification_maximum_mistakes' AND category='$option'");
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_make_conclusion_popup_message . "' WHERE name='amazon_verification_make_conclusion_popup_message' AND category='$option'");
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_product_quantity_popup_message . "' WHERE name='amazon_verification_product_quantity_popup_message' AND category='$option'");
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_product_names_popup_message . "' WHERE name='amazon_verification_product_names_popup_message' AND category='$option'");
    db_query("UPDATE $sql_tbl[config] SET value='" . $amazon_verification_product_images_popup_message . "' WHERE name='amazon_verification_product_images_popup_message' AND category='$option'");

    if (empty($top_message)) {
        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
    }
    func_header_location("configuration.php?option=Amazon_Verification");
}

$aProductsQueue = ExternalVerificationProductsQueue::getProductsQueueEtalon();
$smarty->assign("aProductsQueue", $aProductsQueue);
