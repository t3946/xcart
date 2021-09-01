<?php
//
// Copyright 2011 Auctane LLC. All rights reserved.
// This file and its content is copyright of Auctane LLC for use with the ShipStation software solution.
//
// Any redistribution or reproduction of part or all of the contents in any form is strictly prohibited.
// You may not, except with our express written permission, distribute or commercially exploit the content.
// Nor may you transmit it or store it in any other website or other form of electronic retrieval system.
//
// Version 1.0
//
?>
<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './top.inc.php';
require './init.php';

x_load('crypt', 'order', 'product');

header ('Content-Type: text/xml');

define('DATE_TIME_FORMAT','%m/%d/%Y %H:%M:%S');

function AddFieldToXML($FieldName, $Value)
{
	// 	$FindStr = "&";
	// 	$NewStr  = "&amp;";
	// 	$Result = str_replace($FindStr, $NewStr, $Value);
	$Result = mb_convert_encoding(str_replace('&', '&amp;', $Value), 'UTF-8');

	echo "\t\t<$FieldName>$Result</$FieldName>\n";
}

function zen_date_raw2($date, $reverse = false) {
	if ($reverse) {
		return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
	} else {
		return substr($date, 6, 4) . '-' . substr($date, 0, 2) . '-' . substr($date, 3, 2) . ' ' . substr($date, 11, 2) . '.' . substr($date, 14, 2) . '.'.  '00';
	}
}

function zen_datetime_short2($raw_datetime) {
	if ( ($raw_datetime == '0001-01-01 00:00:00') || ($raw_datetime == '') ) return false;

	$year = (int)substr($raw_datetime, 0, 4);
	$month = (int)substr($raw_datetime, 5, 2);
	$day = (int)substr($raw_datetime, 8, 2);
	$hour = (int)substr($raw_datetime, 11, 2);
	$minute = (int)substr($raw_datetime, 14, 2);
	$second = (int)substr($raw_datetime, 17, 2);

	return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
}
function zen_datetime_shortunix($raw_datetime) {
	if ( ($raw_datetime == '0001-01-01 00:00:00') || ($raw_datetime == '') ) return false;

	$year = (int)substr($raw_datetime, 0, 4);
	$month = (int)substr($raw_datetime, 5, 2);
	$day = (int)substr($raw_datetime, 8, 2);
	$hour = (int)substr($raw_datetime, 11, 2);
	$minute = (int)substr($raw_datetime, 14, 2);
	$second = (int)substr($raw_datetime, 17, 2);
	$dateunix = mktime($hour, $minute, $seconds, $month, $day, $year);

	return date('U', $dateunix);
}
function func_is_password_correct2($password, $crypted)
{
	global $username, $mail_smarty, $active_modules, $usertype, $config, $top_message;

	$password = trim(stripslashes($password));

	if (empty($password)) {

		return false;

	}

	$right_password = text_decrypt($crypted);

	return $password == $right_password;

}

$statuses['I']='Not finished';
$statuses['Q']='Queued';
$statuses['P']='Processed';
$statuses['B']='Backordered';
$statuses['D']='Declined';
$statuses['F']='Failed';
$statuses['C']='Complete';

// SSZen.php??action=export&start_date=11/03/2011 10:11&end_date=11/04/2011 13:13

if (!isset($_SERVER['PHP_AUTH_USER'])) {
	header('WWW-Authenticate: Basic realm="ShipStation"');
	header('HTTP/1.0 401 Unauthorized');
	echo 'Unauthorized';
	exit;
} else {
	$user_data = func_query_first("SELECT * FROM xcart_customers WHERE login='". $_SERVER['PHP_AUTH_USER'] ."' AND usertype='A'");

	if (!$user_data) {
		echo 'Unauthorized, no admin in database';
		exit;
	}
	else {
		$password = stripslashes($_SERVER['PHP_AUTH_PW']);
		if (!func_is_password_correct2($password, $user_data['password'])) {
			header('WWW-Authenticate: Basic realm="ShipStation"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Unauthorized';
			exit;
		}
	}
}

//DONE till here

if($_GET['action']=='export'){

	$sd = zen_datetime_shortunix(zen_date_raw2($_GET['start_date']));
	$ed = zen_datetime_shortunix(zen_date_raw2($_GET['end_date']));

	//func_change_order_status($orderid, $status);


	//begin outputing XML
	echo "<?xml version=\"1.0\" encoding=\"utf-16\"?>\n";
	echo  "<Orders>\n";

	//process orders
	// $result = func_query_hash("SELECT distinct * FROM xcart_orders WHERE date >= '". $sd ."' and date <= '". $ed ."' or orderid in (select distinct orderid from xcart_order_status_history where date_time >= '". $sd ."' and date_time <= '". $ed ."')", "", true, false);
//	$result = db_query("SELECT distinct orderid FROM xcart_orders WHERE date >= '". $sd ."' and date <= '". $ed ."' or orderid in (select distinct orderid from xcart_order_status_history where date_time >= '". $sd ."' and date_time <= '". $ed ."')");
	$result = db_query("SELECT distinct orderid FROM xcart_orders WHERE date >= '". $sd ."' and date <= '". $ed ."'");
	if(($result)){
		while ($query_result = db_fetch_array($result)) {

			$order = func_order_data($query_result['orderid']);

			$orders_id =  $order['order']['orderid'];
			$date =  $order['order']['date'];
			$date =  date('Y-m-d H:i:s', $date);
			$last_modified = '';
			$order_status_id = $order['order']['status'];
			$order_status = $statuses[$order['order']['status']];

			echo  "\t<Order>\n";

			//order details
			AddFieldToXML("OrderNumber", $orders_id);
			AddFieldToXML("OrderDate", $date);
//			AddFieldToXML("OrderStatusCode", $order_status_id);
			AddFieldToXML("OrderStatusCode", "C");
//			AddFieldToXML("OrderStatusName", $order_status);
			AddFieldToXML("OrderStatusName", "Complete");
			AddFieldToXML("LastModified", $last_modified);
			AddFieldToXML("PaymentMethod", $order['order']['payment_method']);
//			AddFieldToXML("ShippingMethod", $order['order']['shipping']);
			AddFieldToXML("ShippingMethod", "USPS Priority Mail");
			AddFieldToXML("CouponCode", $order['order']['coupon']);
			AddFieldToXML("Currency", '');
			AddFieldToXML("CurrencyValue", '');
			AddFieldToXML("OrderTotal", $order['order']['total']);
			AddFieldToXML("TaxAmount", $order['order']['tax']);
			AddFieldToXML("ShippingAmount", $order['order']['shipping_cost']);
			AddFieldToXML("CommentsFromBuyer", '<![CDATA['.$order['order']['customer_notes']. ']]>');
			//order details



			//customer details
			echo  "\t<Customer>\n";

			AddFieldToXML("CustomerNumber", $order['order']['userid']);

			//billing details
			echo  "\t<BillTo>\n";

			$first_name = $order['order']['b_firstname'];
			$last_name = $order['order']['b_lastname'];

			if ($first_name == '')
			{
				$first_name = $order['order']['firstname'];
			}

			if ($last_name == '')
			{
				$last_name = $order['order']['lastname'];
			}

			AddFieldToXML("Name", '<![CDATA['.$first_name. ' ' . $last_name .']]>');
			AddFieldToXML("Company", '<![CDATA['.$order['order']['company']. ']]>');
			AddFieldToXML("Address1", '<![CDATA['.$order['order']['b_address'].($order['order']['b_address_2']!=''?"\n".$order['order']['b_address_2']:'').']]>');
			//AddFieldToXML("Address2", '<![CDATA['.$order['order']['b_county']. ']]>');
			AddFieldToXML("City", '<![CDATA['.$order['order']['b_city']. ']]>');
			AddFieldToXML("State", '<![CDATA['.$order['order']['b_state']. ']]>');
			AddFieldToXML("StateCode", $order['order']['b_state']);
			AddFieldToXML("PostalCode", $order['order']['b_zipcode']);
			AddFieldToXML("Country", $order['order']['b_country']);
			AddFieldToXML("CountryCode", $order['order']['b_country']);
			AddFieldToXML("Phone", $order['order']['b_phone']);
			AddFieldToXML("Email", $order['order']['email']);
			//	AddFieldToXML("CountryCode", $country_result['countries_iso_code_2']);

			echo  "\t</BillTo>\n";
			//billing details
			//shipping details

			echo  "\t<ShipTo>\n";

			$first_name = $order['order']['s_firstname'];
			$last_name = $order['order']['s_lastname'];

			if ($first_name == '')
			{
				$first_name = $order['order']['firstname'];
			}

			if ($last_name == '')
			{
				$last_name = $order['order']['lastname'];
			}

			AddFieldToXML("Name", '<![CDATA['.$first_name. ' ' . $last_name .']]>');
			AddFieldToXML("Company", '<![CDATA['.$order['order']['company']. ']]>');
			AddFieldToXML("Address1", '<![CDATA['.$order['order']['s_address'].($order['order']['s_address_2']!=''?"\n".$order['order']['s_address_2']:''). ']]>');
			//AddFieldToXML("Address2", '<![CDATA['.$order['order']['s_county']. ']]>');
			AddFieldToXML("City", '<![CDATA['.$order['order']['s_city']. ']]>');
			AddFieldToXML("State", '<![CDATA['.$order['order']['s_state']. ']]>');
			AddFieldToXML("StateCode", $order['order']['s_state']);
			AddFieldToXML("PostalCode", $order['order']['s_zipcode']);
			AddFieldToXML("Country", $order['order']['s_country']);
			AddFieldToXML("CountryCode", $order['order']['s_country']);
			AddFieldToXML("Phone", $order['order']['s_phone']);
			AddFieldToXML("Email", $order['order']['email']);
			//	AddFieldToXML("CountryCode", $country_result['countries_iso_code_2']);

			echo  "\t</ShipTo>\n";
			//shipping details

			echo  "\t</Customer>\n";
			//customer details
			echo  "\t<Items>\n";
			//process Order Items

			if(count($order['products']) > 0) {
				foreach ($order['products'] as $products_result) {

					try {
						$product_info = func_select_product($products_result['productid'], @$user_account['membershipid'], false);
					}
					catch(Exception $e)
					{
					}

					$weight = $products_result['weight'];
					$image = $product_info['images'][$product_info['image_type']]['url'];

					echo  "\t<Item>\n";
					AddFieldToXML("ProductID", $products_result['productid']);
					AddFieldToXML("SKU", '<![CDATA['.$products_result['productcode']. ']]>');
					AddFieldToXML("Name", '<![CDATA['. $products_result['product']. ']]>');
					AddFieldToXML("ImageUrl", $image);
					AddFieldToXML("Weight", $weight);
					AddFieldToXML("UnitPrice", $products_result['price']);
					AddFieldToXML("TaxAmount", $order['order']['tax']);
					AddFieldToXML("Quantity", $products_result['amount']);


					if($products_result['product_options']){
						echo  "\t<Attributes>\n";
						foreach ($products_result['product_options'] as $options)
						{
							echo  "\t<Attribute Name=\"". htmlentities($options['classtext'], ENT_QUOTES, "UTF-8") ."\" Value=\"". htmlentities($options['option_name'], ENT_QUOTES, "UTF-8") ."\" />\n";
						}
						echo  "\t</Attributes>\n";
					}

					echo  "\t</Item>\n";


				}
			}
			//process Order Items
			echo  "\t</Items>\n";
			echo  "\t</Order>\n";


		}
	}

	//process Orders

	//finish outputing XML
	echo  "</Orders>";


}
/*elseif($_GET['action']=='verifystatus'){

$status = strtolower($_GET['status']);

$orders_status_query = tep_db_query("select orders_status_id, orders_status_name
from " . TABLE_ORDERS_STATUS . "
where language_id = '" . (int)$languages_id . "' and LOWER(orders_status_name) = '". $status ."'");
$orders_status = tep_db_fetch_array($orders_status_query);

if($orders_status['orders_status_id']) {
echo 'true';
}
else {
echo 'false';
}

}*/
elseif($_GET['action']=='update'){

	//?action=update&order_number=ABC123&status=4&comment=commment

	if($_GET['order_number']){

		$status = $_GET['status'];
		$customer_notified = '0';
		$comments = $_GET['comment'];

		$result_ordercheck = func_query_first("SELECT orderid FROM xcart_orders WHERE orderid = '". $_GET['order_number'] ."'");

		if($result_ordercheck['orderid']){

			//if($statuses[$status]) {
			db_query("UPDATE xcart_orders SET status='C', tracking='$comments' WHERE orderid='". $result_ordercheck['orderid'] ."'");

			echo 'Status updated successfully';
			//}
			//else {
			//echo 'No order status in database';
			//}
		}
		else {
			echo 'Order does not exist in database';
		}


	}
	else {
		echo 'No order number';
	}

}
else {
	echo 'No action parameter. Please contact software provider.';
}
?>
