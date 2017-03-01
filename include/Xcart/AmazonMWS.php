<?php
namespace Xcart;

use Modules\Amazon\Helpers\AmazonHelper;
use Modules\Amazon\Models\AmazonFbaProductModel;
use Modules\Amazon\Models\AmazonFbaProductsQuickModel;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;
use Xcart\External_Product_Verification\ExternalVerificationFeeds;
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
use Xcart\OrderGroup;
use Xcart\Order;
use Xcart\Cart;


define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
define('AWS_ACCESS_KEY_ID', 'AKIAJFLBZ4Y7BVG5Q22A');
define('AWS_SECRET_ACCESS_KEY', '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');
define('APPLICATION_NAME', 's3stores');
define('APPLICATION_VERSION', '1');
define('MERCHANT_ID', 'A2SWKX6V1OVQ89');
define('MARKETPLACE_ID', 'ATVPDKIKX0DER');

class AmazonMWS
{
    const BACK_PROCESS_LOG_NAME = 'AmazonFeeReport';
    const BACK_PROCESS_LOG_NAME_SETTLEMENT = 'Amazon_Reports_Cron';
    const BACK_PROCESS_LOG_NAME_ORDERS = 'amazon_orders';
    const BACK_PROCESS_LOG_NAME_ORDER_INFO = 'amazon_info';
    const BACK_PROCESS_LOG_NAME_FBA_INVENTORY = 'amazon_fba_inventory_receipts';
    const DEFAULT_ORDER_MESSAGE = 'Thank you for your order!';
    const AMAZON_ORDER_LINK = "https://sellercentral.amazon.com/gp/orders-v2/list/ref=ag_myo_apsearch_myosearch?searchType=OrderID&searchKeyword=%s&showPending=1&isDebug=&isAdvancedSearch=1&ignoreSearchType=0&searchLanguage=en_US";

    private $oMWSService;
    private $marketplaceIdArray;
    private $dom_xml_arr;
    private $aWaitLoopExitCondition = [];
    private $aReportValue = [];
    private $aReportIds = [];
    private $sleepTimeOut = 60;
    public $error = [];
    private $amazonReportType;
    private $sql_tbl;
    private $sBackProcessLogName = null;
    /** @var \DateTime tStartDate */
    private $tStartDate = null;
    private $nextToken = 'start';
    private $oOrder = null;
    private $sServiceUrl = null;
    private $getOnlyAcknowledged = true;
    /** @var Product[] $aProducts */
    private $aProducts = null;

    private $bEnableLog = false;
    private $sLogPrefix = null;


    public function __construct($oServiceClass = 'MarketplaceWebService_Client', $uri = '')
    {
        global $sql_tbl;
        $this->sServiceUrl = "https://mws.amazonservices.com" . $uri;
        $a_config = array(
            'ServiceURL' => $this->sServiceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        if ($oServiceClass == 'MarketplaceWebServiceOrders_Client' ||
            $oServiceClass == 'FBAInventoryServiceMWS_Client' ||
            $oServiceClass == 'MarketplaceWebServiceProducts_Client'
        ) {
            $this->oMWSService = new $oServiceClass(
                AWS_ACCESS_KEY_ID,
                AWS_SECRET_ACCESS_KEY,
                APPLICATION_NAME,
                APPLICATION_VERSION,
                $a_config);
        } else
            $this->oMWSService = new $oServiceClass(
                AWS_ACCESS_KEY_ID,
                AWS_SECRET_ACCESS_KEY,
                $a_config,
                APPLICATION_NAME,
                APPLICATION_VERSION);

        $this->marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));
        $this->amazonReportType = '_GET_FBA_ESTIMATED_FBA_FEES_TXT_DATA_';
        $this->sBackProcessLogName = self::BACK_PROCESS_LOG_NAME;

        $this->sql_tbl = $sql_tbl;
    }

    public function setBackProcessName($sName)
    {
        $this->sBackProcessLogName = $sName;
        return $this;
    }

    public function setStartDate($tDate)
    {
        $this->tStartDate = $tDate;
        return $this;
    }

    public function setProcessWithoutAcknowledgedFlag()
    {
        $this->getOnlyAcknowledged = false;
        return $this;
    }

    public function setTimeOut($iTimeOut)
    {
        $this->sleepTimeOut = $iTimeOut;
        return $this;
    }

    public function doRequestReport()
    {
        if (!empty($this->error)) return $this;

        $this->aWaitLoopExitCondition = [];
        $request = new \MarketplaceWebService_Model_RequestReportRequest();
        $request->setMarketplaceIdList($this->marketplaceIdArray);
        $request->setMerchant(MERCHANT_ID);
        $request->setReportType($this->amazonReportType);

        if (!is_null($this->tStartDate))
            $request->setStartDate(new \DateTime($this->tStartDate->format("Y-m-d\T00:00:00P"), new \DateTimeZone('UTC')));

        $this->dom_xml_arr = AmazonHelper::invokeRequestReport($request, $this->oMWSService);

        if (!empty($this->dom_xml_arr['Caught_Exception'])) {
            $this->error[] = $this->dom_xml_arr["Caught_Exception"];
            $log_text = 'RequestReport -> Error:' . $this->dom_xml_arr["Caught_Exception"];
        } else {
            $log_text = 'RequestReport -> ReportRequestId:' . $this->dom_xml_arr['ReportRequestId'];
        }
        if (!empty($this->sBackProcessLogName)) {
            func_backprocess_log($this->sBackProcessLogName, $log_text);
        }
        return $this;
    }

    public function doGetReportRequestList()
    {
        if (!empty($this->error)) return $this;

        $this->setTimeOut(45);

        if ($this->dom_xml_arr['ReportRequestId']) {
            $this->aWaitLoopExitCondition = [['ReportProcessingStatus' => '_DONE_'], ['ReportProcessingStatus' => '_DONE_NO_DATA_'], ['ReportProcessingStatus' => '_CANCELLED_']];

            $reportRequestIdList = new \MarketplaceWebService_Model_IdList();
            $reportRequestIdList->setId($this->dom_xml_arr['ReportRequestId']);

            $request = new \MarketplaceWebService_Model_GetReportRequestListRequest();
            $request->setMerchant(MERCHANT_ID);
            $request->setReportRequestIdList($reportRequestIdList);

            $this->dom_xml_arr = AmazonHelper::invokeGetReportRequestList($request, $this->oMWSService);

            $log_text = 'GetReportRequestList -> ReportProcessingStatus:' . $this->dom_xml_arr['ReportProcessingStatus'];

            if (!empty($this->sBackProcessLogName)) {
                func_backprocess_log($this->sBackProcessLogName, $log_text);
            }

            if (!empty($this->dom_xml_arr['Caught_Exception'])) {
                $this->error[] = $this->dom_xml_arr["Caught_Exception"];
            }

            if ($this->dom_xml_arr['ReportProcessingStatus'] == '_CANCELLED_') {
                $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is CANCELED by Amazon MWS';
            }
            if ($this->dom_xml_arr['ReportProcessingStatus'] == '_DONE_NO_DATA_') {
                $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is DONE_NO_DATA';
            }
        }
        return $this;
    }

    public function doGetReportList()
    {
        $this->aWaitLoopExitCondition = [];

        $this->setTimeOut(60);

        $req = new \MarketplaceWebService_Model_TypeList();

        $req->withType($this->amazonReportType);

        $request = new \MarketplaceWebService_Model_GetReportListRequest();
        $request->setMerchant(MERCHANT_ID);

        $request->setReportTypeList($req);
        $request->setMaxCount("100");
        if ($this->getOnlyAcknowledged)
            $request->setAcknowledged(false);

        $this->dom_xml_arr = AmazonHelper::invokeGetReportList($request, $this->oMWSService);
        if (!empty($this->dom_xml_arr["ReportId"])) {
            $log_text = 'GetReportList -> ReportId:' . implode(',', $this->dom_xml_arr["ReportId"]);
        } else {
            $log_text = 'GetReportList -> No reports found';
        }
        if (!empty($this->sBackProcessLogName)) {
            func_backprocess_log($this->sBackProcessLogName, $log_text);
        }

        $this->setReportId($this->dom_xml_arr["ReportId"]);
        return $this;
    }

    public function doGetReport()
    {
        $this->aWaitLoopExitCondition = [];

        $this->setTimeOut(60);

        if (!empty($this->aReportIds)) {
            if (is_array($this->aReportIds)) {
                $this->dom_xml_arr = [];
                foreach ($this->aReportIds as $reportId) {
                    $request = new \MarketplaceWebService_Model_GetReportRequest();
                    $request->setMerchant(MERCHANT_ID);
                    $request->setReport(@fopen('php://memory', 'rw+'));
                    $request->setReportId($reportId);
                    $this->dom_xml_arr[$reportId] = AmazonHelper::invokeGetReport($request, $this->oMWSService);
                    $log_text = 'GetReport -> ReportId:' . $reportId;
                    if (!empty($this->sBackProcessLogName)) {
                        func_backprocess_log($this->sBackProcessLogName, $log_text);
                    }
                }
            }
        }
        return $this;
    }


    public function doUpdateReportAcknowledgements()
    {
        $this->setTimeOut(45);

        $request = new \MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
        $request->setMerchant(MERCHANT_ID);

        if (!empty($this->aReportIds)) {
            foreach ($this->aReportIds as $iReportId) {
                $idList = new \MarketplaceWebService_Model_IdList();

                $request->setReportIdList($idList->withId($iReportId));
                $request->setAcknowledged(true); //true

                AmazonHelper::invokeUpdateReportAcknowledgements($request, $this->oMWSService);

                $log_text = 'UpdateReportAcknowledgements -> ReportId:' . $iReportId;

                if (!empty($this->sBackProcessLogName)) {
                    func_backprocess_log($this->sBackProcessLogName, $log_text);
                }
            }
        }

        return $this;
    }

    public function setOrder($oOrder)
    {
        $this->oOrder = $oOrder;
    }

    /**
     * @param array $aReportId
     * @return $this
     */
    public function setReportId($aReportId)
    {
        $this->aReportIds = $aReportId;
        return $this;
    }

    public function setReportType($sReportType)
    {
        $this->amazonReportType = $sReportType;
        return $this;
    }

    private function checkLoopExitConditionStatus()
    {
        $res = false;
        if (!empty($this->aWaitLoopExitCondition)) {
            foreach ($this->aWaitLoopExitCondition as $key => $value) {
                if ($this->dom_xml_arr[key($value)] == $value[key($value)]) {
                    $res = true;
                    break;
                }
            }
        } else $res = true;
        return $res;
    }

    public function processReportFulfillmentInventoryData()
    {
        $this->aReportValue = [];
        $ReportContent = AmazonHelper::getReportContent($this->dom_xml_arr);
        $report_data = reset($ReportContent);
        $aReportValue = [];
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $report_data) as $sLine) {
            $arrM = explode("\t", $sLine);
            if (!empty($arrM)) {
                $aReportValue[] = $arrM;
            }
        }
        array_shift($aReportValue);
        if (!empty($aReportValue)) {
            $log_text = "Processing " . (count($aReportValue)) . " rows.";
            $aResult = SQLBuilder::getInstance()->addSelect('max(received_date)', 'rdate')->addFromTable('fba_inventory_receipts')->query_first()->getQueryResult();
            if ($aResult['rdate']) {
                $oMaxdate = \DateTime::createFromFormat('Y-m-d', $aResult['rdate']);
                $log_text .= " Max report date: {$oMaxdate->format('Y-m-d')}";
            }
            if (!empty($this->sBackProcessLogName)) {
                func_backprocess_log($this->sBackProcessLogName, $log_text);
            }
            $cnt = 0;
            foreach ($aReportValue as $vArr) {
                list($date, $fnsku, $sku, $pn, $qty, $fba_shipment_id, $fulfillment_center_id) = $vArr;
                $rDate = \DateTime::createFromFormat(DATE_ISO8601, $date);
                if ((empty($oMaxdate)) || $oMaxdate < $rDate) {
                    Connection::getInstance()->insert('xcart_fba_inventory_receipts',
                        ['received_date' => $rDate->format('Y-m-d'),
                            'sku' => addslashes($sku),
                            'quantity' => $qty,
                            'fba_shipment_id' => $fba_shipment_id
                        ]);
                    $cnt++;
                }

            }
            if ($cnt) {
                $log_text = "Inserted " . $cnt . " new rows";
                if (!empty($this->sBackProcessLogName)) {
                    func_backprocess_log($this->sBackProcessLogName, $log_text);
                }
            }

            Connection::getInstance()->delete('xcart_fba_roi_accounting', array('source' => 'inventory_receipts'));

            $sSql = <<<SQL
SELECT min(received_date) rdate, sku, sum(quantity) qty, fba_shipment_id 
FROM xcart_fba_inventory_receipts 
GROUP BY sku, fba_shipment_id
SQL;
            $aResult = SQLBuilder::getInstance()->setQuery($sSql)->query()->getQueryResult();
            if (!empty($aResult)) {
                foreach ($aResult as $aAggData) {
                    $oProduct = Product::model()->getProductBySKU($aAggData['sku']);
                    if ($oProduct->getProductId()) {
                        $oDate = \DateTime::createFromFormat('Y-m-d', $aAggData['rdate']);
                        Connection::getInstance()->insert('xcart_fba_roi_accounting', [
                            'edate' => $aAggData['rdate'],
                            'productid' => $oProduct->getProductId(),
                            'credit' => round(($oProduct->getProductCostToUs($oDate) * $aAggData['qty']), 2),
                            'debit' => 0,
                            'orderid' => 0,
                            'account' => 'notes_payable',
                            'source' => 'inventory_receipts'
                        ]);
                        Connection::getInstance()->insert('xcart_fba_roi_accounting', [
                            'edate' => $aAggData['rdate'],
                            'productid' => $oProduct->getProductId(),
                            'debit' => 0,
                            'credit' => round(($oProduct->getProductCostToUs($oDate) * $aAggData['qty']), 2),
                            'orderid' => 0,
                            'account' => 'cash',
                            'source' => 'inventory_receipts'
                        ]);
                        Connection::getInstance()->insert('xcart_fba_roi_accounting', [
                            'edate' => $aAggData['rdate'],
                            'productid' => $oProduct->getProductId(),
                            'debit' => round(($oProduct->getProductCostToUs($oDate) * $aAggData['qty']), 2),
                            'credit' => 0,
                            'orderid' => 0,
                            'account' => 'cash',
                            'source' => 'inventory_receipts'
                        ]);
                        Connection::getInstance()->insert('xcart_fba_roi_accounting', [
                            'edate' => $aAggData['rdate'],
                            'productid' => $oProduct->getProductId(),
                            'debit' => round(($oProduct->getProductCostToUs($oDate) * $aAggData['qty']), 2),
                            'credit' => 0,
                            'orderid' => 0,
                            'account' => 'inventory',
                            'source' => 'inventory_receipts'
                        ]);
                    }
                }
            }
        }
    }

    private function fillReportFeeDataFromFile()
    {
        $this->aReportValue = [];
        $ReportContent = AmazonHelper::getReportContent($this->dom_xml_arr);

        if (!empty($ReportContent)) {

            $log_text = "Processing " . count($ReportContent) . " reports";
            if (!empty($this->sBackProcessLogName)) {
                func_backprocess_log($this->sBackProcessLogName, $log_text);
            }

            foreach ($ReportContent as $report_data) {
                $cntLine = 0;
                if ($this->bEnableLog && $this->sLogPrefix && !empty($report_data)) {
                    $log = new \Monolog\Logger('fee_report');
                    $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                    $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
                    $log->addDebug($report_data);
                }
                $aReportValue = [];
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $report_data) as $sLine) {
                    $arrM = explode("\t", $sLine);
                    if (!empty($arrM)) {
                        if ($cntLine == 0) {
                            foreach ($arrM as &$value)
                                $value = str_replace('-', '_', $value);
                        }
                        $aReportValue[] = $arrM;
                    }
                    $cntLine++;
                }

                $aReportData = [];
                $log_text = "Processing " . ($cntLine - 2) . " products";
                if (!empty($this->sBackProcessLogName)) {
                    func_backprocess_log($this->sBackProcessLogName, $log_text);
                }
                for ($y = 0; $y < count($aReportValue); $y++) {
                    foreach ($aReportValue[$y] as $iKey => $sItem) {
                        if ($y > 0) {
                            $aReportData[$y][$aReportValue[0][$iKey]] = $sItem;
                            if ($aReportValue[0][$iKey] == 'sku') {
                                $oProduct = (new Product())->getProductBySKU($sItem);
                                if ($oProduct->getProductId()) {
                                    $aReportData[$y]['productid'] = (int) $oProduct->getProductId();
                                }
                            }
                        }
                    }
                }
                $this->aReportValue[] = $aReportData;
            }
        }
    }

    public function processReportFeeData()
    {
        $allItemsCount = 0;
        $skippedItemsCount = 0;

        $this->fillReportFeeDataFromFile();

        $aFieldsToUpdate = ['productid', 'fnsku', 'asin', 'longest_side', 'median_side', 'shortest_side', 'length_and_girth', 'unit_of_dimension',
            'item_package_weight', 'unit_of_weight', 'product_size_tier', 'estimated_fee_total', 'estimated_referral_fee_per_unit', 'estimated_variable_closing_fee',
            'estimated_order_handling_fee_per_order', 'estimated_pick_pack_fee_per_unit', 'estimated_weight_handling_fee_per_unit', 'amazon_fee_preview_last_update_date',
            'expected_fulfillment_fee_per_unit', 'estimated_future_order_handling_fee_per_order', 'estimated_future_pick_pack_fee_per_unit',
            'estimated_future_weight_handling_fee_per_unit', 'expected_future_fulfillment_fee_per_unit'];

        $aFieldsToUpdate = array_flip($aFieldsToUpdate);
        foreach ($this->aReportValue as $aReport) {
            foreach ($aReport as $aItem) {
                if (!empty($aArrInsert['productid'])) {
                    $model = AmazonProductsFieldsModel::objects()->get(['productid' => $aArrInsert['productid']]);
                    $aArrInsert = array_intersect_key($aItem, $aFieldsToUpdate);
                    if (!is_numeric($aArrInsert['expected_fulfillment_fee_per_unit'])) {
                        $skippedItemsCount++;
                        continue;
                    }
                    $aArrInsert['amazon_fee_preview_last_update_date'] = time();
                    $aArrInsert['estimated_fee_total'] = floatval($aArrInsert['estimated_fee_total']);
                    $aArrInsert['estimated_referral_fee_per_unit'] = floatval($aArrInsert['estimated_referral_fee_per_unit']);
                    $aArrInsert['estimated_variable_closing_fee'] = floatval($aArrInsert['estimated_variable_closing_fee']);
                    $aArrInsert['estimated_order_handling_fee_per_order'] = floatval($aArrInsert['estimated_order_handling_fee_per_order']);
                    $aArrInsert['estimated_pick_pack_fee_per_unit'] = floatval($aArrInsert['estimated_pick_pack_fee_per_unit']);
                    $aArrInsert['estimated_weight_handling_fee_per_unit'] = floatval($aArrInsert['estimated_weight_handling_fee_per_unit']);
                    $aArrInsert['expected_fulfillment_fee_per_unit'] = floatval($aArrInsert['expected_fulfillment_fee_per_unit']);
                    $aArrInsert['estimated_future_order_handling_fee_per_order'] = floatval($aArrInsert['estimated_future_order_handling_fee_per_order']);
                    $aArrInsert['estimated_future_pick_pack_fee_per_unit'] = floatval($aArrInsert['estimated_future_pick_pack_fee_per_unit']);
                    $aArrInsert['expected_fulfillment_fee_per_unit'] = floatval($aArrInsert['expected_fulfillment_fee_per_unit']);
                    $aArrInsert['expected_future_fulfillment_fee_per_unit'] = floatval($aArrInsert['expected_future_fulfillment_fee_per_unit']);
                    $model->setAttributes($aArrInsert);
                    $allItemsCount++;
                    $model->save();
                }
            }
        }

        if ($skippedItemsCount > 0) {
            func_backprocess_log($this::BACK_PROCESS_LOG_NAME, "processReportFeeData: skipped Items count: {$skippedItemsCount}; all items count: {$allItemsCount}");
        }

        return $this;
    }

    public function processReportSettlementData()
    {
        $log = null;
        $ReportContent = AmazonHelper::getReportContent($this->dom_xml_arr);
        if (!empty($ReportContent)) {
            $log_text = "Processing " . count($ReportContent) . " reports";
            func_backprocess_log(self::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

            if ($this->bEnableLog && $this->sLogPrefix) {
                $log = new \Monolog\Logger('settlement_report');
                $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
            }

            foreach ($ReportContent as $report_id => $report_data) {
                if ($this->bEnableLog && $log) {
                    $log->debug("SettlementReportId data:{$report_id}", [$report_data]);
                }

                $aOrderDetails = [];
                $findme_arr = array("Order", "Refund", "Fee", "Component", "Item", "AdjustedItem");
                foreach ($findme_arr as $findme) {
                    $pos = strpos($report_data, "<$findme>");
                    if ($pos !== "false") {
                        $dom_xml_arr = explode("<$findme>", $report_data);
                        $count_dom_xml_arr = count($dom_xml_arr);
                        $report_data = "";
                        foreach ($dom_xml_arr as $k => $v) {
                            $k_n = $k - 1;
                            $v = str_replace("</$findme>", "</$findme$k_n>", $v);
                            $report_data .= $v . ($k != ($count_dom_xml_arr - 1) ? "<$findme$k>" : "");
                        }
                    }
                }
                $aXmlReportConent = func_xml2hash($report_data, "UTF-8");
                if (!empty($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"]) && is_array($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"])) {
                    foreach ($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"] as $k => $v) {
                        $RefundSum = 0;
                        $k_name = '';
                        if (strpos($k, "Order") !== false) {
                            $k_name = "Item";
                        } elseif (strpos($k, "Refund") !== false) {
                            $k_name = "AdjustedItem";
                        }
                        if ($k_name == 'AdjustedItem' && !empty($v["AdjustmentID"])) $v["ShipmentID"] = $v["AdjustmentID"];
                        if (!empty($v["AmazonOrderID"]) && !empty($v["ShipmentID"])) {
                            if ($v['MarketplaceName'] == 'Non-Amazon') {
                                preg_match("/\w+-(\d+)[-]?(\d+)?/", $v['MerchantOrderID'], $aMatchArray);
                                if (!empty($aMatchArray)) {
                                    if (!empty($aMatchArray[1])) {
                                        $iOrderId = intval($aMatchArray[1]);
                                        $order_info = func_query_first("SELECT orderid FROM " . $this->sql_tbl['orders'] . " WHERE orderid=$iOrderId");
                                    }
                                }
                            } else {
                                $order_info = func_query_first("SELECT orderid FROM " . $this->sql_tbl['orders'] . " WHERE amazonorderid='$v[AmazonOrderID]'");
                            }
                            if (!empty($order_info)) {
                                $log_text = "order processed: " . $v["AmazonOrderID"];
                                func_backprocess_log(self::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);
                                foreach ($v["Fulfillment"] as $kk => $vv) {
                                    if ($k_name == "Item") {
                                        if (!empty($vv["ItemFees"])) {
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Quantity'] += $vv['Quantity'];
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Fee';

                                            foreach ($vv["ItemFees"] as $kkk => $vvv) {
                                                if (in_array($vvv["Type"], array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee", "Commission", "ShippingChargeback"))) {
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] += floatval($vvv["Amount"]);
                                                }
                                            }
                                        }
                                        if (!empty($vv["ItemPrice"])) {
                                            foreach ($vv["ItemPrice"] as $kkk => $vvv) {
                                                if (in_array($vvv["Type"], array("Principal", "Shipping"))) {
                                                    //$aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] = floatVal($vvv["Amount"]);
                                                }
                                            }
                                        }
                                        if (!empty($vv["Promotion"])) {
                                            if (in_array($vv["Promotion"]["Type"], array("Shipping"))) {
                                                //$aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += abs(floatval($vv["Promotion"]["Amount"]));
                                            }

                                        }
                                    } elseif ($k_name == "AdjustedItem") {

                                        if (!empty($vv["ItemPriceAdjustments"]) && is_array($vv["ItemPriceAdjustments"])) {
                                            foreach ($vv["ItemPriceAdjustments"] as $kkk => $vvv) {
                                                $field_name = $vvv["Type"];
                                                if (($field_name == "Principal" || $field_name == "Shipping") && !empty($vvv["Amount"])) {
                                                    $RefundSum += $vvv["Amount"];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += floatval($vvv["Amount"]);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Refund';
                                                }
                                                switch ($field_name) {
                                                    case "Principal":
                                                        $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['PrincipalRefund'] = floatval($vvv["Amount"]);
                                                        break;
                                                    case "Shipping":
                                                        $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['ShippingRefund'] = floatval($vvv["Amount"]);
                                                        break;
                                                }
                                            }
                                        }
                                        if (!empty($vv["ItemFeeAdjustments"]) && is_array($vv["ItemFeeAdjustments"])) {
                                            foreach ($vv["ItemFeeAdjustments"] as $kkk => $vvv) {
                                                $field_name = $vvv["Type"];
                                                if (($field_name == "Commission" || $field_name == "RefundCommission" || $field_name == "VariableClosingFee" || $field_name == "ShippingChargeback") && !empty($vvv["Amount"])) {
                                                    $RefundSum += $vvv["Amount"];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += floatval($vvv["Amount"]);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Refund';
                                                }
                                            }
                                        }
                                    }
                                }
                                if (!empty($v["ShipmentFees"])) {
                                    foreach ($v["ShipmentFees"] as $kkk => $vvv) {
                                        if (in_array($vvv["Type"], array("FBATransportationFee"))) {
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] += floatval($vvv["Amount"]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($aOrderDetails)) {
                    foreach ($aOrderDetails as $sAmazonOrderId => $aShippings) {
                        foreach ($aShippings as $sShippingId => $aAmazonCodes) {
                            foreach ($aAmazonCodes as $sAmazonCode => $aFees) {
                                $oOrderAmazonDetail = OrderAmazonDetail::create([
                                    'FBAPerOrderFulfillmentFee' => floatval($aFees['FBAPerOrderFulfillmentFee']),
                                    'FBAPerUnitFulfillmentFee' => floatval($aFees['FBAPerUnitFulfillmentFee']),
                                    'FBATransportationFee' => floatval($aFees['FBATransportationFee']),
                                    'FBAWeightBasedFee' => floatval($aFees['FBAWeightBasedFee']),
                                    'ShippingFee' => floatval($aFees['ShippingChargeback']),
                                    'AmazonCommission' => floatval($aFees['Commission']),
                                    'Principal' => floatval($aFees['Principal']),
                                    'PrincipalRefund' => floatval($aFees['PrincipalRefund']),
                                    'Shipping' => floatval($aFees['Shipping']),
                                    'ShippingRefund' => floatval($aFees['ShippingRefund']),
                                    'Quantity' => intval($aFees['Quantity']),
                                    'type' => $aFees['type'],
                                    'SKU' => $aFees['SKU'],
                                    'AmazonShipmentID' => $sShippingId,
                                    'AmazonOrderItemCode' => $sAmazonCode,
                                    'Refund' => floatval(abs($aFees['Refund'])),
                                    'reportId' => $report_id,
                                    'orderid' => intval($aFees['orderid']),
                                ]);
                                $iOrderId = $oOrderAmazonDetail->orderid;
                                $oProduct = null;
                                /** @var Order $oOrder */
                                $oOrder = Order::objects()->filter(['orderid' => $iOrderId])->get();
                                if ($oOrder) {
                                    $aOrderProducts = $oOrder->getOrderProducts();
                                    if (!empty($aOrderProducts)) {
                                        $sSKU = $oOrderAmazonDetail->SKU;
                                        if (!in_array($sSKU, array_map(function (/** @var Product $oP */
                                            $oP) {
                                            return $oP->getSKU();
                                        }, $aOrderProducts))
                                        ) {
                                            /** @var Product $oOrderProduct */
                                            foreach ($aOrderProducts as $oOrderProduct) {
                                                $oParentProduct = $oOrderProduct->getParentProduct();
                                                $aChildAndParentProducts = $oOrderProduct->getChildProducts();
                                                if ($oParentProduct) {
                                                    $aChildAndParentProducts[] = $oOrderProduct->getParentProduct();
                                                }
                                                $oProductFinded = array_filter(
                                                    $aChildAndParentProducts,
                                                    function ($e) use ($sSKU) {
                                                        return $e->productcode == $sSKU;
                                                    });
                                                if (!empty($oProductFinded)) {
                                                    $oProduct = $oOrderProduct;
                                                    break;
                                                }
                                            }
                                        } else {
                                            $oProduct = Product::objects()->filter(['productcode' => $sSKU])->get();
                                        }
                                        if ($oProduct) {
                                            if ($oOrderAmazonDetail->SKU != $oProduct->getSKU()) {
                                                $oOrderAmazonDetail->_delete();
                                                $oOrderAmazonDetail->SKU = $oProduct->getSKU();
                                            }
                                            $oOrderAmazonDetail->manufacturerid = $oProduct->manufacturerid;
                                        }
                                    }
                                }
                                if ($oOrderAmazonDetail) {
                                    $oOrderAmazonDetail->_insert(true);
                                    $aUpdateValues = func_query_first("SELECT SUM(FBAPerOrderFulfillmentFee) AS FBAPerOrderFulfillmentFee,
                                                     SUM(FBAPerUnitFulfillmentFee) AS FBAPerUnitFulfillmentFee,
                                                     SUM(FBATransportationFee) AS FBATransportationFee,
                                                     SUM(FBAWeightBasedFee) AS FBAWeightBasedFee,
                                                     SUM(AmazonCommission) AS AmazonCommission,
                                                     SUM(ShippingFee) AS ShippingFee,
                                                     SUM(Refund) AS Refund,
                                                     SUM(Shipping) AS Shipping,
                                                     SUM(ShippingRefund) AS ShippingRefund,
                                                     SUM(PrincipalRefund) AS PrincipalRefund,
                                                     count(1) as Rows
                                                     FROM xcart_order_amazon_details WHERE orderid = {$iOrderId} AND SKU = '{$oOrderAmazonDetail->SKU}'");
                                    if ($aUpdateValues['Rows'] > 0) {
                                        if ($aUpdateValues['Refund'] != 0) {
                                            $aUpdateValues['amazon_item_refunded'] = 'Y';
                                        }
                                        unset ($aUpdateValues['Refund']);
                                        unset($aUpdateValues['Rows']);
                                        unset($aUpdateValues['PrincipalRefund']);
                                        unset($aUpdateValues['Shipping']);
                                        unset($aUpdateValues['FBATransportationFee']);
                                        unset($aUpdateValues['ShippingRefund']);
                                        func_array2update('order_details', $aUpdateValues, "orderid = $iOrderId AND productcode='$oOrderAmazonDetail->SKU'");
                                        if (!empty($oOrderAmazonDetail->manufacturerid)) {
                                            $oOrderGroup = new OrderGroup(['orderid' => $iOrderId, 'manufacturerid' => $oOrderAmazonDetail->manufacturerid]);
                                            $oOrderGroup->recalculateAccounting();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function _Request($_request)
    {

        $methodName = 'do' . $_request;
        $this->$methodName();

        while ((!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") || !$this->checkLoopExitConditionStatus()) {
            func_flush("sleeping...");
            func_flush();
            sleep($this->sleepTimeOut);
            func_flush("Unsleeped");
            func_flush();
            $this->$methodName();
            if (!empty($this->error)) return $this;
        }
        return $this;
    }


    /**
     * @param OrderGroup $oOrderGroup
     * @param string $sAmazonShippingMethodSelect
     * @return bool
     */
    public function shipOrderGroupByAmazon(OrderGroup $oOrderGroup, $sAmazonShippingMethodSelect)
    {
        global $login;
        $oOrder = $oOrderGroup->getOrderInstance();
        $log = "Try to place order shipping by Amazon\n";

        if ($oOrder->getOrderGroupsCount() == 1 && $oOrderGroup->getOrderGroupStatusCB() == 'AP') {
            if (!$oOrder->captureOrderAmount()) {
                func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
                return false;
            }
        }
        $oOrderGroup->_refresh();
        if ($oOrderGroup->getOrderGroupStatusCB() != 'P') {
            $log .= "Shipping order by Amazon - failed. Order group status not Paid.\n";
            func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
            return false;
        }

        $address = new \FBAOutboundServiceMWS_Model_Address();

        $address->setName($oOrder->getClientShippingName());
        $address->setLine1($oOrder->getField('s_address'));
        $address->setCity($oOrder->getField('s_city'));
        $address->setStateOrProvinceCode($oOrder->getField('s_state'));
        $address->setCountryCode($oOrder->getField('s_country'));
        $address->setPostalCode($oOrder->getField('s_zipcode'));
        $sPhone = $oOrder->getField('phone');
        if (!empty($sPhone))
            $address->setPhoneNumber($sPhone);

        $aProducts = $oOrderGroup->getOrderGroupProducts();
        if (!empty($aProducts)) {
            $list = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList();

            foreach ($aProducts as $oProduct) {
                $iAmount = 0;
                $item = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem();

                $aOrderDetails = OrderDetail::getOrderDetailsByOrderIdAndProductId($oOrderGroup->getOrderId(), $oProduct->getProductId());
                foreach ($aOrderDetails as $oOrderDetail) {
                    $iAmount += $oOrderDetail->getAmount();
                }
                $aProductsQty = $oProduct->getProductsAvailOnAmazonParentWithChild($iAmount);
                if (!empty($aProductsQty)) {
                    foreach ($aProductsQty as $aFBAAvail) {
                        $item->setSellerSKU($aFBAAvail['oProduct']->getSKU());
                        $item->setSellerFulfillmentOrderItemId($aFBAAvail['oProduct']->getSKU());
                        $item->setQuantity($aFBAAvail['qty']);
                    }
                } else {
                    $item->setSellerSKU($oProduct->getSKU());
                    $item->setSellerFulfillmentOrderItemId($oProduct->getSKU());
                    $item->setQuantity($iAmount);
                }
                $list->withmember($item);
            }

            $req = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest();
            $req->setSellerId(MERCHANT_ID);
            $req->setSellerFulfillmentOrderId($oOrderGroup->getAmazonShippingOrderId());
            $req->setDisplayableOrderId($oOrderGroup->getAmazonShippingOrderId());
            $req->setFulfillmentAction('Ship');
            $req->setFulfillmentPolicy('FillOrKill');

            $req->setDisplayableOrderDateTime($oOrder->getOrderDate(\DateTime::ISO8601));
            $sDisplayAmazonOrderComment = self::DEFAULT_ORDER_MESSAGE;
            $sAmazonShippingNotes = $oOrderGroup->getAmazonShipmentNotes();
            if (!empty($sAmazonShippingNotes))
                $sDisplayAmazonOrderComment = $sAmazonShippingNotes;
            $sDisplayAmazonOrderComment = stripslashes($sDisplayAmazonOrderComment);

            $req->setDisplayableOrderComment($sDisplayAmazonOrderComment);
            $req->setShippingSpeedCategory($sAmazonShippingMethodSelect);
            $req->setDestinationAddress($address);

            $req->setItems($list);

            try {
                $response = $this->oMWSService->CreateFulfillmentOrder($req);

                $log .= "Amazon shipping order placed: <a href='" . sprintf(self::AMAZON_ORDER_LINK, $oOrderGroup->getAmazonShippingOrderId()) . "' target='_blank'>" . $oOrderGroup->getAmazonShippingOrderId() . "</a> \n";
                $log .= "DisplayableOrderComment: " . $sDisplayAmazonOrderComment . "\n";
                $log .= "ShippingSpeedCategory: " . $sAmazonShippingMethodSelect . "\n";
                $oOrderGroup->updateField('amz_fullfilment_order_placed', 'Y');
                $oOrderGroup->changeOrderGroupStatusDC('L');


            } catch (\FBAOutboundServiceMWS_Exception $ex) {
                $log .= "Caught Exception: " . $ex->getMessage() . "\n";
                $log .= "Response Status Code: " . $ex->getStatusCode() . "\n";
                $log .= "Error Code: " . $ex->getErrorCode() . "\n";
                $log .= "Error Type: " . $ex->getErrorType() . "\n";
                $log .= "Request ID: " . $ex->getRequestId() . "\n";
                $log .= "XML: " . $ex->getXML() . "\n";
                $log .= "ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n";
            }

            func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
        }
        return true;
    }

    public function processReportReservedInventory()
    {
        $this->aReportValue = [];
        $ReportContent = AmazonHelper::getReportContent($this->dom_xml_arr);
        if (!empty($ReportContent)) {
            foreach ($ReportContent as $report_id => $report_data) {
                if ($this->bEnableLog && $this->sLogPrefix) {
                    $log = new \Monolog\Logger('amazon_info');
                    $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                    $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
                    $log->addDebug($report_data);
                }
                $cntLine = 0;
                $aReportValue = [];
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $report_data) as $sLine) {
                    echo $sLine . "<br/>";
                    $arrM = explode("\t", $sLine);
                    if (!empty($arrM)) {
                        if ($cntLine == 0) {
                            foreach ($arrM as &$value)
                                $value = str_replace('-', '_', $value);
                        }
                        $aReportValue[] = $arrM;
                    }
                    $cntLine++;
                }
                $aReportData = [];
                $log_text = "Processing Reserved Inventory Report for " . ($cntLine - 2) . " products";
                func_backprocess_log($this->sBackProcessLogName, $log_text);
                for ($y = 0; $y < count($aReportValue); $y++) {
                    foreach ($aReportValue[$y] as $iKey => $sItem) {

                        if ($y == 0) {

                        } else {
                            if ($aReportValue[0][$iKey] == 'sku') {
                                $iProductId = Product::getProductBySKU($sItem)->getProductId();
                            }
                            $aReportData[$iProductId][$aReportValue[0][$iKey]] = $sItem;
                        }
                    }
                }
                $this->aReportValue[] = $aReportData;
            }

            $report_date = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

            foreach ($this->aReportValue as $aReport)
                foreach ($aReport as $iProductId => $aItem) {
                    $params = ['productcode' => $aItem['sku'], 'productid' => $iProductId, 'report_date' => $report_date];
                    if ($oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params)) {
                        $oAmazonProductModel->setAttributes(
                            ['reserved_qty' => $aItem['reserved_qty'],
                                'reserved_customerorders' => $aItem['reserved_customerorders'],
                                'reserved_fc_transfers' => $aItem['reserved_fc_transfers'],
                                'reserved_fc_processing' => $aItem['reserved_fc_processing'],
                                'productid' => $iProductId,
                                'productcode' => $aItem['sku'],
                                'report_date' => $report_date]);
                        if (!empty($aItem['asin'])) {
                            $oAmazonProductModel->ASIN = $aItem['asin'];
                        }
                        if ($oAmazonProductModel->productid) {
                            $oAmazonProductModel->save();
                        }
                    }
                }
        }
        return $this;
    }

    public function groupAmazonFBAProducts()
    {

        global $config;
        /** @var CidevAmazonFbaProducts[][] $aAggregateRows */
        /** @var CidevAmazonFbaProducts[] $aFbaProducts */

        for ($i = 24; $i >= $config['Amazon_FBA_options']['Amazon_FBA_Month']; $i--) {
            $aAggregateRows = $aAggregateStat = $aFbaProducts = null;
            $currentDate = new \DateTime(date('Y-m-d', strtotime("first day of this month")));
            $currentDate->sub(new \DateInterval('P' . $i . 'M'));

            $aFbaProducts = CidevAmazonFbaProducts::model()->findAll(SQLBuilder::getInstance()->addCondition("report_date < " . $currentDate->getTimestamp())->addCondition("precise_data='Y'"));
            if ($aFbaProducts) {
                $log_text = 'Aggregate FBA products data for %s products. Period %s';
                func_backprocess_log($this->sBackProcessLogName, sprintf($log_text, count($aFbaProducts), $currentDate->format('d-M-Y')));
                foreach ($aFbaProducts as $oFbaProduct) {
                    $iReportTimeStamp = $oFbaProduct->getReportPeriod()->getTimeStamp();
                    $iProductId = $oFbaProduct->getField('productid');
                    if (!isset($aAggregateRows[$iProductId][$iReportTimeStamp]))
                        $aAggregateRows[$iProductId][$iReportTimeStamp] = CidevAmazonFbaProducts::model();
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('productid', $iProductId);
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('productcode', $oFbaProduct->getField('productcode'));
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('ASIN', $oFbaProduct->getField('ASIN'));
                    if ($oFbaProduct->getField('cpr_LandedPrice') > 0) {
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_LandedPrice')) + floatval($oFbaProduct->getField('cpr_LandedPrice')));
                        $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_LandedPrice']++;
                    }
                    if ($oFbaProduct->getField('cpr_belongs_LandedPrice') > 0) {
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_belongs_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_belongs_LandedPrice')) + floatval($oFbaProduct->getField('cpr_belongs_LandedPrice')));
                        $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_belongs_LandedPrice']++;
                    }
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_SalesRank', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_SalesRank')) + intval($oFbaProduct->getField('cpr_SalesRank')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_SalesRank']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lis_TotalSupplyQuantity', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lis_TotalSupplyQuantity')) + intval($oFbaProduct->getField('lis_TotalSupplyQuantity')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lis_TotalSupplyQuantity']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lis_InStockSupplyQuantity', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lis_InStockSupplyQuantity')) + intval($oFbaProduct->getField('lis_InStockSupplyQuantity')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lis_InStockSupplyQuantity']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_LandedPrice')) + floatval($oFbaProduct->getField('lp_LandedPrice')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_LandedPrice']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('buybox_in', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('buybox_in')) + intval($oFbaProduct->getField('buybox_in')));
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('buybox_out', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('buybox_out')) + intval($oFbaProduct->getField('buybox_out')));

                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_MultipleOfferListingsAtLowestPrice') != 'Y') {
                        if ($oFbaProduct->getField('lp_MultipleOfferListingsAtLowestPrice') == '') {
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_MultipleOfferListingsAtLowestPrice', 'N');
                        } else
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_MultipleOfferListingsAtLowestPrice', $oFbaProduct->getField('lp_MultipleOfferListingsAtLowestPrice'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_MultipleOfferListingsAtLowestPrice']++;

                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_AllOfferListingsConsidered') != 'N') {
                        if ($oFbaProduct->getField('lp_AllOfferListingsConsidered') == '') $oFbaProduct->setField('lp_AllOfferListingsConsidered', 'N');
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_AllOfferListingsConsidered', $oFbaProduct->getField('lp_AllOfferListingsConsidered'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_AllOfferListingsConsidered']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_NumberOfOfferListingsConsidered', max(intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_NumberOfOfferListingsConsidered')), $oFbaProduct->getField('lp_NumberOfOfferListingsConsidered')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_NumberOfOfferListingsConsidered']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_SellerFeedbackCount', $aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_SellerFeedbackCount') + $oFbaProduct->getField('lp_SellerFeedbackCount'));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_SellerFeedbackCount']++;


                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_FulfillmentChannel') != 'AFN') {
                        if ($oFbaProduct->getField('lp_FulfillmentChannel') == '') $oFbaProduct->setField('lp_FulfillmentChannel', 'AFN');
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_FulfillmentChannel', $oFbaProduct->getField('lp_FulfillmentChannel'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_FulfillmentChannel']++;


                    if (intval($oFbaProduct->getField('lp_SellerPositiveFeedbackRating')) > intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_SellerPositiveFeedbackRating')))
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_SellerPositiveFeedbackRating', $oFbaProduct->getField('lp_SellerPositiveFeedbackRating'));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_SellerPositiveFeedbackRating']++;

                    if ($oFbaProduct->getField('lp_ShippingTime') != '') {
                        if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_ShippingTime') == '') $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_ShippingTime', $oFbaProduct->getField('lp_ShippingTime'));
                        if (intval($oFbaProduct->getField('lp_ShippingTime')) <= intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_ShippingTime')))
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_ShippingTime', $oFbaProduct->getField('lp_ShippingTime'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_ShippingTime']++;
                }
            }

            if (!empty($aAggregateRows)) {
                foreach ($aAggregateRows as $iProductId => $aAggregateRow) {
                    foreach ($aAggregateRow as $iPeriod => $oAggregateRow) {
                        if ($aAggregateStat[$iProductId][$iPeriod]['cpr_LandedPrice'])
                            $oAggregateRow->setField('cpr_LandedPrice', round($oAggregateRow->getField('cpr_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['cpr_LandedPrice'], 2));
                        if ($aAggregateStat[$iProductId][$iPeriod]['cpr_belongs_LandedPrice'])
                            $oAggregateRow->setField('cpr_belongs_LandedPrice', round($oAggregateRow->getField('cpr_belongs_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['cpr_belongs_LandedPrice'], 2));
                        $oAggregateRow->setField('cpr_SalesRank', round($oAggregateRow->getField('cpr_SalesRank') / $aAggregateStat[$iProductId][$iPeriod]['cpr_SalesRank']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lis_TotalSupplyQuantity'])
                            $oAggregateRow->setField('lis_TotalSupplyQuantity', round($oAggregateRow->getField('lis_TotalSupplyQuantity') / $aAggregateStat[$iProductId][$iPeriod]['lis_TotalSupplyQuantity']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lis_InStockSupplyQuantity'])
                            $oAggregateRow->setField('lis_InStockSupplyQuantity', round($oAggregateRow->getField('lis_InStockSupplyQuantity') / $aAggregateStat[$iProductId][$iPeriod]['lis_InStockSupplyQuantity']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lp_LandedPrice'])
                            $oAggregateRow->setField('lp_LandedPrice', round($oAggregateRow->getField('lp_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['lp_LandedPrice'], 2));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lp_SellerFeedbackCount'])
                            $oAggregateRow->setField('lp_SellerFeedbackCount', round($oAggregateRow->getField('lp_SellerFeedbackCount') / $aAggregateStat[$iProductId][$iPeriod]['lp_SellerFeedbackCount']));
                        $oAggregateRow->setField('precise_data', 'N');
                        $oAggregateRow->setField('productid', $iProductId);
                        $oAggregateRow->setField('report_date', $iPeriod);
                        $oAggregateRow->_save(true);
                    }
                }
            }

            if ($aFbaProducts) {
                foreach ($aFbaProducts as $oFbaProduct) {
                    $oFbaProduct->_delete();
                }
            }

        }
    }

    private function doOrderListRequest()
    {
        $timeoffset = 24 * 60 * 30 * 300;
        while (!empty($this->nextToken)) {
            if ($this->nextToken == 'start') {
                $request = new \MarketplaceWebServiceOrders_Model_ListOrdersRequest();
                $request->setSellerId(MERCHANT_ID);
                $request->setMarketplaceId(MARKETPLACE_ID);
                $request->setCreatedAfter(gmdate('Y-m-d\TH:i:s\Z', time() - $timeoffset));
                $request->setOrderStatus(['Shipped', 'Unshipped', 'PartiallyShipped', 'Canceled']);
                $this->dom_xml_arr = AmazonHelper::invokeListOrders($request, $this->oMWSService);
            } else {
                $request = new \MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest();
                $request->setNextToken($this->nextToken);
                $request->setSellerId(MERCHANT_ID);
                $this->dom_xml_arr = AmazonHelper::invokeListOrdersByNextToken($request, $this->oMWSService);
            }
            if (!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") {
                return $this;
            }
            $this->processOrderList();
        }
        return $this;
    }

    private function doOrderRequest()
    {
        if (!is_null($this->oOrder)) {
            $request = new \MarketplaceWebServiceOrders_Model_GetOrderRequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setAmazonOrderId($this->oOrder->getField('amazonorderid'));
            // object or array of parameters
            $this->dom_xml_arr = AmazonHelper::invokeGetOrder($request, $this->oMWSService);
        }
        return $this;
    }

    private function doOrderInfoRequest()
    {
        if (!is_null($this->oOrder)) {
            $request = new \MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setAmazonOrderId($this->oOrder->getField('amazonorderid'));
            // object or array of parameters
            $this->dom_xml_arr = AmazonHelper::invokeListOrderItems($request, $this->oMWSService);
        }
        return $this;
    }

    private function processOrderList()
    {
        if (!empty($this->dom_xml_arr)) {
            $docOrders = new \DOMDocument;
            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
            $docOrders->loadXML($this->dom_xml_arr);
            $xpath = new \DOMXPath($docOrders);
            $aOrdersEntries = $xpath->query('/*/*/Orders/Order');
            if (!empty($aOrdersEntries) && $aOrdersEntries->length > 0) {
                foreach ($aOrdersEntries as $k => $orderNode) {
                    $sAmazonOrderId = $orderNode->getElementsByTagName('AmazonOrderId')->item(0)->nodeValue;
                    $sOrderStatus = $orderNode->getElementsByTagName('OrderStatus')->item(0)->nodeValue;
                    $oOrder = Order::model()->find(SQLBuilder::getInstance()->addCondition("amazonorderid='" . $sAmazonOrderId . "'"));
                    if (!$oOrder->getOrderId()) {
                        if (in_array($sOrderStatus, ['Unshipped', 'Shipped'])) {
                            $oOrder->setField('amazonorderid', $sAmazonOrderId);
                            $this->oOrder = $oOrder;
                            $this->_Request('OrderRequest');

                            $docOrders = new \DOMDocument;
                            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
                            $docOrders->loadXML($this->dom_xml_arr);
                            $xpath2 = new \DOMXPath($docOrders);

                            $aOrderInfo = $xpath2->query('/GetOrderResponse/GetOrderResult/Orders/Order')->item(0);


                            print("ORDER INFO: \r\n");
                            func_print_r($aOrderInfo->nodeValue);
                            $log_text = "Processing order: " . $oOrder->getField('amazonorderid') . "  status: " . $sOrderStatus;
                            print($log_text . "\r\n");


                            func_backprocess_log(self::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

                            $this->_Request('OrderInfoRequest');

                            $docOrders = new \DOMDocument;
                            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
                            $docOrders->loadXML($this->dom_xml_arr);
                            $xpath3 = new \DOMXPath($docOrders);
                            $aOrderItems = $xpath3->query('/ListOrderItemsResponse/ListOrderItemsResult/OrderItems/OrderItem');

                            if (!empty($aOrderItems) && $aOrderItems->length > 0) {
                                $sAddress = $sShippingAddressCity = $sShippingAddressStateOrRegion = $sShippingAddressCountryCode = $sShippingAddressPostalCode = $sShippingAddressPhone = null;
                                $oOrder->setField('orderid', $oOrder->_insert());
                                $oOrderRaw = CidevAmazonOrderRaw::model()->find(SQLBuilder::getInstance()->addCondition('orderid = ' . $oOrder->getOrderId()));
                                $oOrderRaw->setField('orderid', $oOrder->getOrderId())->
                                setField('order_info', addslashes($xpath2->document->saveXML()))->
                                setField('orderitems_info', addslashes($xpath3->document->saveXML()));
                                $oOrderRaw->_insert(true);

                                $sOrderTotal = $aOrderInfo->getElementsByTagName('OrderTotal')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue;
                                $oShippingAddress = $aOrderInfo->getElementsByTagName('ShippingAddress')->item(0);
                                if (!empty($oShippingAddress)) {
                                    $sShippingAddressName = addslashes($oShippingAddress->getElementsByTagName('Name')->item(0)->nodeValue);
                                    $sShippingAddressCity = addslashes($oShippingAddress->getElementsByTagName('City')->item(0)->nodeValue);
                                    $sShippingAddressCountryCode = addslashes($oShippingAddress->getElementsByTagName('CountryCode')->item(0)->nodeValue);
                                    $sShippingAddressPhone = addslashes($oShippingAddress->getElementsByTagName('Phone')->item(0)->nodeValue);
                                    $sShippingAddressPostalCode = addslashes($oShippingAddress->getElementsByTagName('PostalCode')->item(0)->nodeValue);
                                    $sShippingAddressStateOrRegion = addslashes($oShippingAddress->getElementsByTagName('StateOrRegion')->item(0)->nodeValue);
                                    $StateOrRegion_code = State::model()->find(SQLBuilder::getInstance()->addCondition("country_code = '$sShippingAddressCountryCode'")->addCondition("state = '$sShippingAddressStateOrRegion'"))->getField('code');
                                    if (!empty($StateOrRegion_code)) {
                                        $sShippingAddressStateOrRegion = addslashes($StateOrRegion_code);
                                    }
                                    $sAddress = addslashes($oShippingAddress->getElementsByTagName('AddressLine1')->item(0)->nodeValue .
                                        (!empty($oShippingAddress->getElementsByTagName('AddressLine2')->item(0)->nodeValue) ? ' ' . $oShippingAddress->getElementsByTagName('AddressLine2')->item(0)->nodeValue : '') .
                                        (!empty($oShippingAddress->getElementsByTagName('AddressLine3')->item(0)->nodeValue) ? ' ' . $oShippingAddress->getElementsByTagName('AddressLine3')->item(0)->nodeValue : ''));
                                }

                                $sFulfilmentChanel = $aOrderInfo->getElementsByTagName('FulfillmentChannel')->item(0)->nodeValue;

                                $sBuyerName = addslashes($aOrderInfo->getElementsByTagName('BuyerName')->item(0)->nodeValue);


                                $oOrder->
                                setField('order_prefix', 'AZ-')->
                                setField('login', 'amazon')->
                                setField('amazon_fulfillment_channel', $sFulfilmentChanel)->
                                setField('total', $sOrderTotal)->
                                setField('subtotal', $sOrderTotal)->
                                setField('date', strtotime($aOrderInfo->getElementsByTagName('PurchaseDate')->item(0)->nodeValue))->
                                setField('cb_status', ($sOrderStatus == 'Canceled' ? 'A' : 'P'))->
                                setField('dc_status', ($sOrderStatus == 'Unshipped' ? 'T' : 'S'))->
                                setField('bd_status', 'W')->
                                setField('payment_method', 'Amazon Seller')->
                                setField('firstname', $sBuyerName)->
                                setField('s_firstname', (empty($sShippingAddressName) ? $sBuyerName : $sShippingAddressName))->
                                setField('s_address', $sAddress)->
                                setField('s_city', $sShippingAddressCity)->
                                setField('s_state', $sShippingAddressStateOrRegion)->
                                setField('s_country', $sShippingAddressCountryCode)->
                                setField('s_zipcode', $sShippingAddressPostalCode)->
                                setField('b_firstname', $sBuyerName)->
                                setField('b_address', $sAddress)->
                                setField('b_city', $sShippingAddressCity)->
                                setField('b_state', $sShippingAddressStateOrRegion)->
                                setField('b_country', $sShippingAddressCountryCode)->
                                setField('b_zipcode', $sShippingAddressPostalCode)->
                                setField('phone', $sShippingAddressPhone)->
                                setField('email', addslashes($aOrderInfo->getElementsByTagName('BuyerEmail')->item(0)->nodeValue))->
                                setField('language', 'US')->
                                setField('storefrontid', 0)->
                                setField('fraud_status', 'C')->
                                setField('overall_fraud_score', 50)->
                                setField('tracking_all_filled', 'N')->
                                setField('vn_status', ($sFulfilmentChanel == 'AFN' ? 'PV' : 'NS'));
                                $oOrder->_update();


                                $aManufacturerid_arr = [];
                                $product_total = 0;

                                foreach ($aOrderItems as $oOrderItem) {
                                    $sAmazonSKU = addslashes($oOrderItem->getElementsByTagName('SellerSKU')->item(0)->nodeValue);
                                    $oProduct = Product::model()->getProductBySKU($sAmazonSKU);

                                    if (!$oProduct->getProductId()) {
                                        /*search product in fba_missing_sku*/
                                        $oFbaMissing = FbaMissingSku::model(['missing_productcode' => $sAmazonSKU]);
                                        if ($oFbaMissing->getProductId()) {
                                            $oProduct = Product::model(['productid' => $oFbaMissing->getProductId()]);
                                        } else
                                            if ($oFbaMissing->getField('missing_productcode') == '') {
                                                $oFbaMissing->setField('missing_productcode', $sAmazonSKU)->setField('productid', 0)->_insert();
                                                global $config;
                                                $oProduct->setField('productcode', $sAmazonSKU);
                                                $to = $config['Company']['product_management'];
                                                $from = 'team@s3stores.com';
                                                func_send_mail($to, 'mail/missing_sku_subj.tpl', 'mail/missing_sku.tpl', $from, true);
                                            }
                                    }

                                    $iOrderQuantity = intval($oOrderItem->getElementsByTagName('QuantityOrdered')->item(0)->nodeValue);
                                    if ($iOrderQuantity > 0) {
                                        $oOrderDetail = OrderDetail::model()->
                                        setField('orderid', $oOrder->getOrderId())->
                                        setField('productid', $oProduct->getProductId())->
                                        setField('item_cost_to_us', $oProduct->getProductCostToUs())->
                                        setField('price', floatval($oOrderItem->getElementsByTagName('ItemPrice')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue) / $iOrderQuantity)->
                                        setField('amount', $iOrderQuantity)->
                                        setField('productcode', $oProduct->getSKU())->
                                        setField('AmazonOrderItemCode', addslashes($oOrderItem->getElementsByTagName('OrderItemId')->item(0)->nodeValue))->
                                        setField('product', addslashes($oProduct->getProductName()));
                                        $oOrderDetail->_insert();

                                        if (!in_array($oProduct->getManufacturerId(), $aManufacturerid_arr)) {
                                            $fShippingPrice = $fShippingDiscount = 0;

                                            $oShippingPrice = $oOrderItem->getElementsByTagName('ShippingPrice');
                                            if ($oShippingPrice && $oShippingPrice->length > 0) {
                                                $fShippingPrice = floatval($oOrderItem->getElementsByTagName('ShippingPrice')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue);
                                            }
                                            $oShippingDiscount = $oOrderItem->getElementsByTagName('ShippingDiscount');
                                            if ($oShippingDiscount && $oShippingDiscount->length > 0) {
                                                $fShippingDiscount = floatval($oOrderItem->getElementsByTagName('ShippingDiscount')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue);
                                            }


                                            $oOrderGroup = OrderGroup::model(['orderid' => $oOrder->getOrderId(), 'manufacturerid' => $oProduct->getManufacturerId()]);
                                            $oOrderGroup->setField('shipping', addslashes($aOrderInfo->getElementsByTagName('ShipmentServiceLevelCategory')->item(0)->nodeValue))->
                                            setField('cb_status', ($sOrderStatus == 'Canceled' ? 'A' : 'P'))->
                                            setField('dc_status', ($sOrderStatus == 'Unshipped' ? 'T' : 'S'))->
                                            setField('acc_paymentid', PaymentMethod::model()->find(SQLBuilder::getInstance()->addCondition("order_tag_preference='$sFulfilmentChanel'"))->getField('paymentid'))->
                                            setField('bd_status', 'W')->
                                            setField('shipping_gross', $oOrderGroup->getShippingGross() + ($fShippingPrice - $fShippingDiscount));
                                            if (!$oOrderGroup->getOrderId()) {
                                                $oOrderGroup->setField('orderid', $oOrder->getOrderId())->
                                                setField('manufacturerid', $oProduct->getManufacturerId())->_insert();
                                            } else {
                                                $oOrderGroup->_update();
                                            }
                                        }
                                        $product_total += $oOrderDetail->getTotalProductPrice();
                                    }


                                }

                                $oOrder->updateVerificationStatus()->reCalculateTotals();
                                $oOrder->recalculateAccounting();


                                $log = '<a style="color: #1411FF;" href="https://sellercentral.amazon.com/gp/orders-v2/details/ref=ag_orddet_cont_myo?ie=UTF8&orderID=' . $sAmazonOrderId . '" target="_blank">Amazon order # ' . $sAmazonOrderId . '</a><br />Grand total: $' . $product_total;
                                Logs::model()->_log('orders', $oOrder->getOrderId(), 'S', $log, 'Amazon');

                                $statuses = func_query_hash('SELECT code, name, type FROM xcart_order_statuses ORDER BY orderby', array('type', 'code'), false, true);

                                x_load('order');
                                x_load('mail');
                                $order_data = func_order_data($oOrder->getOrderId());
                                $order_status = "I";

                                global $mail_smarty, $config;

                                $mail_smarty->assign("products", $order_data["products"]);
                                $mail_smarty->assign("giftcerts", $order_data["giftcerts"]);
                                $mail_smarty->assign("order", $order_data["order"]);
                                $mail_smarty->assign("userinfo", $order_data["userinfo"]);
                                $mail_smarty->assign('statuses', $statuses);
                                $mail_smarty->assign('oOrder', $oOrder);

                                $aorder_notification = func_get_order_notification($order_status, $order_data);
                                if (!empty($aorder_notification)) {
                                    foreach ($aorder_notification as $oOrderNotification) {
                                        if ($oOrderNotification->isEnabled()) {
                                            $order_notification = $oOrderNotification->getFields();

                                            if ($order_notification['enabled'] == 'Y') {
                                                $mail_smarty->assign('order_notification', $order_notification);

                                                $mail_smarty->assign('type', 'A');
                                                $mail_smarty->assign("show_order_details", "Y");

                                                $mail_smarty->assign("show_amazon_order", "Y");

                                                $to = $config['Company']['orders_department'];
                                                $from = "<" . $config['Company']['orders_department'] . ">";
                                                $reply_to = '';

                                                $attach_pdf_invoice = $order_notification["admin_attach_pdf_invoice"];
                                                $mail_smarty->assign('attach_pdf_invoice', $attach_pdf_invoice);

                                                func_send_mail($to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $from, true, true, false, false, $reply_to);

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $sLog = '';
                        if (in_array($sOrderStatus, ['Shipped', 'Canceled'])) {

                            /** @var OrderGroup $oOrderGroup */
                            $aOrderGroups = $oOrder->getOrderGroups();
                            switch ($sOrderStatus) {
                                case 'Shipped':
                                    foreach ($aOrderGroups as $oOrderGroup) {
                                        if ($oOrderGroup->getOrderGroupStatusCB() != "P") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> cb_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusCB()])->getName() . " -> " . OrderStatus::model(['code' => 'P'])->getName();
                                            $oOrderGroup->updateField('cb_status', 'P');
                                            $oOrder->updateField('cb_status', 'P');
                                        }
                                        if ($oOrderGroup->getOrderGroupStatusDC() != "S") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> dc_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusDC()])->getName() . " -> " . OrderStatus::model(['code' => 'S'])->getName();
                                            $oOrderGroup->updateField('dc_status', 'S');
                                            $oOrder->updateField('dc_status', 'S');
                                        }
                                    }

                                    break;
                                case 'Canceled':
                                    foreach ($aOrderGroups as $oOrderGroup) {
                                        if ($oOrderGroup->getOrderGroupStatusCB() != "A") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> cb_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusCB()])->getName() . " -> " . OrderStatus::model(['code' => 'A'])->getName();
                                            $oOrderGroup->updateField('cb_status', 'A');
                                            $oOrder->updateField('cb_status', 'A');
                                        }
                                    }

                                    break;
                            }
                            if (!empty($sLog))
                                Logs::model()->_log('orders', $oOrder->getOrderId(), 'S', $sLog, 'Amazon');
                        }
                    }

                }
            }

            $this->nextToken = $xpath->query('/*/*/NextToken')->item(0)->nodeValue;
        }
    }

    public function getService()
    {
        return $this->oMWSService;
    }


    /**
     * @param Customer $oCustomer
     * @param Cart $oShippingCart
     * @param ShippingRate[] $aShippingRates
     * @return array|string
     */
    public function getGetFulfillmentRates(Customer $oCustomer, Cart $oShippingCart, $aShippingRates)
    {
        $aShippingRatesCalc = null;
        if (!empty($aShippingRates)) {
            foreach ($aShippingRates as $oShippingRate) {
                $aShipName[] = $oShippingRate->getShippingEntity()->getName();
            }
        }

        if (!empty($aShipName)) {

            $request = new \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest();
            $request->setSellerId(MERCHANT_ID);

            $address = new \FBAOutboundServiceMWS_Model_Address();
            if ($oCustomer->getField("s_firstname")) {
                $address->setName($oCustomer->getField("s_firstname"));
            } else {
                $address->setName('Albert Einstain');
            }
            if (!empty($oCustomer->s_address)) {
                $address->setLine1($oCustomer->s_address . (empty($oCustomer->s_address_2) ? "" : "\n$oCustomer->s_address_2"));
            } else {
                $address->setLine1('Village road 1');
            }

            $address->setCity($oCustomer->getField("s_city"));
            $address->setCountryCode($oCustomer->getField("s_country"));
            $address->setStateOrProvinceCode($oCustomer->getField("s_state"));
            $address->setPostalCode($oCustomer->getField("s_zipcode"));
            $request->setAddress($address);

            $items = [];

            $aProductsCart = $oShippingCart->getElements();

            if (!empty($aProductsCart)) {
                /** @var CartElement $oCartElement */
                foreach ($aProductsCart as $oCartElement) {
                    $oProduct = $oCartElement->getProduct();
                    if (!($oProduct->isAmazonFBAEnabled())) {
                        $aProducts = $oProduct->getProductsAvailOnAmazonParentWithChild(1);
                        if (!empty($aProducts)) {
                            $oProductParentOrChild = reset($aProducts);
                            $oProduct = $oProductParentOrChild['oProduct'];
                        }
                    }
                    $item = new \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem();
                    $item->setSellerSKU($oProduct->getSKU());
                    $item->setQuantity($oCartElement->getQuantity());
                    $item->setSellerFulfillmentOrderItemId($oProduct->getSKU());
                    $items[] = $item;
                }
                $itemList = new \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList();
                $itemList->setmember($items);
                $request->setItems($itemList);
                $shippingArray = new \FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList();
                $aShipName = [];


                $shippingArray->setmember($aShipName);
                $request->setShippingSpeedCategories($shippingArray);
                $aXML = AmazonHelper::invokeGetFulfillmentPreview($request, $this->oMWSService);
                if (!empty($aXML['saveXML'])) {
                    $aXML['saveXML'] = str_replace('http://mws.amazonaws.com', 'https://mws.amazonservices.com', $aXML['saveXML']);
                    $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $aXML['saveXML']);
                    $docShipping = new \DOMDocument;
                    $docShipping->loadXML($this->dom_xml_arr);
                    $xpath = new \DOMXPath($docShipping);
                    $aShippingEntries = $xpath->query('/*/*/FulfillmentPreviews/member');

                    if (!empty($aShippingEntries) && $aShippingEntries->length > 0) {
                        foreach ($aShippingEntries as $k => $shippingNode) {
                            $fAmount = null;
                            $ShippingSpeedCategory = $shippingNode->getElementsByTagName('ShippingSpeedCategory');
                            if ($ShippingSpeedCategory->length) {
                                $sShippingName = $ShippingSpeedCategory->item(0)->nodeValue;
                            }
                            $EstimatedFees = $shippingNode->getElementsByTagName('EstimatedFees');
                            if ($EstimatedFees->length) {
                                $aShippingFees = $EstimatedFees->item(0)->getElementsByTagName('member');
                            }
                            if (!empty($aShippingFees) && $aShippingFees->length > 0) {
                                foreach ($aShippingFees as $oShippingFee) {
                                    $Amount = $oShippingFee->getElementsByTagName('Amount');
                                    if ($Amount->length) {
                                        $value = $Amount->item(0)->getElementsByTagName('Value');
                                        if ($value->length) {
                                            $fAmount += floatval($value->item(0)->nodeValue);
                                        }
                                    }

                                }
                            }
                            $aShippingRatesCalc[$sShippingName] = $fAmount;
                        }
                    }
                } else {
                    if (!empty($aXML['Caught_Exception'])) {
                        throw new \Exception($aXML['Caught_Exception']);
                    }
                }
            }

        }
        return $aShippingRatesCalc;
    }

    public function submitToListingLoader($aFeeds)
    {
        global $login;
        $aRows = $aResult = $aProductIds = [];
        if (!empty($aFeeds)) {
            $oAmazonMarketPlace = $sFeed = null;
            $sFeed = "TemplateType=Offer\tVersion=2014.0703" . str_repeat("\t", 254) . PHP_EOL;
            $aHeader = ['sku',
                'price',
                'quantity',
                'product-id',
                'product-id-type',
                'condition-type',
                'condition-note',
                'ASIN-hint',
                'title',
                'product-tax-code',
                'operation-type',
                'sale-price',
                'sale-start-date',
                'sale-end-date',
                'leadtime-to-ship',
                'launch-date',
                'is-giftwrap-available',
                'is-gift-message-available',
                'fulfillment-center-id',
                'main-offer-image',
                'offer-image1',
                'offer-image2',
                'offer-image3',
                'offer-image4',
                'offer-image5'];
            $sFeed .= implode("\t", $aHeader) . str_repeat("\t", 231) . PHP_EOL;
            $sFeed .= implode("\t", $aHeader) . str_repeat("\t", 231) . PHP_EOL;
            foreach ($aFeeds as $aFeed) {
                /** @var Product $oProduct */
                $oProduct = $aFeed['Product'];
                $aProductIds[] = $oProduct->getProductId();
                $aRows[] = [
                    $oProduct->getSKU(),
                    price_format($oProduct->getAmazonPrice()),
                    0,
                    $aFeed['ASIN'],
                    'ASIN',
                    'New',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $aFeed['cidev_get_amazon_fulfillment_latency'],
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''];
            }

            $aMarketPlaces = StoreFrontMarketPlace::getMarketPlacesByStoreFront(0);
            if (!empty($aMarketPlaces)) {
                foreach ($aMarketPlaces as $oMarketPlace) {
                    if ($oMarketPlace instanceof \Xcart\External_Marketplaces\Marketplaces\Amazon) {
                        $oAmazonMarketPlace = $oMarketPlace;
                    }
                }
            }
            if ($oAmazonMarketPlace && !empty($aRows)) {
                foreach ($aRows as $aRow) {
                    $sFeed .= implode("\t", $aRow) . str_repeat("\t", 231) . PHP_EOL;
                }
                $feedHandle = @fopen('php://temp', 'rw+');
                fwrite($feedHandle, $sFeed);
                if ($feedHandle) {
                    rewind($feedHandle);
                    $parameters = array(
                        'Merchant' => MERCHANT_ID,
                        'MarketplaceIdList' => ["Id" => [$oAmazonMarketPlace->getP2()], "MerchantIdentifier" => $oAmazonMarketPlace->getP1()],
                        'FeedType' => '_POST_FLAT_FILE_LISTINGS_DATA_',
                        'FeedContent' => $feedHandle,
                        'PurgeAndReplace' => false,
                        'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
                    );

                    $request = new \MarketplaceWebService_Model_SubmitFeedRequest($parameters);
                    $aResult = AmazonHelper::invokeSubmitFeed($request, $this->oMWSService);
                    if (!empty($aResult)) {
                        if (!empty($aResult['FeedSubmissionId'])) {
                            if (Connection::getInstance()->insert('xcart_external_verification_feeds',
                                ['amazon_submition_id' => $aResult['FeedSubmissionId'],
                                    'status' => $aResult['FeedProcessingStatus'],
                                    'login' => $login]
                            )
                            ) {
                                $iFeedId = Connection::getInstance()->lastInsertId();
                                foreach ($aProductIds as $iProductId) {
                                    Connection::getInstance()->update('xcart_external_verification_products_queue',
                                        ['feed_id' => $iFeedId, 'amz_listing_status' => 'submitted_to_listing_loader'], ['productid' => $iProductId]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $aResult;
    }

    public function doGetSubmitionResults()
    {
        if (!empty($this->error)) return $this;

        $request = new \MarketplaceWebService_Model_GetFeedSubmissionResultRequest();
        $request->setMerchant(MERCHANT_ID);
        $sReportId = reset($this->aReportIds);
        $request->setFeedSubmissionId($sReportId);
        $handle = @fopen('php://memory', 'rw+');
        $request->setFeedSubmissionResult($handle);

        $this->dom_xml_arr = AmazonHelper::invokeGetFeedSubmissionResult($request, $this->oMWSService);
        $contents = trim(stream_get_contents($handle));
        $aFileRows = explode("\n", $contents);
        if (!empty($aFileRows)) {
            array_shift($aFileRows);
            list ($t, $t, $t, $totalRecords) = explode("\t", array_shift($aFileRows));
            if (intval($totalRecords) > 0) {
                $oFeed = ExternalVerificationFeeds::model()->find(SQLBuilder::getInstance()->addCondition("amazon_submition_id = '{$sReportId}'"));
                if ($oFeed->getField('feed_id')) {
                    $sFeedErrors = '';
                    $aListingProducts = array_flip(Connection::getInstance()
                        ->executeQuery("SELECT productid FROM xcart_external_verification_products_queue WHERE feed_id = {$oFeed->getField('feed_id')}")
                        ->fetchAll(\PDO::FETCH_COLUMN));
                    Connection::getInstance()->update('xcart_external_verification_products_queue', ['amz_listing_status' => 'submit_to_feed_success'], ['feed_id' => $oFeed->getField('feed_id')]);
                    foreach (range(1, 3) as $i) {
                        array_shift($aFileRows);
                    }
                    if (!empty($aFileRows)) {
                        foreach ($aFileRows as $sRows) {
                            list($original_record_number, $sku, $error_code, $error_type, $error_message) = explode("\t", $sRows);
                            if (!empty($sku)) {
                                $oProduct = Product::getProductBySKU($sku);
                                if ($oProduct->getProductId()) {
                                    $this->dom_xml_arr['listing_failed']++;
                                    unset($aListingProducts[$oProduct->getProductId()]);
                                    Connection::getInstance()->update('xcart_external_verification_products_queue',
                                        ['amz_listing_status' => 'submit_to_feed_failed'],
                                        ['feed_id' => $oFeed->getField('feed_id'), 'productid' => $oProduct->getProductId()]);
                                }
                            }
                            if (!empty($error_message)) {
                                $sFeedErrors .= '- ' . $error_message . PHP_EOL;
                            }
                        }
                    }
                    if (!empty($aListingProducts)) {
                        foreach (array_keys($aListingProducts) as $iProductId) {
                            $this->dom_xml_arr['listing_success']++;
                            Connection::getInstance()->update('xcart_products',
                                ['amazon_enabled' => 'Y'],
                                ['productid' => $iProductId]);
                        }
                    }
                    if (!empty($sFeedErrors)) {
                        Logs::_log('amazon_listings', $oFeed->getField('feed_id'), 'X', $sFeedErrors);
                    }
                }

                $oFeed->updateField('status', '_DONE_');
            }
        }


        if (!empty($this->dom_xml_arr['Caught_Exception'])) {
            $this->error[] = $this->dom_xml_arr["Caught_Exception"];
        }

        return $this;
    }

    public function getDOMXML()
    {
        return $this->dom_xml_arr;
    }

    public function doGetLowestOfferListingsForSKU()
    {
        if (!empty($this->aProducts)) {
            $request = new \MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setMarketplaceId(MARKETPLACE_ID);
            $SellerSKUList = new \MarketplaceWebServiceProducts_Model_SellerSKUListType();
            $SellerSKUList->setSellerSKU(array_map(function ($oP) {return $oP->productcode;}, $this->aProducts));
            $request->setSellerSKUList($SellerSKUList);
            $request->setItemCondition("New");
            $request->setExcludeMe(true);

            $this->dom_xml_arr = AmazonHelper::invokeGetLowestOfferListingsForSKU($request, $this->oMWSService);
            if (!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") {
                return $this;
            }
            if ($this->bEnableLog && $this->sLogPrefix) {
                $log = new \Monolog\Logger('amazon_info');
                $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
                $log->debug('GetLowestOfferListingsForSKU', [$this->dom_xml_arr]);
            }
            $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
            $aOffers = AmazonHelper::parseAmazonOffers($this->dom_xml_arr, $this->aProducts);

            if (!empty($aOffers)) {
                foreach ($aOffers as $aOffer) {
                    $aOffer['report_date'] = $iReportDate;
                    $params = ['productcode' => $aOffer['productcode'], 'productid' => $aOffer['productid'], 'report_date' => $aOffer['report_date']];
                    if ($oAmazonFbaProductModel = AmazonHelper::getAmazonFbaProductModel($params)) {
                        $oAmazonFbaProductModel->setAttributes($aOffer);
                        if ($oAmazonFbaProductModel->productid) {
                            $oAmazonFbaProductModel->save();
                        }
                    }
                }
            }
        }
        return $this;
    }

    public function doGetCompetitivePricing()
    {
        if (!empty($this->aProducts)) {
            $request = new \MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setMarketplaceId(MARKETPLACE_ID);
            $SellerSKUList = new \MarketplaceWebServiceProducts_Model_SellerSKUListType();
            $aSKUs = array_map(function ($oP) {
                return $oP->productcode;
            }, $this->aProducts);
            $SellerSKUList->setSellerSKU($aSKUs);
            $request->setSellerSKUList($SellerSKUList);
            $this->dom_xml_arr = AmazonHelper::invokeGetCompetitivePricingForSKU($request, $this->oMWSService);
            if (!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") {
                return $this;
            }
            if ($this->bEnableLog && $this->sLogPrefix) {
                $log = new \Monolog\Logger('amazon_info');
                $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
                $log->debug('GetCompetitivePricing', [$this->dom_xml_arr]);
            }
            $this->dom_xml_arr = str_replace('http://mws.amazonservices.com/schema/Products/2011-10-01', '', $this->dom_xml_arr);
            $docShipping = new \DOMDocument;
            $docShipping->loadXML($this->dom_xml_arr);
            $xpath = new \DOMXPath($docShipping);
            $aCompetitivePricingForSKUResult = $xpath->query('/*/GetCompetitivePricingForSKUResult');
            /** @var \DOMElement[] $aCompetitivePricingForSKUResult */
            foreach ($aCompetitivePricingForSKUResult as $aCompetitivePricing) {
                $sSKU = $aCompetitivePricing->getAttribute('SellerSKU');
                $sStatus = $aCompetitivePricing->getAttribute('status');
                $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
                $aProductModels = array_filter(
                    $this->aProducts,
                    function ($e) use ($sSKU) {
                        return $e->productcode == $sSKU;
                    });
                $oProductModel = reset($aProductModels);
                if ($oProductModel) {
                    $params = ['productcode' => $sSKU, 'productid' => $oProductModel->productid, 'report_date' => $iReportDate];
                    if ($oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params)) {
                        $oAmazonProductModel->report_date = $iReportDate;
                        $aSalesRanking = $aCompetitivePricing->getElementsByTagName('SalesRankings');
                        if ($aSalesRanking->length) {
                            /** @var \DOMElement[] $aSalesRanking */
                            foreach ($aSalesRanking as $SalesRanking) {
                                $aSalesRank = $SalesRanking->getElementsByTagName('SalesRank');
                                if (!empty($aSalesRank)) {
                                    /** @var \DOMElement[] $aSalesRank */
                                    foreach ($aSalesRank as $SalesRank) {
                                        $aRank = $SalesRank->getElementsByTagName('Rank');
                                        if (!empty($aRank)) {
                                            foreach ($aRank as $Rank) {
                                                $oAmazonProductModel->cpr_SalesRank = max(intval($Rank->nodeValue), $oAmazonProductModel->cpr_SalesRank);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $aCompetitivePrice = $aCompetitivePricing->getElementsByTagName('CompetitivePrice');
                        if ($aCompetitivePrice->length) {
                            /** @var \DOMElement[] $aCompetitivePrice */
                            foreach ($aCompetitivePrice as $CompetitivePrice) {
                                if ($CompetitivePrice->getAttribute('condition') == 'New' && $CompetitivePrice->getAttribute('subcondition') == 'New') {
                                    $sLandedPrice = '';
                                    $aLandedPrice = $CompetitivePrice->getElementsByTagName('LandedPrice');
                                    if (!empty($aLandedPrice)) {
                                        $aAmount = $aLandedPrice->item(0)->getElementsByTagName('Amount');
                                        if (!empty($aAmount)) {
                                            $sLandedPrice = $aAmount->item(0)->nodeValue;
                                        }
                                    } else {
                                        $aListingPrice = $CompetitivePrice->getElementsByTagName('ListingPrice');
                                        if (!empty($aListingPrice)) {
                                            $aAmount = $aListingPrice->item(0)->getElementsByTagName('Amount');
                                            if (!empty($aAmount)) {
                                                $sLandedPrice = $aAmount->item(0)->nodeValue;
                                            }
                                        }
                                    }
                                    if ($sLandedPrice) {
                                        switch ($CompetitivePrice->getAttribute('belongsToRequester')) {
                                            case 'true':
                                                $oAmazonProductModel->cpr_belongs_LandedPrice = $sLandedPrice;
                                                $oAmazonProductModel->buybox_in++;
                                                break;
                                            case 'false':
                                                $oAmazonProductModel->cpr_LandedPrice = $sLandedPrice;
                                                $oAmazonProductModel->buybox_out++;
                                                break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($oAmazonProductModel->productid) {
                            if ($sStatus == 'ClientError') {
                                $oAmazonProductModel->ASIN = 'invalid SellerSKU';
                            } else {
                                $aASIN = $aCompetitivePricing->getElementsByTagName('ASIN');
                                if ($aASIN->length) {
                                    $oAmazonProductModel->ASIN = $aASIN->item(0)->nodeValue;
                                }
                            }
                            $oAmazonProductModel->save();
                        }
                    }
                }
            }

        }
        return $this;
    }

    public function doListInventorySupply()
    {
        if (!empty($this->aProducts)) {
            while (!empty($this->nextToken)) {
                if ($this->nextToken == 'start') {
                    $request = new \FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $aSKUs = array_map(function ($oP) {
                        return $oP->productcode;
                    }, $this->aProducts);
                    $sellerSKUs = new \FBAInventoryServiceMWS_Model_SellerSkuList();
                    $sellerSKUs->setmember($aSKUs);
                    $request->setSellerSkus($sellerSKUs);
                    $this->dom_xml_arr = AmazonHelper::invokeListInventorySupply($request, $this->oMWSService);
                } else {
                    $request = new \FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $request->setNextToken($this->nextToken);
                    $this->dom_xml_arr = AmazonHelper::invokeListInventorySupplyByNextToken($request, $this->oMWSService);
                }
                if (!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") {
                    return $this;
                }
                if ($this->bEnableLog && $this->sLogPrefix) {
                    $log = new \Monolog\Logger('amazon_info');
                    $logFile = sprintf("../var/log/{$this->sLogPrefix}-%s.log", date('ymd'));
                    $log->pushHandler(new \Monolog\Handler\StreamHandler($logFile, \Monolog\Logger::DEBUG));
                    $log->debug('ListInventorySupply', [$this->dom_xml_arr]);
                }
                $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
                $this->dom_xml_arr = str_replace('http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/', '', $this->dom_xml_arr);
                $docShipping = new \DOMDocument;
                $docShipping->loadXML($this->dom_xml_arr);
                $xpath = new \DOMXPath($docShipping);
                $aInventorySupplyList = $xpath->query('/*/*/InventorySupplyList/member');
                if (!empty($aInventorySupplyList)) {
                    /** @var \DOMElement[] $aInventorySupplyList */
                    foreach ($aInventorySupplyList as $InventorySupplyList) {
                        $aTotalSupplyQuantity = $InventorySupplyList->getElementsByTagName('TotalSupplyQuantity');
                        $aInStockSupplyQuantity = $InventorySupplyList->getElementsByTagName('InStockSupplyQuantity');
                        $aASIN = $InventorySupplyList->getElementsByTagName('ASIN');
                        $aSKU = $InventorySupplyList->getElementsByTagName('SellerSKU');
                        if (!empty($aSKU)) {
                            $sSKU = $aSKU->item(0)->nodeValue;
                            $aProductModels = array_filter(
                                $this->aProducts,
                                function ($e) use ($sSKU) {
                                    return $e->productcode == $sSKU;
                                });
                            if (!empty($aProductModels)) {
                                $oProductModel = reset($aProductModels);
                                $params = ['productcode' => $sSKU, 'productid' => $oProductModel->productid, 'report_date' => $iReportDate];
                                $oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params);
                                if ($aASIN->length) {
                                    $oAmazonProductModel->ASIN = $aASIN->item(0)->nodeValue;
                                }
                                if ($aTotalSupplyQuantity->length) {
                                    $oAmazonProductModel->lis_TotalSupplyQuantity = $aTotalSupplyQuantity->item(0)->nodeValue;
                                }
                                if ($aInStockSupplyQuantity->length) {
                                    $oAmazonProductModel->lis_InStockSupplyQuantity = $aInStockSupplyQuantity->item(0)->nodeValue;
                                }
                                $oAmazonProductModel->report_date = $iReportDate;
                                if ($oAmazonProductModel->productid) {
                                    $oAmazonProductModel->save();
                                }
                            }
                        }
                    }
                }
                $this->nextToken = $xpath->query('/*/*/NextToken')->item(0)->nodeValue;
            }
            $this->nextToken = 'start';
        }
        return $this;
    }

    public function setProducts($aProducts)
    {
        $this->aProducts = $aProducts;
        return $this;
    }

    public function enableLog($fileprefix)
    {
        $this->bEnableLog = true;
        $this->sLogPrefix = $fileprefix;
        return $this;
    }
}