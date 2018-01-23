<?php

use Modules\Order\Models\OrderCxInvoiceModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Models\ShippingRateModel;
use Xcart\External_Product_Verification\ExternalVerificationBatch;
use Xcart\External_Product_Verification\ExternalVerificationFeeds;
use Xcart\External_Product_Verification\ExternalVerificationProducts;
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
use Xcart\External_Marketplaces\IssuesProcessingRules;
use Xcart\Images\Splash;
use Xcart\Order;
use Xcart\OrderCxInvoice;
use Xcart\PaymentMethod;
use Xcart\Paypal;
use Xcart\ProductsAmazonFields;
use Xcart\ShippingRate;
use Xcart\OrderGroup;
use Xcart\Product;
use Xcart\Customer;
use Xcart\Categories;
use Xcart\SQLBuilder;
use Xcart\Logs;

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
    case "change_processing_rules":
        changeProcessingRules($_POST);
        break;
    case "change_verificator_status":
        changeVerificatorStatus($_POST);
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
    case "verification_arbitrage_full":
        enterVerificationArbitrageFull($_POST);
        break;
    case "get_receivables_orders":
        getReceivablesOrders($_POST);
        break;
    case "get_payable_orders":
        getPayablesOrders($_POST);
        break;
    case "send_paypal_request":
        sendPayPalRequest($_POST);
        break;
    case "get_paypal_invoice_status":
        getPayPalInvoiceStatus($_POST);
        break;
    case "get_amazon_feed_status":
        getAmazonFeedStatus($_POST);
        break;
    case "get_amazon_listing_products":
        getAmazonListingProducts($_POST);
        break;
    case "product_amazon_fba_restricted_change":
        changeAmazonFBARestricted($_POST);
        break;
    case "get_order_shipping_charge":
        getOrderGroupShippingCharge($_POST);
        break;
    case "get_splash_info":
        getSplashInfo($_POST);
        break;
    case "change_product_splash":
        changeProductSplash($_POST);
        break;
    case "get_transactions_log":
        getTransactionLog($_POST);
        break;
    case "get_product_cost_to_us":
        getProductCostToUs($_POST);
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
    $iNewProductid = (int)$aParams['new_productid'];
    $sOldSKU = $aParams['category'];
    $sOldSKUAdd = $aParams['amazon_sku'];
    switch ($aParams['action']) {
        case 'Edit':
            if (!empty($sNewSKU)) {
                $oProduct = Product::model()->getProductBySKU($sNewSKU);
            } elseif ($iNewProductid) {
                $oProduct = Product::model(['productid' => $iNewProductid]);
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
    $iProductId = (int)$aParams['product_id'];
    $iIssueId = (int)$aParams['issue_id'];
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

function enterVerificationArbitrageFull($aParams = [])
{
    global $login;
    $aResult = [];
    $aResult['result'] = false;
    $sAmazonAsin = $iAmazonQty = $iOurSiteQty = $sExternalVerificationProductsAction = null;

    $iProductId = (int)$aParams['product_id'];
    if (!empty($aParams['asin_arbitrage']) || !empty($aParams['listing_upload_asin']) || !empty($aParams['amazon_qty_arbitrage']) || !empty($aParams['our_qty_arbitrage'])) {
        $aRows = Xcart\External_Product_Verification\ExternalVerificationProducts::model()->findAll(
            Xcart\SQLBuilder::getInstance()->addCondition('productid=' . $iProductId)->
            addCondition("action='asin_on_amazon'")
        );
        if (!empty($aRows)) {
            foreach ($aRows as $oRow) {
                $bS = Xcart\External_Product_Verification\ExternalVerificationProducts::model()->
                setField('productid', $iProductId)->
                setField('batch_id', $oRow->getBatchId())->
                setField('login', $login);
                $sAmazonAsin = trim($aParams['asin_arbitrage']);
                $sAmazonListingAsin = trim($aParams['listing_upload_asin']);
                if (!empty($sAmazonAsin)) {
                    $bS->setField('action', 'arbitrage_asin')->
                    setField('value', $sAmazonAsin)->_insert(true);
                    $aResult['result'] = ($bS !== false);
                }
                if (!empty($sAmazonListingAsin)) {
                    $bS->setField('action', 'listing_upload_asin')->
                    setField('value', $sAmazonListingAsin)->_insert(true);
                    $aResult['result'] = ($bS !== false);
                }
                if (!empty($aParams['amazon_qty_arbitrage'])) {
                    $iAmazonQty = intval($aParams['amazon_qty_arbitrage']);
                    $bS->setField('action', 'arbitrage_amazon_qty')->
                    setField('value', $iAmazonQty)->_insert(true);
                    $aResult['result'] = ($bS !== false);
                }
                if (!empty($aParams['our_qty_arbitrage'])) {
                    $iOurSiteQty = intval($aParams['our_qty_arbitrage']);
                    $bS->setField('action', 'arbitrage_our_qty')->
                    setField('value', $iOurSiteQty)->_insert(true);
                    $aResult['result'] = ($bS !== false);
                }
            }
        }
    }

    if (!empty($aParams['arbitrage']) && is_array($aParams['arbitrage'])) {
        foreach ($aParams['arbitrage'] as $aArbitrageValues) {
            switch ($aArbitrageValues['action']) {
                case 'action':
                    if (!is_null($sAmazonAsin)) {
                        continue;
                    }
                    $sExternalVerificationProductsAction = 'arbitrage_confirmation';
                    break;
                case 'qty':
                    if (!is_null($iAmazonQty) && !is_null($iOurSiteQty)) {
                        continue;
                    }
                    $sExternalVerificationProductsAction = 'arbitrage_confirmation_qty';
                    break;
                case 'image':
                    $sExternalVerificationProductsAction = 'arbitrage_confirmation_image';
                    break;
                case 'name':
                    $sExternalVerificationProductsAction = 'arbitrage_confirmation_name';
                    break;
                case 'desc':
                    $sExternalVerificationProductsAction = 'arbitrage_confirmation_desc';
                    break;
            }
            $bS = Xcart\External_Product_Verification\ExternalVerificationProducts::model()->
            setField('productid', $iProductId)->
            setField('batch_id', $aArbitrageValues['batch_id'])->
            setField('login', $aArbitrageValues['login'])->
            setField('action', $sExternalVerificationProductsAction)->
            setField('value', $login)->_insert(true);
            $aResult['result'] = ($bS !== false);
        }
    }
    print(json_encode($aResult));
}

function getReceivablesOrders($aParams = [])
{
    $html = <<<HTML
<tr>
<td colspan="5">
    <table style="width:100%;">    
    <tr class="TableHead">
    <td style="background-color: #D9EAD3;" width="90">Date</td>
    <td style="background-color: #D9EAD3;" width="90">Order #</td>
    <td style="background-color: #D9EAD3;" width="100">PO #</td>
    <td style="background-color: #D9EAD3;" width="*">COMPANY NAME</td>
    <td style="background-color: #D9EAD3;" width="200">BUYER'S NAME</td>
    <td style="background-color: #D9EAD3;" width="90">AMOUNT</td>
    </tr>
    
HTML;
    $aOrderGroups = ((new Xcart\Reconciliation)->getReceivablesOrderGroups($aParams['period']));
    if (!empty($aOrderGroups)) {
        foreach ($aOrderGroups as $oOrderGroup) {
            $oOrder = $oOrderGroup->getOrderInstance();
            $aOrderDetails = $oOrder->getDetails();
            $html .= <<<HTML
<tr>
<td align="center">{$oOrder->getOrderDate('d-M-Y')}</td>
<td align="center"><a target="_blank" href="{$oOrder->getAdminUrl()}">{$oOrder->getDisplayOrderNumber()}</a></td>
<td>{$aOrderDetails['po_number']}</td>
<td>{$aOrderDetails['company_name']}</td>
<td>{$aOrderDetails['name_of_purchaser']}</td>
<td align="center">{$oOrderGroup->getTotalGross()}</td>
</tr>
HTML;

        }
    }
    $html .= <<<HTML
</table>
</td>
</tr>
HTML;
    echo $html;
}

function getPayablesOrders($aParams = [])
{
    $html = <<<HTML
<tr>
<td colspan="5">
    <table style="width:100%;">    
    <tr class="TableHead">
    <td style="background-color: #D9EAD3;" width="90">Date</td>
    <td style="background-color: #D9EAD3;" width="90">Order #</td>
    <td style="background-color: #D9EAD3;" width="90">AMOUNT</td>
    </tr>
    
HTML;
    $aOrderGroups = ((new Xcart\Reconciliation)->getPayablesOrderGroups($aParams['period']));
    if (!empty($aOrderGroups)) {
        foreach ($aOrderGroups as $oOrderGroup) {
            $oOrder = $oOrderGroup->getOrderInstance();
            $html .= <<<HTML
<tr>
<td align="center">{$oOrder->getOrderDate('d-M-Y')}</td>
<td align="center"><a target="_blank" href="{$oOrder->getAdminUrl()}">{$oOrder->getDisplayOrderNumber()}</a></td>
<td align="center">{$oOrderGroup->getTotalGross()}</td>
</tr>
HTML;

        }
    }
    $html .= <<<HTML
</table>
</td>
</tr>
HTML;
    echo $html;
}

function sendPayPalRequest($aParams = [])
{
    $aResult['result'] = false;
    if (!empty($aParams['send_request_orderid'])) {
        $iOrderId = (int)$aParams['send_request_orderid'];
        Xcart\Logs::_log('orders', $iOrderId, 'X', "'Send request' at 'Paypal Payment request' pressed");
        try {
            $oPaypal = (new Paypal());
            $oInv = $oPaypal->sendPaypalRequest($aParams);
            if (!empty($oInv)) {
                \Xcart\Connection::getInstance()->insert('xcart_order_cx_invoices', [
                    'orderid' => $iOrderId,
                    'invoice_order_number' => $aParams['invoice_next_number'],
                    'invoice_number' => $oInv->getId(),
                    'status' => $oPaypal->getPayPalInvoice($oInv->getId())->getStatus(),
                    'payer_email' => $aParams['paypal_request_email'],
                    'payment_request_subject' => $aParams['paypal_request_subject'],
                    'short_payment_description' => $aParams['paypal_request_notes'],
                    'amount' => $aParams['paypal_request_amount'],
                    'currency' => $aParams['paypal_request_currency'],
                ]);
                Xcart\Logs::_log('orders', $iOrderId, 'X',
                    "Paypal Cx invoice # <a target='_blank' href='https://www.paypal.com/webscr?cmd=_history-details-from-hub&id={$oInv->getId()}'>{$oInv->getId()}</a> has been sent");
                $aResult['result'] = true;
            }
        } catch (\Exception $e) {
            Xcart\Logs::_log('orders', $iOrderId, 'X', $e->getMessage());
        }
    }
    print(json_encode($aResult));
}

function getPayPalInvoiceStatus($aParams = [])
{
    $aResult = [];

    $aResult['result'] = false;

    if (!empty($aParams['paypal_invoice_id'])) {

        if ($oInv = (new Paypal())->getPayPalInvoice($aParams['paypal_invoice_id'])) {

            if ($model = OrderCxInvoiceModel::objects()->get(['invoice_number' => $aParams['paypal_invoice_id']])) {

                if ($model->status != $oInv->getStatus() && $oInv->getStatus() == OrderCxInvoiceModel::STATUS_PAID && $payments = $oInv->getPayments()) {
                    foreach ($payments as $payment) {

                        /** @var OrderTransactionModel $txn */
                        [$txn, $txn_new] = OrderTransactionModel::objects()->getOrNew(['transaction_id' => $payment->getTransactionId()]);

                        if ($txn_new) {

                            $txn->setAttributes([
                                'orderid' => $model->orderid,
                                'paymentid' => 100,
                                'type' => OrderTransactionModel::TYPE_CAPTURE,
                                'transaction_status' => OrderTransactionModel::STATUS_COMPLETED,
                                'transaction_currency' => 'USD',
                                'transaction_amount' => $payment->getAmount()->getValue(),
                                'transaction_fee' => 0,
                                'login' => $model->order->login,
                                'transaction_response' => null,
                                'manual_transaction' => 'N'
                            ]);

                            $txn->save();

                            (new TransactionLogModel([
                                'order_transaction_id' => $txn->id,
                                'transaction_id' => $txn->transaction_id,
                                'orderid' => $txn->orderid,
                                'paymentid' => $txn->paymentid,
                                'transaction_status' => $txn->transaction_status,
                                'transaction_currency' => $txn->transaction_currency,
                                'transaction_total' => $txn->transaction_amount,
                                'login' => $txn->login,
                            ]))->save();
                        }
                    }
                }

                $model->status = $oInv->getStatus();

                $model->save();

                $aResult['result'] = true;
                $aResult['status'] = $oInv->getStatus();
            }

        }
    }
    print(json_encode($aResult));
}

function getAmazonFeedStatus($aParams = [])
{
    $aResult['result'] = false;
    if (!empty($aParams['feed_id'])) {
        $oAmazonMWS = new Xcart\AmazonMWS();
        $oAmazonMWS
            ->setReportId([$aParams['feed_id']])
            ->_Request('GetSubmitionResults');
        $oFeed = ExternalVerificationFeeds::model()->find(SQLBuilder::getInstance()->addCondition("amazon_submition_id = '{$aParams['feed_id']}'"));
        $aResult['status'] = $oFeed->getField('status');
        $aResult['success'] = intval($oAmazonMWS->getDOMXML()['listing_success']);
        $aResult['failed'] = intval($oAmazonMWS->getDOMXML()['listing_failed']);
        $aResult['total'] = $aResult['success'] + $aResult['failed'];
        $aResult['result'] = true;

    }
    print(json_encode($aResult));
}

function getAmazonListingProducts($aParams = [])
{
    global $smarty;
    $aVerificationResult = [];
    $html = null;
    if (!empty($aParams['feed_id']) && is_numeric($aParams['feed_id'])) {
        $oFeed = ExternalVerificationFeeds::model(['feed_id' => intval($aParams['feed_id'])]);
        switch ($aParams['type']) {
            case 'success' :
                $sStatus = 'submit_to_feed_success';
                break;
            case 'failed' :
                $sStatus = 'submit_to_feed_failed';
                break;
            default :
                $sStatus = '';
        }
        if ($aParams['type'] != 'log') {
            $aVerProducts = $oFeed->getVerificationProductsByStatus($sStatus);
            if (!empty($aVerProducts)) {
                foreach ($aVerProducts as $oVerificationProduct) {
                    $sFinalASIN = $oVerificationProduct->getASINAfterVerification();
                    $aVerificationResult[] = [
                        'Product' => Product::model(['productid' => $oVerificationProduct->getProductId()]),
                        'pasin' => $oVerificationProduct->getASINAfterVerification(),
                        'AsinLink' => sprintf(ExternalVerificationProducts::AMAZON_PRODUCT_LINK, $sFinalASIN),
                        'amz_listing_status' => ExternalVerificationProductsQueue::$aStatusTitles[$oVerificationProduct->getField('amz_listing_status')]
                    ];
                }
            }
            $smarty->assign('aVerifiactionResults', $aVerificationResult);
            $smarty->assign('readonly', true);

            $html = "<tr class='listing_products'><td colspan='7'>";
            $html .= func_display('admin/main/az_listing_product_table.tpl', $smarty, false);
            $html .= "</td></tr>";
        } else {
            /** @var Logs[] $aLogs */
            $aLogs = (new Xcart\Logs('amazon_listings'))->_getLogs(1, 1, intval($aParams['feed_id']));
            $html = "<tr class='listing_products'><td colspan='7'><b>";
            if (!empty($aLogs)) {

                foreach ($aLogs as $oLog) {
                    $html .= nl2br($oLog->getLogText());
                }

            } else {
                $html .= '<p style="text-align: center;">Errors not found</p>';
            }
            $html .= "</b></td></tr>";
        }
        print $html;
    }
}

function changeAmazonFBARestricted($aParams = [])
{
    $aResult['result'] = false;
    if (!empty($aParams['product_id']) && is_numeric($aParams['product_id'])) {
        $iProductId = (int)$aParams['product_id'];
        $oProductAmazonFields = ProductsAmazonFields::model(['productid' => $iProductId]);
        $sFbaStatus = isset($aParams['status']) ? 'Y' : 'N';
        $oProductAmazonFields->setField('amazon_fba_restricted', $sFbaStatus);
        if ($oProductAmazonFields->getField('productid')) {
            $oProductAmazonFields->_update();
        } else {
            $oProductAmazonFields->setField('productid', $iProductId);
            $oProductAmazonFields->_insert();
        }
        $aResult['result'] = true;
    }
    print(json_encode($aResult));
}

function getOrderGroupShippingCharge($aParams = [])
{
    $sResult = 'Shipping quote not found';
    if (!empty($aParams['orderid']) && !empty($aParams['manufacturerid'])) {
        /** @var OrderGroup $oOrderGroup */
        $oOrderGroup = OrderGroup::objects()->filter(['orderid' => (int)$aParams['orderid'], 'manufacturerid' => (int)$aParams['manufacturerid']])->get();
        if ($oOrderGroup) {
            $aShippingRates = $oOrderGroup->getShippingRates();
            if (!empty($aShippingRates)) {
                /** @var ShippingRateModel $oShippingRate */
                $sResult = '';
                $aShippingRates = reset($aShippingRates);
                foreach ($aShippingRates as $oShippingRate) {
                    $shippingCharge = "$" . price_format($oShippingRate->getShippingCharge());
                    if ($ship_m = $oShippingRate->shipping) {
                        $sResult .= "{$ship_m->getName()} {$ship_m->shipping_time}: {$shippingCharge} \n";
                    }
                }
            }
        }
    }
    print nl2br($sResult);
}

function getSplashInfo($aParams = [])
{
    $aResult['result'] = false;
    if (!empty($aParams['splash_id']) && is_numeric($aParams['splash_id'])) {
        $oSplash = Splash::objects()->filter(['id' => $aParams['splash_id']])->get();
        if ($oSplash) {
            $aResult['result'] = true;
            $aResult['data'] = $oSplash->getFields();
        }
    }
    print json_encode($aResult);
}

function changeProductSplash($aParams = [])
{
    $aResult['result'] = false;
    if (!empty($aParams['product_id'])) {
        $oProduct = Product::objects()->filter(['productid' => $aParams['product_id']])->get();
        $oProduct->setAttribute('splash_id', (int)$aParams['splash_id']);
        $oProduct->_update();
        $aResult['result'] = true;
    }
    print json_encode($aResult);
}

function getTransactionLog($aParams = [])
{
    global $smarty;
    $result = null;
    if ((!empty($aParams['order_transaction_id'])) && ($orderTransaction = OrderTransactionModel::objects()->get(['id' => $aParams['order_transaction_id']]))) {
        $smarty->assign('order_transactions', $orderTransaction->transaction_logs->order(['-id']));
        $smarty->assign('main_transaction', false);
        $result = $smarty->fetch('admin/main/transactions_table.tpl');
    }
    print $result;
}

function getProductCostToUs($aParams = [])
{
    $aResult = ['result' => false];
    if (!empty($aParams['sku'])) {
        $model = ProductModel::objects()->get(['productcode' => $aParams['sku']]);
        if ($model) {
            if ($model->manufacturerid == $aParams['mnf']) {
                $aResult = [
                    'result' => true,
                    'product' => [
                        'productid' => $model->productid,
                        'productcode' => $model->productcode,
                        'cost_to_us' => $model->cost_to_us
                    ],
                ];
            } else {
                $aResult['error'] = "SKU {$aParams['sku']} not found in this distributor";
            }
        } else {
            $aResult['error'] = "SKU {$aParams['sku']} not found";
        }
    } else {
        $aResult['error'] = 'SKU is empty';
    }
    print json_encode($aResult);
}