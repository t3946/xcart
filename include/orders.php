<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (��������� ��� ������ UPS + ��������� � ������ ����� Tracking numbers ��� �������)] */ ?>
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
    

#
##
###
/*
        if (!empty($filter['CA'])) {
                $search_data['orders']['ca_status'] = $filter['CA'];
        }
*/

        if (!empty($filter['PO'])) {
                $search_data['orders']['po_status'] = $filter['PO'];
        }

        if (!empty($filter['product_question_statuses'])) {
                $search_data['orders']['product_question_statuses'] = $filter['product_question_statuses'];
        }

        if (!empty($filter['storefront_ids'])) {
                $search_data['orders']['storefront_ids'] = $filter['storefront_ids'];
        }

        if (!empty($filter['fraud_statuses'])) {
                $search_data['orders']['fraud_statuses'] = $filter['fraud_statuses'];
        }

        if (!empty($filter['orders_source'])) {
                $search_data['orders']['orders_source'] = $filter['orders_source'];
        }

//func_print_r($fid, $filter['attention_tags_values']);

        if (!empty($filter['attention_tags_values'])) {
                $search_data['orders']['attention_tags_values'] = $filter['attention_tags_values'];
        }

        if (!empty($filter['ship_to_countries'])) {
                $search_data['orders']['ship_to_countries'] = $filter['ship_to_countries'];
        }
###
##
#

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

#
##
###
	if (empty($search_data['orders']['date_period'])){
		$pre_date_period = "7";
		$smarty->assign("pre_date_period", $pre_date_period);
	}

	$search_data['orders']['processor_empty'] = $filter['processor_empty'];;
###
##
#
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
$advanced_options = array("orderid1", "orderid2", "total_max", "payment_method", "shipping_method", "provider", "features", "product_substring", "productcode", "productid", "price_max", "customer", "address_type", "phone", "email", "s_zipcode", "orderid", "po_number");
# END: random:18591_18598 [2009 Jul 29 10:36] 

if ($REQUEST_METHOD == "GET") {
	#
	# Quick orders search
	#
	$go_search = false;
	if (!empty($date) && in_array($date, array("M","W","D", "7", "31"))) {
		if ($current_area != 'C') {
			$search_data["orders"] = array();
		}
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
	if ($go_search){
		func_header_location("orders.php?mode=search");
	}
}

if (($REQUEST_METHOD == "POST" && !$do_export) || ($_GET["fast_search"] == "Y" && !empty($posted_data["s_zipcode"]))) {
	
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

/*
		if ($StartMonth) {
			$posted_data["start_date"] = mktime(0,0,0,$StartMonth,$StartDay,$StartYear);
			$posted_data["end_date"] = mktime(23,59,59,$EndMonth,$EndDay,$EndYear);
		}
*/

#
##
###
                if (!empty($posted_data["start_date"]) && !empty($posted_data["end_date"])){
                        $start_date_arr = explode("/", $posted_data["start_date"]);
                        $posted_data["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);

                        $end_date_arr = explode("/", $posted_data["end_date"]);
                        $posted_data["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
                }
###
##
#


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
	if ($current_area == 'A' && (!empty($search_data["orders"]["cb_status"]) || !empty($search_data["orders"]["dc_status"]) || !empty($search_data["orders"]["bd_status"]) || !empty($search_data["orders"]["po_status"]) || !empty($search_data["orders"]["processor_empty"]))) {
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

		if (substr_count($data['orderid'],'-')==2){
			$search_condition .= " AND $sql_tbl[orders].amazonorderid='".addslashes($data['orderid'])."' ";
		} else {

# https://basecamp.com/2070980/projects/1577907/messages/53725423
## Dropping XPY- when doing Order # search 
###
			if (strpos($data['orderid'], "XPY-") !== false){
				$data['orderid'] = str_replace("XPY-","",$data['orderid']);
			}
###
##
#

			$search_condition .= ' AND (CONCAT(' . $sql_tbl['orders'] . '.order_prefix, '
											. $sql_tbl['orders'] . '.orderid) = "'
											. $data['orderid'] . '" OR '
											. $sql_tbl['orders'] . '.orderid = '
											. intval($data['orderid']) . ')';
		}
	}

//        if ($fast_search == 'Y' && !empty($data['s_zipcode'])) {
        if (!empty($data['s_zipcode'])) {
		$data['s_zipcode_without_nbsp'] = str_replace(" ", "", $data['s_zipcode']);

                $new_s_zipcode = $data['s_zipcode_without_nbsp'];
                $strlen_new_s_zipcode = strlen($new_s_zipcode);

                $currect_s_zipcode = "";
                for ($i=0; $i<$strlen_new_s_zipcode; $i++){
	                $currect_s_zipcode .= $new_s_zipcode{$i};
                        if ($i == 2){
        	                $currect_s_zipcode .= " ";
                        }
                }
                $data['s_zipcode_with_nbsp'] = $currect_s_zipcode;


		$search_condition .= " AND ($sql_tbl[orders].s_zipcode='".addslashes($data['s_zipcode'])."' OR $sql_tbl[orders].s_zipcode='".addslashes($data['s_zipcode_without_nbsp'])."' OR $sql_tbl[orders].s_zipcode='".addslashes($data['s_zipcode_with_nbsp'])."') ";
	}

        if (!empty($data['po_number'])) {
                $search_condition .= " AND $sql_tbl[orders].po_number='".addslashes($data['po_number'])."' ";
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

#
##
###
		if (!empty($data['po_status'])) {
			if (!is_array($data['po_status'])) {
				$search_condition .= " AND $sql_tbl[order_groups].po_status='$data[po_status]'";
			} else {
				$search_condition .= " AND $sql_tbl[order_groups].po_status IN ('" . implode('\',\'', array_keys($data['po_status'])) . "')";
			}
		}

/*
                if (!empty($data['ca_status'])) {
                        if (!is_array($data['ca_status'])) {
	                        $search_condition .= " AND $sql_tbl[orders].ca_status='$data[ca_status]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders].ca_status IN ('" . implode('\',\'', array_keys($data['ca_status'])) . "')";
                        }
                }


                if (!empty($data['ca_status'])) {
                        if (!is_array($data['ca_status'])) {
                                $search_condition .= " AND $sql_tbl[orders].ca_status='$data[ca_status]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders].ca_status IN ('" . implode('\',\'', array_keys($data['ca_status'])) . "')";
                        }
                }
*/

                if (!empty($data['fraud_statuses'])) {
                        if (!is_array($data['fraud_statuses'])) {
                                $search_condition .= " AND $sql_tbl[orders].fraud_status='$data[fraud_statuses]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders].fraud_status IN ('" . implode('\',\'', array_keys($data['fraud_statuses'])) . "')";
                        }
                }

#
##
	        if (!empty($data['orders_source'])) {
        	       if ($data['orders_source'] == "xcart_orders_only"){
                	       $search_condition .= " AND $sql_tbl[orders].amazonorderid=''";
	               }
	               elseif ($data['orders_source'] == "amazon_orders_only"){
	                      $search_condition .= " AND $sql_tbl[orders].amazonorderid!=''";
	               }
	               elseif ($data['orders_source'] == "amazon_orders_MFN"){
	                      $search_condition .= " AND $sql_tbl[orders].amazon_fulfillment_channel='MFN'";
	               }
	               elseif ($data['orders_source'] == "amazon_orders_FBA"){
	                      $search_condition .= " AND $sql_tbl[orders].amazon_fulfillment_channel='AFN'";
	               }
	        }
##
#

###
		$search_in_product_question = false;
                if (!empty($data['product_question_statuses'])) {

			$search_in_product_question = true;

                        if (!is_array($data['product_question_statuses'])) {
                                $search_condition .= " AND $sql_tbl[product_question].status='$data[product_question_statuses]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[product_question].status IN ('" . implode('\',\'', array_keys($data['product_question_statuses'])) . "')";
                        }
                }

                if (!empty($data['storefront_ids'])) {

                        if (!is_array($data['storefront_ids'])) {
                                $search_condition .= " AND $sql_tbl[orders].storefrontid='$data[storefront_ids]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders].storefrontid IN ('" . implode('\',\'', array_keys($data['storefront_ids'])) . "')";
                        }
                }
###
                if (!empty($data['orders_source'])) {
			if ($data['orders_source'] == "xcart_orders_only"){
                                $search_condition .= " AND $sql_tbl[orders].amazonorderid=''";
			}
			elseif ($data['orders_source'] == "amazon_orders_only"){
                                $search_condition .= " AND $sql_tbl[orders].amazonorderid!=''";
                        }
                }

		$search_in_orders_additional_tags = false;
                if (!empty($data['attention_tags_values'])) {



//func_print_r($data);
//die();

			$search_in_orders_additional_tags = true;

                        if (!is_array($data['attention_tags_values'])) {
                                $search_condition .= " AND $sql_tbl[orders_additional_tags].status_id='$data[attention_tags_values]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders_additional_tags].status_id IN ('" . implode('\',\'', array_keys($data['attention_tags_values'])) . "')";
                        }
                }

                if (!empty($data['ship_to_countries'])) {
                        if (!is_array($data['ship_to_countries'])) {
                                $search_condition .= " AND $sql_tbl[orders].s_country='$data[ship_to_countries]'";
                        } else {
                              $search_condition .= " AND $sql_tbl[orders].s_country IN ('" . implode('\',\'', array_keys($data['ship_to_countries'])) . "')";
                        }
                }

                if (!empty($data['processor_empty'])) {
                        if ($data['processor_empty'] == "Y") {
                                $search_condition .= " AND $sql_tbl[order_groups].acc_paymentid=''";
                        } else {
                                $search_condition .= " AND $sql_tbl[order_groups].acc_paymentid!=''";
                        }
                }
###
##
#


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
			elseif ($data["date_period"] == "7") {
				$start_date = time() - 60*60*24*7;
			}
                        elseif ($data["date_period"] == "31") {
                                $start_date = time() - 60*60*24*31;
                        }       

			$start_date -= $config["Appearance"]["timezone_offset"];
			$end_date = time();
		}


		if ($data['placement_time_from_type'] == 'D'){
			$placement_time_from_type_cond = "$sql_tbl[order_groups].dc_dispatched_time";
		}
		elseif ($data['placement_time_from_type'] == 'R'){
			$placement_time_from_type_cond = "$sql_tbl[order_groups].dc_received_by_distributor_time";
		}
		elseif ($data['placement_time_from_type'] == 'M'){
			$placement_time_from_type_cond = "IFNULL($sql_tbl[order_groups].dc_dispatched_time, $sql_tbl[order_groups].dc_received_by_distributor_time)";
		}
		else {
			$placement_time_from_type_cond = "$sql_tbl[orders].date";
		}


                if ($data['placement_time_to_type'] == 'D'){
                        $placement_time_to_type_cond = "$sql_tbl[order_groups].dc_dispatched_time";
                }
                elseif ($data['placement_time_to_type'] == 'R'){
                        $placement_time_to_type_cond = "$sql_tbl[order_groups].dc_received_by_distributor_time";
                }
                elseif ($data['placement_time_to_type'] == 'M'){
                        $placement_time_to_type_cond = "IFNULL($sql_tbl[order_groups].dc_dispatched_time, $sql_tbl[order_groups].dc_received_by_distributor_time)";
                }
                else {
                        $placement_time_to_type_cond = "$sql_tbl[orders].date";
                }


//		$search_condition .= " AND " . ($data['placement_time_from_type'] == 'D' ? "$sql_tbl[order_groups].dc_dispatched_time" : "$sql_tbl[orders].date") .  " >='".($start_date)."'";
//                $search_condition .= " AND " . ($data['placement_time_to_type'] == 'D' ? "$sql_tbl[order_groups].dc_dispatched_time" : "$sql_tbl[orders].date") . " <='".($end_date)."'";

                $search_condition .= " AND " . $placement_time_from_type_cond .  " >='".($start_date)."'";
                $search_condition .= " AND " . $placement_time_to_type_cond . " <='".($end_date)."'";

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


	if ($search_in_orders_additional_tags) {
                $search_from[] = $sql_tbl["orders_additional_tags"];
                $search_links[] = "$sql_tbl[orders].orderid=$sql_tbl[orders_additional_tags].orderid";
	}

        if ($search_in_product_question) {
                $search_from[] = $sql_tbl["product_question"];
                $search_links[] = "$sql_tbl[orders].product_question_status_id=$sql_tbl[product_question].id";
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

		if (!empty($_res) && is_array($_res)){
			$search_condition_res = "$search_from WHERE $search_links $search_condition AND $sql_tbl[orders].orderid IN (" . implode(', ', $_res) . ") GROUP BY $sql_tbl[orders].orderid ";
		} else {
			$search_condition_res = "$search_from WHERE $search_links $search_condition GROUP BY $sql_tbl[orders].orderid ";
		}
	}
	$search_condition = $search_condition_res;

	$total_items = (is_array($_res)) ? count($_res) : 0;
	unset($_res);

	if ($filter_preset) {
		return;
	}


	$order_search_condition = $search_condition;
	x_session_save("order_search_condition");


	if ($total_items > 0) {

//func_print_r($search_condition);


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

		$orders[$k]["attention_tags"] = func_query("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid=$v[orderid]");

		if (empty($v["shipping_groups"])){
			$orders[$k]["shipping_groups"]["empty"] = 0;
		} elseif (!empty($v["shipping_groups"]) && is_array($v["shipping_groups"])) {

			$all_codes = array();
			foreach ($v["shipping_groups"] as $kk => $vv){
###
				$invoices = func_query_hash("SELECT * FROM $sql_tbl[order_group_invoices] WHERE orderid='$v[orderid]' AND manufacturerid='$kk'","invoice_number",false);
				if (!empty($invoices)){
					$orders[$k]["shipping_groups"][$kk]["invoices"] = $invoices;
				}

				$memos = func_query_hash("SELECT * FROM $sql_tbl[order_group_memos] WHERE orderid='$v[orderid]' AND manufacturerid='$kk'","memo_number",false);
				if (!empty($memos)){
					$orders[$k]["shipping_groups"][$kk]["memos"] = $memos;
				}
###
				$all_codes[] = $vv["code"];
				$last_manufacturer_id = $kk;
			}
			$all_codes = array_unique($all_codes);
			$orders[$k]["all_codes"] = $all_codes;
			unset($all_codes);
		}

		$orders[$k]["last_manufacturer_id"] = $last_manufacturer_id;

		$last_customer_service_message = func_query_first_cell("SELECT log FROM $sql_tbl[order_logs] WHERE orderid='$v[orderid]' AND type='S' ORDER BY date DESC");

		$orders[$k]["last_customer_service_message"] = strip_tags($last_customer_service_message);

#
##
###
		$date1 = new DateTime("now");
		$date2 = new DateTime("@$v[date]");
		$interval = $date2->diff($date1);

		$years = $interval->format("%y");
		$months = $interval->format("%m");
		$days = $interval->format("%d");
		$hours = $interval->format("%h");
		$mins = $interval->format("%i");

		$order_age_str = "";

		if ($years != 0){
			$order_age_str .= $years." years, ";
		}

		if ($months != 0){
			$order_age_str .= $months." months, ";
		}

                if ($days != 0){
                        $order_age_str .= $days." days, ";
                }

                $order_age_str .= sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";

		$orders[$k]["order_age_str"] = $order_age_str;
###
##
#

		$order_age = time() - $v["date"];

		$order_age_days = $order_age / (60*60*24);
		$order_age_days_intval = intval($order_age_days);

		$order_age_hours = ($order_age_days - $order_age_days_intval) * 24;
		$order_age_hours_intval = intval($order_age_hours);

		$order_age_mins_intval = intval(($order_age_hours - $order_age_hours_intval) * 60);
		if (strlen($order_age_mins_intval) == "1"){
			$order_age_mins_intval .= "0";
		}

		$order_age_arr["days"] = $order_age_days_intval;
		$order_age_arr["hours"] = $order_age_hours_intval;
		$order_age_arr["mins"] = $order_age_mins_intval;

		$orders[$k]["order_age_arr"] = $order_age_arr;


		$last_activity = func_query_first_cell("SELECT date FROM $sql_tbl[order_logs] WHERE orderid='$v[orderid]' ORDER BY date DESC");

#
##
###
		if (empty($last_activity)){
			$last_activity = time();
		}

                $date1 = new DateTime("now");
                $date2 = new DateTime("@$last_activity");
                $interval = $date2->diff($date1);

                $years = $interval->format("%y");
                $months = $interval->format("%m");
                $days = $interval->format("%d");
                $hours = $interval->format("%h");
                $mins = $interval->format("%i");

                $last_activity_age_str = "";

                if ($years != 0){
                        $last_activity_age_str .= $years." years, ";
                }

                if ($months != 0){
                        $last_activity_age_str .= $months." months, ";
                }

                if ($days != 0){
                        $last_activity_age_str .= $days." days, ";
                }

                $last_activity_age_str .= sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";

                $orders[$k]["last_activity_age_str"] = $last_activity_age_str;
###
##
#


                $last_activity_age = time() - $last_activity;

		$last_activity_age_days = $last_activity_age / (60*60*24);
		$last_activity_age_days_intval = intval($last_activity_age_days);

		$last_activity_age_hours = ($last_activity_age_days - $last_activity_age_days_intval) * 24;
		$last_activity_age_hours_intval = intval($last_activity_age_hours);

		$last_activity_age_mins_intval = intval(($last_activity_age_hours - $last_activity_age_hours_intval) * 60);
//		$last_activity_age_mins_intval = intval(($last_activity_age_mins_intval/100)*60);
		if (strlen($last_activity_age_mins_intval) == "1"){
			$last_activity_age_mins_intval .= "0";
		}

                $last_activity_age_arr["days"] = $last_activity_age_days_intval;
		$last_activity_age_arr["hours"] = $last_activity_age_hours_intval;
		$last_activity_age_arr["mins"] = $last_activity_age_mins_intval;

                $orders[$k]["last_activity_age_arr"] = $last_activity_age_arr;


		$all_eta_date_mm_dd_yyyy = func_query("SELECT $sql_tbl[products].eta_date_mm_dd_yyyy FROM $sql_tbl[products] LEFT JOIN $sql_tbl[order_details] ON $sql_tbl[order_details].productid = $sql_tbl[products].productid WHERE $sql_tbl[order_details].orderid='$v[orderid]' AND $sql_tbl[products].eta_date_mm_dd_yyyy!=''");

		if (!empty($all_eta_date_mm_dd_yyyy) && is_array($all_eta_date_mm_dd_yyyy)){

			$max_eta = 0;

			foreach ($all_eta_date_mm_dd_yyyy as $k_e => $v_e){
				if ($v_e["eta_date_mm_dd_yyyy"]==""){
					$v_e["eta_date_mm_dd_yyyy"] = 0;
				}

//				$tmp_eta_date_mm_dd_yyyy_arr = explode("/", $v_e["eta_date_mm_dd_yyyy"]);
//				$tmp_mktime = mktime(0, 0, 0, $tmp_eta_date_mm_dd_yyyy_arr[0], $tmp_eta_date_mm_dd_yyyy_arr[1], $tmp_eta_date_mm_dd_yyyy_arr[2]);
				$tmp_mktime = $v_e["eta_date_mm_dd_yyyy"];

				if ($tmp_mktime > $max_eta){
					$max_eta = $tmp_mktime;
//					$orders[$k]["max_eta"] = $v_e["eta_date_mm_dd_yyyy"];
					$orders[$k]["max_eta"] = func_convert_date_mm_dd_yyyy($v_e["eta_date_mm_dd_yyyy"], "m/d/Y");

/*
					$diff_time = $max_eta - time();
					$diff_time /= (60*60*24);

					if ($diff_time < 5){
						$orders[$k]["max_eta_pinkcolor"] = "Y";
					}
*/
				}
			}

			if ($max_eta > 0){

				$eta_date_x = $max_eta - ($config["backorder_decision_request"]["backorder_eta_date_x"]*60*60*24);
				$eta_date_y = $max_eta + ($config["backorder_decision_request"]["backorder_eta_date_y"]*60*60*24);

				if (time() < $eta_date_x){
					$orders[$k]["max_eta_color"] = "blue";
				}

                                if ($eta_date_x < time() && time() < $eta_date_y){
                                        $orders[$k]["max_eta_color"] = "pink";
                                }

                                if (time() > $eta_date_y){
                                        $orders[$k]["max_eta_color"] = "do_not_show";
                                }
			}
		}
	}
}
###
##
#

//func_print_r($orders);

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

//func_print_r($search_data["orders"]);
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
