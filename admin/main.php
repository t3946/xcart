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
# $Id: main.php,v 1.14.2.1 2006/11/08 06:25:41 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: error_message.php?permission_denied"); die("Access denied"); }

x_session_register("previous_login_date");

$location[] = array(func_get_langvar_by_name("lbl_top_info"), "");

$max_top_sellers = 10;

#
# Generate dates range
#
$curtime = time();

$start_dates[] = $previous_login_date;  # Since last login
$start_dates[] = mktime(0,0,0,date("m",$curtime),date("d",$curtime),date("Y",$curtime))-$config["Appearance"]["timezone_offset"]; # Today
$start_week = $curtime - (date("w",$curtime))*24*3600; # Week starts since Sunday
$start_dates[] = mktime(0,0,0,date("m",$start_week),date("d",$start_week),date("Y",$start_week))-$config["Appearance"]["timezone_offset"]; # Current week
$start_dates[] = mktime(0,0,0,date("m",$curtime),1,date("Y",$curtime))-$config["Appearance"]["timezone_offset"]; # Current month


foreach($start_dates as $start_date) {

	$date_condition = "AND $sql_tbl[orders].date>='$start_date' AND $sql_tbl[orders].date<='$curtime'";

	#
	# Get the orders info
	#
    /*$orders['P'][] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders]"
        . " WHERE cb_status='P' $date_condition");
    $orders['F'][] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders]"
        . " WHERE (cb_status='F' OR cb_status='D') $date_condition");
    $orders['I'][] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE cb_status='I' $date_condition");
    $orders['Q'][] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE cb_status='Q' $date_condition");*/
    $aOrderStat = func_query_first("SELECT SUM(total) as summa, count(1) as order_count FROM $sql_tbl[orders] WHERE 1 $date_condition");

	$gross_total[] = array ('value' => price_format($aOrderStat['summa']), 'count' => $aOrderStat['order_count']);

/*
    $authorized_total[] = price_format(func_query_first_cell("
SELECT SUM($sql_tbl[transaction_logs].transaction_total) FROM $sql_tbl[transaction_logs] WHERE $sql_tbl[transaction_logs].date>='$start_date' AND $sql_tbl[transaction_logs].date<='$curtime' AND transaction_status IN ('AP', 'authorized', 'Pending')
    "));
*/

    $ref_total_gross = func_query_first_cell("SELECT SUM($sql_tbl[refund_groups].total_gross) FROM $sql_tbl[refund_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[refund_groups].orderid WHERE 1=1 $date_condition");

    if ($ref_total_gross == ""){
	$ref_total_gross = 0;
    }

/*
    $authorized_total[] = price_format(func_query_first_cell("SELECT SUM(total) FROM $sql_tbl[orders]"
        . " WHERE cb_status='AP' $date_condition") - $ref_total_gross);
*/
    $authorized_total_value_arr = func_query_first("SELECT SUM(total) as summa, count(1) as order_count FROM $sql_tbl[orders]"
        . " WHERE cb_status='AP' $date_condition") ;

	$authorized_total_value = price_format($authorized_total_value_arr['summa']- $ref_total_gross);

    $authorized_total[] = array ('value' => $authorized_total_value, 'count' => $authorized_total_value_arr['order_count']);


/*
    $refund_rate[] = price_format(func_query_first_cell("
Select 
        (SUM(RG.total_net)/SUM(OG.total_net))*100
        
From xcart_order_groups OG
        inner join xcart_orders O ON O.orderid = OG.orderid and O.date>='$start_date' AND O.date<='$curtime'
        left join xcart_refund_groups RG ON RG.orderid = O.orderid
where OG.cb_status IN ('H','V','3','R','P','AP')
    "));
*/

    $refund_rate_and_total_net = func_query_first("
					SELECT SUM(RG.total_net) AS RG_total,
						   (SUM(RG.total_net) / SUM(OG.total_net)) * 100 AS refund_rate, count(distinct RG.orderid) as order_count
					  FROM $sql_tbl[order_groups] OG
						   INNER JOIN $sql_tbl[orders] O
							  ON O.orderid = OG.orderid AND
								 O.date >= '$start_date' AND
								 O.date <= '$curtime'
						   LEFT JOIN xcart_refund_groups RG ON RG.orderid = O.orderid
					 WHERE OG.cb_status IN ('H','V','3','R','P','AP')");
    $refund_rate[] = price_format($refund_rate_and_total_net["refund_rate"]);

	$refund_order_count[] = $refund_rate_and_total_net["order_count"];

    $total_refunded[] = array('value' => price_format($refund_rate_and_total_net["RG_total"]), 'count' => $refund_rate_and_total_net["order_count"]);

//func_print_r($refund_rate_and_total_net);

/*
    $total_paid[] = price_format(func_query_first_cell("SELECT SUM(total) FROM $sql_tbl[orders]"
        . " WHERE (cb_status='P' OR dc_status='C') $date_condition") - $ref_total_gross);
*/
    $total_paid_value_arr = func_query_first("SELECT SUM(total) as summa, count(1) as order_count FROM $sql_tbl[orders]"
        . " WHERE (cb_status='P' OR dc_status='C') $date_condition");

	$total_paid_value = price_format($total_paid_value_arr['summa']);
	$total_paid[] = array ('value' => $total_paid_value, 'count' => $total_paid_value_arr['order_count']);

    $total_authorized_and_paid[] = array('value' => $authorized_total_value + $total_paid_value, 'count' => $authorized_total_value_arr['order_count']+ $total_paid_value_arr['order_count']);

	# Get top N products
	if (!empty($active_modules['Multiple_Storefronts'])) {
		$ordered_products = func_query("SELECT $sql_tbl[order_details].productid, $sql_tbl[products].productcode,"
			. " $sql_tbl[products].product, COUNT($sql_tbl[order_details].productid) as count"
			. " FROM $sql_tbl[order_details], $sql_tbl[orders], $sql_tbl[products]"
			. " LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products].productid=$sql_tbl[products_sf].productid"
			. " WHERE $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $date_condition"
            . " AND $sql_tbl[orders].cb_status NOT IN ('F','D')"
            . " AND $sql_tbl[order_details].productid = $sql_tbl[products].productid"
			. " AND $sql_tbl[products_sf].sfid=$current_storefront GROUP BY $sql_tbl[order_details].productid"
			. " ORDER BY count DESC LIMIT 0, $max_top_sellers");
	} else {
        $ordered_products = func_query("SELECT $sql_tbl[order_details].productid, $sql_tbl[products].productcode,"
            . " $sql_tbl[products].product, COUNT($sql_tbl[order_details].productid) as count"
            . " FROM $sql_tbl[order_details], $sql_tbl[orders], $sql_tbl[products]"
            . " WHERE $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $date_condition"
            . " AND $sql_tbl[orders].cb_status NOT IN ('F','D')"
            . " AND $sql_tbl[order_details].productid = $sql_tbl[products].productid"
            . " GROUP BY $sql_tbl[order_details].productid ORDER BY count DESC LIMIT 0, $max_top_sellers");
	}

	if (is_array($ordered_products)) {

		# Get top N categories
		if (!empty($active_modules['Multiple_Storefronts'])) {
			$categories = func_query("SELECT $sql_tbl[products_categories].categoryid, COUNT($sql_tbl[products_categories].categoryid) as count FROM $sql_tbl[order_details], $sql_tbl[orders], $sql_tbl[products_categories] LEFT JOIN $sql_tbl[categories] ON $sql_tbl[products_categories].categoryid=$sql_tbl[categories].categoryid WHERE $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $date_condition AND $sql_tbl[order_details].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[categories].storefrontid=$current_storefront GROUP BY $sql_tbl[products_categories].categoryid ORDER BY count DESC LIMIT 0, $max_top_sellers");
		} else {
		$categories = func_query("SELECT $sql_tbl[products_categories].categoryid, COUNT($sql_tbl[products_categories].categoryid) as count FROM $sql_tbl[order_details], $sql_tbl[orders], $sql_tbl[products_categories] WHERE $sql_tbl[order_details].orderid=$sql_tbl[orders].orderid $date_condition AND $sql_tbl[order_details].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].main='Y' GROUP BY $sql_tbl[products_categories].categoryid ORDER BY count DESC LIMIT 0, $max_top_sellers");
		}

		if (is_array($categories)) {
			foreach ($categories as $idx => $category) {
				$c = func_query_first("SELECT categoryid_path, category FROM $sql_tbl[categories] WHERE categoryid = '$category[categoryid]'");
				if (empty($c))
					continue;
				$category = func_array_merge($category, $c);

				if (substr_count($category["categoryid_path"], "/") > 1) {
					$__tmp = explode("/", $category["categoryid_path"]);
					$category["category"] = func_query_first_cell("SELECT category FROM $sql_tbl[categories] WHERE categoryid='$__tmp[0]'") . "/.../" . $category["category"];
				}
				$categories[$idx] = $category;
			}
		}
	}

	$top_sellers[] = $ordered_products;
	$top_categories[] = $categories;

}

#
# Get the last order information
#
$last_order = func_query_first('SELECT orderid, order_prefix, cb_status, dc_status, bd_status,'
    . ' total, title, firstname, lastname, date'
    . " FROM $sql_tbl[orders] ORDER BY date DESC LIMIT 1");

if (!empty($last_order)) {
	# Get products ordered in the last order
	$last_order_products = func_query("SELECT productid, product_options, price, amount FROM $sql_tbl[order_details] WHERE orderid='$last_order[orderid]'");
	if (is_array($last_order_products)) {
		foreach ($last_order_products as $k=>$v) {
			$last_order["products"][] = func_array_merge(func_query_first("SELECT * FROM $sql_tbl[products] WHERE productid='$v[productid]'"), $v);
		}
	}
	# Get gift certificates ordered in the last order
	$last_order["giftcerts"] = func_query("SELECT gcid, amount FROM $sql_tbl[giftcerts] WHERE orderid='$last_order[orderid]'");

	$last_order['date'] += $config["General"]["timezone_offset"];

#
##
###
	$last_order['cb_status'] = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid='$last_order[orderid]'");
###
##
#
}


if (!x_session_is_registered("hide_security_warning")) {
	$smarty->assign("current_passwords_security", func_check_default_passwords($login));
	$smarty->assign("default_passwords_security", func_check_default_passwords());
	x_session_register("hide_security_warning");
	x_session_save("hide_security_warning");
}
#
# Set up the smarty templates variables
#
$smarty->assign("orders", $orders);
$smarty->assign("gross_total", $gross_total);
$smarty->assign("authorized_total", $authorized_total);
$smarty->assign("refund_rate", $refund_rate);
$smarty->assign("total_refunded", $total_refunded);
$smarty->assign("total_authorized_and_paid", $total_authorized_and_paid);
$smarty->assign("total_paid", $total_paid);

$smarty->assign("max_top_sellers", $max_top_sellers);
$smarty->assign("top_sellers", $top_sellers);
$smarty->assign("top_categories", $top_categories);

$smarty->assign("last_order", $last_order);

?>
