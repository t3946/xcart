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

# $Id: checks_deposited.php,v 1.30.2.1 2006/04/25 11:28:29 svowl Exp $

# This script allow to create static html checks_deposited within  X-Cart

define('USE_TRUSTED_POST_VARIABLES',1);
define('USE_TRUSTED_SCRIPT_VARS',1);
$trusted_post_variables = array("notes");

require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Checks deposited", "");

$checks_deposited_id = intval($checks_deposited_id);

if ($REQUEST_METHOD == "POST") {

	if ($mode == "add_order" || $mode == "update_deposit"){

		$checks_deposited["currency"] = $currency;

		if (empty($checks_deposited_id)){
			$checks_deposited_id = func_array2insert("checks_deposited", $checks_deposited);
		}

                if (!empty($date)){
                        $date_mm_dd_yyyy_time_arr = explode("/", $date);
                        if (!empty($date_mm_dd_yyyy_time_arr) && is_array($date_mm_dd_yyyy_time_arr)){
                                $date_mm_dd_yyyy_time = mktime(0, 0, 0, $date_mm_dd_yyyy_time_arr[0], $date_mm_dd_yyyy_time_arr[1], $date_mm_dd_yyyy_time_arr[2]);
                                $checks_deposited["date"] = $date_mm_dd_yyyy_time;
                        }  
		}
	
		func_array2update("checks_deposited", $checks_deposited, "checks_deposited_id = '$checks_deposited_id'");
	}

        if ($mode == "add_order"){

//func_print_r($_POST, $add_orderids);
//die();

		if (!empty($checks_deposited_id) && !empty($add_orderids) && is_array($add_orderids)){

		  foreach ($add_orderids as $key_number => $add_orderid){

			$add_amount = $add_amounts[$key_number];
			$add_check_number = $add_check_numbers[$key_number];
			$add_note = $add_notes[$key_number];
			
			if (empty($add_amount)){
				continue;
			}


			if (strpos($add_orderid,"-") !== false){
				$add_orderid_arr = explode("-", $add_orderid);
				$add_orderid = $add_orderid_arr["1"];
			}

			$add_orderid = trim($add_orderid);
			$add_amount = trim($add_amount);
			$add_amount = price_format($add_amount);

			$grand_total_currency = func_query_first("SELECT total, currency FROM $sql_tbl[orders] WHERE orderid='$add_orderid'");

			if (empty($grand_total_currency)){
				if (!isset($top_message["content"])){
					$top_message["content"] = "";
				}
	                        $top_message["content"] .= 'Order # '.$add_orderid." doesn't exist in our system.<br />";
        	                $top_message["type"] = "E";

				continue;
			}

			$grand_total = $grand_total_currency["total"];
			$currency_in_db = $grand_total_currency["currency"];

//			if ($currency_in_db == $currency){

				$allowed_statuses = array("IO","O");
				$allowed_status_found = true;
				$cb_statuses = func_query("SELECT cb_status, manufacturerid FROM $sql_tbl[order_groups] WHERE orderid='$add_orderid'");

				if (!empty($cb_statuses)){
					foreach ($cb_statuses as $k => $v){
						if (!in_array($v["cb_status"], $allowed_statuses)){
							$allowed_status_found = false;
							break;
						}
					}
	
        	                        db_query("UPDATE $sql_tbl[checks_deposited] SET total_deposit_amount=total_deposit_amount+$add_amount WHERE checks_deposited_id='$checks_deposited_id'");
                        	        db_query("INSERT INTO $sql_tbl[checks_deposited_orders] (checks_deposited_id, orderid, check_number, amount, notes) VALUES ('$checks_deposited_id', '$add_orderid', '".trim($add_check_number)."', '$add_amount', '".trim($add_note)."')");

					$log = "";

                	                if ($allowed_status_found && $grand_total == $add_amount && $currency_in_db == $currency){
/*
	                                        foreach ($cb_statuses as $k => $v){

                	                        }
*/
						$log = "cb_status -> 'Paid'";

						db_query("UPDATE $sql_tbl[order_groups] SET cb_status='P' WHERE orderid='$add_orderid'");

						db_query("UPDATE $sql_tbl[checks_deposited] SET currency_locked='Y' WHERE checks_deposited_id='$checks_deposited_id'");
        	                        } else {

//func_print_r($config["Checks_deposited_options"]["Checks_deposited_Attention_tag"]);
						if (!empty($config["Checks_deposited_options"]["Checks_deposited_Attention_tag"])){

							$is_such_tag_in_db = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$add_orderid' AND status_id='".$config["Checks_deposited_options"]["Checks_deposited_Attention_tag"]."'");
							

//func_print_r($is_such_tag_in_db);
							if (empty($is_such_tag_in_db)){

//func_print_r($add_orderid);
								db_query("INSERT INTO $sql_tbl[orders_additional_tags] (status_id, orderid) VALUES ('".$config["Checks_deposited_options"]["Checks_deposited_Attention_tag"]."','$add_orderid')");

								$tag_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='".$config["Checks_deposited_options"]["Checks_deposited_Attention_tag"]."'");
						                $log .= "<br />'".$tag_name."' attention tag added";
							}
						}
	                                }

					if ($log != ""){
						func_log_order($add_orderid, 'X', $log, $login);
					}
				}
//			}

		  } // foreach ($add_orderids as $key_number => $add_orderid)

		} // if (!empty($checks_deposited_id) && !empty($add_orderid) && is_array($add_orderid))
        }

	if (empty($top_message["content"])){
	        $top_message["content"] = 'Done';
        	$top_message["type"] = "I";
	}

	func_header_location("checks_deposited.php?checks_deposited_id=".$checks_deposited_id);
} # /if ($REQUEST_METHOD == "POST")


if (isset($_GET['checks_deposited_id'])) {

	$location[count($location)-1][1] = "checks_deposited.php";
	$location[] = array("Deposit", "");

	$smarty->assign("checks_deposited_id", $checks_deposited_id);
	$smarty->assign("main", "checks_deposited_edit");

	if (!empty($checks_deposited_id)){
	        $checks_deposited = func_query_first("SELECT * FROM $sql_tbl[checks_deposited] WHERE checks_deposited_id='$checks_deposited_id'");
        	$smarty->assign("checks_deposited", $checks_deposited);

		$checks_deposited_orders = func_query("SELECT * FROM $sql_tbl[checks_deposited_orders] WHERE checks_deposited_id='$checks_deposited_id' ORDER BY orderid");
		if (!empty($checks_deposited_orders)){

			foreach ($checks_deposited_orders as $k => $v){
				$order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid='$v[orderid]'");
				$checks_deposited_orders[$k]["order_prefix"] = $order_prefix;
			}

			$smarty->assign("checks_deposited_orders", $checks_deposited_orders);
		}
	}
}
else {
#
# Prepare data for checks_deposited list
#
	$checks_deposited = func_query("SELECT * FROM $sql_tbl[checks_deposited] ORDER BY date");

	$smarty->assign("checks_deposited", $checks_deposited);
	$smarty->assign("main", "checks_deposited");
}


# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
