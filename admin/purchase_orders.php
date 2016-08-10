<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir . "/include/security.php";
require_once $xcart_dir . "/include/class/classPOPipeline.php";
require_once $xcart_dir . "/include/class/classLogs.php";
require_once $xcart_dir . "/include/class/classStoreFronts.php";

global $REQUEST_METHOD, $purchase_order_number_upload, $purchase_order_number_search, $purchase_order_storefront_upload, $location, $login, $po_pending;


if ($REQUEST_METHOD == "POST") {

    if (!empty($purchase_order_upload_submit)) {
        $oPO = classPOPipeLine::getPOByNumber($purchase_order_number_upload);
        if (!empty($oPO)) {
            $oOrder = $oPO->getOrderInstance();
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(classPOPipeLine::PO_LINK_ON_MODIFY, $purchase_order_number_upload, $oOrder->getOrderModifyURL(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(classPOPipeLine::PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "E";
            }
        } else {
            if (!empty($_FILES['purchase_order_file']) && $_FILES['purchase_order_file']['error'] == UPLOAD_ERR_OK) {
                $oPoPipeline = new classPOPipeLine();
                try {
                    $oPoPipeline->uploadPurchaseOrder($purchase_order_number_upload, $purchase_order_storefront_upload);
                    $top_message["content"] = sprintf(classPOPipeLine::PO_HAS_BEEN_UPLOADED, $purchase_order_number_upload);
                    $top_message["type"] = "I";
                } catch (Exception $ex) {
                    $top_message["content"] = $ex->getMessage();
                    $top_message["type"] = "E";
                }
            }
        }
    } elseif (!empty($purchase_order_search_submit)) {
        $oPoPipeline = classPOPipeLine::getPOByNumber($purchase_order_number_search);
        if (empty($oPoPipeline)) {
            $top_message["content"] = sprintf(classPOPipeLine::PO_NOT_IN_OUR_SYSTEM, $purchase_order_number_search);
            $top_message["type"] = "I";
            func_header_location("purchase_orders.php?po_found=no&po_number=$purchase_order_number_search");
        } else {
            $oOrder = $oPoPipeline->getOrderInstance();
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(classPOPipeLine::PO_LINK_ON_MODIFY, $purchase_order_number_search, $oOrder->getOrderModifyURL(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(classPOPipeLine::PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "I";
            }
        }
    } elseif (!empty($purchase_order_drop_submit)) {
        if (!empty($po_selected)) {
            foreach ($po_selected as $sOrderNumber) {
                $oPoPipeline = new classPOPipeLine(['po_id' => reset($po_selected)]);
                $pOID = $oPoPipeline->getPOId();
                if (!empty($pOID)) {
                    $oPoPipeline->updateOrderStatus('deleted');
                    classLogs::_log('purchase_orders', $oPoPipeline->getPOId(), classLogs::LOG_TYPE_CLIENT, sprintf(classPOPipeLine::PO_HAS_BEEN_DROPPED, $oPoPipeline->getOrderNumber() . " (" . $oPoPipeline->getOrderOriginalFileName() . ")"));
                }
            }
        }
    }

    func_header_location("purchase_orders.php");
}

if (!empty($po_found) && $po_found == "no" && !empty($po_number)) {
    $smarty->assign("po_number", $po_number);
}

$smarty->assign("po_pending", $po_pending);

if (empty($page))  $page = 1;
$objects_per_page = 50;

$oLogs = new classLogs('purchase_orders');
$aLogs = $oLogs->_getLogs($page, $objects_per_page);
if (!empty($aLogs) && count($aLogs) > 0) {

    $total_items = $oLogs->_getFoundRows();
    $total_nav_pages = ceil($total_items / $objects_per_page) + 1;

    include $xcart_dir . "/include/navigation.php";
    $smarty->assign('navigation_script', 'purchase_orders.php?show_log=1');
}

$smarty->assign("aPendingOrdersLog", $aLogs);
$smarty->assign("aPendingOrders", classPOPipeLine::getPendingPOrders());

$oStoreFronts = new classStoreFronts();
$smarty->assign("aStorefronts", $oStoreFronts);

$smarty->assign("main", "purchase_orders");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
