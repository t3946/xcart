<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
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
# $Id: orders.php,v 1.24 2006/01/11 06:55:57 mclap Exp $
#

define("NUMBER_VARS", "posted_data[total_min],posted_data[total_max],posted_data[price_min],posted_data[price_max]");
require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$smarty->assign("show_order_details", "Y");

if ($current_area == 'A') {
	$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer FROM $sql_tbl[manufacturers] WHERE avail='Y' ORDER BY manufacturer, orderby","manufacturerid",false);
	if (!empty($search_data["order_reports"]["manufacturers"])) {
		foreach ($search_data["order_reports"]["manufacturers"] as $key) {
			$manufacturers[$key]["selected"] = true;
		}
	}
	$smarty->assign('manufacturers', $manufacturers);
}

if ($mode=="subscriptions" && $active_modules["Subscriptions"])
    include $xcart_dir."/modules/Subscriptions/subscriptions.php";
else {

if ($mode == 'delete_all') {
#
# Delete ALL orders and move them to the orders_deleted table
#
	include $xcart_dir."/include/process_order.php";
}

include $xcart_dir."/include/orders.php";

# START: random:20341 [2010 Jul 29 14:46] 
$all_processors = func_query_hash("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM $sql_tbl[payment_methods] WHERE acc_proc='Y' ORDER BY orderby","paymentid", false);
$smarty->assign("all_processors", $all_processors);

if ($mode == 'search') {
	$end_date = time() + $config["Appearance"]["timezone_offset"];
	$smarty->assign("cur_time", $end_date);
	$start_date = mktime(0,0,0,date("n",$end_date),date("j",$end_date),date("Y",$end_date)) - $config["Appearance"]["timezone_offset"];
	$end_date -= $config["Appearance"]["timezone_offset"];
	$today_totals = array();
    $query = "SELECT SUM(g.total_net) FROM $sql_tbl[order_groups] AS g"
        . " INNER JOIN $sql_tbl[orders] AS o ON o.orderid=g.orderid"
        . " LEFT JOIN $sql_tbl[manufacturers] AS m ON m.manufacturerid=g.manufacturerid"
        . " WHERE o.date>='$start_date' AND o.date<='$end_date'"
        . " AND (g.cb_status = 'P' OR g.dc_status IN ('C','S')) AND m.manufacturerid";
	$today_totals['ARTS'] = func_query_first_cell("$query='$artss_manufacturerid'");
	$today_totals['other'] = func_query_first_cell("$query!='$artss_manufacturerid'");
	$smarty->assign("today_totals", $today_totals);
}

# END: random:20341 [2010 Jul 29 14:46] 
}

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
