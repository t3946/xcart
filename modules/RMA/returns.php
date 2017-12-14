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
# $Id: returns.php,v 1.23 2006/03/27 05:44:54 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','mail');

if (!$active_modules['RMA'])
    func_header_location ("error_message.php?access_denied&id=41");

if ($start_date_Month) {
	$search['start_date'] = mktime(0,0,0,$start_date_Month, $start_date_Day, $start_date_Year);
	$search['end_date'] = mktime(23,59,59,$end_date_Month, $end_date_Day, $end_date_Year);
}

x_session_register("search_data". []);

if (($current_area == 'C') && ($returnid || $to_delete)) {
	$returnids = func_array_merge(array($returnid), array_keys((array)$to_delete));
	if (!empty($returnids)) {
		$returnids = "'".implode("','", $returnids)."'";
		$found = func_query_first_cell("SELECT COUNT($sql_tbl[returns].returnid) FROM $sql_tbl[returns], $sql_tbl[order_details], $sql_tbl[orders] WHERE $sql_tbl[returns].itemid = $sql_tbl[order_details].itemid AND $sql_tbl[order_details].orderid = $sql_tbl[orders].orderid AND $sql_tbl[orders].login = '$login' AND $sql_tbl[returns].returnid IN ($returnids)");

		if ($found == 0) {
			func_header_location("returns.php");
		}
	}
}

# Save search data
if($mode == 'search' && $search) {
	$search_data['returns'] = $search;
	func_header_location("returns.php");

# Create credit
} elseif($mode == 'credit_create' && $returnid && $current_area != 'C') {
	$return = func_return_data($returnid);
	$gcid = substr(strtoupper(md5(uniqid(rand()))), 0, 16);
	$gc_amount = func_convert_number($gc_amount);
	if($gc_amount < 0) {
		$amount = price_format(($return['amount']*$return['product']['price']));
	} else {
		$amount = $gc_amount;
	}
	db_query("INSERT INTO $sql_tbl[giftcerts] (gcid, purchaser, recipient, send_via, recipient_email, recipient_firstname, recipient_lastname, recipient_address, recipient_city, recipient_state, recipient_country, recipient_zipcode, recipient_phone, message, amount, debit, status, add_date) values ('$gcid', '".addslashes(func_get_langvar_by_name("lbl_return_service", false, $config['default_admin_language'], true))."','".addslashes($return['order']['login'])."','E','".addslashes($return['order']['email'])."','".addslashes($return['order']['firstname'])."','".addslashes($return['order']['lastname'])."','".addslashes($return['order']['b_address'])."','".addslashes($return['order']['b_city'])."','".addslashes($return['order']['b_statename'])."','".addslashes($return['order']['b_countryname'])."','".addslashes($return['order']['b_zipcode'])."','".addslashes($return['order']['phone'])."','".addslashes(func_get_langvar_by_name("txt_rma_credit_message", false, $config['default_admin_language'], true))."','$amount','$amount','A','".time()."')");
	$res = func_query_first("SELECT * FROM $sql_tbl[giftcerts] WHERE gcid='$gcid'");
	$mail_smarty->assign("giftcert", $res);
	$mail_smarty->assign("returnid", $returnid);
	func_send_mail($res["recipient_email"], "mail/giftcert_return_subj.tpl", "mail/giftcert_return.tpl", $config["Company"]["orders_department"], false);
	db_query("UPDATE $sql_tbl[returns] SET credit = '$gcid' WHERE returnid = '$returnid'");
	func_header_location("returns.php");

# Modify return
} elseif ($mode == 'modify' && $returnid && !empty($_POST['posted_data']) && $REQUEST_METHOD == "POST") {
	$old_status = func_query_first_cell("SELECT status FROM $sql_tbl[returns] WHERE returnid = '$returnid'");
	if($current_area == 'C')
		$posted_data['status'] = $old_status;
	db_query("UPDATE $sql_tbl[returns] SET reason = '$posted_data[reason]', action = '$posted_data[action]', status = '$posted_data[status]', comment = '$posted_data[comment]' WHERE returnid = '$returnid'");
	if($old_status != $posted_data['status'] && ($posted_data['status'] == 'A' || $posted_data['status'] == 'D'))
		func_rma_send($returnid);
	func_header_location("returns.php?mode=modify&returnid=".$returnid);

# Delete return(s)
} elseif($mode == 'delete' && $to_delete && is_array($to_delete)) {
	if($current_area == 'C')
		db_query("UPDATE $sql_tbl[returns] SET status = 'E' WHERE returnid IN ('".implode("','", array_keys($to_delete))."')");
	else
		db_query("DELETE FROM $sql_tbl[returns] WHERE returnid IN ('".implode("','", array_keys($to_delete))."')");
	func_header_location("returns.php");

# Update returns
} elseif($mode == 'update' && is_array($update) && !empty($update) && $current_area != 'C') {
	foreach($update as $k => $v) {
		$old_status = func_query_first_cell("SELECT status FROM $sql_tbl[returns] WHERE returnid = '$k'");
		db_query("UPDATE $sql_tbl[returns] SET status = '$v[status]' WHERE returnid = '$k'");
		if($old_status != $v['status'] && ($v['status'] == 'A' || $v['status'] == 'D'))
			func_rma_send($k);
	}
	func_header_location("returns.php");

# Update reasons
} elseif($mode == 'reasons' && ((is_array($posted_data) && !empty($posted_data)) || $new) && $current_area != 'C') {
	if (!empty($new))
		$posted_data[] = $new;
	foreach($posted_data as $k => $v) {
		func_languages_alt_insert("lbl_rma_reason_".$k, $v, $shop_language);
	}
	db_query("REPLACE INTO $sql_tbl[config] (name, value) VALUES ('rma_reasons', '".addslashes(serialize($posted_data))."')");
	func_header_location("returns.php?mode=reasons");

# Delete reason(s)
} elseif($mode == 'reasons_delete' && isset($idx) && $current_area != 'C') {
	db_query("DELETE FROM $sql_tbl[languages_alt] WHERE name = 'lbl_rma_reason_$idx'");
	$posted_data = unserialize($config['rma_reasons']);
	unset($posted_data[$idx]);
	db_query("REPLACE INTO $sql_tbl[config] (name, value) VALUES ('rma_reasons', '".addslashes(serialize($posted_data))."')");
    func_header_location("returns.php?mode=reasons");

# Update actions
} elseif($mode == 'actions' && ((is_array($posted_data) && !empty($posted_data)) || $new) && $current_area != 'C') {
	if (!empty($new))
		$posted_data[] = $new;
	foreach($posted_data as $k => $v) {
		func_languages_alt_insert("lbl_rma_action_".$k, $v, $shop_language);
	}
	db_query("REPLACE INTO $sql_tbl[config] (name, value) VALUES ('rma_actions', '".addslashes(serialize($posted_data))."')");
    func_header_location("returns.php?mode=actions");

# Delete action(s)
} elseif($mode == 'actions_delete' && isset($idx) && $current_area != 'C') {
    db_query("DELETE FROM $sql_tbl[languages_alt] WHERE name = 'lbl_rma_action_$idx'");
    $posted_data = unserialize($config['rma_actions']);
    unset($posted_data[$idx]);
    db_query("REPLACE INTO $sql_tbl[config] (name, value) VALUES ('rma_actions', '".addslashes(serialize($posted_data))."')");
    func_header_location("returns.php?mode=actions");
}

$reasons = func_get_rma_reasons();
if (!empty($reasons))
	$smarty->assign("reasons", $reasons);
$actions = func_get_rma_actions();
if (!empty($actions))
	$smarty->assign("actions", $actions);

$statuses = array(
    "R" => func_get_langvar_by_name("lbl_return_requested"),
    "A" => func_get_langvar_by_name("lbl_return_authorized"),
    "D" => func_get_langvar_by_name("lbl_return_declined"),
    "C" => func_get_langvar_by_name("lbl_return_completed"),
    "E" => func_get_langvar_by_name("lbl_removed_by_customer")
);
$smarty->assign("statuses", $statuses);

if ($mode == 'modify') {
	if ($to_delete)
		list($returnid,) = each($to_delete);

	if ($returnid) {
		$smarty->assign("returnid", $returnid);
		$smarty->assign("return", func_return_data($returnid));
	}
	else {
		func_header_location("returns.php");
	}
}
elseif ($mode == 'print' && $returnid) {
	$smarty->assign("returnid", $returnid);
	$smarty->assign("return", func_return_data($returnid));
	func_display("modules/RMA/return_slip.tpl", $smarty);
	exit;
}
elseif (empty($mode)) {
	# Search returns

	$search = $search_data['returns'];
	if (!empty($search)) {
		$where = array();
		if ($search['start_date'])
			$where[] = "$sql_tbl[returns].date > $search[start_date] AND $sql_tbl[returns].date < $search[end_date]";

		if ($search['status'])
			$where[] = "$sql_tbl[returns].status = '$search[status]'";

		if ($search['returnid'])
			$where[] = "$sql_tbl[returns].returnid = '$search[returnid]'";

		if ($search['itemid'])
			$where[] = "$sql_tbl[returns].itemid = '$search[itemid]'";

		if ($search['orderid'])
			$where[] = "$sql_tbl[orders].orderid  = '$search[orderid]'";

		if ($current_area == 'C')
			$where[] = "$sql_tbl[returns].status <> 'E' AND $sql_tbl[orders].login = '$login'";

		if ($where)
			$where = " AND ".implode(" AND ", $where);

		$returns = func_query("SELECT $sql_tbl[returns].*, IFNULL($sql_tbl[products].product, 'PRODUCT (deleted from database)') as product, IFNULL($sql_tbl[products].productid, 0) as productid, $sql_tbl[orders].orderid, $sql_tbl[orders].firstname, $sql_tbl[orders].lastname, $sql_tbl[orders].login, $sql_tbl[orders].date as order_date, $sql_tbl[order_details].amount as order_amount, $sql_tbl[order_details].price, $sql_tbl[order_details].product_options FROM $sql_tbl[returns], $sql_tbl[orders], $sql_tbl[order_details] LEFT JOIN $sql_tbl[products] ON $sql_tbl[order_details].productid = $sql_tbl[products].productid WHERE $sql_tbl[returns].itemid = $sql_tbl[order_details].itemid AND $sql_tbl[orders].orderid = $sql_tbl[order_details].orderid".$where);
		if (!empty($returns)) {
			$smarty->assign("returns", $returns);
			$smarty->assign("returns_count", @count($returns));
		}

		$mode = 'search';
	}
}

if (empty($search)) {
	$tmp = func_query_first("SELECT MIN(date) as min, MAX(date) as max FROM $sql_tbl[returns]");
	if (!empty($tmp['min']))
		$search['start_date'] = $tmp['min'];

	if (!empty($tmp['max']))
		$search['end_date'] = $tmp['max'];
}

$smarty->assign("search", $search);

$smarty->assign("mode", $mode);
?>
