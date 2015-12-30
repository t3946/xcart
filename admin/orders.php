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
x_session_register("order_page_title");
x_session_register("order_search_condition");
x_session_register("no_orders_test_checkout_hide_time");

#
##
###
if ($REQUEST_METHOD == 'POST'){

#
## https://basecamp.com/2070980/projects/1577907/messages/46647624
### Start: Cart number feature
/*
	if ($mode == "search_cart"){

		if (!empty($cart_number)){

			$customers_cart = "";

			$cart_number_info = func_query_first_cell("SELECT cart FROM $sql_tbl[customers] WHERE cart_number='$cart_number'");
			$cart_number_info = unserialize(stripslashes($cart_number_info));
			if (!empty($cart_number_info["products"])){
				$customers_cart = $cart_number_info;
			}

			if (empty($customers_cart)){
				$cart_number_info = func_query_first_cell("SELECT data FROM $sql_tbl[sessions_data] WHERE cart_number='$cart_number'");
				$cart_number_info = unserialize(stripslashes($cart_number_info));

				if (!empty($cart_number_info["cart"]["products"])){
					$customers_cart = $cart_number_info["cart"];
				}
			}

			if (!empty($customers_cart)){
				func_header_location("customers_cart.php?cart_number=$cart_number");
			}
		}

                func_header_location("orders.php?page_name=dashboard");
        }
*/
###
## End: Cart number feature
#

	if ($mode == "hide_no_orders_test_checkout_message"){
		$no_orders_test_checkout_hide_time = time();
		x_session_save("no_orders_test_checkout_hide_time");

                $log_text = $userfullname . " (" . $login . ") clicked 'Done'.";
                func_backprocess_log("Test_checkout", $log_text);
		func_header_location("orders.php?page_name=dashboard");
	}

        if ($mode == "add_inquiry"){ 

	        $top_message["content"] = 'Done.';
        	$top_message["type"] = "I";

                $add_inq_subject = trim($add_inq_subject);
                if(!empty($add_inq_subject) && !empty($add_inq_type_id)){
                        db_query("INSERT INTO $sql_tbl[inquiries] (inq_type_id, inq_subject, datetime, createdby_login) VALUES ('$add_inq_type_id', '$add_inq_subject', '".time()."', '$login')");

			$inq_id = db_insert_id();

			$inquiry_type = func_query_first_cell("SELECT inquiry_type FROM $sql_tbl[inquiry_types] WHERE inq_type_id='$add_inq_type_id'");
			$inq_id_edited = sprintf('%1$05d', $inq_id);

			$subject = "INQ-".$inq_id_edited.": ".$inquiry_type." by ".$userfirstname;
			$body = $add_inq_subject;

		        $to = "inquiries_internal@s3stores.com";
			$from = "xcart@s3stores.com";
		        func_send_simple_mail($to, $subject, $body, $from);
                } else {
                        $top_message["content"] = 'Not added.';
                        $top_message["type"] = "E";
                }

	        func_header_location("orders.php?page_name=dashboard");
        }
}

$inquiry_types = func_query("SELECT * FROM $sql_tbl[inquiry_types] WHERE active='Y' ORDER BY inquiry_type");

$inquiry_attn_tags = func_query("SELECT $sql_tbl[inquiries_attention_tags].*, COUNT($sql_tbl[inquiries].inq_type_id) as count FROM $sql_tbl[inquiries_attention_tags] LEFT JOIN $sql_tbl[inquirires_tags] ON $sql_tbl[inquirires_tags].inq_tag_id=$sql_tbl[inquiries_attention_tags].inq_tag_id LEFT JOIN $sql_tbl[inquiries] ON $sql_tbl[inquiries].inq_id=$sql_tbl[inquirires_tags].inq_id GROUP BY $sql_tbl[inquiries_attention_tags].inq_tag_id ORDER BY inquiry_attn_tag");

if (!empty($inquiry_attn_tags)){
	$flag_delete_tag = false;
	foreach ($inquiry_attn_tags as $k => $v){
		if ($v["count"] == 0){
			unset($inquiry_attn_tags[$k]);
			$flag_delete_tag = true;
		}
	}

	if ($flag_delete_tag && !empty($inquiry_attn_tags)){
		$inquiry_attn_tags = array_values($inquiry_attn_tags);
	}
}

$inquiries = func_query("SELECT $sql_tbl[inquiry_types].*, COUNT($sql_tbl[inquiries].inq_type_id) as count FROM $sql_tbl[inquiry_types] LEFT JOIN $sql_tbl[inquiries] ON $sql_tbl[inquiry_types].inq_type_id=$sql_tbl[inquiries].inq_type_id WHERE $sql_tbl[inquiries].status='O' AND $sql_tbl[inquiry_types].active='Y' GROUP BY $sql_tbl[inquiry_types].inq_type_id ORDER BY $sql_tbl[inquiry_types].inquiry_type");

//func_print_r($inquiry_attn_tags);
//func_print_r($inquiries);
$smarty->assign("inquiry_types", $inquiry_types);
$smarty->assign("inquiry_attn_tags", $inquiry_attn_tags);
$smarty->assign("inquiries", $inquiries);
###
##
#

#
##
###
$product_questions = func_query("SELECT $sql_tbl[product_question].status, COUNT($sql_tbl[product_question].id) as count FROM $sql_tbl[product_question] GROUP BY $sql_tbl[product_question].status ORDER BY $sql_tbl[product_question].status");
if (!empty($product_questions) && !empty($product_question_statuses)){

	$product_questions_arr = array();
	foreach ($product_question_statuses as $k => $v){
		foreach ($product_questions as $kk => $vv){
			if ($k == $vv["status"]){
				$product_questions_arr[] = $vv;
			}
		}
	}

//func_print_r($product_questions);

	$smarty->assign("product_questions", $product_questions_arr);
}
###
##
#

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

#
##
###
foreach ($fraud_statuses as $k => $v){
	$fraud_statuses_filter[$k]["name"] = $v;

        if (!empty($search_data["order_reports"]["fraud_statuses"])) {
                foreach ($search_data["order_reports"]["fraud_statuses"] as $key) {
                        $fraud_statuses_filter[$key]["selected"] = true;
                }
        }
}
$smarty->assign('fraud_statuses_filter', $fraud_statuses_filter);


foreach ($attention_tags_values as $k => $v){
        $attention_tags_values_filter[$k]["name"] = $v;

        if (!empty($search_data["order_reports"]["attention_tags_values"])) {
                foreach ($search_data["order_reports"]["attention_tags_values"] as $key) {
                        $attention_tags_values_filter[$key]["selected"] = true;
                }
        }
}
$smarty->assign('attention_tags_values_filter', $attention_tags_values_filter);
###
##
#

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

/*
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
*/

# END: random:20341 [2010 Jul 29 14:46] 
}

// Build filter presets
if (empty($total_items)) {

	$fid_total_items = array();

	$filters = array();
	$_tmp = array();
	$find = false;
	$_filters = func_query("SELECT fid, title, bold, enabled FROM $sql_tbl[filter_presets]");
	$filter_preset = true;
	$c = count($_filters);
	for ($_i = 0; $_i < $c; $_i++) {
		if ($_i % FILTER_PRESET_PER_ROW == 0 && $_i != 0) {
			if ($find) {
				$find = false;
				$filters[] = $_tmp;
			}
			$_tmp = array();
		}
		if ($_filters[$_i]['enabled'] == 'Y' && !empty($_filters[$_i]['title'])) {
			// Calculate order counts from filter
			$fid = $_filters[$_i]['fid'];
			include $xcart_dir."/include/orders.php";
			$_filters[$_i]['count'] = $total_items;
			$fid_total_items[$fid] = $total_items;
			$_tmp[] = $_filters[$_i];
			$find = true;
		} else {
			$_tmp[] = array();
		}
	}
	if ($find) {
                $filters[] = $_tmp;
        }

	$filter_preset = false;



#
##
###
    $_filters_sorted = func_query("SELECT * FROM $sql_tbl[filter_presets] ORDER BY preset_position, fid");

    foreach ($_filters_sorted as $k => $filter) {
     if (empty($filter["preset_position"])){

        $row = ceil($filter['fid'] / FILTER_PRESET_PER_ROW);
        $column = intval($filter['fid'] - ($row - 1) * FILTER_PRESET_PER_ROW);

        $preset_position = $row.",".$column;

        $used_preset_position_fid = func_query_first_cell("SELECT fid FROM $sql_tbl[filter_presets] WHERE preset_position='$preset_position' AND fid!='$filter[fid]'");

        if (!empty($used_preset_position_fid)){

                $preset_position = "";
                $found_empty_preset_position = false;

                $count_filter_presets = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets]");
                $count_rows_in_filter_presets = ceil($count_filter_presets/FILTER_PRESET_PER_ROW);

                for ($i=FILTER_PRESET_PER_ROW; $i>=1; $i--){
                        for ($j=$count_rows_in_filter_presets; $j>=1; $j--){
                                $tmp_preset_position = $j.",".$i;
                                $is_such_preset_position = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets] WHERE preset_position='$tmp_preset_position'");
                                if (empty($is_such_preset_position) || $is_such_preset_position == 0){
                                        $preset_position = $tmp_preset_position;
                                        $found_empty_preset_position = true;
                                        $row = $j;
                                        $column = $i;
                                        break;
                                }
                        }

                        if ($found_empty_preset_position){
                                break;
                        }
                }
        }

        db_query("UPDATE $sql_tbl[filter_presets] SET preset_position='$preset_position' WHERE fid='$filter[fid]'");

        $_filters_sorted[$k]["preset_position"] = $preset_position;
     }

     if (!empty($_filters_sorted[$k]["preset_position"])){
        $preset_position_arr = explode(",", $_filters_sorted[$k]["preset_position"]);
        $_filters_sorted[$k]["row"] = $preset_position_arr[0];
        $_filters_sorted[$k]["column"] = $preset_position_arr[1];
     }
    }

    $filters_sorted = array();
    foreach ($_filters_sorted as $k => $v) {
	if (!empty($v["marker"])){
		$markers = array();
		$marker_arr = explode(",", $v["marker"]);
		foreach ($marker_arr as $km => $vm){
			$vm_arr = explode(":", $vm);
			$markers[$km]["title"] = trim($vm_arr[0]);
			$markers[$km]["code"] = trim($vm_arr[1]);
		}
		$v["marker_arr"] = $markers;
	}

        $filters_sorted[$v["row"]][$v["column"]] = $v;
    }

    ksort($filters_sorted);

    foreach ($filters_sorted as $k => $v) {
        ksort($v);
        $filters_sorted[$k] = $v;
    }

//    if (!empty($fid_total_items)){
	    foreach ($filters_sorted as $k_fs => $v_fs){
		foreach ($v_fs as $kk_fs => $vv_fs){
			if (!empty($fid_total_items[$vv_fs["fid"]]) && $fid_total_items[$vv_fs["fid"]] >= 0){
				$filters_sorted[$k_fs][$kk_fs]["count"] = $fid_total_items[$vv_fs["fid"]];
			} else {
//				$filters_sorted[$k_fs][$kk_fs] = array();
			}
		}
	    }
//    }

    $filters = $filters_sorted;
###
##
#

//func_print_r($filters);


	$smarty->assign('filters', $filters);

	$order_page_title = "";
	x_session_save("order_page_title");
} else {
	if (!empty($fid)){
		$order_page_title = func_query_first_cell("SELECT title FROM $sql_tbl[filter_presets] WHERE fid='$fid'");
		x_session_save("order_page_title");
	}
}

$smarty->assign('order_page_title', $order_page_title);

if ($page_name == "export"){
	include_once $xcart_dir."/include/import_tools.php";
}


#
##
###
if ($page_name=="dashboard"){
	$last_order_date = func_query_first("SELECT xcart_orders.orderid, xcart_orders.date FROM xcart_orders LEFT JOIN xcart_order_groups ON xcart_order_groups.orderid=xcart_orders.orderid WHERE xcart_order_groups.cb_status='P' ORDER BY orderid DESC limit 1");

	$diff_order_time = time() - $last_order_date["date"];
	$no_orders_test_checkout_sec = $config["Logging"]["no_orders_test_checkout"] * 60;
	$diff_order_time = $diff_order_time;
	$show_no_orders_test_checkout_message = "N";

	if ($diff_order_time > $no_orders_test_checkout_sec){

		if (!empty($no_orders_test_checkout_hide_time)){
			$diff_no_orders_test_checkout_hide_time = time() - $no_orders_test_checkout_hide_time;
			$show_message_in_time = 60*60;

			if ($diff_no_orders_test_checkout_hide_time > $show_message_in_time){
				$show_no_orders_test_checkout_message = "Y";
				$no_orders_test_checkout_hide_time = "";
				x_session_save("no_orders_test_checkout_hide_time");
			}
			else {
				$show_no_orders_test_checkout_message = "N";
			}
		}
		else {
			$show_no_orders_test_checkout_message = "Y";
		}

		if ($show_no_orders_test_checkout_message == "Y"){
			$log_text = $userfullname . " (" . $login . ") has seen 'Test checkout' notification.";
			func_backprocess_log("Test_checkout", $log_text);
		}
	}

}
$smarty->assign('show_no_orders_test_checkout_message', $show_no_orders_test_checkout_message);
###
##
#


# Assign the current location line
$smarty->assign("page_name", $page_name);
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
