<?php

define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

//include $xcart_dir ."/include/func/func.amazon.php";

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
 
 $dom_xml_arr = invokeGetReportList((new \Xcart\AmazonMWS())->getService(), $request);

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

 $dom_xml_3 = invokeGetReport((new \Xcart\AmazonMWS())->getService(), $request);

 func_print_r($dom_xml_3);

}// if ($mode == "GetReport" && !empty($reportId))


if ($mode == "Acknowledgement" && !empty($reportId) && !empty($setAcknowledged)){

                $request = new MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
                $request->setMerchant(MERCHANT_ID);

                $idList = new MarketplaceWebService_Model_IdList();
                $request->setReportIdList($idList->withId($reportId));
                $request->setAcknowledged($setAcknowledged); 

                func_print_r($request);
                invokeUpdateReportAcknowledgements((new \Xcart\AmazonMWS())->getService(), $request);
}


print("Done.");

?>
