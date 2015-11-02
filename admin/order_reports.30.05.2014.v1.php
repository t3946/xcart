<?php /* ADDED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: order_reports.php, v 1.0.0 2010/04/12 17:38:59 random Exp $
#

define("NUMBER_VARS", "posted_data[total_min],posted_data[total_max],posted_data[price_min],posted_data[price_max]");
define("ORDER_REPORTS", 1);
require "./auth.php";
require $xcart_dir."/include/security.php";

set_time_limit(86400);
ini_set("memory_limit", "500M");

x_session_register("search_data");

$smarty->assign("show_order_details", "Y");


if ($REQUEST_METHOD == "POST") {
	#
	# Update the session $search_data variable from $posted_data
	#
	if (!empty($posted_data)) {

		if (!empty($StartMonth)) {
			$posted_data["start_date"] = mktime(0,0,0,$StartMonth,$StartDay,$StartYear);
			$posted_data["end_date"] = mktime(23,59,59,$EndMonth,$EndDay,$EndYear);
		}
		$posted_data["report_mode"] = $mode;

		$search_data["order_reports"] = $posted_data;

	}

	func_header_location("order_reports.php?mode=report");
}

if ($mode == "report") {

	x_load('order');

	if (is_array($search_data["order_reports"])) {
		$data = $search_data["order_reports"];
	}	

	$search_condition = "";

	if (!empty($data["date_period"])) {
		if ($data["date_period"] == "C") {
			# ...orders within specified period
			$start_date = $data["start_date"] - $config["Appearance"]["timezone_offset"];
			$end_date = $data["end_date"] - $config["Appearance"]["timezone_offset"];
		}
		else {
			# ...orders within this month
			$end_date = time() + $config["Appearance"]["timezone_offset"];
			if ($data["date_period"] == "M") {
				$start_date = mktime(0,0,0,date("n",$end_date),1,date("Y",$end_date));
			}
			elseif ($data["date_period"] == "D") {
				$start_date = mktime(0,0,0,date("n",$end_date),date("j",$end_date),date("Y",$end_date));
			}
			elseif ($data["date_period"] == "W") {
				$first_weekday = $end_date - (date("w",$end_date) * 86400);
				$start_date = mktime(0,0,0,date("n",$first_weekday),date("j",$first_weekday),date("Y",$first_weekday));
			}

			$data["end_date"] = $end_date = time();
			$data["start_date"] = $start_date; // already with timezone offset
			$start_date -= $config["Appearance"]["timezone_offset"];
		}

		$search_condition .= " AND o.date>='".($start_date)."'";
		$search_condition .= " AND o.date<='".($end_date)."'";
	}

#
##
###
	$search_condition .= " AND o.cb_status != 'A'";
###
##
#
	$orders = func_query("SELECT o.* FROM $sql_tbl[orders] AS o WHERE 1 $search_condition ORDER BY o.date");
	$manufacturers = array();

	if (!empty($orders)) {
		$data["total_accounting"] = array();
		$data["total"] = array();
		$data["total_margin"] = 0;
		for ($i=0;$i<=5;$i++) {
			$data["total_accounting"][$i] = array();
			foreach ($price_details_names as $dn) {
				$data["total_accounting"][$i][$dn] = 0;
				$data["totals"][$dn] = 0;
			}
		}

		foreach ($orders as $k => $v) {
			$orders[$k]["shipping_groups"] = func_get_shipping_groups($v["orderid"]);
			foreach ($orders[$k]["shipping_groups"] as $mid => $group) {

//func_print_r($group, $data);
//die();

				if (
		                    (!empty($data['manufacturers']) && !in_array($mid, $data['manufacturers'])) 
		                    || ($data['profit_margin_range'] == "margin_less_100" && $group['profit_margin'] == 100) 
//                                  || (empty($data['include_margin_100']) && $group['profit_margin'] == 100)
				    || ($group["acc_paymentid"] == "0")
                		    || !in_array($group['cb_status'], array('P','R','O','H','A')) 
				    || ($data['profit_margin_range'] == "margin_less_1" && ($group['profit_margin'] > $data['profit_margin_range_less_1'] || $group['profit_margin'] == 100))
				    || ($data['profit_margin_range'] == "margin_1_2" && ($group['profit_margin'] < $data['profit_margin_range_1'] || $group['profit_margin'] > $data['profit_margin_range_2'] || $group['profit_margin'] == 100) )
		                ) {
					unset($orders[$k]["shipping_groups"][$mid]);
				} else {

					$manufacturers[$mid] = $group["code"];
			                $accounting_enabled = in_array($group['cb_status'], array('P','R','O','H','A'));
					foreach ($price_details_names as $dn) {
						if ($accounting_enabled) {
							for ($i=0;$i<=5;$i++) {
								$data["total_accounting"][$i][$dn] += $group["accounting"][$i][$dn];
							}
						}
						$data["total"][$dn] += $group["total"][$dn];
					}
				}
			}

			if ($accounting_enabled) {
				$data["total_margin"] = @price_format($data["total_accounting"][5]["net"]/$data["total_accounting"][0]["net"]*100);
			}

#
##
###
                        $data["real_net"] = $data["total_accounting"][0]["net"] + $data["total_accounting"][4]["net"] - $data["total_accounting"][3]["net"];
			if ($data["real_net"] > 0){
	                        $data["real_pm"] = (($data["total_accounting"][0]["net"] + $data["total_accounting"][4]["net"] - $data["total_accounting"][3]["net"] - $data["total_accounting"][1]["net"] - $data["total_accounting"][2]["net"])/($data["real_net"]))*100;
			}
###
##
#

			if (empty($orders[$k]["shipping_groups"])) {
				unset($orders[$k]);
				continue;
			}
			$orders[$k]["s_countryname"] = func_get_country($v["s_country"]);
			$orders[$k]["date"] += $config["Appearance"]["timezone_offset"];
		}

		$smarty->assign("manufacturers", $manufacturers);
		$smarty->assign("orders", $orders);
		$smarty->assign("mode", $mode);
		$smarty->assign("data", $data);

		$all_processors = func_query_hash("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM $sql_tbl[payment_methods] WHERE acc_proc='Y' ORDER BY orderby","paymentid", false);
		$smarty->assign("all_processors", $all_processors);

		if ($data["report_mode"] != "csv") {
			func_display("main/order_report_html.tpl",$smarty);
		} else {
			$smarty->assign("delimiter", ";");
			$fn = 'sales_report_'.date('Y-m-d').'.csv';
			header('Content-type: text/csv; name="'.$fn.'"');
			header("Content-Disposition: attachment; filename=".$fn);
			func_display("main/order_report_csv.tpl",$smarty);
		}
		exit;
	}

}

if (!empty($search_data["order_reports"])){
 $smarty->assign("search_prefilled", @$search_data["order_reports"]);
}

$location[] = array(func_get_langvar_by_name("lbl_order_reports"), "order_reports.php");

$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer FROM $sql_tbl[manufacturers] WHERE avail='Y' ORDER BY manufacturer, orderby","manufacturerid",false);
if (!empty($search_data["order_reports"]["manufacturers"])) {
	foreach ($search_data["order_reports"]["manufacturers"] as $key) {
		$manufacturers[$key]["selected"] = true;
	}
}
$smarty->assign("manufacturers", $manufacturers);

$smarty->assign("main","order_reports");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
