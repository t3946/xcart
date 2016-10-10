<?php
require './auth.php';
require '../include/security.php';
require_once '../include/class/classProducts.php';
require_once '../include/class/classCategories.php';
require_once '../include/class/classOrder.php';
require_once '../include/class/classPOPipeline.php';
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";


switch ($_POST['ajax_action']) {
    case "change_verify_product_status" :
        changeVerifyProductStatus($_POST);
        break;
    case "change_verify_order_status" :
        changeVerifyOrderStatus($_POST);
        break;
    case "ship_order_by_amazon" :
        shipOrderByAmazon($_POST);
        break;
    case "select_purchase_order_for_entry":
        selectPurchaseOrderForEntry($_POST);
        break;
    case "change_verify_batch_status":
        changeVerifyBatchStatus($_POST);
        break;
    case "change_processing_rules":
        changeProcessingRules($_POST);
    case "change_verificator_status":
        changeVerificatorStatus($_POST);
        break;
    case "add_new_batch":
        addNewBatch($_POST);
        break;
    case "category_structure_change":
        changeCategoryStructure($_POST);
        break;
}

function changeVerifyProductStatus($aPostParam = [])
{
    $bResult = [];
    $iProductId = (int)$aPostParam['product_id'];
    $aOrders = explode(',', $aPostParam['order_id']);
    $iStatusId = (int)$aPostParam['verify_status_id'];
    $sNote = $aPostParam['note_text'];
    if (!empty($iProductId)) {
        $oProduct = new classProduct(['productid'=>$iProductId]);
        $bResult = $oProduct->changeVerificationStatus($iStatusId, $sNote, true, $aOrders);
        if (!empty($aOrders)) {
            foreach ($aOrders as $iOrderId) {
                $oOrder = new classOrder(['orderid'=>$iOrderId]);
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
        $oOrder = new classOrder(['orderid'=>$iOrderId]);
        $bResult = $oOrder->changeVerificationStatus($sOrderStatus);
    }
    print(json_encode($bResult));
}

function shipOrderByAmazon($aPostParam = [])
{
    $iOrderId = (int)$aPostParam['orderid'];
    $iManufacturerid = (int)$aPostParam['manufacturerid'];
    $oOrderGroup = new classOrderGroup(['orderid' => $iOrderId, 'manufacturerid' => $iManufacturerid]);
    $sAmazonShipmentNotesSend = $aPostParam['submit_amazon_shipment_with_notes'];
    $sAmazonShippingMethodSelect = $aPostParam['amazon_shipping_method_select'];
    if ($sAmazonShipmentNotesSend != 'false') {
        $sAmazonShipmentNotes = $aPostParam['submit_amazon_shipment_notes'];
        $oOrderGroup->updateAmazonShipmentWithNotes('Y');
        $oOrderGroup->updateAmazonShipmentNotes($sAmazonShipmentNotes);
    }
    if (!empty($sAmazonShippingMethodSelect))
        $oOrderGroup->shipOrderGroupByAmazon($sAmazonShippingMethodSelect);
}

function selectPurchaseOrderForEntry($aPostParam = [])
{
    if (!empty($aPostParam['ordernumber']) && is_numeric($aPostParam['ordernumber'])) {
        $oPoPipeline = new classPOPipeLine(['po_id' => $aPostParam['ordernumber']]);
        $iPoPipe = $oPoPipeline->getPOId();
        if ($iPoPipe) {
            $aResult = $oPoPipeline->selectOrderForEntry();
            print(json_encode($aResult));
        }
    }
}

function changeVerifyBatchStatus($aPostParam = [])
{
    $aResult = [];
    if (!empty($aPostParam['batch_id']) && is_numeric($aPostParam['batch_id'])) {
        $oVerificationBatch = new classExternalVerificationBatch(['batch_id' => $aPostParam['batch_id']]);
        if ($oVerificationBatch->getBatchStatus() != $aPostParam['verify_status_id']) {
            $oVerificationBatch->setVerificationStatus($aPostParam['verify_status_id']);
        }
    }
    print(json_encode($aResult));
}

function changeVerificatorStatus($aPostParam = [])
{
    $aResult = [];
    if (!empty($aPostParam['customer_id'])) {
        if (!empty($aPostParam['user_status_id']) && $aPostParam['user_status_id']=='unblocked') {
            $oCustomer = new classCustomer(['login' => $aPostParam['customer_id']]);
            if ($oCustomer->getCustomerLogin()) {
                $oCustomer->unblockAmazonAccount();
            }
        }
    }
    print(json_encode($aResult));
}

function addNewBatch($aPostParam = [])
{
    $aResult = [];
    $oVerificationBatch = new classExternalVerificationBatch();
    $oVerificationBatch->setField('login', $aPostParam['login']);
    $oVerificationBatch->setField('batch_amount', $aPostParam['batch_amount']);
    $oCustomer = new classCustomer(['login' => $aPostParam['login']]);
    $iMaxBatchNumber = $oCustomer->getAmazonBatchesMaxNumber();
    $oVerificationBatch->setField('batch_number', $iMaxBatchNumber + 1);
    if (!empty($_POST['test_batch']) && $_POST['test_batch'] == 'Y')
        $oVerificationBatch->setField('is_test', 'Y');
    $oVerificationBatch->_insert();
    print(json_encode($aResult));
}

function changeProcessingRules($aParams = [])
{
    $aResult = [];
    $aRule = new classIssuesProcessingRules(['issue_id'=> $aParams['rule_id']]);
    $aRule->updateField('issue_processing', $aParams['status_id']);
    print(json_encode($aResult));
}

function changeCategoryStructure($aParams = []) {
    $aResult = [];
    $sStatus = '';
    $iCategoryId = (int) $aParams['category'];
    switch ($aParams['action']){
        case 'Relist':
            $sStatus = 'AC';
            break;
        case 'Reclassify':
            $sStatus = 'NC';
            break;
    }
    $oProducts = new classCategories();
    $aResult['result'] = $oProducts->updateProductsInChildCategories($iCategoryId, $sStatus);
    print(json_encode($aResult));
}