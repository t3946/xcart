<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (��������� ��� ������ UPS + ��������� � ������ ����� Tracking numbers ��� �������)] */ ?>
<?php /* MODIFIED: random:19778 [2009 Nov 26 09:45][Custom development (�������������� ������� ��������)] */ ?>
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
# $Id: history_order.php,v 1.32.2.3 2006/10/05 06:03:10 max Exp $
#
# Collect infos about ordered products
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('order');

if (empty($mode)) $mode = "";

if ('xpdf_invoice' == $mode) {

    if ($active_modules['XPDF']) {
        $orders = explode(',', $orderid);
        xpdf_get_invoices($orders);
    }

    func_page_not_found($current_area);

} else

if ($mode == "invoice" or $mode == "label") {
	header("Content-Type: text/html");
	header("Content-Disposition: inline; filename=invoice.txt");

	$orders = explode(",", $orderid);

	if ($orders) {
		$orders_data = array();
		foreach ($orders as $orderid) {
			$order_data = func_order_data($orderid);
			if (empty($order_data))
				continue;

		        $order_data['shipping_groups'] = func_get_shipping_groups($orderid);
	                $tracking_links = func_query_hash("SELECT * FROM $sql_tbl[tracking_links]", 'linkid', false);
			$tracking_links_carrier = func_query_hash("SELECT * FROM $sql_tbl[tracking_links_carrier] ORDER BY orderby", 'carrier_id', false);

			#
			# Security check if order owned by another customer
			#
			if ($current_area == 'C' && $order_data["userinfo"]["login"] != $login) {
				func_header_location("error_message.php?access_denied&id=34");
			}

# START: random:18591_18598 [2009 Jul 29 10:36] 
# START: random:20341 [2010 Jul 29 14:46] 
/*
# END: random:20341 [2010 Jul 29 14:46] 
			if ($current_area == 'A' && $user_account["flag"] == "FS" && !in_array($order_data["order"]["status"], array('C','S'))) {
				func_header_location("error_message.php?access_denied&id=127");
			}
# START: random:20341 [2010 Jul 29 14:46] 
*/
# END: random:20341 [2010 Jul 29 14:46] 
# END: random:18591_18598 [2009 Jul 29 10:36] 
			$order = $order_data["order"];
			$customer = $order_data["userinfo"];
			$giftcerts = $order_data["giftcerts"];
			$products = $order_data['products'];
#
##
###
			if ($action == "incorrect" && !empty($order["shipping_groups"]) && is_array($order["shipping_groups"]) && !empty($order["refund_groups"]) && is_array($order["refund_groups"])){

				foreach ($order["shipping_groups"] as $manufacturerid => $v){

					$ref_ship = $order["refund_groups"][$manufacturerid]["ref_ship"];
					if (!empty($ref_ship) && $ref_ship > 0){
						$new_ship = $v["shipping_cost"]["gross"] - $ref_ship;
						$order["shipping_groups"][$manufacturerid]["shipping_cost"]["gross"] = $new_ship;
						$order["shipping_groups"][$manufacturerid]["shipping_cost"]["net"] = $new_ship;
						$order["shipping_cost"] -= $ref_ship;
						$order["display_shipping_cost"] -= $ref_ship;
						$order["total"] -= $ref_ship;
					}

					if (!empty($v["products"]) && is_array($v["products"])){
						foreach ($v["products"] as $kk => $vv){
							$productid = $vv["productid"];

							$ref_qty = $order["refund_groups"][$manufacturerid]["products"][$productid]["ref_qty"];
							if (!empty($ref_qty) && $ref_qty > 0){
								$new_amount = $vv["amount"] - $ref_qty;
								$order["shipping_groups"][$manufacturerid]["products"][$kk]["amount"] = $new_amount;

								$order["subtotal"] -= $ref_qty*$vv["ordered_price"];
								$order["discounted_subtotal"] -= $ref_qty*$vv["ordered_price"];
								$order["display_subtotal"] -= $ref_qty*$vv["ordered_price"];
								$order["display_discounted_subtotal"] -= $ref_qty*$vv["ordered_price"];
								$order["total"] -= $ref_qty*$vv["ordered_price"];
							}
						}
					}
				}
			}
###
##
#
			$orders_data[] = array ("order" => $order, "customer" => $customer, "products" => $products, "giftcerts" => $giftcerts);
		}


		$smarty->assign("orders_data", $orders_data);
	        $smarty->assign('tracking_links',$tracking_links);
	        $smarty->assign('tracking_links_carrier',$tracking_links_carrier);
	        $smarty->assign('show_shipping_groups','Y');

		$_tmp_smarty_debug = $smarty->debugging;
		$smarty->debugging = false;

		if ($mode == "invoice") {
			if ($current_area == "A" || ($current_area == "P" && !empty($active_modules["Simple_Mode"])))
                if (!empty($active_modules['Multiple_Storefronts']) && is_numeric($order['storefrontid'])) {

                    // If there is a redirect to another domain on checkout
                    $sf_info = func_get_storefront_info($order['storefrontid'], 'ID', true);
                    if (is_array($sf_info) && !empty($sf_info)) {
                        $smarty->assign('sf_info', $sf_info);
                    }
                }
				$smarty->assign("show_order_details", "Y");
			func_display("main/order_invoice_print.tpl",$smarty);
		} elseif ($mode == "label")
			func_display("main/order_labels_print.tpl",$smarty);

		$smarty->debugging = $_tmp_smarty_debug;
	}

	exit;
} else {
	$order_data = func_order_data($orderid);

//func_print_r($order_data);

	if (empty($order_data))
		return false;

	#
	# Security check if order owned by another customer
	#
	if ($current_area == 'C' && $order_data["userinfo"]["login"] != $login) {
		func_header_location("error_message.php?access_denied&id=35");
	}

# START: random:18591_18598 [2009 Jul 29 10:36] 
# START: random:20341 [2010 Jul 29 14:46] 
/*
# END: random:20341 [2010 Jul 29 14:46] 
	if ($current_area == 'A' && $user_account["flag"] == "FS" && !in_array($order_data["order"]["status"], array('C','S'))) {
		func_header_location("error_message.php?access_denied&id=128");
	}
# START: random:20341 [2010 Jul 29 14:46] 
*/
# END: random:20341 [2010 Jul 29 14:46] 
# END: random:18591_18598 [2009 Jul 29 10:36] 
	$smarty->assign("order_details_fields_labels", func_order_details_fields_as_labels());
	$smarty->assign("order", $order_data["order"]);
	$smarty->assign("customer", $order_data["userinfo"]);
	$order_language = ($current_area == 'C' ? (empty($userinfo['language']) ? $config['default_customer_language'] : $userinfo['language']) : $shop_language);
	$order_data["products"] = func_translate_products($order_data["products"], $order_language);
	$smarty->assign("products", $order_data["products"]);
	$smarty->assign("giftcerts", $order_data["giftcerts"]);
	if ($order_data) {
		$owner_condition = "";
		if ($current_area == "C")
			$owner_condition = " AND $sql_tbl[orders].login='".$login."'";
		elseif ($current_area == "P" && !$single_mode ) {
			$owner_condition = " AND $sql_tbl[order_details].provider='".$login."'";
		}
# START: random:18591_18598 [2009 Jul 29 10:36] 
# START: random:20341 [2010 Jul 29 14:46] 

		$fulfil_condition = $fulfil_join = "";
# END: random:20341 [2010 Jul 29 14:46] 
		if ($current_area == 'A' && $user_account["flag"] == "FS") {
# START: random:20341 [2010 Jul 29 14:46] 
			$fulfil_join = " INNER JOIN $sql_tbl[order_groups] ON $sql_tbl[order_groups].orderid=$sql_tbl[orders].orderid ";
			$fulfil_condition = " AND ($sql_tbl[order_groups].dc_status = 'C' OR $sql_tbl[order_groups].dc_status = 'S') GROUP BY $sql_tbl[orders].orderid";
# END: random:20341 [2010 Jul 29 14:46] 
		}
# START: random:20341 [2010 Jul 29 14:46] 

# END: random:20341 [2010 Jul 29 14:46] 
# END: random:18591_18598 [2009 Jul 29 10:36] 
		# find next
# START: random:20341 [2010 Jul 29 14:46] 



//func_print_r($search_data, $fulfil_condition, $owner_condition);
//func_print_r("order_search_condition:", $order_search_condition);

		if ($current_area != 'C' && !empty($order_search_condition)){

			if ( strpos($order_search_condition, "WHERE") !== false){

				$order_search_condition_arr = explode("WHERE", $order_search_condition);
				$order_search_condition_arr[1] = " $sql_tbl[orders].orderid>'".$orderid."' AND " . $order_search_condition_arr[1];

				$order_search_condition_new = implode(" WHERE ", $order_search_condition_arr);
				$order_search_condition_new .= "ASC LIMIT 1";
				$order_search_condition_new = "SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix ".$order_search_condition_new;

				$tmp = func_query_first($order_search_condition_new);				
			}

		} else {

			$tmp = func_query_first("SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix FROM ($sql_tbl[orders], $sql_tbl[order_details]) $fulfil_join WHERE $sql_tbl[orders].orderid>'".$orderid."' AND $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $owner_condition $fulfil_condition ORDER BY $sql_tbl[orders].orderid ASC LIMIT 1");

		}

# END: random:20341 [2010 Jul 29 14:46] 
		if (!empty($tmp["orderid"])) {
			$smarty->assign("orderid_next", $tmp["orderid"]);
			$smarty->assign("order_prefix_next", $tmp["order_prefix"]);
		}
		# find prev
# START: random:20341 [2010 Jul 29 14:46] 


                if ($current_area != 'C' && !empty($order_search_condition)){

                        if ( strpos($order_search_condition, "WHERE") !== false){

                                $order_search_condition_arr = explode("WHERE", $order_search_condition);
                                $order_search_condition_arr[1] = " $sql_tbl[orders].orderid<'".$orderid."' AND " . $order_search_condition_arr[1];

                                $order_search_condition_new = implode(" WHERE ", $order_search_condition_arr);
                                $order_search_condition_new .= "DESC LIMIT 1";
                                $order_search_condition_new = "SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix ".$order_search_condition_new;

                                $tmp = func_query_first($order_search_condition_new);   
                        }

		} else {
			$tmp = func_query_first("SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix FROM ($sql_tbl[orders], $sql_tbl[order_details]) $fulfil_join WHERE $sql_tbl[orders].orderid<'".$orderid."' AND $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $owner_condition $fulfil_condition ORDER BY $sql_tbl[orders].orderid DESC LIMIT 1");
		}

# END: random:20341 [2010 Jul 29 14:46] 
		if (!empty($tmp["orderid"])) {
			$smarty->assign("orderid_prev", $tmp["orderid"]);
			$smarty->assign("order_prefix_prev", $tmp["order_prefix"]);
		}
	}
}

$location[] = array(func_get_langvar_by_name("lbl_orders_management"), "orders.php");
$location[] = array(func_get_langvar_by_name("lbl_order_details_label"), "");

# START: random:18591_18598 [2009 Jul 29 10:36] 
# START: random:19778 [2009 Nov 26 09:45] 
$tracking_links = func_query_hash("SELECT * FROM $sql_tbl[tracking_links] ORDER BY orderby", 'linkid', false);
$tracking_links_carrier = func_query_hash("SELECT * FROM $sql_tbl[tracking_links_carrier] ORDER BY orderby", 'carrier_id', false);
# END: random:19778 [2009 Nov 26 09:45] 
$smarty->assign("tracking_links", $tracking_links);
$smarty->assign("tracking_links_carrier", $tracking_links_carrier);

//func_print_r($tracking_links_carrier, $tracking_links);

# END: random:18591_18598 [2009 Jul 29 10:36] 
if(!empty($active_modules['RMA'])) {
    include $xcart_dir."/modules/RMA/add_returns.php";
}

if(!empty($active_modules['Anti_Fraud'])) {
    include $xcart_dir."/modules/Anti_Fraud/order.php";
}

?>
