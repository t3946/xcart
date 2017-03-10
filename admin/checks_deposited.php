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

x_load("order", "order_edit");

$location[] = array("Checks deposited", "");

$checks_deposited_id = intval($checks_deposited_id);

if ($REQUEST_METHOD == "POST") {

	if ($mode == "unfreeze_order"){

		$orig_unfreeze_orderid = $unfreeze_orderid;

		if (strpos($unfreeze_orderid,"-") !== false){
                                $unfreeze_orderid_arr = explode("-", $unfreeze_orderid);
                                $unfreeze_orderid = $unfreeze_orderid_arr["1"];
		}
		$unfreeze_orderid = trim($unfreeze_orderid);

		if (!empty($unfreeze_orderid)){
			db_query("UPDATE $sql_tbl[orders] SET unfreeze_cb_status='Y' WHERE orderid='$unfreeze_orderid'");

	                $top_message["content"] = 'C2B payment status for order # '.$orig_unfreeze_orderid.' has been unfrozen.';
               		$top_message["type"] = "I";

		        func_header_location("checks_deposited.php");
		}
	}
	elseif ($mode == "update_deposit"){

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

		if (!empty($posted_data) && is_array($posted_data)){
			$total_deposit_amount = 0;

			foreach ($posted_data as $k => $v){
				$orderid = trim($v["orderid"]);
				$check_number = trim($v["check_number"]);
				$amount = trim($v["amount"]);
				$amount = price_format($amount);
				$notes = trim($v["notes"]);

				if ($v["del"] == "Y"){
					db_query("DELETE FROM $sql_tbl[checks_deposited_orders] WHERE id='$k'");
					continue;
				}

	                        if (empty($amount)){
        	                        continue;
                	        }

/*
	                        if (strpos($orderid,"-") !== false){
        	                        $orderid_arr = explode("-", $orderid);
                	                $orderid = $orderid_arr["1"];
	                        }

	                        $orderid = trim($orderid);

	                        $orderid = func_query_first_cell("SELECT orderid FROM $sql_tbl[orders] WHERE orderid='$orderid'");

        	                if (empty($orderid)){
                	                if (!isset($top_message["content"])){
                        	                $top_message["content"] = "";
                                	}
	                                $top_message["content"] .= 'Order # '.$orderid." doesn't exist in our system.<br />";
        	                        $top_message["type"] = "E";

                	                continue;
                        	}
*/

//				$current_checks_deposited_order = func_query_first("SELECT * FROM $sql_tbl[checks_deposited_orders] WHERE id='$k'");

	                        $current_time = time();
//        	                db_query("UPDATE $sql_tbl[checks_deposited_orders] SET orderid='$orderid', check_number='$check_number', amount='$amount', notes='$notes' WHERE id='$k'");
        	                db_query("UPDATE $sql_tbl[checks_deposited_orders] SET check_number='$check_number', amount='$amount', notes='$notes' WHERE id='$k'");

//	                        $log = "checks_deposited_orders_".$current_time;
//        	                func_log_order($add_orderid, 'S', $log, $login);

				$total_deposit_amount += $amount;
			
			}

			db_query("UPDATE $sql_tbl[checks_deposited] SET total_deposit_amount='$total_deposit_amount' WHERE checks_deposited_id='$checks_deposited_id'");
		}


//func_print_r($_POST);
//die();

		if (!empty($checks_deposited_id) && !empty($add_orderids) && is_array($add_orderids)){

		  $allowed_statuses = array("IO","O");

                  $top_message["content"] = "";

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

                        $add_orderid = func_query_first_cell("SELECT orderid FROM $sql_tbl[orders] WHERE orderid='$add_orderid'");

                        if (empty($add_orderid)){

	                        if (!empty($top_message["content"])){
        	                        $top_message["content"] .= "<br />";
                	        }

                                $top_message["content"] .= 'Order # '.$add_orderid." doesn't exist in our system.";
                                $top_message["type"] = "E";

                                continue;
                        }

                        $allowed_status_found = true;
                        $cb_statuses = func_query("SELECT cb_status, manufacturerid FROM $sql_tbl[order_groups] WHERE orderid='$add_orderid'");

                        if (!empty($cb_statuses)){
	                        foreach ($cb_statuses as $ks => $vs){
        	                        if (!in_array($vs["cb_status"], $allowed_statuses)){
                	                        $allowed_status_found = false;
                                                break;
                                        }
                                }
			}

			if (!$allowed_status_found){
                                if (!empty($top_message["content"])){
                                        $top_message["content"] .= "<br />";
                                }

                                $top_message["content"] .= 'Order # '.$add_orderid." must be in ‘Unpaid: PO’ or ‘Incomplete: PO’ C2B status to be added to the deposit.";
                                $top_message["type"] = "E";

                                continue;
			}


                        $add_amount = trim($add_amount);
                        $add_amount = price_format($add_amount);

                        db_query("UPDATE $sql_tbl[checks_deposited] SET total_deposit_amount=total_deposit_amount+$add_amount WHERE checks_deposited_id='$checks_deposited_id'");
                        $current_time = time();
                        db_query("INSERT INTO $sql_tbl[checks_deposited_orders] (checks_deposited_id, orderid, check_number, amount, notes, date_added) VALUES ('$checks_deposited_id', '$add_orderid', '".trim($add_check_number)."', '$add_amount', '".trim($add_note)."', '$current_time')");

//                        $log = "checks_deposited_orders_".$current_time;
//                        func_log_order($add_orderid, 'S', $log, $login);
		  } // foreach ($add_orderids as $key_number => $add_orderid)
		} // if (!empty($checks_deposited_id) && !empty($add_orderids) && is_array($add_orderids))
	}

//        if ($mode == "add_order")
        if ($mode == "checks_are_now_deposited_with_the_bank"){

//func_print_r($_POST, $add_orderids);
//die();

		$top_message["content"] = "";

		$checks_deposited_orders = func_query("SELECT * FROM $sql_tbl[checks_deposited_orders] WHERE checks_deposited_id='$checks_deposited_id'");

		if (!empty($checks_deposited_id) && !empty($checks_deposited_orders) && is_array($checks_deposited_orders)){

		  db_query("UPDATE $sql_tbl[checks_deposited] SET status='D' WHERE checks_deposited_id='$checks_deposited_id'");

		  $orders_marked_as_paid = array();
		  $orders_NOT_marked_as_paid = array();
		  $allowed_statuses = array("IO","O");

		  foreach ($checks_deposited_orders as $k => $v){

#
	                $log = "checks_deposited_orders_".$current_time;
                  	func_log_order($v["orderid"], 'S', $log, $login);
#

			$grand_total_currency = func_query_first("SELECT total, currency FROM $sql_tbl[orders] WHERE orderid='$v[orderid]'");

			$grand_total = $grand_total_currency["total"];
			$currency_in_db = $grand_total_currency["currency"];

//			if ($currency_in_db == $currency){

				$allowed_status_found = true;
				$cb_statuses = func_query("SELECT cb_status, manufacturerid FROM $sql_tbl[order_groups] WHERE orderid='$v[orderid]'");

				if (!empty($cb_statuses)){
					foreach ($cb_statuses as $ks => $vs){
						if (!in_array($vs["cb_status"], $allowed_statuses)){
							$allowed_status_found = false;
							break;
						}
					}
	
					$log = "";

                	                if ($allowed_status_found && $grand_total == $v["amount"] && $currency_in_db == $currency){

						$log = "cb_status -> 'Paid'";

						db_query("UPDATE $sql_tbl[order_groups] SET cb_status='P' WHERE orderid='$v[orderid]'");

						db_query("UPDATE $sql_tbl[checks_deposited] SET currency_locked='Y' WHERE checks_deposited_id='$checks_deposited_id'");

						$orders_marked_as_paid[] = $v["orderid"];

						$order_data = func_order_data($v["orderid"]);
						$order = $order_data["order"];
						if (!empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){
							foreach ($order["shipping_groups"] as $m_id => $group){
								$update = array();

				                                func_recalculate_accounting($group);
				                                $update['profit_margin'] = $group['profit_margin'];
				                                $update = func_add_accounting_fields($update, '', '', '', "order_groups", $group['accounting']);
								func_log_order_groups($update, $v["orderid"], $m_id, 'X', $login);
								func_array2update("order_groups", $update ,"orderid='$v[orderid]' AND manufacturerid='$m_id'");
								unset($update);
							}
						}

        	                        } else {

						if (!empty($config["Purchase_Order"]["Checks_deposited_Attention_tag"])){

							$is_such_tag_in_db = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$v[orderid]' AND status_id='".$config["Purchase_Order"]["Checks_deposited_Attention_tag"]."'");
							

//func_print_r($is_such_tag_in_db);
							if (empty($is_such_tag_in_db)){
                                \Xcart\App\Main\Xcart::app()->event->trigger('order:tag', ['status_id' => $config["Purchase_Order"]["Checks_deposited_Attention_tag"], 'order_id' => $v['orderid'] ]);
								$tag_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='".$config["Purchase_Order"]["Checks_deposited_Attention_tag"]."'");
						                $log .= "<br />'".$tag_name."' attention tag added";
							}

							$orders_NOT_marked_as_paid[] = $v["orderid"];
						}
	                                }

					if ($log != ""){
						func_log_order($v["orderid"], 'X', $log, $login);
					}
				}
//			}

		  } // 

		} // 
        }

	if (!empty($orders_marked_as_paid)){
		$top_message["type"] = "I";
		foreach ($orders_marked_as_paid as $orderid){
			if (!empty($top_message["content"])){
				$top_message["content"] .= "<br />";
			}

			$top_message["content"] .= "Order # $orderid has been marked as 'Paid'.";
		}
	}

	if (!empty($orders_NOT_marked_as_paid)){
		$top_message["type"] = "I";
		foreach ($orders_NOT_marked_as_paid as $orderid){
                        if (!empty($top_message["content"])){
                                $top_message["content"] .= "<br />";
                        }

			$top_message["content"] .= "Order # $orderid has NOT been marked as 'Paid'. No action needs to be taken. Our AR specialist will look into this situation.";
		}
	}

	if (empty($top_message["content"])){
	        $top_message["content"] = 'Done';
        	$top_message["type"] = "I";
	}

	func_header_location("checks_deposited.php?checks_deposited_id=".$checks_deposited_id);
//	func_header_location("checks_deposited.php");
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

$deposite_statuses = array(
	"P" => "Pending deposit",
	"D" => "Deposited with the bank"
);

$smarty->assign("deposite_statuses", $deposite_statuses);

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
