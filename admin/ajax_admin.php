<?php
use Xcart\External_Product_Verification\ExternalVerificationBatch;
use Xcart\External_MarketPlace\IssuesProcessingRules;
use Xcart\Order;
use Xcart\OrderGroup;
use Xcart\Product;
use Xcart\Customer;
use Xcart\Categories;

require './auth.php';
require '../include/security.php';
global $xcart_dir;


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
    case "change_verify_batch_status":
        changeVerifyBatchStatus($_POST);
        break;
    case "change_verificator_status":
        changeVerificatorStatus($_POST);
        break;
    case "change_processing_rules":
        changeProcessingRules($_POST);
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
        $bResult = Product::model(['productid'=>$iProductId])->changeVerificationStatus($iStatusId, $sNote, true, $aOrders);
        if (!empty($aOrders)) {
            foreach ($aOrders as $iOrderId) {
                Order::model(['orderid'=>$iOrderId])->updateVerificationStatus();
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
        $bResult = Order::model(['orderid'=>$iOrderId])->changeVerificationStatus($sOrderStatus);
    }
    print(json_encode($bResult));
}

function shipOrderByAmazon($aPostParam = [])
{
    $iOrderId = (int)$aPostParam['orderid'];
    $iManufacturerid = (int)$aPostParam['manufacturerid'];
    $oOrderGroup = new OrderGroup(['orderid' => $iOrderId, 'manufacturerid' => $iManufacturerid]);
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


function changeVerifyBatchStatus($aPostParam = [])
{
    $aResult = [];
    if (!empty($aPostParam['batch_id']) && is_numeric($aPostParam['batch_id'])) {
        $oVerificationBatch = new ExternalVerificationBatch(['batch_id' => $aPostParam['batch_id']]);
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
            $oCustomer = new Customer(['login' => $aPostParam['customer_id']]);
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
    $oVerificationBatch = new ExternalVerificationBatch();
    $oVerificationBatch->setField('login', $aPostParam['login']);
    $oVerificationBatch->setField('batch_amount', $aPostParam['batch_amount']);
    $oCustomer = new Customer(['login' => $aPostParam['login']]);
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
    $aRule = new IssuesProcessingRules(['issue_id'=> $aParams['rule_id']]);
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
    $oProducts = new Categories();
    $aResult['result'] = $oProducts->updateProductsInChildCategories($iCategoryId, $sStatus);
    print(json_encode($aResult));
}