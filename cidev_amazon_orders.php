<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";


### Amazon ###
include_once "MarketplaceWebServiceOrders/Samples/.config.inc.php";
require_once "MarketplaceWebServiceOrders/Client.php";
require_once "MarketplaceWebServiceOrders/Exception.php";
require_once "MarketplaceWebServiceOrders/Model/ListOrdersRequest.php";
require_once "MarketplaceWebServiceOrders/Model/GetOrderRequest.php";
require_once "MarketplaceWebServiceOrders/Model/ListOrderItemsRequest.php";
require_once "MarketplaceWebServiceOrders/Model/ListOrderItemsByNextTokenRequest.php";
require_once "MarketplaceWebServiceOrders/Model/ListOrdersByNextTokenRequest.php";


#################################
if ($config["cidev_amazon_orders"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_amazon_orders'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_amazon_orders'");
#################################

//die('123');

$a_config = array (
  'ServiceURL' => "https://mws.amazonservices.com/Orders/2013-09-01",
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'ProxyUsername' => null,
  'ProxyPassword' => null,
  'MaxErrorRetry' => 3,
);

$service = new MarketplaceWebServiceOrders_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        APPLICATION_NAME,
        APPLICATION_VERSION,
        $a_config);


ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt', 'xml', 'mail', 'order');

function invokeListOrders(MarketplaceWebServiceOrders_Interface $service, $request)
  {
      try {
        $response = $service->ListOrders($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();
//        echo $dom->saveXML();
//        echo ("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        $return_echo["function"] = "invokeListOrders";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
	func_print_r($return_echo);
	$log_text = "...ListOrders throttling delay";
	func_backprocess_log("amazon_orders", $log_text);

        return $return_echo;
     }
}

# GetOrder Not Used
function invokeGetOrder(MarketplaceWebServiceOrders_Interface $service, $request)
  {
      try {
        $response = $service->GetOrder($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();
//        echo $dom->saveXML();
//        echo ("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        $return_echo["function"] = "invokeGetOrder";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
	func_print_r($return_echo);
	$log_text = "...GetOrder throttling delay";
	func_backprocess_log("amazon_orders", $log_text);

        return $return_echo;
     }
}

function invokeListOrderItems(MarketplaceWebServiceOrders_Interface $service, $request)
  {
      try {
        $response = $service->ListOrderItems($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();
//        echo $dom->saveXML();
//        echo ("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        $return_echo["function"] = "invokeListOrderItems";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
	func_print_r($return_echo);
	$log_text = "...ListOrderItems  throttling delay";
	func_backprocess_log("amazon_orders", $log_text);

        return $return_echo;
     }
}

   # Future
   function invokeListOrdersByNextToken(MarketplaceWebServiceOrders_Interface $service, $request)
   {
      try {
        $response = $service->ListOrdersByNextToken($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
	return $dom->saveXML();
//        echo $dom->saveXML();
//        echo ("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
	$return_echo["function"] = "invokeListOrdersByNextToken";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
	func_print_r($return_echo);
	$log_text = "...ListOrdersByNextToken  throttling delay";
	func_backprocess_log("amazon_orders", $log_text);

	return $return_echo;
     }
   }

     # Future
//   function ListOrderItemsByNextToken

$started_at = time();

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("amazon_orders", $log_text);



### 1s

$NextToken = "start";
$timeoffset = 24*60*30*300;

while (!empty($NextToken)){

  $dom_xml_arr_orders = array();

  if ($NextToken == "start"){
    $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
    $request->setSellerId(MERCHANT_ID);
    $request->setMarketplaceId(MARKETPLACE_ID);
    $now = gmdate('Y-m-d\TH:i:s\Z', time()-$timeoffset);
    $request->setCreatedAfter($now);
    // object or array of parameters
    $dom_xml = invokeListOrders($service, $request);
    
###    func_print_r($dom_xml);

    while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
	func_flush("sleeping...");
	func_flush();
	sleep('123');
        func_flush("Unsleeped");
        func_flush();

	$request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
	$request->setSellerId(MERCHANT_ID);
	$request->setMarketplaceId(MARKETPLACE_ID);
	$now = gmdate('Y-m-d\TH:i:s\Z', time()-$timeoffset);
	$request->setCreatedAfter($now);
	$dom_xml = invokeListOrders($service, $request);
    }
###    func_print_r($dom_xml);

    $dom_xml_arr = array();
    if (!empty($dom_xml)){
	$pos = strpos($dom_xml, "<Order>");
	if ($pos !== "false"){
		$dom_xml_arr = explode("<Order>",$dom_xml);
		$count_dom_xml_arr = count($dom_xml_arr);
		$dom_xml = "";		
		foreach ($dom_xml_arr as $k => $v){
			$k_n = $k-1;
			$v = str_replace("</Order>","</Order$k_n>",$v);
			$dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<Order$k>":"");
		}

		$dom_xml_arr = func_xml2hash($dom_xml);
		$dom_xml_arr_orders = $dom_xml_arr["ListOrdersResponse"]["ListOrdersResult"]["Orders"];
	}
    }

    if (!empty($dom_xml_arr) && !empty($dom_xml_arr["ListOrdersResponse"]["ListOrdersResult"]["NextToken"])){
	$NextToken = $dom_xml_arr["ListOrdersResponse"]["ListOrdersResult"]["NextToken"];
#	print("Next token: \r\n");
#	func_print_r($NextToken);
    } 
    else {
	$NextToken = "";
#	print("Next token: \r\n");
#	func_print_r($NextToken);
    }

  } // if ($NextToken == "start")
  elseif (!empty($NextToken)) {

    $request = new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest();
    $request->setSellerId(MERCHANT_ID);
    $request->setNextToken($NextToken);
    $now = gmdate('Y-m-d\TH:i:s\Z', time()-$timeoffset);
    $dom_xml = invokeListOrdersByNextToken($service, $request);
    
###    func_print_r($dom_xml);

    while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
        func_flush("sleeping...");
        func_flush();
        sleep('123');
        func_flush("Unsleeped");
        func_flush();

        $request = new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest();
        $request->setSellerId(MERCHANT_ID);
        $request->setNextToken($NextToken);
        $now = gmdate('Y-m-d\TH:i:s\Z', time()-$timeoffset);
        $dom_xml = invokeListOrdersByNextToken($service, $request);
    }
###    func_print_r($dom_xml);

    $dom_xml_arr = array();
    if (!empty($dom_xml)){
        $pos = strpos($dom_xml, "<Order>");
        if ($pos !== "false"){
                $dom_xml_arr = explode("<Order>",$dom_xml);
                $count_dom_xml_arr = count($dom_xml_arr);
                $dom_xml = "";
                foreach ($dom_xml_arr as $k => $v){
                        $k_n = $k-1;
                        $v = str_replace("</Order>","</Order$k_n>",$v);
                        $dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<Order$k>":"");
                }

                $dom_xml_arr = func_xml2hash($dom_xml);

                $dom_xml_arr_orders = $dom_xml_arr["ListOrdersByNextTokenResponse"]["ListOrdersByNextTokenResult"]["Orders"];

		if (!empty($dom_xml_arr) && !empty($dom_xml_arr["ListOrdersByNextTokenResponse"]["ListOrdersByNextTokenResult"]["NextToken"])){
		        $NextToken = $dom_xml_arr["ListOrdersByNextTokenResponse"]["ListOrdersByNextTokenResult"]["NextToken"];
#	print("Next token: \r\n");
#	func_print_r($NextToken);
		}
		else {
		        $NextToken = "";
#	print("Next token: \r\n");
#	func_print_r($NextToken);
		}
        }
    }

  } //elseif (!empty($NextToken))

  if (!empty($dom_xml_arr_orders) && is_array($dom_xml_arr_orders)){

	$cnt = 0;

//	foreach ($dom_xml_arr["ListOrdersResponse"]["ListOrdersResult"]["Orders"] as $k => $v
	foreach ($dom_xml_arr_orders as $k => $v){
		func_flush(".");
		func_flush();

		$orderid = func_query_first_cell("SELECT orderid FROM $sql_tbl[orders] WHERE amazonorderid='$v[AmazonOrderId]'");

//func_print_r($orderid, $v);
//die("====");

		if (empty($orderid)){

			
			$request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
			$request->setSellerId(MERCHANT_ID);
			$request->setAmazonOrderId($v["AmazonOrderId"]);
			// object or array of parameters
			$GetOrder_xml = invokeGetOrder($service, $request);

//                        if (!empty($GetOrder_xml["Caught_Exception"]) && $GetOrder_xml["Caught_Exception"] == "Request is throttled" && $GetOrder_xml["Response_Status_Code"] == "503")
                        while (!empty($GetOrder_xml["Caught_Exception"]) && $GetOrder_xml["Caught_Exception"] == "Request is throttled" && $GetOrder_xml["Response_Status_Code"] == "503"){
                                func_flush("sleeping...");
                                func_flush();
                                sleep('123');
                                func_flush("Unsleeped");
                                func_flush();

				print("..GetOrder throttle cycle\r\n");
	                        $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
        	                $request->setSellerId(MERCHANT_ID);
                	        $request->setAmazonOrderId($v["AmazonOrderId"]);
                        	// object or array of parameters
	                        $GetOrder_xml = invokeGetOrder($service, $request);
			}

			if (!empty($GetOrder_xml)){
				$GetOrder_arr = func_xml2hash($GetOrder_xml);
				$order_info = $GetOrder_arr["GetOrderResponse"]["GetOrderResult"]["Orders"]["Order"];
			}

			print("ORDER INFO: \r\n");
			func_print_r($order_info);
			$log_text = "Processing order: ".$v[AmazonOrderId]." - ".$orderid."  status: ".$order_info["OrderStatus"];
			print($log_text."\r\n");
			func_backprocess_log("amazon_orders", $log_text);

			$request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
			$request->setSellerId(MERCHANT_ID);
			$request->setAmazonOrderId($order_info["AmazonOrderId"]);
			// object or array of parameters
			$dom_OrderItems_xml = invokeListOrderItems($service, $request);
			print("..ListOrderItems one\r\n");
			
			func_print_r($dom_OrderItems_xml);

//			if (!empty($dom_OrderItems_xml["Caught_Exception"]) && $dom_OrderItems_xml["Caught_Exception"] == "Request is throttled" && $dom_OrderItems_xml["Response_Status_Code"] == "503")
			while (!empty($dom_OrderItems_xml["Caught_Exception"]) && $dom_OrderItems_xml["Caught_Exception"] == "Request is throttled" && $dom_OrderItems_xml["Response_Status_Code"] == "503"){
		                func_flush("sleeping...");
                		func_flush();
				sleep('123');
			        func_flush("Unsleeped");
			        func_flush();

				print("..ListOrderItems throttle cycle\r\n");
	                        $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
        	                $request->setSellerId(MERCHANT_ID);
                	        $request->setAmazonOrderId($order_info["AmazonOrderId"]);
				$dom_OrderItems_xml = invokeListOrderItems($service, $request);
			}
			
			func_print_r($dom_OrderItems_xml);

			$OrderItems_arr = array();
			if (!empty($dom_OrderItems_xml) && strpos($dom_OrderItems_xml, "<OrderItem>")!==false){

		                $dom_OrderItems_xml_arr = explode("<OrderItem>",$dom_OrderItems_xml);
                		$count_dom_OrderItems_xml_arr = count($dom_OrderItems_xml_arr);
				$OrderItems_xml = "";
		                foreach ($dom_OrderItems_xml_arr as $ko => $vo){
                		        $ko_n = $ko-1;
		                        $vo = str_replace("</OrderItem>","</OrderItem$ko_n>",$vo);
                		        $OrderItems_xml .= $vo.($ko != ($count_dom_OrderItems_xml_arr-1)?"<OrderItem$ko>":"");
		                }

				$OrderItems_arr = func_xml2hash($OrderItems_xml);
			}

//func_print_r($OrderItems_arr);
//die("111---===---111");


			if (!empty($OrderItems_arr) && is_array($OrderItems_arr["ListOrderItemsResponse"]["ListOrderItemsResult"]["OrderItems"]) && ($order_info['OrderStatus']=='Unshipped' || $order_info['OrderStatus']=='Shipped')){
				print(" ... shipped. enter order creation section\r\n");
				$PurchaseDate = strtotime($order_info['PurchaseDate']);

				$StateOrRegion = $order_info['ShippingAddress']['StateOrRegion'];

				$StateOrRegion_code = func_query_first_cell("SELECT code FROM $sql_tbl[states] WHERE country_code = '".$order_info['ShippingAddress']['CountryCode']."' AND state = '".$StateOrRegion."'");
				if (!empty($StateOrRegion_code)){
					$StateOrRegion = $StateOrRegion_code;
				}

				$PostalCode = $order_info['ShippingAddress']['PostalCode'];

				if ($order_info['ShippingAddress']['CountryCode'] == "US" && strpos($PostalCode, "-")!==false){
					$PostalCode_arr = explode("-",$PostalCode);
					$PostalCode = $PostalCode_arr[0];
				}

				$Address = $order_info['ShippingAddress']['AddressLine1'] .(!empty($order_info['ShippingAddress']['AddressLine2'])?' '.$order_info['ShippingAddress']['AddressLine2'] :'').(!empty($order_info['ShippingAddress']['AddressLine3'])?' '.$order_info['ShippingAddress']['AddressLine3'] :'');

        		        $insert_data = array (
		                        'order_prefix' => 'AZ-',
                		        'login' => 'amazon',
		                        'amazonorderid' => $order_info["AmazonOrderId"],
					'amazon_fulfillment_channel' => $order_info["FulfillmentChannel"],
                		        'total' => $order_info['OrderTotal']['Amount'],
		                        'date' => $PurchaseDate,
					'cb_status' => ($order_info['OrderStatus']=='Canceled' ? 'A' : 'P'),
		                        'dc_status' => ($order_info['OrderStatus']=='Unshipped' ? 'T' : 'S'),
                		        'bd_status' => 'W',
		                        'payment_method' => 'Amazon Seller',
                		        'firstname' => addslashes($order_info['BuyerName']),
                		        's_firstname' => addslashes($order_info['ShippingAddress']['Name']),
		                        's_address' => addslashes($Address),
		                        's_city' => addslashes($order_info['ShippingAddress']['City']),
		                        's_state' => addslashes($StateOrRegion),
                		        's_country' => addslashes($order_info['ShippingAddress']['CountryCode']),
		                        's_zipcode' => addslashes($PostalCode),
		                        'b_firstname' => addslashes($order_info['BuyerName']),
                                        'b_address' => addslashes($Address),
                                        'b_city' => addslashes($order_info['ShippingAddress']['City']),
                                        'b_state' => addslashes($StateOrRegion),
                                        'b_country' => addslashes($order_info['ShippingAddress']['CountryCode']),
		                        'b_zipcode' => addslashes($PostalCode),
                		        'phone' => addslashes($order_info['ShippingAddress']['Phone']),
		                        'email' => addslashes($order_info['BuyerEmail']),
                		        'language' => 'US',
//		                        'paymentid' => '1',
                		        'storefrontid' => '0',
		                        'fraud_status' => 'C',
                		        'overall_fraud_score' => '50',
		                        'tracking_all_filled' => 'N'
                		);

                		$new_orderid = func_array2insert('orders', $insert_data);
				unset($insert_data);


				$manufacturerid_arr = array();

				$product_total = 0;

				foreach ($OrderItems_arr["ListOrderItemsResponse"]["ListOrderItemsResult"]["OrderItems"] as $k_item => $v_item){

//func_print_r($v_item, $insert_data);

				    $prod_info = func_query_first("SELECT productid, manufacturerid, product, cost_to_us FROM $sql_tbl[products] WHERE productcode='".addslashes($v_item['SellerSKU'])."'");

				    if (!in_array($prod_info["manufacturerid"], $manufacturerid_arr)){
	                                    $insert_data2 = array (
        	                                'orderid' => $new_orderid,
                	                        'manufacturerid' => $prod_info["manufacturerid"],
                        	                'shipping' => addslashes($order_info['ShipmentServiceLevelCategory']),
                                	        'cb_status' => ($order_info['OrderStatus']=='Canceled' ? 'A' : 'P'),
                                        	'dc_status' => ($order_info['OrderStatus']=='Unshipped' ? 'T' : 'S'),
	                                        'bd_status' => 'W',
        	                                'total_net' => $order_info['OrderTotal']['Amount'],
                	                        'total_gross' => $order_info['OrderTotal']['Amount']
//                        	                'acc_paymentid' => '1'
                                	    );

	                                    func_array2insert('order_groups', $insert_data2);
        	                            unset($insert_data2);

					    $manufacturerid_arr[] = $prod_info["manufacturerid"];
				    }

				    $extra_data["display"]["price"] = $v_item['ItemPrice']['Amount'] / $v_item['QuantityOrdered'];
				    $extra_data["display"]["discounted_price"] = $v_item['ItemPrice']['Amount'];
				    $extra_data["display"]["subtotal"] = $extra_data["display"]["discounted_price"];

				    $product_total += $extra_data["display"]["discounted_price"];

                                    $insert_data3 = array (
                                        'orderid' => $new_orderid,
                                        'productid' => $prod_info["productid"],
                                        'item_cost_to_us' => $prod_info["cost_to_us"],
                                        'price' => $v_item['ItemPrice']['Amount'] / $v_item['QuantityOrdered'],
                                        'amount' => $v_item['QuantityOrdered'],
                                        'productcode' => addslashes($v_item['SellerSKU']),
					'AmazonOrderItemCode' => addslashes($v_item['OrderItemId']),
                                        'product' => addslashes($prod_info["product"]),
					'extra_data' => serialize($extra_data)
                                    );

                                    if (!empty($order_info['item_cost_to_us'])){
                                        $insert_data3['item_cost_to_us'] = $order_info['item_cost_to_us'];
                                    }

                                    func_array2insert('order_details', $insert_data3);
                                    unset($insert_data3);
                                    

//func_print_r($v_item, $insert_data, $insert_data2, $insert_data3);

					
				}

				global $xcart_dir;
				include_once $xcart_dir."/include/class/classOrders.php";
				$oOrder = new classOrder($new_orderid);
				$oOrder->updateVerificationStatus();

				$extra["product_total"]["net"] = $extra["product_total"]["gross"] = $product_total;
				db_query("UPDATE $sql_tbl[orders] SET extra='".serialize($extra)."', subtotal='$product_total' WHERE orderid='$new_orderid'");

				$id = func_query_first_cell("SELECT id FROM $sql_tbl[cidev_amazon_order_raw] WHERE orderid='$new_orderid'");
				if (!empty($id)){
					db_query("DELETE FROM $sql_tbl[cidev_amazon_order_raw] WHERE id='$id'");
				}

				db_query("INSERT INTO $sql_tbl[cidev_amazon_order_raw] (orderid, order_info, orderitems_info) VALUES ('$new_orderid', '".addslashes(serialize($v))."', '".addslashes(serialize($OrderItems_arr).$GetOrder_xml)."')");

		                $log = '<a style="color: #1411FF;" href="https://sellercentral.amazon.com/gp/orders-v2/details/ref=ag_orddet_cont_myo?ie=UTF8&orderID='.$order_info["AmazonOrderId"].'" target="_blank">Amazon order # '.$order_info["AmazonOrderId"].'</a><br />Grand total: $'.$product_total;
                		func_log_order($new_orderid, 'S', $log, "Amazon");



###
	                        $statuses = func_query_hash('SELECT code, name, type FROM ' . $sql_tbl['order_statuses']
        		        . ' ORDER BY orderby', array('type', 'code'), false, true);

                	        $order_data = func_order_data($new_orderid);
        	                $order_status="I";

	                        $mail_smarty->assign("products",$order_data["products"]);
                        	$mail_smarty->assign("giftcerts",$order_data["giftcerts"]);
                	        $mail_smarty->assign("order",$order_data["order"]);
        	                $mail_smarty->assign("userinfo",$order_data["userinfo"]);
	                        $mail_smarty->assign('statuses', $statuses);

                	        $prefix = ($order_status=="I"?"init_":"");

        	                $mes .= "STEP L ".date("H:i:s")."\n";

	                        $order_notification = func_get_order_notification($order_status, $order_data);

                	        if ($order_notification['enabled'] == 'Y') {
        	                        $mail_smarty->assign('order_notification', $order_notification);

	                                $mail_smarty->assign('type', 'A');
                                	$mail_smarty->assign("show_order_details", "Y");

					$mail_smarty->assign("show_amazon_order", "Y");

                        	        $to = $config['Company']['orders_department'];
                	                $from = $userinfo["firstname"]."<".$config['Company']['orders_department'].">";
        	                        $reply_to = $userinfo["firstname"]."<".$userinfo['email'].">";

#
##
###
				        $attach_pdf_invoice = $order_notification["admin_attach_pdf_invoice"];
				        $mail_smarty->assign('attach_pdf_invoice', $attach_pdf_invoice);
###
##
#

	
        	                        func_send_mail($to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $from, true, true, false, false, $reply_to);  // <-----------------
	                        }
###


//func_print_r($OrderItems_arr);
			}
		} // if (empty($orderid))
		else {

		  if ($config["Amazon_Orders_options"]["amazon_enable_one_time_rewrite_process"] == "Y"){

                       $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
                       $request->setSellerId(MERCHANT_ID);
                       $request->setAmazonOrderId($v["AmazonOrderId"]);
                       // object or array of parameters
                       $GetOrder_xml = invokeGetOrder($service, $request);

                        while (!empty($GetOrder_xml["Caught_Exception"]) && $GetOrder_xml["Caught_Exception"] == "Request is throttled" && $GetOrder_xml["Response_Status_Code"] == "503"){
                                func_flush("sleeping...");
                                func_flush();
                                sleep('123');
                                func_flush("Unsleeped");
                                func_flush();

                                $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
                                $request->setSellerId(MERCHANT_ID);
                                $request->setAmazonOrderId($v["AmazonOrderId"]);
                                // object or array of parameters
                                $GetOrder_xml = invokeGetOrder($service, $request);
                        }

                        if (!empty($GetOrder_xml)){
				$GetOrder_arr = func_xml2hash($GetOrder_xml);
				$order_info = $GetOrder_arr["GetOrderResponse"]["GetOrderResult"]["Orders"]["Order"];
                        }


//func_print_r($GetOrder_xml);
//die();
                        $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
                        $request->setSellerId(MERCHANT_ID);
                        $request->setAmazonOrderId($order_info["AmazonOrderId"]);
                        // object or array of parameters
                        $dom_OrderItems_xml = invokeListOrderItems($service, $request);

//                        if (!empty($dom_OrderItems_xml["Caught_Exception"]) && $dom_OrderItems_xml["Caught_Exception"] == "Request is throttled" && $dom_OrderItems_xml["Response_Status_Code"] == "503")
                        while (!empty($dom_OrderItems_xml["Caught_Exception"]) && $dom_OrderItems_xml["Caught_Exception"] == "Request is throttled" && $dom_OrderItems_xml["Response_Status_Code"] == "503"){
                                func_flush("sleeping...");
                                func_flush();
                                sleep('123');
                                func_flush("Unsleeped");
                                func_flush();

                                $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
                                $request->setSellerId(MERCHANT_ID);
                                $request->setAmazonOrderId($order_info["AmazonOrderId"]);
                                $dom_OrderItems_xml = invokeListOrderItems($service, $request);
                        }

                        $OrderItems_arr = array();
                        if (!empty($dom_OrderItems_xml) && strpos($dom_OrderItems_xml, "<OrderItem>")!==false){

                                $dom_OrderItems_xml_arr = explode("<OrderItem>",$dom_OrderItems_xml);
                                $count_dom_OrderItems_xml_arr = count($dom_OrderItems_xml_arr);
                                $OrderItems_xml = "";
                                foreach ($dom_OrderItems_xml_arr as $ko => $vo){
                                        $ko_n = $ko-1;
                                        $vo = str_replace("</OrderItem>","</OrderItem$ko_n>",$vo);
                                        $OrderItems_xml .= $vo.($ko != ($count_dom_OrderItems_xml_arr-1)?"<OrderItem$ko>":"");
                                }

                                $OrderItems_arr = func_xml2hash($OrderItems_xml);
                        }

//func_print_r($OrderItems_arr);
//die("222---===---222");

			if (!empty($OrderItems_arr) && is_array($OrderItems_arr["ListOrderItemsResponse"]["ListOrderItemsResult"]["OrderItems"]) && ($order_info['OrderStatus']=='Unshipped' || $order_info['OrderStatus']=='Shipped')){
                                $PurchaseDate = strtotime($order_info['PurchaseDate']);

                                $StateOrRegion = $order_info['ShippingAddress']['StateOrRegion'];

                                $StateOrRegion_code = func_query_first_cell("SELECT code FROM $sql_tbl[states] WHERE country_code = '".$order_info['ShippingAddress']['CountryCode']."' AND state = '".$StateOrRegion."'");
                                if (!empty($StateOrRegion_code)){
                                        $StateOrRegion = $StateOrRegion_code;
                                }

                                $PostalCode = $order_info['ShippingAddress']['PostalCode'];

                                if ($order_info['ShippingAddress']['CountryCode'] == "US" && strpos($PostalCode, "-")!==false){
                                        $PostalCode_arr = explode("-",$PostalCode);
                                        $PostalCode = $PostalCode_arr[0];
                                }

                                $Address = $order_info['ShippingAddress']['AddressLine1'] .(!empty($order_info['ShippingAddress']['AddressLine2'])?' '.$order_info['ShippingAddress']['AddressLine2'] :'').(!empty($order_info['ShippingAddress']['AddressLine3'])?' '.$order_info['ShippingAddress']['AddressLine3'] :'');

				$new_orderid = $orderid;


                                $insert_data = array (
                                        'order_prefix' => 'AZ-',
                                        'login' => 'amazon',
                                        'amazonorderid' => $order_info["AmazonOrderId"],
					'amazon_fulfillment_channel' => $order_info["FulfillmentChannel"],
                                        'total' => $order_info['OrderTotal']['Amount'],
                                        'date' => $PurchaseDate,
					'cb_status' => ($order_info['OrderStatus']=='Canceled' ? 'A' : 'P'),
                                        'dc_status' => ($order_info['OrderStatus']=='Unshipped' ? 'T' : 'S'),
                                        'bd_status' => 'W',
                                        'payment_method' => 'Amazon Seller',
                                        'firstname' => addslashes($order_info['BuyerName']),
                                        's_firstname' => addslashes($order_info['ShippingAddress']['Name']),
                                        's_address' => addslashes($Address),
                                        's_city' => addslashes($order_info['ShippingAddress']['City']),
                                        's_state' => addslashes($StateOrRegion),
                                        's_country' => addslashes($order_info['ShippingAddress']['CountryCode']),
                                        's_zipcode' => addslashes($PostalCode),
                                        'b_firstname' => addslashes($order_info['BuyerName']),
                                        'b_address' => addslashes($Address),
                                        'b_city' => addslashes($order_info['ShippingAddress']['City']),
                                        'b_state' => addslashes($StateOrRegion),
                                        'b_country' => addslashes($order_info['ShippingAddress']['CountryCode']),
                                        'b_zipcode' => addslashes($PostalCode),
                                        'phone' => addslashes($order_info['ShippingAddress']['Phone']),
                                        'email' => addslashes($order_info['BuyerEmail']),
                                        'language' => 'US',
//                                        'paymentid' => '1',
                                        'storefrontid' => '0',
                                        'fraud_status' => 'C',
                                        'overall_fraud_score' => '50',
                                        'tracking_all_filled' => 'N'
                                );

				func_array2update("orders", $insert_data, "orderid = '$new_orderid'");
                                unset($insert_data);


                                $manufacturerid_arr = array();

                                $product_total = 0;

				db_query("DELETE FROM $sql_tbl[order_details] WHERE orderid='$new_orderid'");

                                foreach ($OrderItems_arr["ListOrderItemsResponse"]["ListOrderItemsResult"]["OrderItems"] as $k_item => $v_item){

//func_print_r($v_item, $insert_data);

                                    $prod_info = func_query_first("SELECT productid, manufacturerid, product, cost_to_us FROM $sql_tbl[products] WHERE productcode='".addslashes($v_item['SellerSKU'])."'");

                                    if (!in_array($prod_info["manufacturerid"], $manufacturerid_arr)){
                                            $insert_data2 = array (
                                                'orderid' => $new_orderid,
                                                'manufacturerid' => $prod_info["manufacturerid"],
                                                'shipping' => addslashes($order_info['ShipmentServiceLevelCategory']),
						'cb_status' => ($order_info['OrderStatus']=='Canceled' ? 'A' : 'P'),
                                                'dc_status' => ($order_info['OrderStatus']=='Unshipped' ? 'T' : 'S'),
                                                'bd_status' => 'W',
                                                'total_net' => $order_info['OrderTotal']['Amount'],
                                                'total_gross' => $order_info['OrderTotal']['Amount']
//                                              'acc_paymentid' => '1'
                                            );

//                                            func_array2insert('order_groups', $insert_data2);
                                            func_array2update('order_groups', $insert_data2, "orderid = '$new_orderid' AND manufacturerid=".$prod_info["manufacturerid"]);
                                            unset($insert_data2);

                                            $manufacturerid_arr[] = $prod_info["manufacturerid"];
                                    }
                                    $extra_data["display"]["price"] = $v_item['ItemPrice']['Amount'] / $v_item['QuantityOrdered'];
                                    $extra_data["display"]["discounted_price"] = $v_item['ItemPrice']['Amount'];
                                    $extra_data["display"]["subtotal"] = $extra_data["display"]["discounted_price"];

                                    $product_total += $extra_data["display"]["discounted_price"];

                                    $insert_data3 = array (
                                        'orderid' => $new_orderid,
                                        'productid' => $prod_info["productid"],
                                        'item_cost_to_us' => $prod_info["cost_to_us"],
                                        'price' => $v_item['ItemPrice']['Amount'] / $v_item['QuantityOrdered'],
                                        'amount' => $v_item['QuantityOrdered'],
                                        'productcode' => addslashes($v_item['SellerSKU']),
                                        'AmazonOrderItemCode' => addslashes($v_item['OrderItemId']),
                                        'product' => addslashes($prod_info["product"]),
                                        'extra_data' => serialize($extra_data)
                                    );

                                    if (!empty($order_info['item_cost_to_us'])){
                                        $insert_data3['item_cost_to_us'] = $order_info['item_cost_to_us'];
                                    }

                                    func_array2insert('order_details', $insert_data3);
                                    unset($insert_data3);

//func_print_r($v_item, $insert_data, $insert_data2, $insert_data3);


                                }

                                $extra["product_total"]["net"] = $extra["product_total"]["gross"] = $product_total;
                                db_query("UPDATE $sql_tbl[orders] SET extra='".serialize($extra)."', subtotal='$product_total' WHERE orderid='$new_orderid'");

                                $id = func_query_first_cell("SELECT id FROM $sql_tbl[cidev_amazon_order_raw] WHERE orderid='$new_orderid'");
                                if (!empty($id)){
                                        db_query("DELETE FROM $sql_tbl[cidev_amazon_order_raw] WHERE id='$id'");
                                }

                                db_query("INSERT INTO $sql_tbl[cidev_amazon_order_raw] (orderid, order_info, orderitems_info) VALUES ('$new_orderid', '".addslashes(serialize($v))."', '".addslashes(serialize($OrderItems_arr).$GetOrder_xml)."')");


                                $log = '<a style="color: #1411FF;" href="https://sellercentral.amazon.com/gp/orders-v2/details/ref=ag_orddet_cont_myo?ie=UTF8&orderID='.$order_info["AmazonOrderId"].'" target="_blank">Amazon order # '.$order_info["AmazonOrderId"].'</a><br />Grand total: $'.$product_total;
                                func_log_order($new_orderid, 'S', $log, "Amazon");

			}

		  } // if ($config["Amazon_Orders_options"]["amazon_enable_one_time_rewrite_process"] == "Y")
/////////////

		  $log = "";
		  if (!empty($prod_info["manufacturerid"])){
			$manufacturerid = $prod_info["manufacturerid"];
		  } else {
			$manufacturerid = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");
		  }
		  $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");


		  $current_cb_status = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");
		  $current_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_cb_status'");
		  $current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");
		  $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

//		  if ($order_info["OrderStatus"] == "Shipped")
		  if ($v["OrderStatus"] == "Shipped"){

				if ($current_cb_status != "P"){
					if (!empty($log)) $log .= "<br />";
					$log .= "<B>".$code.":</B> cb_status: ". $current_cb_status_value . " -> Paid";
				}

                                if ($current_dc_status != "S"){
                                        if (!empty($log)) $log .= "<br />";
                                        $log .= "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> Shipped";
                                }

				db_query("UPDATE $sql_tbl[order_groups] SET cb_status='P', dc_status='S' WHERE orderid='$orderid'");
				db_query("UPDATE $sql_tbl[orders] SET cb_status='P', dc_status='S' WHERE orderid='$orderid'");
		  }
		  elseif ($v["OrderStatus"] == "Canceled"){

//if ($orderid=="41778"){
//func_print_r($order_info["OrderStatus"], $v);
//}

                                if ($current_cb_status != "A"){
                                        if (!empty($log)) $log .= "<br />";
                                        $log .= "<B>".$code.":</B> cb_status: ". $current_cb_status_value . " -> Canceled";
                                }

				db_query("UPDATE $sql_tbl[order_groups] SET cb_status='A' WHERE orderid='$orderid'");
				db_query("UPDATE $sql_tbl[orders] SET cb_status='A' WHERE orderid='$orderid'");
		  }
		  elseif ($v["OrderStatus"] == "Unshipped"){

		  }

		  if (!empty($log)){
			func_log_order($orderid, 'S', $log, "Amazon");
		  }

//if ($orderid == "41696"){
//func_print_r($v, $order_info, $orderid);
//die();
//}

		} // else

//		func_print_r($v);
//die();

	} //foreach ($dom_xml_arr_orders as $k => $v)
	print("All orders of ListOrders request is processed...\r\n");
  } // if (!empty($dom_xml_arr_orders) && is_array($dom_xml_arr_orders))
} // while (!empty($NextToken))
print("while (!empty($NextToken)) \r\n");
### 1e









$date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $started_at));
$date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', time()));
$interval = $date1->diff($date2);
$years = $interval->format("%y");
$months = $interval->format("%m");
$days = $interval->format("%d");
$hours = $interval->format("%h");
$mins = $interval->format("%i");
$duration = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_amazon_orders'");
db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='amazon_enable_one_time_rewrite_process'");

$log_text = "Cron completed. Duration: ".$duration;
func_backprocess_log("amazon_orders", $log_text);

die("DONE!");
?>
