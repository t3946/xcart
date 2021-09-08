<?php
global $xcart_dir;

use Modules\Order\Models\PurchaseOrderModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Exceptions\UploadException;
use Xcart\Logs;
use Xcart\POPipeline;

require "./auth.php";
require $xcart_dir . "/include/security.php";

global $REQUEST_METHOD, $purchase_order_number_upload, $purchase_order_number_search, $purchase_order_storefront_upload, $location, $login, $po_pending, $purchase_order_received_status;


if ($REQUEST_METHOD == "POST") {

    if (!empty($purchase_order_upload_submit)) {
        $oPO = Xcart\POPipeline::getPOByNumber($purchase_order_number_upload);
        if (!empty($oPO) && $oPO->getPOId() && $oPO->getStatus() != Xcart\POPipeline::PO_STATUS_DROPED) {
            $oOrder = $oPO->getOrderInstance();
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(Xcart\POPipeline::PO_LINK_ON_MODIFY, $purchase_order_number_upload, $oOrder->getAdminUrl(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(Xcart\POPipeline::PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "E";
            }
        } else {
            if (!empty($_FILES['purchase_order_file'])) {
                try {
                    if ($_FILES['purchase_order_file']['error'] === UPLOAD_ERR_OK) {
                        Xcart\POPipeline::model()->uploadPurchaseOrder($purchase_order_number_upload, $purchase_order_storefront_upload, $purchase_order_received_status);
                        $top_message["content"] = sprintf(Xcart\POPipeline::PO_HAS_BEEN_UPLOADED, $purchase_order_number_upload);
                        $top_message["type"] = "I";
                        func_header_location("purchase_orders.php#pending_po");
                    } else {
                        throw new UploadException($_FILES['purchase_order_file']['error']);
                    }
                } catch (Throwable $ex) {
                    $top_message["content"] = $ex->getMessage();
                    $top_message["type"] = "E";
                }
            }
        }
    } elseif (!empty($purchase_order_search_submit)) {
        $oPoPipeline = Xcart\POPipeline::getPOByNumber($purchase_order_number_search);
        $orderModel = \Modules\Order\Models\OrderModel::objects()->limit(1)->get(['po_number' => $purchase_order_number_search]);
        if (!$orderModel && (empty($oPoPipeline) || $oPoPipeline->getStatus() == Xcart\POPipeline::PO_STATUS_DROPED || !$oPoPipeline->getPOId())) {
            $top_message["content"] = sprintf(Xcart\POPipeline::PO_NOT_IN_OUR_SYSTEM, $purchase_order_number_search);
            $top_message["type"] = "I";
            \Xcart\App\Main\Xcart::app()->request->redirect("purchase_orders.php?po_found=no&po_number=$purchase_order_number_search#po_upload");
        } else {
            if ($orderModel) {
                $oOrder = $orderModel;
            } else {
                $oOrder = $oPoPipeline->getOrderInstance();
            }
            if (!empty($oOrder)) {
                $top_message["content"] = sprintf(Xcart\POPipeline::PO_LINK_ON_MODIFY, $purchase_order_number_search, $oOrder->getAdminUrl(), $oOrder->getDisplayOrderNumber());
                $top_message["type"] = "I";
            } else {
                $top_message["content"] = sprintf(Xcart\POPipeline::PO_HAS_ALREADY_BEEN_ADDED, $purchase_order_number_upload);
                $top_message["type"] = "I";
                \Xcart\App\Main\Xcart::app()->request->redirect("purchase_orders.php#pending_po");
            }
        }
    } elseif (!empty($purchase_order_enter_submit)) {
        if (!empty($po_selected)) {
            if ($pop = PurchaseOrderModel::objects()->get(['po_id' => $po_selected])) {
                Logs::_log('purchase_orders', $pop->po_id, Logs::LOG_TYPE_CLIENT,
                    sprintf(POPipeline::PO_HAS_BEEN_SELECTED, "{$pop->PO_number} ({$pop->original_po_file})"));
                if ($site = SiteModel::objects()->get(['storefrontid' => $purchase_order_storefront[$po_selected]])) {
                    $pop->storefront_id = $site->storefrontid;
                    $pop->save();
                    \Xcart\App\Main\Xcart::app()->request->redirect($site->getAbsoluteUrl()."/?purchase_order_selected=" . $pop->po_id);
                }
            }
        }

    } elseif (!empty($purchase_order_drop_submit)) {
        if (!empty($po_selected) && $pipe = PurchaseOrderModel::objects()->get(['po_id' => $po_selected])) {
            $pipe->status = Xcart\POPipeline::PO_STATUS_DROPED;
            $pipe->save();
            $pMessage = sprintf(Xcart\POPipeline::PO_HAS_BEEN_DROPPED, $pipe->PO_number . " (" . $pipe->original_po_file . ")");
            $top_message = ['content' => $pMessage, 'type' => 'I'];
            Xcart\Logs::_log('purchase_orders', $pipe->po_id, Xcart\Logs::LOG_TYPE_CLIENT, $pMessage);
            \Xcart\App\Main\Xcart::app()->request->redirect("purchase_orders.php#pending_po");
        }
    }

    if (!empty($entity_type_unlock)) {
        Xcart\Locks::model(['lock_type' => $entity_type_unlock])->unlockEntity();
    }

    func_header_location("purchase_orders.php");
}

if (!empty($po_found) && $po_found == "no" && !empty($po_number)) {
    $smarty->assign("po_number", $po_number);
}

$smarty->assign("po_pending", $po_pending);

if (empty($page)) $page = 1;
$objects_per_page = 50;

$oLogs = new Xcart\Logs('purchase_orders');
$aLogs = $oLogs->_getLogs($page, $objects_per_page);
if (!empty($aLogs) && count($aLogs) > 0) {

    $total_items = $oLogs->_getFoundRows();
    $total_nav_pages = ceil($total_items / $objects_per_page) + 1;

    include $xcart_dir . "/include/navigation.php";
    $smarty->assign('navigation_script', 'purchase_orders.php?show_log=1');
}

$smarty->assign("aPendingOrdersLog", $aLogs);
$smarty->assign("aPendingOrders", Xcart\POPipeline::getPendingPOrders());

$oStoreFronts = new Xcart\StoreFronts();
$smarty->assign("aStorefronts", $oStoreFronts);

$smarty->assign("aRecievedStatuses", Xcart\POPipeline::getRecievedStatuses());

$oLock = Xcart\Locks::model(['lock_type' => 'purchase_order']);
if (!$oLock->getLockType()) {
    $oLock->setField('lock_type', 'purchase_order');
}
$oLock->checkLock();

$oLock = Xcart\Locks::model(['lock_type' => 'purchase_order']);
//$oLock->unlockEntity();

$smarty->assign("lockEntity", $oLock);

$smarty->assign("main", "purchase_orders");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
