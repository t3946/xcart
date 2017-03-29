<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load("fraud", "order", "order_edit");

require $xcart_dir."/include/history_order.php";
require $xcart_dir."/include/countries.php";

if (!empty($active_modules['Google_Checkout']))
        include $xcart_dir."/modules/Google_Checkout/gcheckout_admin.php";

$order = $order_data["order"];
$userinfo = $order_data["userinfo"];
$products = $order_data["products"];
$giftcerts = $order_data["giftcerts"];

//func_print_r($order);

$all_processors = func_query_hash("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM $sql_tbl[payment_methods] WHERE acc_proc='Y' ORDER BY orderby","paymentid", false);
$smarty->assign("all_processors", $all_processors);


#
## Always first
###
if ($REQUEST_METHOD == "POST" && $mode == "unlock_order") {

        db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='0' WHERE orderid='".addslashes($orderid)."'");

        $unlock_message = "Order unlocked.";
        $smarty->assign("order_unlocked", "Y");
        $smarty->assign("unlock_message", $unlock_message);
} elseif ($REQUEST_METHOD == "POST" && $mode == "unlock_orders") {

        db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='0' WHERE login_last_opened_or_saved='".addslashes($login)."'");

        $unlock_message = "All orders unlocked.";
        $smarty->assign("order_unlocked", "Y");
        $smarty->assign("unlock_message", $unlock_message);

} else {
        $time_for_order_in_mins = 10; //Setting: operators can be on this mage during this time.
        $current_time = time();

        $last_opened_or_saved = func_query_first("SELECT login_last_opened_or_saved, time_last_opened_or_saved FROM $sql_tbl[orders] WHERE orderid='".addslashes($orderid)."'");
        $login_last_opened_or_saved = $last_opened_or_saved["login_last_opened_or_saved"];
        $time_last_opened_or_saved = $last_opened_or_saved["time_last_opened_or_saved"];

        $diff_time_in_mins = ($current_time - $time_last_opened_or_saved)/60;

        $you_have_right_to_change_order = true;

        if ($login_last_opened_or_saved == $login){
                db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='$current_time' WHERE orderid='".addslashes($orderid)."'");
                $time_last_opened_or_saved = $current_time;
        } else {
                if ($diff_time_in_mins > $time_for_order_in_mins){
                        db_query("UPDATE $sql_tbl[orders] SET login_last_opened_or_saved='".addslashes($login)."', time_last_opened_or_saved='$current_time' WHERE orderid='".addslashes($orderid)."'");
                        $time_last_opened_or_saved = $current_time;
                } else {
                        $you_have_right_to_change_order = false;
                }
        }

        $time_unlock = $time_last_opened_or_saved + $time_for_order_in_mins*60 + 60*60;

        if (!$you_have_right_to_change_order){

                if ($REQUEST_METHOD == "POST"){
                        $top_message["content"] = 'Order not saved!';
                        $top_message["type"] = "E";
                        func_header_location("fraud_page.php?orderid=$orderid");
                }

                $operator_on_order = func_query_first("SELECT firstname, s_firstname, b_firstname FROM $sql_tbl[customers] WHERE login='".addslashes($login_last_opened_or_saved)."'");
                $operator_firstname = "";
                if (!empty($operator_on_order["firstname"])){
                        $operator_firstname = $operator_on_order["firstname"];
                } elseif (!empty($operator_on_order["s_firstname"])) {
                        $operator_firstname = $operator_on_order["s_firstname"];
                } else {
                        $operator_firstname = $operator_on_order["b_firstname"];
                }

//              $warning_message = $operator_firstname."(".$login_last_opened_or_saved.") is working in this order. You will not be able to modify this order untill he complete his work with it or unlock it.";

                $warning_message = "This order is locked by $operator_firstname ($login_last_opened_or_saved) until ".date("G:i", $time_unlock).".
If you need to make urgent changes to the order, ask $operator_firstname to unlock it.";

                $smarty->assign("warning_message", $warning_message);
                $smarty->assign("you_cannot_modify_order", "Y");
        } else {
//              $lock_message = "Order locked to you from ".date("G:i", $time_last_opened_or_saved)." for ".$time_for_order_in_mins." minutes";
                $lock_message = 'You locked this order. Nobody can make any changes to it. The order will be unlocked at '.date("G:i", $time_unlock).'. You can also ';

                $smarty->assign("lock_message", $lock_message);

#
##
                $tmp_diff_time = time() - 60*$time_for_order_in_mins;
                $count_locked_orders = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE login_last_opened_or_saved='".addslashes($login)."' AND time_last_opened_or_saved > '$tmp_diff_time'");

                $smarty->assign("count_locked_orders", $count_locked_orders);
##
#
        }
}
###
##
#



if ($REQUEST_METHOD == "POST" && !($mode == "unlock_order" || $mode == "unlock_orders")) {

//func_print_r($_POST);
//die();

	$log = "";
	
	if ($mode == "apply_changes_and_update_fraud_scores"){
		$log = "'Apply changes and update fraud scores' at 'Fraud page'";
	} elseif ($mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status"){
		$log = "'Apply changes, update fraud scores and change fraud check status' at 'Fraud page'";
	}

	if (($mode == "apply_changes_and_update_fraud_scores" || $mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status") && !empty($posted_data) && is_array($posted_data)){


#
##
###
                if (!empty($groups) && is_array($groups)) {

                        $new_groups = array();

			$for_all_paymentid = "";
                        foreach ($groups as $k => $v){
				$m_id = $k;

				if (empty($for_all_paymentid)){
					$for_all_paymentid = $v["paymentid"];
				}

                                $new_groups[$k]["paymentid"] = $for_all_paymentid;

                                $new_groups[$k]["acc"][1]["gst"] = $order['shipping_groups'][$m_id]['accounting'][1]["gst"];
                                $new_groups[$k]["acc"][2]["gst"] = $order['shipping_groups'][$m_id]['accounting'][2]["gst"];
                                $new_groups[$k]["acc"][3]["gst"] = $order['shipping_groups'][$m_id]['accounting'][3]["gst"];
                                $new_groups[$k]["acc"][4]["gst"] = $order['shipping_groups'][$m_id]['accounting'][4]["gst"];

                                $new_groups[$k]["acc"][1]["pst"] = $order['shipping_groups'][$m_id]['accounting'][1]["pst"];
                                $new_groups[$k]["acc"][2]["pst"] = $order['shipping_groups'][$m_id]['accounting'][2]["pst"];
                                $new_groups[$k]["acc"][3]["pst"] = $order['shipping_groups'][$m_id]['accounting'][3]["pst"];
                                $new_groups[$k]["acc"][4]["pst"] = $order['shipping_groups'][$m_id]['accounting'][4]["pst"];

                                $new_groups[$k]["acc"][1]["gross"] = $order['shipping_groups'][$m_id]['accounting'][1]["gross"];
                                $new_groups[$k]["acc"][2]["gross"] = $order['shipping_groups'][$m_id]['accounting'][2]["gross"];
                                $new_groups[$k]["acc"][3]["gross"] = $order['shipping_groups'][$m_id]['accounting'][3]["gross"];
                                $new_groups[$k]["acc"][4]["gross"] = $order['shipping_groups'][$m_id]['accounting'][4]["gross"];
                        }

                        $groups = $new_groups;


                        $applied_per_trans_payments = array();
                        foreach ($groups as $m_id => $v) {

                                $order['shipping_groups'][$m_id]['acc_paymentid'] = $v['paymentid'];
                                $order['shipping_groups'][$m_id]['manufacturerid'] = $m_id;

                                if (is_array($order['shipping_groups'][$m_id]['accounting']) && !empty($order['shipping_groups'][$m_id]['accounting'])) {
                                    $acc_zero_data = array(
                                        ACC_COST_TO_US  => true,
                                        ACC_REF_TO_CUST => true,
                                        ACC_REF_TO_US   => true,
                                    );
                                    $acc_new_data = array(
                                        ACC_COST_TO_US  => false,
                                        ACC_REF_TO_CUST => false,
                                        ACC_REF_TO_US   => false,
                                    );

                                    foreach ($order['shipping_groups'][$m_id]['accounting'] as $col => $sga) {
                                        foreach ($sga as $pdn => $pdv) {
                                            if (
                                                in_array($col, array_keys($acc_zero_data))
                                                && !in_array($pdn, array('filled', 'net'))
                                            ) {
                                                $pdv = intval($pdv);

                                                if (!empty($pdv)) {
                                                    $acc_zero_data[$col] = false;
                                                }

                                                if (isset($v['acc'][$col][$pdn])) {
                                                    $_pdv = intval($v['acc'][$col][$pdn]);
                                                    if (!empty($_pdv)) {
                                                        $acc_new_data[$col] = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

/*
                                if (
                                    in_array($order['shipping_groups'][$m_id]['cb_status'], array('P','R','H')) 
                                    || in_array($order['shipping_groups'][$m_id]['dc_status'], array('C','S')) 
                                ) {
*/
                                        for ($ak=1; $ak<=4; $ak++) {
                                                if ($ak == ACC_REF_TO_CUST) {
                                                    $refund_group = func_query_first('SELECT total_net, total_gst, total_pst, total_gross'
                                                        . ' FROM ' . $sql_tbl['refund_groups']
                                                        . ' WHERE orderid = "' . $order['shipping_groups'][$m_id]['orderid'] . '"'
                                                        . ' AND manufacturerid = "' . $m_id .'"');
                                                }

                                                $order['shipping_groups'][$m_id]['accounting'][$ak] = array();

                                                foreach ($price_details_names as $af) {
                                                    if ($ak == ACC_REF_TO_CUST) {
                                                        if (!empty($refund_group)) {
                                                            $v['acc'][$ak][$af] = $refund_group['total_' . $af];
                                                        } else {
                                                            $v['acc'][$ak][$af] = 0;
                                                        }
                                                    }

                                                    $order['shipping_groups'][$m_id]['accounting'][$ak][$af] = $v['acc'][$ak][$af];
                                                }
                                        }
//                              }



                                if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
                                        $applied_per_trans_payments[] = $v['paymentid'];
                                }

                                func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);

                                $update = array();
                                $update['accounting'] = (serialize($order['shipping_groups'][$m_id]['accounting']));
                                $update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];
                                $update['acc_paymentid'] = $v['paymentid'];

                                func_log_order_groups($update, $orderid, $m_id, 'X', $login);

                                func_array2update("order_groups", $update ,"orderid='$orderid' AND manufacturerid='$m_id'");

                                // Change the order group status

                                if (
                                    $acc_zero_data[ACC_REF_TO_US]
                                    && $acc_new_data[ACC_REF_TO_US]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'Z');
                                } elseif ($acc_zero_data[ACC_COST_TO_US]
                                    && $acc_new_data[ACC_COST_TO_US]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'X');
                                }

                                if (
                                    $acc_zero_data[ACC_REF_TO_CUST]
                                    && $acc_new_data[ACC_REF_TO_CUST]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'R');
                                }

                                $oOrder = Xcart\Order::model(['orderid'=>$orderid]);
                                $oOrder->recalculateAccounting();
                        }



//func_print_r($_POST, $order['shipping_groups'][$m_id]['accounting'], $new_groups);
//die();


                }

###
##
#



		db_query("DELETE FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid'");

		$manual_action_not_selected = "";
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
			$additional_info = "";

			if ($auto == "Y"){

				$func_name = "func_".$question_code;

				if (function_exists($func_name)) {
					$bare_fraud_score_arr = $func_name($order_data);

					$fraud_result = $bare_fraud_score_arr["fraud_result"];
					$bare_fraud_score = $bare_fraud_score_arr["score"];

					$additional_info = $bare_fraud_score_arr["additional_info"];
					if (!empty($additional_info)){
						$additional_info = serialize($additional_info);
					} else {
						$additional_info = "";
					}

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
					$manual_action_not_selected = "Y";
				}

				$fraud_score = $bare_fraud_score;
			}

			$overall_fraud_score += $fraud_score;

			db_query("INSERT INTO $sql_tbl[order_fraud_checks] (orderid, question_code, manual_action, fraud_score, bare_fraud_score, fraud_result, additional_info) VALUES ('$orderid', '$question_code', '$manual_action', '$fraud_score', '$bare_fraud_score', '$fraud_result', '".addslashes($additional_info)."')");
		}

		$current_overall_fraud_score = func_query_first_cell("SELECT overall_fraud_score FROM $sql_tbl[orders] WHERE orderid='$orderid'");

		$overall_fraud_score = price_format($overall_fraud_score);

		if ($current_overall_fraud_score != $overall_fraud_score){

			if ($log != "") $log .= "<br />";
			$log .= "overall_fraud_score: ".$current_overall_fraud_score." -> ".$overall_fraud_score;

			db_query("UPDATE $sql_tbl[orders] SET overall_fraud_score='$overall_fraud_score' WHERE orderid='$orderid'");
		}


#
##
###
		$current_fraud_status = $order["fraud_status"];
        $old_fraud_status = $current_fraud_status;


		if ($mode == "apply_changes_and_update_fraud_scores"){
			if ($overall_fraud_score > $config["Fraud_check"]["Overall_FC_threshold_for_Clear_status"]){
				$new_fraud_status = $config["Fraud_check"]["Threshold_status"];

				if ($manual_action_not_selected == "Y"){
					$new_fraud_status = $config["Fraud_check"]["below_threshold_status"];
				}

			} else {
				$new_fraud_status = $config["Fraud_check"]["below_threshold_status"];
			}

			if ($current_fraud_status != $new_fraud_status){
                               	if ($log != "") $log .= "<br />";
                       	        $current_fraud_status_name = $fraud_statuses[$current_fraud_status];
                                $new_fraud_status_name = $fraud_statuses[$new_fraud_status];
        	                $log .= "fraud_status: ".$current_fraud_status_name." -> ".$new_fraud_status_name;
                                db_query("UPDATE $sql_tbl[orders] SET fraud_status='$new_fraud_status' WHERE orderid='$orderid'");
			}
		}

		if ($mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status"){

			if ($current_fraud_status != $fraud_status){

				if ($log != "") $log .= "<br />";
				$current_fraud_status_name = $fraud_statuses[$current_fraud_status];
				$fraud_status_name = $fraud_statuses[$fraud_status];
				$log .= "fraud_status: ".$current_fraud_status_name." -> ".$fraud_status_name;

				db_query("UPDATE $sql_tbl[orders] SET fraud_status='$fraud_status' WHERE orderid='$orderid'");
			}
		}

        if (
            ($mode == "apply_changes_and_update_fraud_scores" || $mode == "apply_changes_and_update_fraud_scores_and_change_fraud_check_status") &&
            ($overall_fraud_score > $config["Fraud_check"]["Overall_FC_threshold_for_Clear_status"]) &&
            ($old_fraud_status != "C") &&
            (empty($manual_action_not_selected))
        ){
            if ($orderid) {
                $oOrder = \Xcart\Order::model(['orderid'=>$orderid]);
                $oOrder->submitOrderEntry();
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


$geo_litecity_location = func_get_geoip_locations($customer_ip);
if (!empty($geo_litecity_location)) {
    $geoip_state = $geo_litecity_location["region"];

    $geoip_address = $geo_litecity_location["country"].", ".$geo_litecity_location["region"].", ".$geo_litecity_location["city"].", ".$geo_litecity_location["postalCode"];
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
$google_billing_address = str_replace("&", "and", $google_billing_address);

$shipping_address_comma = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? ", $userinfo[s_address_2]": "") .", ". $userinfo["s_city"]. ", ". $userinfo["s_state"]. ", ". $userinfo["s_zipcode"];

$shipping_address = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? " $userinfo[s_address_2]": "") ." ". $userinfo["s_city"]. " ". $userinfo["s_state"]. " ". $userinfo["s_zipcode"];
$google_shipping_address = str_replace(" ", "+", $shipping_address);
$google_shipping_address = str_replace("#", "", $google_shipping_address);
$google_shipping_address = str_replace("&", "and", $google_shipping_address);

$phone = $userinfo["phone"] . (!empty($userinfo["phone_ext"]) ? " ext $userinfo[phone_ext]": "");

$userinfo_area_code = "";

$google_phone = $userinfo["phone"];
$google_phone = preg_replace("/[^0-9]/S","", $userinfo["phone"]);
$google_phone_strlen = strlen($google_phone);

if ($google_phone_strlen == 11 && $google_phone{0} == "1"){
	$google_phone{0} = "";
	$google_phone = trim($google_phone);
	$google_phone_strlen = strlen($google_phone);
}

if ($google_phone_strlen >= 10){

                $tmp_counter = 0;
                $google_phone_new = "";
                for ($i=$google_phone_strlen; $i>=0; $i--){

                        if ($tmp_counter > 7 && $tmp_counter <= 10){
                                $userinfo_area_code = $google_phone{$i}.$userinfo_area_code;
                        }

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


$phone_area_code_address = "";
$areacode_state = "";
$phone_area_code_state = "";
$userinfo_phone = $userinfo["phone"];
/*
$userinfo_phone = str_replace(" ", "", $userinfo_phone);
$userinfo_phone = str_replace("(", "", $userinfo_phone);
$userinfo_phone = str_replace(")", "", $userinfo_phone);
$userinfo_area_code = substr($userinfo_phone, 0, 3);
*/

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


$userinfo_site_arr = explode("@", $userinfo["email"]);
$userinfo_site = $userinfo_site_arr[1];

$email_domain_website = '<a target="_blank" href="http://www.'.$userinfo_site_arr[1].'" style="color: #1F08F8;">www.'.$userinfo_site_arr[1].'</a>';

$orders_full_names = $userinfo["s_firstname"]."<br />".$userinfo["b_firstname"]."<br />".$userinfo["firstname"];

$orders_company_names = $userinfo["additional_fields"][1]["value"]."<br />".$userinfo["additional_fields"][0]["value"];


require $xcart_dir."/include/transaction_logs.php";

$cidev_order_details_TransID = func_query_first_cell("SELECT transaction_id FROM $sql_tbl[transaction_logs] WHERE transaction_id!='' AND orderid='$orderid'");

$oTransaction = \Xcart\OrderTransaction::objects()->filter(['orderid' => $orderid])->order(['date'])->limit(1)->get();

$smarty->assign("cidev_order_details_TransID", $cidev_order_details_TransID);


$payment_method = $order["payment_method"];

$fraud_checks = func_query("SELECT id, question_code, question_template_body, importance_factor, auto FROM $sql_tbl[fraud_check] ORDER BY orderby");

$overall_fraud_score = 0;
$update_overall_fraud_score = false;


$fraud_Google_address_search_exclusions = trim($config["Fraud_check"]["fraud_Google_address_search_exclusions"]);
if (!empty($fraud_Google_address_search_exclusions)){
	$fraud_Google_address_search_exclusions = str_replace(",", "+-", $fraud_Google_address_search_exclusions);
	$fraud_Google_address_search_exclusions = str_replace(" ", "+", $fraud_Google_address_search_exclusions);
	$fraud_Google_address_search_exclusions = "+-".$fraud_Google_address_search_exclusions;
}

$fraud_Google_phone_search_exclusions = trim($config["Fraud_check"]["fraud_Google_phone_search_exclusions"]);
if (!empty($fraud_Google_phone_search_exclusions)){
        $fraud_Google_phone_search_exclusions = str_replace(",", "+-", $fraud_Google_phone_search_exclusions);
        $fraud_Google_phone_search_exclusions = str_replace(" ", "+", $fraud_Google_phone_search_exclusions);
        $fraud_Google_phone_search_exclusions = "+-".$fraud_Google_phone_search_exclusions;
}

$fraud_Google_email_search_exclusions = trim($config["Fraud_check"]["fraud_Google_email_search_exclusions"]);
if (!empty($fraud_Google_email_search_exclusions)){
        $fraud_Google_email_search_exclusions = str_replace(",", "+-", $fraud_Google_email_search_exclusions);
        $fraud_Google_email_search_exclusions = str_replace(" ", "+", $fraud_Google_email_search_exclusions);
        $fraud_Google_email_search_exclusions = "+-".$fraud_Google_email_search_exclusions;
}

/*
$fraud_Google_search_negative_words = trim($config["Fraud_check"]["fraud_Google_search_negative_words"]);
if (!empty($fraud_Google_search_negative_words)){
	$fraud_Google_search_negative_words = str_replace(" ", "+", $fraud_Google_search_negative_words);
	$fraud_Google_search_negative_words = "+".$fraud_Google_search_negative_words;
	$fraud_Google_address_search_exclusions .= $fraud_Google_search_negative_words;
}
*/

if (!empty($fraud_checks) && is_array($fraud_checks)){
	foreach ($fraud_checks as $k => $v){
		$question_template_body = $v["question_template_body"];

		$replace_with = $phone;
		$question_template_body = str_replace("{{customer_phone}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_shipping_address.$fraud_Google_address_search_exclusions.'" style="color: #1F08F8;">Google shipping address</a>';
		$question_template_body = str_replace("{{google_shipping}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_billing_address.$fraud_Google_address_search_exclusions.'" style="color: #1F08F8;">Google billing address</a>';
		$question_template_body = str_replace("{{google_billing}}", $replace_with, $question_template_body);

		$replace_with = "<a target='_blank' href='https://www.google.com/#q=\"".$userinfo["email"].'"'.$fraud_Google_email_search_exclusions."' style='color: #1F08F8;'>Google email</a>";
		$question_template_body = str_replace("{{google_email}}", $replace_with, $question_template_body);

		$replace_with = '<a target="_blank" href="https://www.google.com/#q='.$google_phone.$fraud_Google_phone_search_exclusions.'" style="color: #1F08F8;">Google phone</a>';
		$question_template_body = str_replace("{{google_phone}}", $replace_with, $question_template_body);

		$replace_with = '@'.$userinfo_site;
		$question_template_body = str_replace("{{emails_domain}}", $replace_with, $question_template_body);

        $sTransactionReplaceText = '';
        $sPaymentMethodReplaceText = '';
        if ($oTransaction) {
            $oPaymentMethod = \Xcart\PaymentMethod::objects()->filter(['paymentid' => $oTransaction->paymentid])->get();
            if ($oPaymentMethod) {
                $sTransactionLink = str_replace('{{trans-id}}', $oTransaction->transaction_id, $oPaymentMethod->transaction_id_link);
                $sTransactionReplaceText = "<a target='_blank' href='{$sTransactionLink}' style='color:#1F08F8;'>Link to transaction</a>";
                $sPaymentMethodReplaceText = "{$oPaymentMethod->payment_method} ({$oPaymentMethod->transaction_link_anchor})";
            }
        }

        $question_template_body = str_replace("{{link_to_paypal_transaction}}", $sTransactionReplaceText, $question_template_body);
        $question_template_body = str_replace("{{payment_method}}", $sPaymentMethodReplaceText, $question_template_body);

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

        $fraud_checks[$k]["question_template_body"] = $question_template_body;

		$fraud_checks[$k]["manual_action"] = func_query_first_cell("SELECT manual_action FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");

		$bare_fraud_score = func_query_first_cell("SELECT bare_fraud_score FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		$fraud_score = func_query_first_cell("SELECT fraud_score FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		$fraud_result = func_query_first_cell("SELECT fraud_result FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		$additional_info = func_query_first_cell("SELECT additional_info FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND question_code='$v[question_code]'");
		if (!empty($additional_info)){
			$additional_info = unserialize($additional_info);
		}

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

				$additional_info = $bare_fraud_score_arr["additional_info"];

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
				$fraud_score = price_format($fraud_score);
			}

			$update_overall_fraud_score = true;

		} elseif ($fraud_score == "" && $v["auto"] != "Y"){
			if (strpos($config["Fraud_check"]["fraud_domains_free_email_provider"], $userinfo_site) !== false && ($v["question_code"] == "MANUAL_CHECK_EMAIL_DOMAIN_WEBSITE" || $v["question_code"] == "MANUAL_CHECK_EMAIL_DOMAIN_WEBSITE_FOR_SHIPPING_ADDRESS")){
				$bare_fraud_score = $importance_factor_arr[0];
				$fraud_score = $bare_fraud_score; 
				$fraud_result = "negative";
				$fraud_checks[$k]["manual_action"] = "N";
				$update_overall_fraud_score = true;
			}
		}

		$fraud_checks[$k]["bare_fraud_score"] = $bare_fraud_score;
		$fraud_checks[$k]["fraud_score"] = $fraud_score;
		$fraud_checks[$k]["fraud_result"] = $fraud_result;
		$fraud_checks[$k]["additional_info"] = $additional_info;

		$overall_fraud_score += $fraud_score;
	}
}

if ($update_overall_fraud_score) {
	db_query("UPDATE $sql_tbl[orders] SET overall_fraud_score='$overall_fraud_score' WHERE orderid='$orderid'");
} else {
	$overall_fraud_score = func_query_first_cell("SELECT overall_fraud_score FROM $sql_tbl[orders] WHERE orderid='$orderid'");
}

$smarty->assign("orderid", $orderid);
$smarty->assign("order", $order);
$smarty->assign("overall_fraud_score", $overall_fraud_score);
$smarty->assign("fraud_checks", $fraud_checks);
$smarty->assign("main","fraud_page");

$fraud_page_name = "Fraud check for order # ".$order["order_prefix"].$order["orderid"];
$smarty->assign("fraud_page_name", $fraud_page_name);

$location[2][1] = "order.php?orderid=$orderid";
$location[3][0] = $fraud_page_name;

$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
