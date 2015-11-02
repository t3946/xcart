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
# $Id: generator.php,v 1.9.2.5 2006/12/06 13:21:12 twice Exp $
#
# Core module
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

x_load('order');

$location[] = array(func_get_langvar_by_name("lbl_shipping_labels"));

if (!empty($orderid)) {
	if (strpos($orderid, ",") !== false) {
		$tmp = explode(",", $orderid);
		if ($tmp) {
			foreach ($tmp as $v) {
				$orderids[$v] = true;
			}
		}
	} else {
		$orderids[$orderid] = true;
	}
}

$up_orders = array();
if ($update === "Y") {
	$up_orders = $orderids;
	$orderids = array();
	$orderids = $orderids_all;
}

if (empty($orderids)) {
	if ($mode == 'get_label') {
		$top_message['content'] = func_get_langvar_by_name("lbl_selected_orders_have_no_shipping_labels");
		$top_message['type'] = 'E';
		x_session_save();
?>
<script type="text/javascript">
<!--
if (window.opener)
	window.opener.history.go(0);
window.close();
-->
</script>
<?php
		exit;
	} else {
		func_header_location("error_message.php?access_denied&id=42");
	}
}

$all_ups_shipping_labels = array();
$is_first_ups_label = true;
foreach($orderids as $id => $v) {
	if ($update != "Y") {
		$e_type = func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE orderid = '$id' AND khash = 'shipping_label_error'");
		$l_type = func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE orderid = '$id' AND khash = 'shipping_label_type'");
	}
	$is_true = false;
	$order = func_order_data($id);
	if (empty($order))
		continue;
	if (($update == "Y") && (!array_key_exists($id, $up_orders)))
		continue;   
	$module = func_get_shipping_module($order['order']['shippingid']);
	if ((empty($e_type) && empty($l_type) && ($update != "N")) || ($module == "ups.php")) {
		if (!empty($module) && file_exists($xcart_dir."/modules/Shipping_Label_Generator/".$module)) {
			$response = array();
			include $xcart_dir."/modules/Shipping_Label_Generator/".$module;
			if ($response['result'] != 'ok') {
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label_error','".$response['error']."')");
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label_type','')");
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label','')");
			} else {
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label','".addslashes($response['image'])."')");
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label_type','$response[image_type]')");
				db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('$id','shipping_label_error','')");
				$is_true = true;
			}
		}
	} elseif ($type != 'error') {
		$is_true = true;
	}
	$orderids[$id] = $is_true;
}

if (!empty($all_ups_shipping_labels)) {
	db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('ups','shipping_label','".addslashes($all_ups_shipping_labels['image'])."')");
	db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('ups','shipping_label_type','$all_ups_shipping_labels[image_type]')");
	db_query("REPLACE INTO $sql_tbl[order_extras] (orderid,khash,value) VALUES ('ups','shipping_label_error','')");
	$smarty->assign("is_ups_exists", true);
}
if ($REQUEST_METHOD == 'POST') {
	func_header_location("generator.php?orderid=".implode(",", array_keys($orderids)));
}

#
# Get label
#
if ($mode == 'get_label' && $orderids) {
	foreach ($orderids as $id => $v) {
		if (!$v) {
			unset($orderids[$id]);
		} else {
			$lable_type = func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE orderid = '$v[orderid]' AND khash = 'shipping_label_type'");
			if (strpos($lable_type, "image/") != 0) {
				unset($orderids[$id]);	
			}
		}
	}
	if (empty($orderids)) {
		$top_message['content'] = func_get_langvar_by_name("lbl_selected_orders_have_no_shipping_labels");
		$top_message['type'] = 'E';
		x_session_save();
		echo "<script>if(window.opener) window.opener.history.go(0); window.close();</script>";
		exit;
	}
	$smarty->assign("orderids", array_keys($orderids));
	func_display("modules/Shipping_Label_Generator/labels.tpl", $smarty);
	exit;
}

if ($orderids) {
	$orders = func_query("SELECT $sql_tbl[orders].*, $sql_tbl[shipping].* FROM $sql_tbl[orders] LEFT JOIN $sql_tbl[shipping] ON $sql_tbl[orders].shippingid = $sql_tbl[shipping].shippingid WHERE $sql_tbl[orders].orderid IN ('".implode("','", array_keys($orderids))."')");
	if (empty($orders)) {
		func_header_location("error_message.php?access_denied&id=49");
	}
	$is_sl_i_type = false;
	foreach ($orders as $k => $v) {
		$orders[$k]['shipping_label_type'] = $v['shipping_label_type'] = func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE orderid = '$v[orderid]' AND khash = 'shipping_label_type'");

		$orders[$k]['shipping_label_error'] = $v['shipping_label_error'] = func_query_first_cell("SELECT value FROM $sql_tbl[order_extras] WHERE orderid = '$v[orderid]' AND khash = 'shipping_label_error'");
		$is_image = 'N';
		if (strpos($v['shipping_label_type'], "image/") === 0) {
			$orders[$k]['sl_type'] = 'I';
			$is_sl_i_type = 'Y';
			$is_image = 'Y';
		} elseif (empty($v['shipping_label_type']) && (!empty($v['shipping_label_error']))) {
			$orders[$k]['sl_type'] = 'E';
		} elseif (!empty($v['shipping_label_type'])) {
			$orders[$k]['sl_type'] = 'D';
		} else {
			$orders[$k]['sl_type'] = 'N';
		}
	}
	$smarty->assign("is_image", $is_image);
	$smarty->assign("orders", $orders);
	$smarty->assign("is_sl_i_type", $is_sl_i_type);
}

$smarty->assign("main", "slg");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
