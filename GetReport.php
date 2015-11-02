<?php

define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

include $xcart_dir ."/include/func/func.amazon.php";

ini_set('memory_limit', '512M');
set_time_limit(0);
x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt', 'xml');

if ($sid != "2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija" || empty($mode)){
	func_header_location("/");
}


if ($mode == "GetReportList" && !empty($setAcknowledged1)){

 // @TO DO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest
 // object or array of parameters
 // $parameters = array (
 //   'Merchant' => MERCHANT_ID,
 //   'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
 //   'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
 //   'Acknowledged' => false, 
 //   'MWSAuthToken' => '<MWS Auth Token>', // Optional
 // );
 // 
 // $request = new MarketplaceWebService_Model_GetReportListRequest($parameters);


 $reqt = new MarketplaceWebService_Model_TypeList;
 $reqt->withType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_');

 $request = new MarketplaceWebService_Model_GetReportListRequest();
 $request->setMerchant(MERCHANT_ID);
 // $request->setAvailableToDate(new DateTime('now', new DateTimeZone('UTC')));
 // $request->setAvailableFromDate(new DateTime('-3 months', new DateTimeZone('UTC')));

 $request->setReportTypeList($reqt);
 $request->setMaxCount("100");

func_print_r("setAcknowledged1_value:", $setAcknowledged1);

 if ($setAcknowledged1 != "all"){

  print"setAcknowledged1 != 'all' So making setAcknowledged <br /><br />";

  $request->setAcknowledged($setAcknowledged1);
 }

 // $request->setAcknowledged(false);
 // $request->setMWSAuthToken('<MWS Auth Token>'); // Optional

 //$dom_xml3_a =  invokeGetReportList($service, $request);
 func_print_r($request);
 
 $dom_xml_arr = invokeGetReportList($service, $request);

 func_print_r($dom_xml_arr);

} // if ($mode == "GetReportList")



if ($mode == "GetReport" && !empty($reportId)){

###########
//$reportId = 24375331893;
//$reportId = 59251048223;
//$reportId = 57479778633;
###########

// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'Report' => @fopen('php://memory', 'rw+'),
//   'ReportId' => $reportId,
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );
// $request = new MarketplaceWebService_Model_GetReportRequest($parameters);

 $request = new MarketplaceWebService_Model_GetReportRequest();
 $request->setMerchant(MERCHANT_ID);
 $request->setReport(@fopen('php://memory', 'rw+'));
 $request->setReportId($reportId);
//$request->setMWSAuthToken('<MWS Auth Token>'); // Optional

 func_print_r($request);    

 $dom_xml_3 = invokeGetReport($service, $request);

 func_print_r($dom_xml_3);
//die();

 $dom_xml = $dom_xml_3["Report_Contents"];

 if (!empty($dom_xml)){

        $findme_arr = array("Order", "Refund", "Fee", "Component", "Item", "AdjustedItem");

        foreach ($findme_arr as $findme){
                $pos = strpos($dom_xml, "<$findme>");
                if ($pos !== "false"){
                        $dom_xml_arr = explode("<$findme>",$dom_xml);
                        $count_dom_xml_arr = count($dom_xml_arr);
                        $dom_xml = "";
                        foreach ($dom_xml_arr as $k => $v){
                                $k_n = $k-1;
                                $v = str_replace("</$findme>","</$findme$k_n>",$v);
                                $dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<$findme$k>":"");
                        }
                }
        }

        $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");
 }


//func_print_r($dom_xml_arr);
//die();

 if (!empty($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"]) && is_array($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"])){

    $order_not_found = false;

    foreach ($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"] as $k => $v){
        if (!empty($v["AmazonOrderID"])){

                $fields = "orderid";
                $order_info = func_query_first("SELECT $fields FROM $sql_tbl[orders] WHERE amazonorderid='$v[AmazonOrderID]'");

                if (!empty($order_info)){
                        $log_text = "order processed: <AmazonOrderID>".$v["AmazonOrderID"]."</AmazonOrderID>";
                        func_backprocess_log("Amazon_Reports_Cron", $log_text);


                        $acc_paymentid = "";
                        if ($v["Fulfillment"]["MerchantFulfillmentID"] == "MFN"){
                                $acc_paymentid = "1";
                        }
                        elseif ($v["Fulfillment"]["MerchantFulfillmentID"] == "AFN"){
                                $acc_paymentid = "101";
                        }

                        db_query("UPDATE $sql_tbl[order_groups] SET acc_paymentid='$acc_paymentid' WHERE orderid='".$order_info["orderid"]."'");


                        if (strpos($k, "Order") !== false){
                                $k_name = "Item";
                        }
                        elseif (strpos($k, "Refund") !== false) {
                                $k_name = "AdjustedItem";
                        }

//func_print_r($v);
//die();

                        $mid_info = array();

                        foreach ($v["Fulfillment"] as $kk => $vv){
                                if (strpos($kk, $k_name)!==false){
                                        $SKU = $vv["SKU"];
                                        $manufacturerid = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[products] WHERE productcode='$SKU'");

                                        $cost_to_us = func_query_first_cell("SELECT cost_to_us FROM $sql_tbl[products] WHERE productcode='$SKU'");

#
##
                                        $RefundSum = 0;
                                        if ($k_name == "AdjustedItem"){

#
##
###
                                                db_query("UPDATE $sql_tbl[order_details] SET amazon_item_refunded='Y' WHERE orderid='".$order_info["orderid"]."' AND productcode='$SKU'");

//                                              $current_cb_status = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");

//                                              if ( !($current_cb_status == "H" || $current_cb_status == "R")){
                                                        db_query("UPDATE $sql_tbl[order_groups] SET cb_status='H' WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");
//                                              }

                                                $igor_query_for_status = func_query_first("select SUM(if(amazon_item_refunded='Y',1,0)) As refunded_items, COUNT(AmazonOrderItemCode) As all_items from xcart_order_details where orderid = '".$order_info["orderid"]."'Group By orderid");

                                                if ($igor_query_for_status["refunded_items"] == $igor_query_for_status["all_items"]){
                                                        db_query("UPDATE $sql_tbl[order_groups] SET cb_status='R' WHERE orderid='".$order_info["orderid"]."'");
                                                }
###
##
#

                                                if (!isset($vv["Quantity"])){
                                                        $vv["Quantity"] = 1;
                                                }

                                                if (!empty($vv["ItemPriceAdjustments"]) && is_array($vv["ItemPriceAdjustments"])){
                                                        foreach ($vv["ItemPriceAdjustments"] as $kkk => $vvv){
                                                                $field_name = $vvv["Type"];
                                                                if ($field_name == "Principal" && !empty($vvv["Amount"])){
                                                                        $RefundSum += $vvv["Amount"];
                                                                }
                                                        }
                                                }

                                                if (!empty($vv["ItemFeeAdjustments"]) && is_array($vv["ItemFeeAdjustments"])){
                                                        foreach ($vv["ItemFeeAdjustments"] as $kkk => $vvv){
                                                                $field_name = $vvv["Type"];
                                                                if (($field_name == "Commission" || $field_name == "RefundCommission") && !empty($vvv["Amount"])){
                                                                        $RefundSum += $vvv["Amount"];
                                                                }
                                                        }
                                                }


                                        }

                                        $RefundSum = abs($RefundSum);
                                        if (!isset($mid_info[$manufacturerid]["RefundSum"])){
                                                $mid_info[$manufacturerid]["RefundSum"] = 0;
                                        }
                                        $mid_info[$manufacturerid]["RefundSum"] += $RefundSum;
##
#


                                        $cost_to_us *= $vv["Quantity"];
                                        if (!isset($mid_info[$manufacturerid]["cost_to_us"])){
                                                $mid_info[$manufacturerid]["cost_to_us"] = 0;
                                        }
                                        $mid_info[$manufacturerid]["cost_to_us"] += $cost_to_us;


                                        $ProcessorFee = 0;

                                        if (!empty($vv["ItemFees"]) && is_array($vv["ItemFees"])){


                                                $update_fields = array();

                                                foreach ($vv["ItemFees"] as $kkk => $vvv){
                                                        if (in_array($vvv["Type"], array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee", "Commission"))){
                                                                $field_name = $vvv["Type"];
                                                                if ($field_name == "Commission"){
                                                                        $field_name = "AmazonCommission";
                                                                }

##
                                                                if ($v["Fulfillment"]["MerchantFulfillmentID"] == "MFN" && in_array($field_name, array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee"))){
                                                                        $vvv["Amount"] = 0;
                                                                }
##

                                                                $update_fields[] = $field_name."='".$vvv["Amount"]."'";

                                                                $ProcessorFee += $vvv["Amount"];
                                                        }
                                                }

                                                if (!empty($update_fields)){
                                                        db_query("UPDATE $sql_tbl[order_details] SET ".implode(', ', $update_fields)." WHERE orderid='".$order_info["orderid"]."' AND productcode='$SKU'");
                                                        unset($update_fields);
                                                }
                                        }

                                        $ProcessorFee = abs($ProcessorFee);
                                        if (!isset($mid_info[$manufacturerid]["ProcessorFee"])){
                                                $mid_info[$manufacturerid]["ProcessorFee"] = 0;
                                        }
                                        $mid_info[$manufacturerid]["ProcessorFee"] += $ProcessorFee;
                                }
                        }

                        if (!empty($mid_info)){
                                foreach ($mid_info as $manufacturerid => $m_val){

                                        $order_group_info = func_query_first("SELECT * FROM $sql_tbl[order_groups] WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");

                                        $accounting_net_0=$order_group_info['accounting_net_0'];
                                        $accounting_gross_0=$order_group_info['accounting_gross_0'];
                                        $accounting_gross_1_cost_to_us=$order_group_info['accounting_gross_1_cost_to_us'];
                                        $accounting_net_1_cost_to_us=$order_group_info['accounting_net_1_cost_to_us'];
                                        $accounting_net_2_shipping=$order_group_info['accounting_net_2_shipping'];
                                        $accounting_gross_2_shipping=$order_group_info['accounting_gross_2_shipping'];
                                        $accounting_net_3_ref_to_cust=$order_group_info['accounting_net_3_ref_to_cust'];
                                        $accounting_gross_3_ref_to_cust=$order_group_info['accounting_gross_3_ref_to_cust'];
                                        $accounting_net_4_ref_to_us=$order_group_info['accounting_net_4_ref_to_us'];
                                        $accounting_gross_4_ref_to_us=$order_group_info['accounting_gross_4_ref_to_us'];
                                        $accounting_net_5_profit=$order_group_info['accounting_net_5_profit'];
                                        $accounting_gross_5_profit=$order_group_info['accounting_gross_5_profit'];
                                        $profit_margin=$order_group_info['profit_margin'];


                                        if ($m_val["RefundSum"] > 0){
                                                $accounting_net_3_ref_to_cust = $accounting_gross_3_ref_to_cust = $m_val["RefundSum"];
                                        }
                                        else {

                                                $ProcessorFee = $m_val["ProcessorFee"];
                                                $accounting_net_0 = $order_group_info["total_net"] - $ProcessorFee;
                                                $accounting_gross_0 = $order_group_info["total_gross"] - $ProcessorFee;

                                                if ($v["Fulfillment"]["MerchantFulfillmentID"] == "AFN"){
                                                        $accounting_net_1_cost_to_us = $accounting_gross_1_cost_to_us = $m_val["cost_to_us"];
                                                } else {
                                                        $accounting_net_1_cost_to_us = $accounting_gross_1_cost_to_us = 0;
                                                }

//        $acc[5]["net"] = $acc[0]["net"] - $acc[1]["net"] - $acc[2]["net"] - $acc[3]["net"] + $acc[4]["net"];
//        $acc[5]["gross"] = $acc[0]["gross"] - $acc[1]["gross"] - $acc[2]["gross"] - $acc[3]["gross"] + $acc[4]["gross"];

                                                $accounting_net_5_profit = $accounting_net_0 - $accounting_net_1_cost_to_us - $accounting_net_2_shipping - $accounting_net_3_ref_to_cust + $accounting_net_4_ref_to_us;
                                                $accounting_gross_5_profit = $accounting_gross_0 - $accounting_gross_1_cost_to_us - $accounting_gross_2_shipping - $accounting_gross_3_ref_to_cust + $accounting_gross_4_ref_to_us;
                                        }

                                        $profit_margin = price_format(($accounting_net_5_profit/($accounting_net_0 - $accounting_net_3_ref_to_cust + $accounting_net_4_ref_to_us))*100);

                                        db_query("UPDATE $sql_tbl[order_groups] SET accounting_net_0='$accounting_net_0', accounting_gross_0='$accounting_gross_0', accounting_gross_1_cost_to_us='$accounting_gross_1_cost_to_us', accounting_net_1_cost_to_us='$accounting_net_1_cost_to_us', accounting_net_3_ref_to_cust='$accounting_net_3_ref_to_cust', accounting_gross_3_ref_to_cust='$accounting_gross_3_ref_to_cust', accounting_net_4_ref_to_us='$accounting_net_4_ref_to_us', accounting_gross_4_ref_to_us='$accounting_gross_4_ref_to_us', accounting_net_5_profit='$accounting_net_5_profit', accounting_gross_5_profit='$accounting_gross_5_profit', profit_margin='$profit_margin' WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");
                                }
                        }
                        unset($mid_info);


                } else {
                        $dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"][$k]["order_not_found"] = "Y";

                        $order_not_found = true;

                        $log_text = "!!! ORDER NOT FOUND YET: <AmazonOrderID>".$v["AmazonOrderID"]."</AmazonOrderID>";
                        func_backprocess_log("Amazon_Reports_Cron", $log_text);
                }
        }
    }
 }

}// if ($mode == "GetReport" && !empty($reportId))


if ($mode == "Acknowledgement" && !empty($reportId) && !empty($setAcknowledged)){

                $request = new MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
                $request->setMerchant(MERCHANT_ID);

                $idList = new MarketplaceWebService_Model_IdList();
                $request->setReportIdList($idList->withId($reportId));
                $request->setAcknowledged($setAcknowledged); 

		func_print_r($request);
                invokeUpdateReportAcknowledgements($service, $request);
}


print("Done.");

?>
