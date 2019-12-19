<?php

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

func_display("admin/home.tpl", $smarty);
?>
