<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load("fraud", "order");

require $xcart_dir."/include/history_order.php";
require $xcart_dir."/include/countries.php";

if (!empty($active_modules['Google_Checkout']))
        include $xcart_dir."/modules/Google_Checkout/gcheckout_admin.php";

$order = $order_data["order"];
$userinfo = $order_data["userinfo"];
$products = $order_data["products"];
$giftcerts = $order_data["giftcerts"];

if ($REQUEST_METHOD == "POST") {

//func_print_r($_POST);
//die();

	$log = "";
	
	if ($mode == "apply_changes_and_update_fraud_scores"){
		$log = "'Apply changes and update fraud scores' at 'Fraud page'";
	} elseif ($mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status"){
		$log = "'Apply changes, update fraud scores and change fraud check status' at 'Fraud page'";
	}

	if (($mode == "apply_changes_and_update_fraud_scores" || $mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status") && !empty($posted_data) && is_array($posted_data)){

		db_query("DELETE FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid'");

		$overall_fraud_score = 0;
		foreach ($posted_data as $k => $v){
			$question_code = strtoupper($v["question_code"]);
			$manual_action = $v["manual_action"];

			$importance_factor = func_query_first_cell("SELECT importance_factor FROM $sql_tbl[fraud_check] WHERE question_code='$question_code'");

			$importance_factor = str_replace(' ', '', $importance_factor);
			$importance_factor_arr = explode(",", $importance_factor);

			$auto = func_query_first_cell("SELECT auto FROM $sql_tbl[fraud_check] WHERE question_code='$question_code'");

			$fraud_score = 0;
			$bare_fraud_score = 0;
			$selected_importance_factor = 0;
			$fraud_result = "";

			if ($auto == "Y"){

				$func_name = "func_".$question_code;

				if (function_exists($func_name)) {
					$bare_fraud_score_arr = $func_name($order_data);

					$fraud_result = $bare_fraud_score_arr["fraud_result"];

					$bare_fraud_score = $bare_fraud_score_arr["score"];

					if ($fraud_result == "negative"){
						$selected_importance_factor = $importance_factor_arr[0];
					} elseif ($fraud_result == "positive"){
						$selected_importance_factor = $importance_factor_arr[2];
                                        } else {
                                                $selected_importance_factor = $importance_factor_arr[1];
					}

					$fraud_score = $selected_importance_factor * $bare_fraud_score;
				}

			} else {
				if ($manual_action == "Y"){
					$bare_fraud_score = $importance_factor_arr[2];
				} elseif ($manual_action == "N"){
					$bare_fraud_score = $importance_factor_arr[0];
				} else {
					$bare_fraud_score = $importance_factor_arr[1];
				}

				$fraud_score = $bare_fraud_score;
			}

			$overall_fraud_score += $fraud_score;

			db_query("INSERT INTO $sql_tbl[order_fraud_checks] (orderid, question_code, manual_action, fraud_score, bare_fraud_score, fraud_result) VALUES ('$orderid', '$question_code', '$manual_action', '$fraud_score', '$bare_fraud_score', '$fraud_result')");
		}

		$current_overall_fraud_score = func_query_first_cell("SELECT overall_fraud_score FROM $sql_tbl[orders] WHERE orderid='$orderid'");

		$overall_fraud_score = price_format($overall_fraud_score);

		if ($current_overall_fraud_score != $overall_fraud_score){

			if ($log != "") $log .= "<br />";
			$log .= "overall_fraud_score: ".$current_overall_fraud_score." -> ".$overall_fraud_score;

			db_query("UPDATE $sql_tbl[orders] SET overall_fraud_score='$overall_fraud_score' WHERE orderid='$orderid'");
		}

		if ($mode == "apply_changes_and_update_fraud_scores"){
			if ($overall_fraud_score > $config["Fraud_check"]["Overall_FC_threshold_for_Clear_status"]){
				$new_fraud_status = $config["Fraud_check"]["Threshold_status"];
				$current_fraud_status = func_query_first_cell("SELECT fraud_status FROM $sql_tbl[orders] WHERE orderid='$orderid'");

				if ($current_fraud_status != $new_fraud_status){
                                	if ($log != "") $log .= "<br />";
                        	        $current_fraud_status_name = $fraud_statuses[$current_fraud_status];
                	                $new_fraud_status_name = $fraud_statuses[$new_fraud_status];
        	                        $log .= "fraud_status: ".$current_fraud_status_name." -> ".$new_fraud_status_name;

	                                db_query("UPDATE $sql_tbl[orders] SET fraud_status='$new_fraud_status' WHERE orderid='$orderid'");
				}
			}
		}

		if ($mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status"){

			$current_fraud_status = func_query_first_cell("SELECT fraud_status FROM $sql_tbl[orders] WHERE orderid='$orderid'");
			if ($current_fraud_status != $fraud_status){

				if ($log != "") $log .= "<br />";
				$current_fraud_status_name = $fraud_statuses[$current_fraud_status];
				$fraud_status_name = $fraud_statuses[$fraud_status];
				$log .= "fraud_status: ".$current_fraud_status_name." -> ".$fraud_status_name;

				db_query("UPDATE $sql_tbl[orders] SET fraud_status='$fraud_status' WHERE orderid='$orderid'");
			}
		}

		if ($log != ""){
			func_log_order($orderid, 'X', $log, $login);
		}
	}

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("fraud_page.php?orderid=".$orderid."#buttons");
}


$geoip_address = "";
$customer_ip = $order["extra"]["ip"];
$geoip_state = "";
if (!empty($customer_ip)){
        $customer_ip_arr = explode(".", $customer_ip);
        if (!empty($customer_ip_arr) && is_array($customer_ip_arr)){
                $customer_ip_INTEGER = $customer_ip_arr[0]*16777216 + $customer_ip_arr[1]*65536 + $customer_ip_arr[2]*256 + $customer_ip_arr[3];
        }

        if (!empty($customer_ip_INTEGER)){
                $locId = func_query_first_cell("SELECT locId FROM $sql_tbl[geo_litecity_blocks] WHERE $customer_ip_INTEGER BETWEEN startIpNum AND endIpNum LIMIT 1");

                if (!empty($locId)){
                        $geo_litecity_location = func_query_first("SELECT * FROM $sql_tbl[geo_litecity_location] WHERE locId='".addslashes($locId)."'");

                        if (!empty($geo_litecity_location)){
				$geoip_state = $geo_litecity_location["region"];

				$geoip_address = $geo_litecity_location["country"].", ".$geo_litecity_location["region"].", ".$geo_litecity_location["city"].", ".$geo_litecity_location["postalCode"];
                        }
                }
        }
}

$phone_area_code_address = "";
$areacode_state = "";
$phone_area_code_state = "";
$userinfo_phone = $userinfo["phone"];
$userinfo_phone = str_replace(" ", "", $userinfo_phone);
$userinfo_phone = str_replace("(", "", $userinfo_phone);
$userinfo_phone = str_replace(")", "", $userinfo_phone);
$userinfo_area_code = substr($userinfo_phone, 0, 3);

$Telephone_area_codes = func_query_first("SELECT * FROM $sql_tbl[Telephone_area_codes] WHERE area_code='".addslashes($userinfo_area_code)."'");

if (!empty($Telephone_area_codes)){

	foreach ($countries as $k => $v){
        	if ($v["country"] == $Telephone_area_codes["country"]){
                	$country_code = $v["country_code"];
                        break;
                }
        }

	if (!empty($country_code)){
		$state_name = trim($Telephone_area_codes["state"]);
		$areacode_state = func_query_first_cell("SELECT code FROM $sql_tbl[states] WHERE state='$state_name' AND country_code='$country_code'");
		$Telephone_area_codes["state"] = $areacode_state;
	}
	else {
		$areacode_state = $Telephone_area_codes["state"];
	}
	$Telephone_area_code_info = $Telephone_area_codes["area"] . " (".$areacode_state . ")";
	$phone_area_code_state = $Telephone_area_code_info;

	$phone_area_code_address = $Telephone_area_codes["country"] . ", ". $Telephone_area_codes["state"] . ", ". $Telephone_area_codes["area"];
}

$links_to_ordered_products = '';
if (!empty($products) && is_array($products)){
	$last_index = count($products) - 1;
	foreach ($products as $k => $v){
		$links_to_ordered_products .= '<a href="'.$v["links"]["customer"].'" target="_blank" style="color: #1F08F8;">'.$v["productcode"].'</a>';
		if ($k != $last_index){
			$links_to_ordered_products .= '<br />';
		}
	}
}

$billing_address_comma = $userinfo["b_address"] . (!empty($userinfo["b_address_2"])? ", $userinfo[b_address_2]": "") .", ". $userinfo["b_city"]. ", ". $userinfo["b_state"]. ", ". $userinfo["b_zipcode"];

$billing_address = $userinfo["b_address"] . (!empty($userinfo["b_address_2"])? " $userinfo[b_address_2]": "") ." ". $userinfo["b_city"]. " ". $userinfo["b_state"]. " ". $userinfo["b_zipcode"];
$google_billing_address = str_replace(" ", "+", $billing_address);
$google_billing_address = str_replace("#", "", $google_billing_address);

$shipping_address_comma = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? ", $userinfo[s_address_2]": "") .", ". $userinfo["s_city"]. ", ". $userinfo["s_state"]. ", ". $userinfo["s_zipcode"];

$shipping_address = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? " $userinfo[s_address_2]": "") ." ". $userinfo["s_city"]. " ". $userinfo["s_state"]. " ". $userinfo["s_zipcode"];
$google_shipping_address = str_replace(" ", "+", $shipping_address);
$google_shipping_address = str_replace("#", "", $google_shipping_address);

$phone = $userinfo["phone"] . (!empty($userinfo["phone_ext"]) ? " ext $userinfo[phone_ext]": "");

$google_phone = $userinfo["phone"];
$google_phone = preg_replace("/[^0-9]/S","", $userinfo["phone"]);
$google_phone_strlen = strlen($google_phone);

if ($google_phone_strlen >= 10){

                $tmp_counter = 0;
                $google_phone_new = "";
                for ($i=$google_phone_strlen; $i>=0; $i--){

                        $google_phone_new = $google_phone{$i}.$google_phone_new;

                        if ($tmp_counter == 4){
                                $google_phone_new = "-".$google_phone_new;
                        }

                        if ($tmp_counter == 7){
                                $google_phone_new = ") ".$google_phone_new;
                        }

                        if ($tmp_counter == 10){
                                $google_phone_new = "(".$google_phone_new;

                                if ($google_phone_strlen > 10){
                                        $google_phone_new = "] ".$google_phone_new;
                                }
                        }

                        $tmp_counter++;
                }

                if ($google_phone_strlen > 10){
                        $google_phone_new = "[+".$google_phone_new;

                        $google_phone_new = urlencode($google_phone_new);
                }

                $google_phone = $google_phone_new;
}

//func_print_r($google_phone, $google_phone_new);

$google_phone = $google_phone . (!empty($userinfo["phone_ext"]) ? " ext $userinfo[phone_ext]": "");
$google_phone =  str_replace(" ", "+", $google_phone);

$userinfo_site_arr = explode("@", $userinfo["email"]);
$userinfo_site = $userinfo_site_arr[1];

$email_domain_website = '<a target="_blank" href="http://www.'.$userinfo_site_arr[1].'" style="color: #1F08F8;">www.'.$userinfo_site_arr[1].'</a>';

$orders_full_names = $userinfo["s_firstname"]."<br />".$userinfo["b_firstname"]."<br />".$userinfo["firstname"];

$orders_company_names = $userinfo["additional_fields"][1]["value"]."<br />".$userinfo["additional_fields"][0]["value"];

$cidev_order_details = $order["details"];
$cidev_order_details_err = explode("TransID #", $cidev_order_details);
if (!empty($cidev_order_details_err[1])){
        if (strpos($cidev_order_details_err[1], ')') !== false){
                        $cidev_order_details_TransID_arr = explode(")", $cidev_order_details_err[1]);
                        $cidev_order_details_TransID = $cidev_order_details_TransID_arr[0];
        } else {
                $cidev_order_details_TransID = substr($cidev_order_details_err[1], 0, -1);
        }
}

$payment_method = $order["payment_method"];

$fraud_checks = func_query("SELECT id, question_code, question_template_body, importance_factor, auto FROM $sql_tbl[fraud_check] ORDER BY orderby");

$overall_fraud_score = 0;
$update_overall_fraud_score = false;

if (!empty($fraud_checks) && is_array($fraud_checks)){
	foreach ($fraud_checks as $k => $v){
		$question_template_body = $v["question_template_body"];

		$replace_with = $phone;
		$question_template_body = str_replace("{{customer_phone}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_shipping_address.'" style="color: #1F08F8;">Google shipping address</a>';
		$question_template_body = str_replace("{{google_shipping}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_billing_address.'" style="color: #1F08F8;">Google billing address</a>';
		$question_template_body = str_replace("{{google_billing}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$userinfo["email"].'" style="color: #1F08F8;">Google email</a>';
		$question_template_body = str_replace("{{google_email}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_phone.'" style="color: #1F08F8;">Google phone</a>';
		$question_template_body = str_replace("{{google_phone}}", $replace_with, $question_template_body);

		$replace_with = '@'.$userinfo_site;
		$question_template_body = str_replace("{{emails_domain}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id='.$cidev_order_details_TransID.'" style="color: #1F08F8;">Link to PayPal transaction</a>';
		$question_template_body = str_replace("{{link_to_paypal_transaction}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="http://www.spokeo.com/search?q='.$google_shipping_address.'" style="color: #1F08F8;">Spokeo shipping address</a>';
		$question_template_body = str_replace("{{spokeo_shipping}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="http://www.spokeo.com/search?q='.$google_billing_address.'" style="color: #1F08F8;">Spokeo billing address</a>';
		$question_template_body = str_replace("{{spokeo_billing}}", $replace_with, $question_template_body);

		$replace_with = $links_to_ordered_products;
		$question_template_body = str_replace("{{links_to_ordered_products}}", $replace_with, $question_template_body);

		$replace_with = $billing_address_comma; 
		$question_template_body = str_replace("{{billing_address}}", $replace_with, $question_template_body);

		$replace_with = $shipping_address_comma;
		$question_template_body = str_replace("{{shipping_address}}", $replace_with, $question_template_body);

		$replace_with = $orders_full_names;
		$question_template_body = str_replace("{{orders_full_names}}", $replace_with, $question_template_body);

                $replace_with = $userinfo["s_state"];
                $question_template_body = str_replace("{{shipping_state}}", $replace_with, $question_template_body);

                $replace_with = $userinfo["b_state"];
                $question_template_body = str_replace("{{billing_state}}", $replace_with, $question_template_body);

                $replace_with = $geoip_state;
                $question_template_body = str_replace("{{geoip_state}}", $replace_with, $question_template_body);

                $replace_with = $phone_area_code_state;
                $question_template_body = str_replace("{{phone_area_code_state}}", $replace_with, $question_template_body);

                $replace_with = $userinfo["email"];
                $question_template_body = str_replace("{{customer_email}}", $replace_with, $question_template_body);

                $replace_with = $areacode_state;
                $question_template_body = str_replace("{{areacode_state}}", $replace_with, $question_template_body);

                $replace_with = $geoip_address;
                $question_template_body = str_replace("{{geoip_address}}", $replace_with, $question_template_body);

                $replace_with = $phone_area_code_address;
                $question_template_body = str_replace("{{phone_area_code_address}}", $replace_with, $question_template_body);

                $replace_with = $orders_company_names;
                $question_template_body = str_replace("{{orders_company_names}}", $replace_with, $question_template_body);

                $replace_with = $email_domain_website;
                $question_template_body = str_replace("{{email_domain_website}}", $replace_with, $question_template_body);

                $replace_with = $payment_method;
                $question_template_body = str_replace("{{payment_method}}", $replace_with, $question_template_body);

		$fraud_checks[$k]["question_template_body"] = $question_template_body;

		$fraud_checks[$k]["manual_action"] = func_query_first_cell("SELECT manual_action FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");

		$bare_fraud_score = func_query_first_cell("SELECT bare_fraud_score FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		$fraud_score = func_query_first_cell("SELECT fraud_score FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		$fraud_result = func_query_first_cell("SELECT fraud_result FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");

                $importance_factor = str_replace(' ', '', $v["importance_factor"]);
                $importance_factor_arr = explode(",", $importance_factor);

		$fraud_checks[$k]["importance_factor_arr"] = $importance_factor_arr;

		if ($fraud_score == "" && $v["auto"] == "Y"){

			$func_name = "func_".$v["question_code"];

			if (function_exists($func_name)) {
				$bare_fraud_score_arr = $func_name($order_data);

				$fraud_result = $bare_fraud_score_arr["fraud_result"];

				$bare_fraud_score = $bare_fraud_score_arr["score"];
				$bare_fraud_score = price_format($bare_fraud_score);

//	                        $importance_factor = str_replace(' ', '', $v["importance_factor"]);
//        	                $importance_factor_arr = explode(",", $importance_factor);

                                if ($fraud_result == "negative"){
	                                $selected_importance_factor = $importance_factor_arr[0];
                                } elseif ($fraud_result == "positive"){
         	                       $selected_importance_factor = $importance_factor_arr[2];
                                } else {
                	                $selected_importance_factor = $importance_factor_arr[1];
				}

				$fraud_score = $bare_fraud_score*$selected_importance_factor;
			}

			$update_overall_fraud_score = true;
		}

		$fraud_checks[$k]["bare_fraud_score"] = $bare_fraud_score;
		$fraud_checks[$k]["fraud_score"] = $fraud_score;
		$fraud_checks[$k]["fraud_result"] = $fraud_result;

		$overall_fraud_score += $fraud_score;
	}
}

if ($update_overall_fraud_score) {
	db_query("UPDATE $sql_tbl[orders] SET overall_fraud_score='$overall_fraud_score' WHERE orderid='$orderid'");
} else {
	$overall_fraud_score = func_query_first_cell("SELECT overall_fraud_score FROM $sql_tbl[orders] WHERE orderid='$orderid'");
}

//func_print_r($fraud_checks);

$smarty->assign("orderid", $orderid);
$smarty->assign("order", $order);
$smarty->assign("overall_fraud_score", $overall_fraud_score);
$smarty->assign("fraud_checks", $fraud_checks);
$smarty->assign("main","fraud_page");

$location[2][1] = "order.php?orderid=$orderid";
$location[3][0] = "Fraud page";

$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
