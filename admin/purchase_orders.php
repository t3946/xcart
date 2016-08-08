<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir . "/include/security.php";
require_once $xcart_dir . "/include/class/classPOPipeline.php";
require_once $xcart_dir . "/include/class/classLogs.php";

global $REQUEST_METHOD, $purchase_order_number_upload, $purchase_order_number_search, $location, $login;

define("PO_LINK_ON_MODIFY", "PO# %s corresponds to the following order(s): <a href='%s' target='_blank'>%s</a>");
define("PO_NOT_IN_OUR_SYSTEM", "PO# %s is not yet in our system");
define("PO_HAS_ALREADY_BEEN_ADDED", "PO# %s has already been added to Pending entry POs queue");
define("PO_HAS_BEEN_UPLOADED", "PO# %s has been uploaded");
define("PO_HAS_BEEN_DROPPED", "PO# %s has been dropped");

if ($REQUEST_METHOD == "POST") {

    if (!empty($purchase_order_upload_submit)) {
        $oPO = classPOPipeLine::getPOByNumber($purchase_order_number_upload);
        if (!empty($oPO)) {
            $oOrder = $oPO->getOrderInstance();
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(PO_LINK_ON_MODIFY, $purchase_order_number_upload, $oOrder->getOrderModifyURL(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "E";
            }


        } else {
            if (!empty($_FILES['purchase_order_file']) && $_FILES['purchase_order_file']['error'] == UPLOAD_ERR_OK) {

                $aPathInfo = (pathinfo($_FILES["purchase_order_file"]['name']));
                $sFileName = $purchase_order_number_upload . '.' . $aPathInfo['extension'];
                $sNewFilePath = $xcart_dir . '/files/purchase_orders/' . $sFileName;

                if (move_uploaded_file($_FILES["purchase_order_file"]['tmp_name'], $sNewFilePath)) {
                    $oPoPipeline = new classPOPipeLine();
                    $oPoPipeline->setField('PO_number', $purchase_order_number_upload);
                    $oPoPipeline->setField('login', $login);
                    $oPoPipeline->setField('file_name', $sFileName);
                    $oPoPipeline->setField('original_po_file', $_FILES["purchase_order_file"]['name']);
                    $oPoPipeline->_insert();
                    $top_message["content"] = sprintf(PO_HAS_BEEN_UPLOADED, $purchase_order_number_upload);
                    $top_message["type"] = "I";
                    $sLogText = sprintf(PO_HAS_BEEN_UPLOADED, $oPoPipeline->getOrderNumber()." (".$oPoPipeline->getOrderOriginalFileName().")");
                    classLogs::init('purchase_orders');
                    classLogs::_log($oPoPipeline->getPOId(), classLogs::LOG_TYPE_CLIENT, $sLogText);

                } else {
                    $top_message["content"] = "PO#$purchase_order_number_upload upload failed";
                    $top_message["type"] = "E";
                }
            }
        }
    } elseif (!empty($purchase_order_search_submit)) {
        $oPoPipeline = classPOPipeLine::getPOByNumber($purchase_order_number_search);
        if (empty($oPoPipeline)) {
            $top_message["content"] = sprintf(PO_NOT_IN_OUR_SYSTEM, $purchase_order_number_search);
            $top_message["type"] = "I";
            func_header_location("purchase_orders.php?po_found=no&po_number=$purchase_order_number_search");
        } else {
            $oOrder = $oPoPipeline->getOrderInstance();
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(PO_LINK_ON_MODIFY, $purchase_order_number_search, $oOrder->getOrderModifyURL(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "I";
            }
        }
    } elseif (!empty($purchase_order_drop_submit)) {
        if (!empty($po_selected)) {
            foreach ($po_selected as $sOrderNumber) {
                $oPoPipeline = new classPOPipeLine(['po_id' => reset($po_selected)]);
                if (!empty($oPoPipeline)) {
                    $sLogText = sprintf(PO_HAS_BEEN_DROPPED, $oPoPipeline->getOrderNumber()." (".$oPoPipeline->getOrderOriginalFileName().")");
                    classLogs::init('purchase_orders');
                    classLogs::_log($oPoPipeline->getPOId(), classLogs::LOG_TYPE_CLIENT, $sLogText);
                    $oPoPipeline->_delete();
                }
            }
        }
    }

    func_header_location("purchase_orders.php");
}

if (!empty($po_found) && $po_found == "no" && !empty($po_number)) {
    $smarty->assign("po_number", $po_number);
}

$oLogs = new classLogs('purchase_orders');
$smarty->assign("aPendingOrdersLog", $oLogs->_getLogs());
$smarty->assign("aPendingOrders", classPOPipeLine::getPendingPOrders());


$smarty->assign("main", "purchase_orders");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);

?>
