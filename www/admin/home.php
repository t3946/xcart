<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
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
# $Id: home.php,v 1.33 2006/01/11 06:55:57 mclap Exp $
#

require "./auth.php";

###
if (empty($login))
        func_header_location("error_message.php?antibot_error");
###

# START: random:18591_18598 [2009 Jul 29 10:36] 
if (!empty($login))
	require $xcart_dir."/include/security.php";
# END: random:18591_18598 [2009 Jul 29 10:36] 

if (!empty($login) && $user_account["flag"] != "FS") {

	include "./quick_menu.php";
	
	#
	# Define data for the navigation within section
	#
	$dialog_tools_data = array();

	$dialog_tools_data["left"][] = array("link" => "home.php?promo#menu", "title" => func_get_langvar_by_name("lbl_quick_menu"));
	
	if (!isset($promo)) {
		$dialog_tools_data["left"][] = array("link" => "#orders", "title" => func_get_langvar_by_name("lbl_last_orders_statistics"));
		$dialog_tools_data["left"][] = array("link" => "#topsellers", "title" => func_get_langvar_by_name("lbl_top_sellers"));

		$dialog_tools_data["right"][] = array("link" => "home.php?promo", "title" => func_get_langvar_by_name("lbl_quick_start"));
		$dialog_tools_data["right"][] = array("link" => "home.php?promo&display=news", "title" => func_get_langvar_by_name("lbl_new_features_in_xcart"), "style" => "hl");
	}
	else {
		$dialog_tools_data["left"][] = array("link" => "home.php?promo#qs", "title" => func_get_langvar_by_name("lbl_quick_start_text"));
		$dialog_tools_data["left"][] = array("link" => "home.php?promo&display=news", "title" => func_get_langvar_by_name("lbl_new_features_in_xcart"), "style" => "hl");

		$dialog_tools_data["right"][] = array("link" => "home.php", "title" => func_get_langvar_by_name("lbl_top_info"));
	}

	# Assign the section navigation data
	$smarty->assign("dialog_tools_data", $dialog_tools_data);


	if (isset($promo)) {
		if ($display == "news") {
			$location[] = array(func_get_langvar_by_name("lbl_new_features_in_xcart"), "");
			$smarty->assign("display", "news");
		}
		else
			$location[] = array(func_get_langvar_by_name("lbl_quick_start"), "");
		$smarty->assign("main", "promo");
	}
	else {
		include "./main.php";
		$smarty->assign("main","top_info");
	}

#
##
###
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

        $query = "SELECT SUM(g.total_net) FROM $sql_tbl[order_groups] AS g"
        . " INNER JOIN $sql_tbl[orders] AS o ON o.orderid=g.orderid"
        . " LEFT JOIN $sql_tbl[manufacturers] AS m ON m.manufacturerid=g.manufacturerid"
        . " WHERE o.date>='$start_date' AND o.date<='$end_date'"
        . " AND g.cb_status = 'AP' AND m.manufacturerid";
        $today_totals['ARTS_Authorized'] = func_query_first_cell("$query='$artss_manufacturerid'");
        $today_totals['other_Authorized'] = func_query_first_cell("$query!='$artss_manufacturerid'");

	$count_days = date("j", time());
	$end_date = time() + $config["Appearance"]["timezone_offset"];
        $start_date = mktime(0,0,0,date("n",$end_date),0,date("Y",$end_date)) - $config["Appearance"]["timezone_offset"];
        $end_date -= $config["Appearance"]["timezone_offset"];
	$query = "SELECT SUM(g.total_net) FROM $sql_tbl[order_groups] AS g"
        . " INNER JOIN $sql_tbl[orders] AS o ON o.orderid=g.orderid"
//        . " LEFT JOIN $sql_tbl[manufacturers] AS m ON m.manufacturerid=g.manufacturerid"
        . " WHERE o.date>='$start_date' AND o.date<='$end_date'"
        . " AND (g.cb_status = 'P' OR g.dc_status IN ('C','S'))";

	$Average_daily_S3_Stores_sales_all = func_query_first_cell("$query AND o.amazonorderid=''");
	$Average_daily_S3_Stores_sales = $Average_daily_S3_Stores_sales_all/$count_days;
	$today_totals['Average_daily_S3_Stores_sales'] = price_format($Average_daily_S3_Stores_sales);

	$Average_Amazon_daily_sales_all = func_query_first_cell("$query AND o.amazonorderid!=''");
	$Average_Amazon_daily_sales = $Average_Amazon_daily_sales_all/$count_days;
	$today_totals['Average_Amazon_daily_sales'] = price_format($Average_Amazon_daily_sales);

	$Average_Amazon_FBS_daily_sales_all = func_query_first_cell("$query AND o.amazonorderid!='' AND o.amazon_fulfillment_channel='MFN'");
	$Average_Amazon_FBS_daily_sales = $Average_Amazon_FBS_daily_sales_all/$count_days;
	$today_totals['Average_Amazon_FBS_daily_sales'] = price_format($Average_Amazon_FBS_daily_sales);

	$Average_Amazon_FBA_daily_sales_all = func_query_first_cell("$query AND o.amazonorderid!='' AND o.amazon_fulfillment_channel='AFN'");
	$Average_Amazon_FBA_daily_sales = $Average_Amazon_FBA_daily_sales_all/$count_days;
	$today_totals['Average_Amazon_FBA_daily_sales'] = price_format($Average_Amazon_FBA_daily_sales);

        $smarty->assign("today_totals", $today_totals);
###
##
#

} else {
	$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer FROM $sql_tbl[manufacturers] WHERE avail='Y' ORDER BY manufacturer, orderby","manufacturerid",false);
	if (!empty($search_data["manufacturers"])) {
		foreach ($search_data["manufacturers"] as $key) {
			$manufacturers[$key]["selected"] = true;
		}
	}
	$smarty->assign("manufacturers", $manufacturers);
	$smarty->assign("main", "home");
}


# Assign the current location line
if (!empty($login))
	$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

/*if (isset($mccornic)) {
    func_xpay_func_load();
    $txn_msg = '';
    $api_data = array(
        'txnId' => '5d79e25ed824a7de7db95a751d9b2754',
    );
    list($api_status, $api_response) = xpc_api_request('payment', 'get_additional_info', $api_data);
    if (!empty($api_response['transactions'])) {
        $txn_id = $api_response['transactions'][0]['txnid'];
        if (!empty($txn_id)) {
            $txn_msg = " (TransID #$txn_id)";
        }
    }
    var_dump($api_response);
    var_dump($txn_msg);
}*/

func_display("admin/home.tpl", $smarty);
?>
