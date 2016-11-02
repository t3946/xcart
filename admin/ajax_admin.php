<?php
use Xcart\External_Product_Verification\ExternalVerificationBatch;
use Xcart\External_Marketplaces\IssuesProcessingRules;
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
    case "missing_structure_change":
        changeMissingStructure($_POST);
        break;
    case "issue_processing":
        changeIssueProcessing($_POST);
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
        $bResult = Product::model(['productid' => $iProductId])->changeVerificationStatus($iStatusId, $sNote, true, $aOrders);
        if (!empty($aOrders)) {
            foreach ($aOrders as $iOrderId) {
                Order::model(['orderid' => $iOrderId])->updateVerificationStatus();
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
        $bResult = Order::model(['orderid' => $iOrderId])->changeVerificationStatus($sOrderStatus);
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
        if (!empty($aPostParam['user_status_id']) && $aPostParam['user_status_id'] == 'unblocked') {
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
    $aRule = new IssuesProcessingRules(['issue_id' => $aParams['rule_id']]);
    $aRule->updateField('issue_processing', $aParams['status_id']);
    print(json_encode($aResult));
}

function changeCategoryStructure($aParams = [])
{
    $aResult = [];
    $sStatus = '';
    $iCategoryId = (int)$aParams['category'];
    switch ($aParams['action']) {
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

function changeMissingStructure($aParams = [])
{
    $aResult = [];
    $aResult['result'] = false;
    $oProduct = null;
    $sNewSKU = $aParams['new_sku'];
    $iNewProductid = (int) $aParams['new_productid'];
    $sOldSKU = $aParams['category'];
    $sOldSKUAdd = $aParams['amazon_sku'];
    switch ($aParams['action']) {
        case 'Edit':
            if (!empty($sNewSKU)) {
                $oProduct = Xcart\Product::model()->getProductBySKU($sNewSKU);
            } elseif ($iNewProductid) {
                $oProduct = Xcart\Product::model(['productid'=>$iNewProductid]);
            }
            if ($oProduct->getProductId()) {
                if ($oProduct->isForSale()) {
                    if (!empty($sOldSKUAdd)) {
                        Xcart\FbaMissingSku::model()->setField('missing_productcode', $sOldSKUAdd)->setField('productid', $oProduct->getProductId())->_insert(true);
                    } else {
                        Xcart\FbaMissingSku::model(['missing_productcode' => $sOldSKU])->setField('productid', $oProduct->getProductId())->_update();
                    }
                    $aResult['result'] = true;
                } else {
                    $aResult['error'] = func_get_langvar_by_name('lbl_match_fba_missing_workplace_not_enabled_sku');
                    $aResult['result'] = false;
                }
            } else {
                $aResult['error'] = func_get_langvar_by_name('lbl_match_fba_missing_workplace_product_not_found');
                $aResult['result'] = false;
            }

            break;
        case 'Delete':
            Xcart\FbaMissingSku::model(['missing_productcode' => $sOldSKU])->_delete();
            $aResult['result'] = true;
            break;
        case 'Fix_orders':
            if (!empty($sOldSKU)) {
                Xcart\FbaMissingSku::model(['missing_productcode' => $sOldSKU])->fixOrders();
            }
            $aResult['result'] = true;
            break;
        default :
            $aResult['result'] = false;
            $aResult['error'] = 'Action not defined';
    }

    print(json_encode($aResult));
}

function changeIssueProcessing($aParams = [])
{
    $aResult = [];
    $aResult['result'] = false;
    $iProductId = (int) $aParams['product_id'];
    $iIssueId = (int) $aParams['issue_id'];
    switch ($aParams['action']) {
        case 'fixed':
            $oIssue = new Xcart\External_Marketplaces\GMCQualityIssues(['productid' => $iProductId, 'issue_id' => $iIssueId]);
            $oIssue->updateField('fixed', 'Y');
            $aResult['result'] = true;
            break;
        case 'exclude':
            $oStoreFrontMarketPlace = new Xcart\External_Marketplaces\Marketplaces\GMC();
            $oIssue = new Xcart\External_Marketplaces\GMCQualityIssues(['productid' => $iProductId, 'issue_id' => $iIssueId]);
            $oIssue->updateField('fixed', 'Y');
            $oStoreFrontMarketPlace->restoreQueue([['productid' => $iProductId]], 1);
            //Google
            $oDisableMarketplace = new Xcart\External_Marketplaces\DisabledMarketPlace();
            $oDisableMarketplace->fill(['marketplace_id' => 1, 'resource_id' => $iProductId, 'resource_type' => 'P']);
            $oDisableMarketplace->addDisabledMarketPlace();
            //Bing
            $oDisableMarketplace->fill(['marketplace_id' => 2, 'resource_id' => $iProductId, 'resource_type' => 'P']);
            $oDisableMarketplace->addDisabledMarketPlace();
            $aResult['result'] = true;
            break;
    }
    print(json_encode($aResult));
}