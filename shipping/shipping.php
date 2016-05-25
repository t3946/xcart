<?php /* MODIFIED: random:20460 [2010 Mar 18 13:43][Custom development (Free shipping modifications)] */ ?>
<?php /* MODIFIED: random:17710_17631 [2009 Mar 26 09:25][Custom development ("Shipping quote" functionality and other modifications) + Other] */ ?>
<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: shipping.php,v 1.41.2.17 2007/01/18 09:28:53 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('cart','amazon_shipping');

#
# This function creates the shipping methods/rates list
#
# START: random:17710_17631 [2009 Mar 26 09:25] 
function func_get_shipping_methods_list($cart, $products, $userinfo, $return_all_available=false, $for_manufacturerid=0) {
# END: random:17710_17631 [2009 Mar 26 09:25] 
	global $sql_tbl, $config, $active_modules, $single_mode, $smarty;
	global $xcart_dir;
	global $intershipper_recalc, $intershipper_rates;
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	global $intershipper_rates_all;
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	global $intershipper_error;
	global $shipping_calc_service;
	global $current_carrier;
	global $login;
	global $arb_account_used;
	global $empty_other_carriers;
	global $pointid_ab_testing_arr;

	if (empty($products))
		return;

# START: random:17710_17631 [2009 Mar 26 09:25] 
	if (empty($login) && $config["General"]["apply_default_country"] != "Y" && $config["Shipping"]["enable_all_shippings"] == "Y" && !defined('IS_SHIPPING_QUOTE')) {
# END: random:17710_17631 [2009 Mar 26 09:25] 
		$enable_all_shippings = true;
		$smarty->assign("force_delivery_dropdown_box", "Y");
	}

	#
	# If $enable_shipping then calculate shipping rates
	#
# START: random:17710_17631 [2009 Mar 26 09:25] 
	$enable_shipping = ((!empty($userinfo) && !empty($login)) || $config["General"]["apply_default_country"] == "Y" || defined('IS_SHIPPING_QUOTE'));
# END: random:17710_17631 [2009 Mar 26 09:25] 

	#
	# Get the total products weight
	#
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	$total_weight_shipping = func_weight_shipping_products($products, true);
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

	#
	# Get the total products weight that is valid for rates calculation
	#
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	$total_weight_shipping_valid = func_weight_shipping_products($products);
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

# START: random:20460 [2010 Mar 18 13:43] 
	$total_weight_shipping_with_free = func_weight_shipping_products($products, true, $userinfo);

# END: random:20460 [2010 Mar 18 13:43] 
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	$cart_subtotal = "0";
/*	if (trim($userinfo['s_state'])=='' && trim($userinfo['s_zipcode']) != '' && trim($userinfo['s_country']) == 'US')
	    {
		$tzip = $userinfo['s_zipcode'];
		$state_by_zip =  func_query_first_cell("SELECT Z.state FROM $sql_tbl[zip_code_info] Z WHERE Z.zip = '$tzip'");		
		$state_name = func_query_first_cell("SELECT S.state FROM $sql_tbl[states] S WHERE S.country_code = 'US' and S.code = '$state_by_zip'");
		print($state_name);
		print($state_by_zip);
		$userinfo['s_state'] = $state_by_zip;
		$userinfo['s_statename'] = $state_name;
	    }*/
	    
	    
	foreach ($products as $kp=>$vp) {
		$cart_subtotal += $products[$kp]["subtotal"];
	}
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

	if ($config["Shipping"]["realtime_shipping"] == "Y" && $enable_shipping && $intershipper_recalc != "N") {
		x_load('http');
		#
		# Get the real time shipping rates
		#
		if ($config["Shipping"]["use_intershipper"] == "Y") {
			include_once $xcart_dir."/shipping/intershipper.php";
		}
		else {
			include_once $xcart_dir."/shipping/myshipper.php";
		}


//func_print_r($intershipper_rates);

		func_https_ctl('IGNORE');

# START: random:17710_17631 [2009 Mar 26 09:25] 
		global $ship_manufacturerid;
		$ship_manufacturerid = $for_manufacturerid;
		global $ship_manufacturerid_products;
		$ship_manufacturerid_products = $products;
# END: random:17710_17631 [2009 Mar 26 09:25] 
		$intershipper_rates = func_shipper($total_weight_shipping, $userinfo ,'N', $cart);
# START: random:20460 [2010 Mar 18 13:43]

#
##
###
		$USE_MY_UPS_FEDEX_ACCOUNT_functionality = func_query_first_cell("SELECT USE_MY_UPS_FEDEX_ACCOUNT_functionality FROM $sql_tbl[manufacturers] WHERE manufacturerid='$ship_manufacturerid'");

		$USE_MY_TRUCKING_ACCOUNT_functionality = func_query_first_cell("SELECT USE_MY_TRUCKING_ACCOUNT_functionality FROM $sql_tbl[manufacturers] WHERE manufacturerid='$ship_manufacturerid'");
###
##
#

 
		if (!empty($intershipper_rates) && $total_weight_shipping != $total_weight_shipping_with_free) {
			$min_rate = array("rate" => -1, "id" => 0, "key" => -1);
			foreach ($intershipper_rates as $ik => $ir) {
				if ($ir["rate"] < $min_rate["rate"] || $min_rate["rate"] < 0) {
					$min_rate["rate"] = $ir["rate"];
					$min_rate["id"] = $ir["methodid"];
					$min_rate["key"] = $ik;
				}
			}
			if ($min_rate["key"] != -1) {
				if ($total_weight_shipping_with_free > 0) {
					$_intershipper_rates = $intershipper_rates;
					$intershipper_rates_with_free = func_shipper($total_weight_shipping_with_free, $userinfo ,'N', $cart);
					# func_shipper overwrites intershipper_rates, restore them:
					$intershipper_rates = $_intershipper_rates;
					foreach ($intershipper_rates_with_free as $ir) {
						if ($ir["methodid"] == $min_rate["id"]) {
							$intershipper_rates[$min_rate["key"]]["rate"] = $ir["rate"];
							break;
						}
					}
				} else {
					$intershipper_rates[$min_rate["key"]]["rate"] = '0.00';
				}
			}
			unset($min_rate);
		}

# END: random:20460 [2010 Mar 18 13:43] 
			$intershipper_rates_all[$for_manufacturerid] = $intershipper_rates;

		if ($empty_other_carriers == "Y") {
			$shipping = func_query("SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition $weight_condition ORDER BY orderby");
			if (!empty($shipping)) {
				$tmp_shipping = array();
				foreach ($shipping as $k=>$v) {
					if (($config["Shipping"]["realtime_shipping"]=="Y" && $v["code"]=="") || $config["Shipping"]["realtime_shipping"]!="Y") {
						$v['allowed'] = $is_method_allowed = func_is_shipping_method_allowable($v["shippingid"], $userinfo, $products, $total_weight_shipping_valid, $cart_subtotal, $for_manufacturerid);

						if (!$return_all_available && !$is_method_allowed)
							continue;
					}

					$tmp_shipping[] = $v;
				}


//func_print_r($tmp_shipping);

				if (is_array($tmp_shipping)) {
					$tmp_cart = $cart;
					foreach ($tmp_shipping as $k=>$v) {
					#
					# Fetch shipping rate if it wasn't defined
					#
						if (!$v['allowed'])
							continue;

						$tmp_cart["shippingid"] = $v["shippingid"];
						$tmp_cart["shippingids"] = NULL;
						$calc_result = func_calculate($tmp_cart, $products, $userinfo["login"], $userinfo["usertype"]);
						if (!empty($calc_result["display_shipping_cost"])) {
							$empty_other_carriers = "N";
						}
					}

					unset($tmp_cart);
				}

			}
			
		} 
		$smarty->assign("is_other_carriers_empty", $empty_other_carriers);
		func_https_ctl('STORE');

//func_print_r($intershipper_error, $empty_other_carriers);

		if (!empty($intershipper_error)){
			$smarty->assign("shipping_calc_service",$shipping_calc_service?$shipping_calc_service:"Intershipper");
			$smarty->assign("shipping_calc_error",$intershipper_error);

			$msg  = "Service: ".($shipping_calc_service?$shipping_calc_service:"Intershipper")."\n";
			$msg .= "Error: ".$intershipper_error."\n";
			$msg .= "Login: ".$login."\n";
			$msg .= "Shipping address: ".$userinfo['s_address']." ".$userinfo['s_address_2']."\n";
			$msg .= "Shipping city: ".$userinfo['s_city']."\n";
			$msg .= "Shipping state: ".$userinfo['s_statename']."\n";
			$msg .= "Shipping country: ".$userinfo['s_countryname']."\n";
			$msg .= "Shipping zipcode: ".$userinfo['s_zipcode'];
			x_log_add('SHIPPING', $msg);
		}

		$intershipper_recalc = "N";
	}

	#
	# The preparing to search the allowable shipping methods
	#
	$weight_condition = " AND weight_min<='$total_weight_shipping' AND (weight_limit='0' OR weight_limit>='$total_weight_shipping')";

	if (!empty($active_modules["UPS_OnLine_Tools"]) && $config["Shipping"]["use_intershipper"] != "Y") {

		$condition = "";

		if ($enable_all_shippings) {
			global $ups_services;
			include $xcart_dir."/modules/UPS_OnLine_Tools/ups_shipping_methods.php";
		}

		$ups_condition = $condition;

		if ($config["Shipping"]["realtime_shipping"] == "Y" && $current_carrier == "UPS") {
			$ups_condition .= " AND $sql_tbl[shipping].code='UPS' AND $sql_tbl[shipping].service_code!=''";
		}

#
## !!!!!!!!!!!!!!!!!!!! WHAT ??????????????????????????
###
//		$weight_condition .= $ups_condition;
###
##
#
	}

	if (!empty($active_modules["UPS_OnLine_Tools"]) && $config["Shipping"]["realtime_shipping"] == "Y" && $config["Shipping"]["use_intershipper"] != "Y") {
		$_carriers["UPS"] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE code='UPS' AND service_code!='' AND weight_min<='$total_weight_shipping' AND (weight_limit='0' OR weight_limit>='$total_weight_shipping') AND active='Y'");
		$_carriers["other"] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE code<>'UPS' AND weight_min<='$total_weight_shipping' AND (weight_limit='0' OR weight_limit>='$total_weight_shipping') AND active='Y'");
		if ($_carriers["UPS"] == 0 || $_carriers["other"] == 0) {
			$current_carrier = ($_carriers["UPS"] == 0 ? "" : "UPS");
			x_session_save("current_carrier");
		}
		else {
			$smarty->assign("show_carriers_selector", "Y");
		}
	}

#
##
###
        if (!empty($for_manufacturerid) && $for_manufacturerid > 0) {
		$config["Company"]["location_country"] = func_query_first_cell("SELECT m_country FROM $sql_tbl[manufacturers] WHERE manufacturerid='$for_manufacturerid'");
        }
###
##
#

	if (($enable_shipping || $config["Shipping"]["enable_all_shippings"] != "Y") && !$return_all_available) {
		$destination_condition = " AND destination=".(!empty($userinfo) && $userinfo["s_country"] == $config["Company"]["location_country"] ? "'L'" : "'I'");


//func_print_r($destination_condition, $userinfo["s_country"], $config["Company"]["location_country"]);

	}

	if (!$enable_shipping || $config["Shipping"]["realtime_shipping"] != "Y") {

		#
		# Get ALL shipping methods according to the conditions (W/O real time)
		#
		$shipping = func_query("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $destination_condition $weight_condition $ups_condition ORDER BY orderby");

//func_print_r($shipping);
#
##
###
		$_USE_MY_UPS_FEDEX_ACCOUNT_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $destination_condition AND shipping='_USE_MY_UPS_FEDEX_ACCOUNT_'");
		if (!empty($_USE_MY_UPS_FEDEX_ACCOUNT_) && is_array($_USE_MY_UPS_FEDEX_ACCOUNT_) && $USE_MY_UPS_FEDEX_ACCOUNT_functionality == "Y"){
                        $_USE_MY_UPS_FEDEX_ACCOUNT_["allowed"] = 1;
                        $_USE_MY_UPS_FEDEX_ACCOUNT_["new_hardcoded_shipping_method"] = "Y";
			$shipping[] = $_USE_MY_UPS_FEDEX_ACCOUNT_;
		}

                $_USE_MY_TRUCKING_ACCOUNT_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $destination_condition AND shipping='_USE_MY_TRUCKING_ACCOUNT_'");
                if (!empty($_USE_MY_TRUCKING_ACCOUNT_) && is_array($_USE_MY_TRUCKING_ACCOUNT_) && $USE_MY_TRUCKING_ACCOUNT_functionality == "Y"){
                        $_USE_MY_TRUCKING_ACCOUNT_["allowed"] = 1;
                        $_USE_MY_TRUCKING_ACCOUNT_["new_hardcoded_shipping_method"] = "Y";
                        $shipping[] = $_USE_MY_TRUCKING_ACCOUNT_;
                }

		$_SHIP_BY_FASTEST_METHOD_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $destination_condition AND shipping='_SHIP_BY_FASTEST_METHOD_'");
		if (!empty($_SHIP_BY_FASTEST_METHOD_) && is_array($_SHIP_BY_FASTEST_METHOD_)){
                        $_SHIP_BY_FASTEST_METHOD_["allowed"] = 1;
                        $_SHIP_BY_FASTEST_METHOD_["new_hardcoded_shipping_method"] = "Y";
			$shipping[] = $_SHIP_BY_FASTEST_METHOD_;
		}
###
##
#

	}
	else {
		#
		# Gathering the defined shipping methods
		#
		$shipping = func_query ($qqq="SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition $weight_condition ORDER BY orderby");

		if (empty($shipping)){
			$shipping = func_query ($qqq="SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $destination_condition $weight_condition $ups_condition ORDER BY orderby");
		}

//func_print_r($shipping, $qqq, $destination_condition, $weight_condition);

		if ($intershipper_rates) {
			#
			# Gathering the shipping methods from $intershipper_rates
			#
			foreach ($intershipper_rates as $intershipper_rate) {
# START: random:17710_17631 [2009 Mar 26 09:25] 
				/* getting ship time from $intershipper_rates disabled
# END: random:17710_17631 [2009 Mar 26 09:25] 
				$ship_time = "";
				if (!empty($intershipper_rate["shipping_time"])) {
					if (is_numeric($intershipper_rate["shipping_time"]))
						$ship_time = $intershipper_rate["shipping_time"]." ".func_get_langvar_by_name("lbl_day_s");
					else
						$ship_time = $intershipper_rate["shipping_time"];
				}

				if ($ship_time != "")
					$ship_time_column = "'".$ship_time."' AS shipping_time";
				else
					$ship_time_column = "shipping_time";
# START: random:17710_17631 [2009 Mar 26 09:25] 
				*/
# END: random:17710_17631 [2009 Mar 26 09:25] 

# START: random:17710_17631 [2009 Mar 26 09:25] 
				$result = func_query_first("SELECT *, '$intershipper_rate[rate]' AS rate, '$intershipper_rate[warning]' AS warning FROM $sql_tbl[shipping] WHERE subcode='$intershipper_rate[methodid]' AND active='Y' $weight_condition ORDER BY orderby");
# END: random:17710_17631 [2009 Mar 26 09:25] 

				$result['allowed'] = true;

				if ($result) {
# START: random:17710_17631 [2009 Mar 26 09:25] 
					if (empty($for_manufacturerid) || func_query_first_cell("SELECT rateid FROM $sql_tbl[shipping_rates] WHERE shippingid='$result[shippingid]' AND manufacturerid='$for_manufacturerid' LIMIT 1 ")){
# END: random:17710_17631 [2009 Mar 26 09:25] 
						$shipping[] = $result;
//func_print_r($result);
					}
				}
			}
		}

#
##
###
                if (empty($shipping) && !empty($tmp_shipping)){
                        $shipping = $tmp_shipping;
                }
###
##
#


		if (is_array($shipping))
			usort($shipping, "usort_array_cmp_orderby");

#
##
###
                $_USE_MY_UPS_FEDEX_ACCOUNT_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition AND shipping='_USE_MY_UPS_FEDEX_ACCOUNT_'");
                if (!empty($_USE_MY_UPS_FEDEX_ACCOUNT_) && is_array($_USE_MY_UPS_FEDEX_ACCOUNT_) && $USE_MY_UPS_FEDEX_ACCOUNT_functionality == "Y"){
			$_USE_MY_UPS_FEDEX_ACCOUNT_["allowed"] = 1;
			$_USE_MY_UPS_FEDEX_ACCOUNT_["new_hardcoded_shipping_method"] = "Y";
                        $shipping[] = $_USE_MY_UPS_FEDEX_ACCOUNT_;
                }

                $_USE_MY_TRUCKING_ACCOUNT_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition AND shipping='_USE_MY_TRUCKING_ACCOUNT_'");
                if (!empty($_USE_MY_TRUCKING_ACCOUNT_) && is_array($_USE_MY_TRUCKING_ACCOUNT_) && $USE_MY_TRUCKING_ACCOUNT_functionality == "Y"){
                        $_USE_MY_TRUCKING_ACCOUNT_["allowed"] = 1;
                        $_USE_MY_TRUCKING_ACCOUNT_["new_hardcoded_shipping_method"] = "Y";
                        $shipping[] = $_USE_MY_TRUCKING_ACCOUNT_;
                }
                
                $_SHIP_BY_FASTEST_METHOD_ = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition AND shipping='_SHIP_BY_FASTEST_METHOD_'");
                if (!empty($_SHIP_BY_FASTEST_METHOD_) && is_array($_SHIP_BY_FASTEST_METHOD_)){
			$_SHIP_BY_FASTEST_METHOD_["allowed"] = 1;
			$_SHIP_BY_FASTEST_METHOD_["new_hardcoded_shipping_method"] = "Y";
                        $shipping[] = $_SHIP_BY_FASTEST_METHOD_;
                }
###             
##                      
#                       

	}


//func_print_r($shipping);

	if (!empty($shipping)) {
		#
		# Final preparing the shipping methods list
		#
		$tmp_shipping = array();
		foreach ($shipping as $k=>$v) {
			if (($config["Shipping"]["realtime_shipping"]=="Y" && $v["code"]=="") || $config["Shipping"]["realtime_shipping"]!="Y") {
				#
				# Check accessibility only for defined shipping methods
				#

				if ($v["new_hardcoded_shipping_method"] == "Y"){
					$is_method_allowed = 1;
				} else {
					$v['allowed'] = $is_method_allowed = func_is_shipping_method_allowable($v["shippingid"], $userinfo, $products, $total_weight_shipping_valid, $cart_subtotal, $for_manufacturerid);
				}
	
				if (!$return_all_available && !$is_method_allowed)
					continue;
			}
			elseif ($config["Shipping"]["realtime_shipping"] == "Y" && $v["code"] != "" && !$enable_shipping)
				continue;

			$tmp_shipping[] = $v;
		}

		$shipping = $tmp_shipping;
		unset($tmp_shipping);

		if (is_array($shipping)) {
			$tmp_cart = $cart;
			foreach ($shipping as $k=>$v) {
				#
				# Fetch shipping rate if it wasn't defined
				#
				if (!$v['allowed'])
					continue;

				$tmp_cart["shippingid"] = $v["shippingid"];
				$tmp_cart["shippingids"] = NULL;
				$calc_result = func_calculate($tmp_cart, $products, $userinfo["login"], $userinfo["usertype"]);
				$shipping[$k]["rate"] = $calc_result["display_shipping_cost"];
				$shipping[$k]["tax_cost"] = price_format($calc_result["tax_cost"]);
			}
			# random, 2009-01-08, sort by rate
			usort($shipping, "usort_array_cmp_rate");
			unset($tmp_cart);
		}

	}

//func_print_r($shipping);

	if ($arb_account_used && is_array($shipping)) {
		foreach ($shipping as $v) {
			if ($v["code"] == "ARB" && $v["shippingid"] == $cart["shippingid"]) {
				$smarty->assign("arb_account_used", true);
				break;
			}
		}
	}


#
##
###
	$Amazon_code_found = false;
	$flat_rate_shipping_found = false;
	if (!empty($shipping) && is_array($shipping)){
		foreach ($shipping as $k => $v){
			if ($v["code"] == "Amazon"){
				$Amazon_code_found = true;
			}

			if (empty($v["code"]) && empty($v["new_hardcoded_shipping_method"])){
				$flat_rate_shipping_found = true;
			}
		}

		$shipping_deleted_flag = false;
		foreach ($shipping as $k => $v){
			if ($Amazon_code_found || $flat_rate_shipping_found){

				if (
				    ($Amazon_code_found && $v["code"] != "Amazon") ||
				    (!$Amazon_code_found && $flat_rate_shipping_found && !empty($v["code"]))
				){
					unset($shipping[$k]);
					$shipping_deleted_flag = true;
				}
			}
		}

		if ($shipping_deleted_flag){
			$shipping = array_values($shipping);
		}
	}
###
##
#
//func_print_r($shipping);


	if ($shipping){

		if (is_array($shipping)){

			$count_products = count($products);


			foreach ($shipping as $k => $v){

				if ($v["new_hardcoded_shipping_method"] == "Y"){
                                        $shipping[$k]["new_orderby"] = 1000 + $v["orderby"];

					if ($v["shipping"] == "_SHIP_BY_FASTEST_METHOD_"){
						$shipping[$k]["rate"] = "0.00";
					}

                                        if ($v["shipping"] == "_USE_MY_UPS_FEDEX_ACCOUNT_"){
                                                $shipping[$k]["rate"] = "5.00";
                                        }

                                        if ($v["shipping"] == "_USE_MY_TRUCKING_ACCOUNT_"){
                                                $shipping[$k]["rate"] = "5.00";
                                        }
				}

#
##
###
				if ($v["shippingid"] == "1" && $userinfo["s_country"] == "US" && $v["rate"] > 0){
					# $UPS_Ground_found = true;

					if (empty($userinfo["s_state"]) && !empty($userinfo["s_zipcode"])){
						$userinfo["s_state"] = func_query_first_cell("SELECT state FROM $sql_tbl[zip_code_info] WHERE zip='$userinfo[s_zipcode]'");
					}

					$approximation_shipping_rates = func_query_first($qqq="SELECT * FROM $sql_tbl[approximation_shipping_rates] WHERE manufacturerid='".$products[0]["manufacturerid"]."' AND state='$userinfo[s_state]'");

					if (!empty($approximation_shipping_rates)){


###
			                        $total_weight = 0;
						$count_products_with_free_ship_zone = 0;

						if (!empty($products))
							foreach ($products as $k_p => $v_p) {

								if ($v_p["free_ship_zone"] == "14") {
									$count_products_with_free_ship_zone++;
									continue;
								}

								/*if (empty($v_p["weight"]) || $v_p["weight"] == "0.00") {
									$v_p["weight"] = "0.1";
								}

								$real_weight = $v_p["weight"] * $v_p["amount"];

								if (!empty($v_p["shipping_weight"]))
									$real_weight = $v_p["shipping_weight"] * $v_p["amount"];

								$Volume = $v_p["dim_x"] * $v_p["dim_y"] * $v_p["dim_z"] * $v_p["amount"];

								if ($Volume > $v["vol_threshold"] && !empty($v["dim_factor"])) {
									$bw = $Volume / $v["dim_factor"];
									$weight = max($real_weight, $bw);
								} else {
									$weight = $real_weight;
								}*/
								include_once $xcart_dir."/include/class/classShipping.php";
								$classShipping = new classShipping();
								$weight = $classShipping->getShippingWeight($v_p['productid'], $v['shippingid'], $v_p["amount"], $v_p, $v);
								unset ($classShipping);

								$total_weight += $weight;
							}
###

						$weight = ceil($total_weight);

						$Shipping_charge = "0.00";

						if ($count_products != $count_products_with_free_ship_zone){

							$shipping[$k]["rate_before_approximation"] = $shipping[$k]["rate"];

				                        if ($weight > 0 && $weight <= 1){
				                                $Shipping_charge = $approximation_shipping_rates["bw_1"];
				                        }
				                        elseif ($weight > 1 && $weight <= 75){
			        	                        $Shipping_charge = $approximation_shipping_rates["bw_1"] + ($approximation_shipping_rates["bw_75"] - $approximation_shipping_rates["bw_1"])/(75 - 1) * ($weight - 1);
			                	        }
			                        	elseif ($weight > 75){
                        			        	$Shipping_charge = $approximation_shipping_rates["bw_75"] + ($approximation_shipping_rates["bw_150"] - $approximation_shipping_rates["bw_75"])/(150 - 75) * ($weight - 75);
				                        }

                        				$Shipping_charge = price_format($Shipping_charge);
						}


						global $intershipper_rates; 
						$shipping_id = "1";

				                foreach($intershipper_rates as $ki => $vi){
                               				if ($vi["methodid"] == $shipping_id){
					                        $intershipper_rates[$ki]["rate"] = $Shipping_charge;
							}
               					}


						if ($count_products != $count_products_with_free_ship_zone){
                        	                        $approximation_intershipper_rates[0]["methodid"] = $shipping_id;
                	                                $approximation_intershipper_rates[0]["rate"] = $Shipping_charge;


				                        $customer_info["b_state"] = $customer_info["s_state"] = $userinfo["s_state"];
	                        			$customer_info["b_country"] = $customer_info["s_country"] = "US";
			                        	$customer_info["b_zipcode"] = $customer_info["s_zipcode"] = func_query_first_cell("SELECT base_state_zipcode FROM $sql_tbl[states] WHERE code='$userinfo[s_state]' AND country_code='US'");


                        				$Add_Shipping_rate = func_calculate_shippings($products, $shipping_id, $customer_info, "master", $approximation_intershipper_rates);

							unset($approximation_intershipper_rates);


				                        if (!empty($Add_Shipping_rate["shipping_cost"]) && $Add_Shipping_rate["shipping_cost"] > 0){
                	        			        $Shipping_charge = $Add_Shipping_rate["shipping_cost"];
				                                $Shipping_charge = price_format($Shipping_charge);
				                        }
						}

						$shipping[$k]["rate"] = $Shipping_charge;
					}
					else {
						$shipping[$k]["rate_before_approximation"] = $shipping[$k]["rate"];
					}
				} else {
					$shipping[$k]["rate_before_approximation"] = $shipping[$k]["rate"];
				}
###
##
#

				$shipping[$k]["rate"] = price_format($shipping[$k]["rate"]);

			}

#
## Sort
###
			$shipping = my_array_sort($shipping, "rate");

                        $new_orderby = 0;
                        foreach ($shipping as $k => $v){
                                if ($v["new_hardcoded_shipping_method"] != "Y"){
                                        $shipping[$k]["new_orderby"] = $new_orderby;
                                        $new_orderby++;
                                }
			}

			$shipping = my_array_sort($shipping, "new_orderby");
###
##
#

//func_print_r($intershipper_rates, $shipping);

#
##
###
			foreach ($shipping as $k => $v){
				if ($v["shippingid"] == "1" && !empty($intershipper_rates) && is_array($intershipper_rates)){
					foreach ($intershipper_rates as $v_i_r){
						if ($v_i_r["methodid"] == "1"){
							$shipping[$k]["original_rate"] = $v_i_r["rate"];

							global $XCART_SESSION_NAME, $$XCART_SESSION_NAME, $mode, $variant_id_for_point2;

							if (($mode == "checkout" && empty($_GET["paymentid"]) && $_POST["cart_operation"] != "cart_operation") || $mode == "shipping"){

								$s_address_log = $userinfo["s_address"] . " " . $userinfo["s_address_2"] . "<br />";
								$s_address_log .= $userinfo["s_city"] . "<br />";
								$s_address_log .= $userinfo["s_state"] . "<br />";
								$s_address_log .= $userinfo["s_country"] . "<br />";
								$s_address_log .= $userinfo["s_zipcode"] . "<br />";


								$manufacturerid_cost = 0;
								foreach ($cart["products"] as $kk => $vv){
									if ($vv["manufacturerid"] == $ship_manufacturerid){
										$manufacturerid_cost += $vv["amount"] * $vv["price"];
									}
								}

								$max_quote_id = 1 + func_query_first_cell("SELECT MAX(quote_id) FROM $sql_tbl[shipping_quote_log]");
								db_query("UPDATE $sql_tbl[shipping_quote_log] SET customer_id='$login' WHERE session_id='".$$XCART_SESSION_NAME."' AND customer_id=''");

/*
								# set the same for all m_ids in cart
								$common_quote_id = func_query_first_cell("SELECT quote_id FROM $sql_tbl[shipping_quote_log] WHERE customer_id='$login' AND session_id='".$$XCART_SESSION_NAME."'");
								if (!empty($common_quote_id)){
									db_query("UPDATE $sql_tbl[shipping_quote_log] SET quote_id='$common_quote_id' WHERE customer_id='$login' AND session_id='".$$XCART_SESSION_NAME."'");
									$max_quote_id = $common_quote_id;
								}
								#


								$shipping_quote_logs = func_query("SELECT * FROM $sql_tbl[shipping_quote_log] WHERE session_id='".$$XCART_SESSION_NAME."' AND customer_id='$login' AND manufacturerid='$ship_manufacturerid'");

                                                                if (empty($shipping_quote_logs)){
*/

									$ab_options = "";

									if (!empty($pointid_ab_testing_arr) && is_array($pointid_ab_testing_arr)){
										foreach ($pointid_ab_testing_arr as $point_id => $variant_id_arr){
											if (!empty($ab_options)) $ab_options .= "<br />";
											$ab_options .= $point_id."/".$variant_id_arr["variant_id"];
										}
									}

       	                                                                db_query("INSERT INTO $sql_tbl[shipping_quote_log] (quote_id, datetime, manufacturerid, session_id, customer_id, ups_ground_quote, approx_ground_quote, product_cost, s_address, ups_server_quote, ab_options, logging_point) VALUES ('$max_quote_id', '".time()."', '$ship_manufacturerid', '".$$XCART_SESSION_NAME."', '$login', '".$shipping[$k]["rate_before_approximation"]."', '".$shipping[$k]["rate"]."', '".$manufacturerid_cost."', '".addslashes($s_address_log)."', '".$shipping[$k]["original_rate"]."', '$ab_options', '$mode')");

               	                                                        foreach ($cart["products"] as $kk => $vv){
                       	                                                        if ($vv["manufacturerid"] == $ship_manufacturerid){
                               	                                                        db_query("INSERT INTO $sql_tbl[shipping_quote_products_log] (quote_id, productid, qty, manufacturerid) VALUES ('$max_quote_id', '".$vv["productid"]."', '".$vv["amount"]."', '$ship_manufacturerid')");
                                               	                                }
                                                       	                }
/*
                                                                } 
								else {
									foreach ($shipping_quote_logs as $quote_log){

										$current_quote_id = $quote_log["quote_id"];

										db_query("DELETE FROM $sql_tbl[shipping_quote_log] WHERE quote_id='$current_quote_id' AND manufacturerid='$ship_manufacturerid'");
										db_query("DELETE FROM $sql_tbl[shipping_quote_products_log] WHERE quote_id='$current_quote_id' AND manufacturerid='$ship_manufacturerid'");

                                                                                db_query("INSERT INTO $sql_tbl[shipping_quote_log] (quote_id, datetime, manufacturerid, session_id, customer_id, ups_ground_quote, approx_ground_quote, product_cost, s_address, ups_server_quote) VALUES ('$current_quote_id', '".time()."', '$ship_manufacturerid', '".$$XCART_SESSION_NAME."', '$login', '".$shipping[$k]["rate_before_approximation"]."', '".$shipping[$k]["rate"]."', '".$manufacturerid_cost."', '".addslashes($s_address_log)."', ".$shipping[$k]["original_rate"].")");

       	                                                                        foreach ($cart["products"] as $kk => $vv){
               	                                                                        if ($vv["manufacturerid"] == $ship_manufacturerid){
                       	                                                                        db_query("INSERT INTO $sql_tbl[shipping_quote_products_log] (quote_id, productid, qty, manufacturerid) VALUES ('$current_quote_id', '".$vv["productid"]."', '".$vv["amount"]."', '$ship_manufacturerid')");
                               	                                                        }
                                       	                                        }
									}
								}
*/
							}

							break;
						}
					}
				}
			}
###
##
#

		}


		return $shipping;
	}
	else
		return;
}

#
# This function checks if shipping method have defined shipping rates
#
function func_is_shipping_method_allowable($shippingid, $customer_info, $products, $weight=0, $subtotal=0, $for_manufacturerid=0) {
	global $sql_tbl, $config, $single_mode;
	global $login;

# START: random:17710_17631 [2009 Mar 26 09:25] 
	if (empty($login) && !defined('IS_SHIPPING_QUOTE')) {
# END: random:17710_17631 [2009 Mar 26 09:25] 
		if ($config["Shipping"]["enable_all_shippings"] == "Y")
			return true;

		if ($config["General"]["apply_default_country"] != "Y")
			return false;
	}

	foreach ($products as $product) {
		if (!$single_mode) {
			#
			# Get the provider info (only for PRO, single_mode=false)
			#
			$provider = func_query_first_cell("SELECT provider FROM $sql_tbl[products] WHERE productid='$product[productid]'");


#
##
###
			$provider = "master";              
###
##
# 

			$provider_condition = "AND provider='$provider'";
		}
		else {
			$provider = $provider_condition = "";
		}

		#
		# Get the customer's shipping zone
		#
		$customer_zone = func_get_customer_zone_ship($customer_info, $provider, "D", $for_manufacturerid);

//func_print_r($customer_zone, $provider);

		#
		# Find existing shipping rates for $customer_zone
		#
		$shipping = func_query_first_cell($qqq="SELECT COUNT(*) FROM $sql_tbl[shipping_rates] WHERE shippingid='$shippingid' AND minweight<='$weight' AND maxweight>='$weight' AND mintotal<='$subtotal' AND maxtotal>='$subtotal' $provider_condition AND zoneid='$customer_zone' AND type='D' AND manufacturerid='$product[manufacturerid]'");

//func_print_r($qqq, $shipping);

		if ($shipping)
			return true;
	}

	return false;
}

function func_use_arb_account($params = false) {
	global $sql_tbl, $config;

	if (empty($config["Shipping"]["ARB_id"]) || empty($config["Shipping"]["ARB_password"]) || empty($config["Shipping"]["ARB_account"]))
		return false;

	if (!is_array($params))
		$params = func_query_first("SELECT param07 FROM $sql_tbl[shipping_options] WHERE carrier='ARB'");

	if (isset($params["param07"])) {
		$tmp = explode(',',$params["param07"]);
		return (isset($tmp[1]) && $tmp[1] == "Y");
	}

	return false;
}

#
# Add new realtime shipping method
#
function func_add_new_smethod($method, $code, $added = array()) {
	global $sql_tbl;

	if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE code = '".addslashes($code)."'") == 0)
		return false;

	if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE shipping = '".addslashes($method)."' AND code = '".addslashes($code)."'") > 0)
		return false;

	if (isset($added['service_code'])) {
		if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE code = '".addslashes($code)."' AND service_code = '".addslashes($added['service_code'])."'") > 0)
			return false;
	}

	$max_subcode = func_query_first_cell("SELECT MAX(subcode+0) FROM $sql_tbl[shipping]")+1;
	$data = array(
		"shipping"	=> addslashes($method),
		"subcode"	=> $max_subcode,
		"active"	=> "N",
		"is_new"	=> "Y",
		"code"		=> $code);

	if (!empty($added) && is_array($added))
		$data = func_array_merge($data, $added);

	$id = func_array2insert("shipping", $data);
	if (empty($id))
		return false;

	return $id;
}

# START: random:20460 [2010 Mar 18 13:43] 
function func_weight_shipping_products ($products, $ignore_freight=true, $userinfo_for_zones=false) {
# END: random:20460 [2010 Mar 18 13:43] 
	global $active_modules, $config;

	$total_weight = 0;

	foreach ($products as $product) {
		if (@$product["deleted"]) continue; # for Advanced_Order_Management module

# START: random:20460 [2010 Mar 18 13:43] 
		$product["free_shipping"] = "N";

#
##
###
		$product["provider"] = "master";
###
##
#


		$free_shipping_arr = func_is_customer_free_ship_zone($product["free_ship_zone"], $userinfo_for_zones, $product["provider"]);

		if ($userinfo_for_zones && $free_shipping_arr["is_customer_free_ship_zone"]){
				$product["free_shipping"] = "Y";
		}

# END: random:20460 [2010 Mar 18 13:43] 
		if ($product["free_shipping"] == "Y" || ($active_modules["Egoods"] && $product["distribution"] != "") || (!$ignore_freight && $config["Shipping"]["replace_shipping_with_freight"] == "Y" && $product["shipping_freight"] > 0)) {
			continue;
		}

		/*if (floatval($product["shipping_weight"]) > 0)
			$total_weight += $product["shipping_weight"] * $product["amount"];
		else*/
			$total_weight += $product["weight"] * $product["amount"];
	}

	return $total_weight;
}

#
# Sort shipping list by the 'orderby' field
#
function usort_array_cmp_orderby($a, $b) {
	return $a["orderby"] - $b["orderby"];
}

function usort_array_cmp_rate($a, $b) {
        return $a["rate"] - $b["rate"];
}

function func_shipper_show_rates($rates_list) {
	global $config, $sql_tbl;

	echo "<h1>Shipping Rates</h1>";

	if (empty($rates_list)) {
		echo "No rates";
		return;
	}

	$l_search = array("##SM##","##R##");
	$l_replace = array("<sup>SM</sup>","&#174;");

	foreach ($rates_list as $rate) {
		$method = func_query_first("SELECT shipping FROM $sql_tbl[shipping] WHERE subcode='$rate[methodid]'");
		if (!empty($rate["currency"]))
			$_currency_symbol = $rate["currency"];
		else
			$_currency_symbol = $config["General"]["currency_symbol"];

		echo "<p>".str_replace($l_search,$l_replace,$method["shipping"])." ($_currency_symbol ".price_format($rate["rate"]).")";
	}
}

?>
