<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
<?php /* MODIFIED: random:19017 [2009 Sep 14 14:13][Custom development (Add new option to "Order status" selector and "Empty tracking number detection")] */ ?>
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
# $Id: orders.php,v 1.76.2.3 2006/12/18 08:19:14 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

# START: random:20341 [2010 Jul 29 14:46] 
x_load('export','order');
# END: random:20341 [2010 Jul 29 14:46] 

// Find by order preset
if ($current_area == 'A' && isset($fid)) {
	$fid = intval($fid);
    
	$filter = func_get_filter($fid);
    
	if (!$filter) {
		func_header_location('orders.php');
	}

	$search_data['orders'] = array(
				       'need_advanced_options' => false,
				       'sort_field' => 'orderid',
				       'sort_direction' => 1
				 );
    
	if (!empty($filter['CB'])) {
		$search_data['orders']['cb_status'] = $filter['CB'];
	}
	if (!empty($filter['DC'])) {
		$search_data['orders']['dc_status'] = $filter['DC'];
	}
	if (!empty($filter['BD'])) {
		$search_data['orders']['bd_status'] = $filter['BD'];
	}
	if (!empty($filter['distributors'])) {
		$search_data['orders']['manufacturers'] = $filter['distributors'];
	}
	if ($filter['time_from_mode'] == 'D') {
		$search_data['orders']['start_date'] = intval($filter['time_from_date']);
	} else {
		$search_data['orders']['start_date'] = time() - intval($filter['time_from']) * 3600;
	}
	$search_data['orders']['end_date'] = time() - intval($filter['time_to']) * 3600;
	$search_data['orders']['date_period'] = 'C';
	$search_data['orders']['placement_time_from_type'] = $filter['placement_time_from_type'];
	$search_data['orders']['placement_time_to_type'] = $filter['placement_time_to_type'];
    
	foreach ($search_data['orders'] as $k => $v) {
		if (is_array($v)) {
			$tmp = array();
			foreach ($v as $k1 => $v1) {
				$tmp[$v1] = 1;
			}
			$search_data['orders'][$k] = $tmp;
		}
	}
    
	$mode = 'search';
}

if (!$filter_preset) {
    $location[] = array(func_get_langvar_by_name("lbl_orders_management"), "orders.php");
    $smarty->assign("location", $location);
}

$do_export = in_array($mode, array("export","export_found", "export_all"));

# START: random:18591_18598 [2009 Jul 29 10:36] 
$advanced_options = array("orderid1", "orderid2", "total_max", "payment_method", "shipping_method", "provider", "features", "product_substring", "productcode", "productid", "price_max", "customer", "address_type", "phone", "email");
# END: random:18591_18598 [2009 Jul 29 10:36] 

if ($REQUEST_METHOD == "GET") {
	#
	# Quick orders search
	#
	$go_search = false;
	if (!empty($date) && in_array($date, array("M","W","D"))) {
		$search_data["orders"]["date_period"] = $date;
		$go_search = true;
	}

# START: random:19017 [2009 Sep 14 14:13]
	if (!empty($status) && in_array($status, array('P','C','D','O','F','Q','B','S','not_DCS','R','H'))) {
# END: random:19017 [2009 Sep 14 14:13]
		$search_data["orders"]["status"] = $status;
		$go_search = true;
	}

# START: random:18591_18598 [2009 Jul 29 10:36] 
	if ($user_account["flag"] == "FS" && $go_search) {
		if (empty($search_data["orders"]["status"]) || !in_array($search_data["orders"]["status"], array("C","S", "B", "G"))) {
			$search_data["orders"]["status"] = '';
		}
	}

# END: random:18591_18598 [2009 Jul 29 10:36] 
	if ($go_search)
		func_header_location("orders.php?mode=search");
}

if ($REQUEST_METHOD == "POST" && !$do_export) {
	
	$fast_search_parameter = (!empty($fast_search)) ? '&fast_search=' . $fast_search : '';
	
	#
	# Update the session $search_data variable from $posted_data
	#
	if (!empty($posted_data)) {

		$need_advanced_options = false;
		foreach ($posted_data as $k=>$v) {
			if (!is_array($v) && !is_numeric($v))
				$posted_data[$k] = stripslashes($v);

			if (is_array($v)) {
				$tmp = array();
				foreach ($v as $k1=>$v1) {
					$tmp[$v1] = 1;
				}

				$posted_data[$k] = $tmp;
			}

			if (in_array($k, $advanced_options) && !empty($v))
				$need_advanced_options = true;
		}

		if (!$need_advanced_options)
			$need_advanced_options = (doubleval($posted_data["price_min"]) != 0 || doubleval($posted_data["total_min"]) != 0);

		$posted_data["need_advanced_options"] = $need_advanced_options;

		if ($StartMonth) {
			$posted_data["start_date"] = mktime(0,0,0,$StartMonth,$StartDay,$StartYear);
			$posted_data["end_date"] = mktime(23,59,59,$EndMonth,$EndDay,$EndYear);
		}

		if (empty($search_data["orders"]["sort_field"])) {
			$posted_data["sort_field"] = "orderid";
			$posted_data["sort_direction"] = 1;
		}
		else {
			$posted_data["sort_field"] = $search_data["orders"]["sort_field"];
			$posted_data["sort_direction"] = $search_data["orders"]["sort_direction"];
		}

# START: random:18591_18598 [2009 Jul 29 10:36] 
		if ($user_account["flag"] == "FS") {
			if (empty($posted_data['status']) || !in_array($posted_data['status'], array("C","S",'B','G'))) {
				if (!empty($fast_search_parameter)) {
					$posted_data['status'] = '';
				}
			}
		}

# END: random:18591_18598 [2009 Jul 29 10:36] 
		$search_data["orders"] = $posted_data;

	}

	func_header_location("orders.php?mode=search$fast_search_parameter");
}
elseif ($REQUEST_METHOD == "POST" && $do_export) {
	#
	# Export all orders
	#
	include $xcart_dir."/include/orders_export.php";
}

if ($mode == "search") {
	#
	# Perform search and display results
	#

	$data = array();

	$flag_save = false;

	#
	# Prepare the search data
	#
	if (!empty($sort) && in_array($sort, array("orderid","status","customer","date","provider", "total"))) {
		# Store the sorting type in the session
		$search_data["orders"]["sort_field"] = $sort;
		$search_data["orders"]["sort_direction"] = abs(intval($search_data["orders"]["sort_direction"]) - 1);
		$flag_save = true;
	}

	if (!empty($page) && $search_data["orders"]["page"] != intval($page)) {
		# Store the current page number in the session
		$search_data["orders"]["page"] = $page;
		$flag_save = true;
	}

	if ($flag_save)
		x_session_save("search_data");

	if (is_array($search_data["orders"])) {
		$data = $search_data["orders"];
		foreach ($data as $k=>$v) {
			if (!is_array($v) && !is_numeric($v))
				$data[$k] = addslashes($v);
		}
	}

	$search_condition = "";
	$search_in_order_details = false;
	$search_in_products = false;
	$search_from = array($sql_tbl["orders"]);
	$search_links = array();

# START: random:20341 [2010 Jul 29 14:46] 
//	if ($current_area == 'A') {
	if ($current_area == 'A' && (!empty($search_data["orders"]["cb_status"]) || !empty($search_data["orders"]["dc_status"]) || !empty($search_data["orders"]["bd_status"]))) {
		$search_from[] = $sql_tbl["order_groups"];
		$search_links[] = "$sql_tbl[order_groups].orderid=$sql_tbl[orders].orderid";
	}

# END: random:20341 [2010 Jul 29 14:46] 
	# Search by orderid
	if (!empty($data["orderid1"]))
		$search_condition .= " AND $sql_tbl[orders].orderid>='".intval($data["orderid1"])."'";

	if (!empty($data["orderid2"]))
		$search_condition .= " AND $sql_tbl[orders].orderid<='".intval($data["orderid2"])."'";

	if ($fast_search == 'Y' && !empty($data['orderid'])) {
		$search_condition .= ' AND (CONCAT(' . $sql_tbl['orders'] . '.order_prefix, '
											. $sql_tbl['orders'] . '.orderid) = "'
											. $data['orderid'] . '" OR '
											. $sql_tbl['orders'] . '.orderid = '
											. intval($data['orderid']) . ')';
	}

	# Search by order total
	if (!empty($data["total_min"]) && doubleval($data["total_min"]) != 0)
		$search_condition .= " AND $sql_tbl[orders].total>='".doubleval($data["total_min"])."'";

	if (!empty($data["total_max"]))
		$search_condition .= " AND $sql_tbl[orders].total<='".doubleval($data["total_max"])."'";

	# Search by payment method
	if (!empty($data["payment_method"]))
		$search_condition .= " AND $sql_tbl[orders].payment_method LIKE '".$data["payment_method"]."%'";

	# Search by shipping method
	if (!empty($data["shipping_method"]))
		$search_condition .= " AND $sql_tbl[orders].shippingid='".intval($data["shipping_method"])."'";

	# Search by order status
# START: random:19017 [2009 Sep 14 14:13] 
	if ($current_area == 'A') {
		if (!empty($data['cb_status'])) {
			if (!is_array($data['cb_status'])) {
			$search_condition .= " AND $sql_tbl[order_groups].cb_status='$data[cb_status]'";
			} else {
				$search_condition .= " AND $sql_tbl[order_groups].cb_status IN ('" . implode('\',\'', array_keys($data['cb_status'])) . "')";
			}
			}
        
		if (!empty($data['dc_status'])) {
			if (!is_array($data['dc_status'])) {
			$search_condition .= " AND $sql_tbl[order_groups].dc_status='$data[dc_status]'";
			} else {
				$search_condition .= " AND $sql_tbl[order_groups].dc_status IN ('" . implode('\',\'', array_keys($data['dc_status'])) . "')";
			}
			}
			
		if (!empty($data['bd_status'])) {
			if (!is_array($data['bd_status'])) {
			$search_condition .= " AND $sql_tbl[order_groups].bd_status='$data[bd_status]'";
			} else {
				$search_condition .= " AND $sql_tbl[order_groups].bd_status IN ('" . implode('\',\'', array_keys($data['bd_status'])) . "')";
			}
			}
	}

	if ($user_account["flag"] == "FS" || !empty($data["status"])) {
		if (in_array($data['status'], array('C', 'S', 'B', 'G'))) {
			$search_condition .= " AND $sql_tbl[orders].dc_status='$data[status]'";
		} else {
			$search_condition .= " AND $sql_tbl[orders].dc_status  IN ('C', 'S', 'B', 'G')";
		}
	}
# END: random:19017 [2009 Sep 14 14:13] 

	#
	# Exact search by provider (for provider area and $single_mode = false)
	#
	if (!empty($data["provider_login"])) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].provider='".$data["provider_login"]."'";
	}

	# Search by provider
	if (!empty($data["provider"])) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].provider LIKE '%".$data["provider"]."%'";
	}

	#
	# Search by date condition
	#
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

			$start_date -= $config["Appearance"]["timezone_offset"];
			$end_date = time();
		}

		$search_condition .= " AND " . ($data['placement_time_from_type'] == 'D' ? "$sql_tbl[order_groups].dc_dispatched_time" : "$sql_tbl[orders].date") .  " >='".($start_date)."'";
                $search_condition .= " AND " . ($data['placement_time_to_type'] == 'D' ? "$sql_tbl[order_groups].dc_dispatched_time" : "$sql_tbl[orders].date") . " <='".($end_date)."'";

	}

	#
	# Exact search by customer login (for customers area)
	#
	if (!empty($data["customer_login"]))
		$search_condition .= " AND $sql_tbl[orders].login='".$data["customer_login"]."'";

	#
	# Search by custtomer
	#
	if (!empty($data["customer"]) && (!empty($data['by_username']) || !empty($data['by_firstname']) || !empty($data['by_lastname']))) {
		$condition = array();	
		if (!empty($data['by_username']))
			$condition[] = "$sql_tbl[orders].login LIKE '%".$data["customer"]."%'";
		if (!empty($data['by_firstname']))
			$condition[] = "$sql_tbl[orders].firstname LIKE '%".$data["customer"]."%'";
		if (!empty($data['by_lastname']))
			$condition[] = "$sql_tbl[orders].lastname LIKE '%".$data["customer"]."%'";
		if (preg_match("/^(.+)\s+(.+)$/", $data["customer"], $found) && !empty($data["by_firstname"]) && !empty($data["by_lastname"]))
			$condition[] = "$sql_tbl[orders].firstname LIKE '%".trim($found[1])."%' AND $sql_tbl[orders].lastname LIKE '%".trim($found[2])."%'";

		if (!empty($condition))
			$search_condition .= " AND (".implode(" OR ", $condition).")";
	}

	if (!empty($data["address_type"])) {
		#
		# Search by address...
		#
		if (!empty($data["city"]))
			$address_condition .= " AND $sql_tbl[orders].PREFIX_city LIKE '%".$data["city"]."%'";

		if (!empty($data["state"]))
			$address_condition .= " AND $sql_tbl[orders].PREFIX_state='".$data["state"]."'";

		if (!empty($data["country"]))
			$address_condition .= " AND $sql_tbl[orders].PREFIX_country='".$data["country"]."'";

		if (!empty($data["zipcode"]))
			$address_condition .= " AND $sql_tbl[orders].PREFIX_zipcode LIKE '%".$data["zipcode"]."%'";

		if ($data["address_type"] == "B" || $data["address_type"] == "Both")
			$search_condition .= preg_replace("/AND ".$sql_tbl["orders"]."\.PREFIX_(city|state|country|zipcode)/", "AND ".$sql_tbl["orders"].".b_\\1", $address_condition);

		if ($data["address_type"] == "S" || $data["address_type"] == "Both")
			$search_condition .= preg_replace("/AND ".$sql_tbl["orders"]."\.PREFIX_(city|state|country|zipcode)/", "AND ".$sql_tbl["orders"].".s_\\1", $address_condition);
	}

	# Search by e-mail pattern
	if (!empty($data["email"]))
		$search_condition .= " AND $sql_tbl[orders].email LIKE '%".$data["email"]."%'";

	# Search by phone/fax pattern
	if (!empty($data["phone"]))
		$search_condition .= " AND ($sql_tbl[orders].phone LIKE '%".$data["phone"]."%' OR $sql_tbl[orders].fax LIKE '%".$data["phone"]."%')";

	#
	# Search by special features
	#
	if (!empty($data["features"])) {
		# Search for orders that payed by Gift Certificates
		if (!empty($data["features"]["gc_applied"]))
			$search_condition .= " AND $sql_tbl[orders].giftcert_discount>0";

		# Search for orders with global discount applied
		if (!empty($data["features"]["discount_applied"]))
			$search_condition .= " AND $sql_tbl[orders].discount>0";

		# Sea4rch for orders with discount coupon applied
		if (!empty($data["features"]["coupon_applied"]))
			$search_condition .= " AND $sql_tbl[orders].coupon!=''";

		# Search for orders with free shipping (shipping cost = 0)
		if (!empty($data["features"]["free_ship"]))
			$search_condition .= " AND $sql_tbl[orders].shipping_cost=0";

		# Search for orders with free taxes
		if (!empty($data["features"]["free_tax"]))
			$search_condition .= " AND $sql_tbl[orders].tax=0 ";

		# Search for orders with notes assigned
		if (!empty($data["features"]["notes"]))
			$search_condition .= " AND $sql_tbl[orders].notes!=''";

		# Search for orders with Gift Certificates ordered
		if (!empty($data["features"]["gc_ordered"])) {
			$search_from[] = $sql_tbl["giftcerts"];
			$search_links[] = "$sql_tbl[orders].orderid=$sql_tbl[giftcerts].orderid";
		}
	}

	#
	# Search by ordered products
	#
	if (!empty($data["product_substring"])) {

		$search_in_order_details = true;
		$condition = array();

		# Search by product title
		if (!empty($data["by_title"])) {
			$search_in_products = true;
			$condition[] = "$sql_tbl[products].product LIKE '%".$data["product_substring"]."%'";
		}

		# Search by product options
		if (!empty($data["by_options"])) {
			$search_in_order_details = true;
			$condition[] = "$sql_tbl[order_details].product_options LIKE '%".$data["product_substring"]."%'";
		}

		if (!empty($condition) && is_array($condition)) {
			$search_condition .= " AND (".implode(" OR ", $condition).")";
		}
	}

	# Search by product code (SKU)
	if (!empty($data["productcode"])) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].productcode LIKE '%".$data["productcode"]."%'";
	}

	# Search by product ID
	if (!empty($data["productid"])) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].productid='".$data["productid"]."'";
	}

	#
	# Search by product price range
	#
	if (!empty($data["price_min"]) && doubleval($data["price_min"]) != 0) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].price>='".$data["price_min"]."'";
	}

	if (!empty($data["price_max"])) {
		$search_in_order_details = true;
		$search_condition .= " AND $sql_tbl[order_details].price<='".$data["price_max"]."'";
	}

	$sort_string = "$sql_tbl[orders].orderid DESC";

	if (!empty($data["sort_field"])) {
		# Sort the search results...

		$direction = ($data["sort_direction"] ? "DESC" : "ASC");
		switch ($data["sort_field"]) {
			case "orderid":
				$sort_string = "$sql_tbl[orders].orderid $direction";
				break;
			case "status":
				$sort_string = "$sql_tbl[orders].cb_status $direction";
				break;
			case "customer":
				$sort_string = "$sql_tbl[orders].login $direction";
				break;
			case "provider":
				if (!$single_mode && $search_in_order_details)
					$sort_string = "$sql_tbl[order_details].provider $direction";
				break;
			case "date":
				$sort_string = "$sql_tbl[orders].date $direction";
				break;
			case "total":
				$sort_string = "$sql_tbl[orders].total $direction";
		}
	}

	#
	# Prepare the SQL query
	#
	if ($search_in_order_details) {
		$search_from[] = $sql_tbl["order_details"];
		$search_links[] = "$sql_tbl[orders].orderid=$sql_tbl[order_details].orderid";
		if ($search_in_products) {
			$search_from[] = $sql_tbl["products"];
			$search_links[] = "$sql_tbl[order_details].productid=$sql_tbl[products].productid";
		}

	}

	if (is_array($search_from))
		$search_from = "FROM ".implode(", ", $search_from);

	if (!empty($search_links))
		$search_links = implode(" AND ", $search_links);
	else
		$search_links = "1";




//func_print_r($search_from , $search_links, $search_condition );
//die();



	$search_condition_res = "$search_from WHERE $search_links $search_condition GROUP BY $sql_tbl[orders].orderid ";

	#
	# Count the items in the search results
	#
	$_res = func_query_column("SELECT $sql_tbl[orders].orderid $search_condition_res");
	if (is_array($_res) && !empty($data['manufacturers'])) {
		foreach ($_res as $k => $oid) {
			$suitable = false;
			$shipping_groups = func_get_shipping_groups($oid);
			foreach ($shipping_groups as $mid => $group) {
				if ((!empty($data['manufacturers']) && in_array($mid, array_keys($data['manufacturers'])))) {
					$suitable = true;
				}
			}
			if (!$suitable) {
				unset($_res[$k]);
			}
		}
		unset($shipping_groups);
		$search_condition_res = "$search_from WHERE $search_links $search_condition AND $sql_tbl[orders].orderid IN (" . implode(', ', $_res) . ") GROUP BY $sql_tbl[orders].orderid ";
	}
	$search_condition = $search_condition_res;

	$total_items = (is_array($_res)) ? count($_res) : 0;
	unset($_res);

	if ($filter_preset) {
		return;
	}

	if ($total_items > 0) {
		#
		# Perform the SQL and get the search results
		#
		if ($data['is_export'] == 'Y') {

			func_export_range_save("ORDERS", "SELECT $sql_tbl[orders].orderid $search_condition");
			$top_message['content'] = func_get_langvar_by_name("lbl_export_orders_add");
			$top_message['type'] = 'I';
			func_header_location("import.php?mode=export");
		}
		elseif ($_GET['export'] == 'export_found') {
			# Export all found orders
			$REQUEST_METHOD = "POST";
			$orderids = func_query_column("SELECT $sql_tbl[orders].orderid $search_condition");
			include $xcart_dir."/include/orders_export.php";

		}
		else {
			#
			# If orders do not exports, separate them on the pages
			#
			$page = $search_data["orders"]["page"];

			#
			# Prepare the page navigation
			#
			$objects_per_page = $config["Appearance"]["orders_per_page_admin"];

			$total_nav_pages = ceil($total_items/$objects_per_page)+1;

			include $xcart_dir."/include/navigation.php";

			#
			# Get the results for current pages
			#
			$orders = func_query("SELECT $sql_tbl[orders].* $search_condition ORDER BY $sort_string LIMIT $first_page, $objects_per_page");

			# Assign the Smarty variables
			$smarty->assign("navigation_script","orders.php?mode=search");
			$smarty->assign("first_item", $first_page+1);
			$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
		}

		if ($orders) {
			$manufacturers = array();
			foreach ($orders as $k => $v) {
				
# START: random:20341 [2010 Jul 29 14:46] 
				if ($current_area == 'A') {
					$orders[$k]["shipping_groups"] = func_get_shipping_groups($v["orderid"]);
					$orders[$k]["s_countryname"] = func_get_country($v["s_country"]);
					if (!empty($data["status"])) {
						foreach ($orders[$k]["shipping_groups"] as $osgk => $osgv) {
							if (
                                (
                                    $data['status'] == 'not_DCS' 
                                    && (
                                        in_array($osgv['dc_status'], array('C', 'S'))
                                        || $osgv['cb_status'] == 'D'
                                    )
                                )
                                || (
                                    $data['status'] == 'CS' 
                                    && !in_array($osgv['dc_status'], array('C','S'))
                                ) 
                                || (
                                    $data['status'] != 'not_DCS' 
                                    && $data['status'] != 'CS' 
                                    && $osgv['cb_status'] != $data['status']
                                    && $osgv['dc_status'] != $data['status']
                                    && $osgv['bd_status'] != $data['status']
                                )
                            ) {
								unset($orders[$k]["shipping_groups"][$osgk]); # shipping groups will not became empty because according to query orders will have at least one shipping groups with requested status 
							}
						}	
					}
				}
				
# END: random:20341 [2010 Jul 29 14:46] 
				if (!empty($active_modules['Google_Checkout']))
					$orders[$k]['goid'] = func_query_first_cell("SELECT goid FROM $sql_tbl[gcheckout_orders] WHERE orderid='$v[orderid]'");

				if (!$single_mode)
					$orders[$k]["provider"] = func_query_first_cell("SELECT provider FROM $sql_tbl[order_details] WHERE orderid='$v[orderid]'");

				$orders[$k]["date"] += $config["Appearance"]["timezone_offset"];
				if (!empty($v["add_date"]))
					$orders[$k]["add_date"] += $config["Appearance"]["timezone_offset"];

				if ($current_area != 'C' && $active_modules['Stop_List']) {
					$orders[$k]['blocked'] = func_ip_exist_slist(func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE khash = 'ip' AND orderid = '$v[orderid]'"));
				}
			}
		}


#
##
###
if (!empty($orders) && is_array($orders)){
	foreach ($orders as $k => $v){
		if (empty($v["shipping_groups"])){
			$orders[$k]["shipping_groups"]["empty"] = 0;
		}
	}
}
###
##
#

		$smarty->assign("orders", $orders);
	}

	$smarty->assign("total_items", $total_items);
	$smarty->assign("mode", $mode);
}

include $xcart_dir."/include/states.php";
include $xcart_dir."/include/countries.php";

if (empty($search_data['orders']['end_date'])) {
	$search_data['orders']['end_date'] = $search_data['orders']['start_date'] = time() + $config["Appearance"]["timezone_offset"];
}

//$smarty->assign("search_prefilled", $search_data["orders"]);

$payment_methods = func_query("SELECT payment_method FROM $sql_tbl[payment_methods] ORDER BY payment_method");
$smarty->assign("payment_methods", $payment_methods);

$shipping_methods = func_query("SELECT shippingid, shipping FROM $sql_tbl[shipping] WHERE active='Y' ORDER BY code, shipping");
$smarty->assign("shipping_methods", $shipping_methods);

$smarty->assign("orders_full", @$orders_full);

$smarty->assign("single_mode", $single_mode);

$smarty->assign("start_date",$start_date);
$smarty->assign("end_date",$end_date);
$smarty->assign("main","orders");

if ($fast_search == 'Y' && $total_items == 1 && isset($orders)) {
	func_header_location('order.php?orderid=' . $orders[0]['orderid']);
}

?>
