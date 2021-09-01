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
# $Id: add_returns.php,v 1.11 2006/01/11 06:56:17 mclap Exp $
#
# Add return request in customer area
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

x_load('mail','user');

if ($mode == 'add_returns' && is_array($returns) && !empty($returns) && $orderid) {
	$send = array();
	foreach ($returns as $k => $v) {
		if ($v['avail'] != 'Y')
			continue;

		db_query("INSERT INTO $sql_tbl[returns] (itemid, amount, reason, action, comment, date, creator) VALUES ('$k', '$v[amount]', '$return_reason', '$return_action', '$return_comment', '".time()."', '$current_area')");
		$return = func_return_data(db_insert_id());
		if ($return !== false)
			$send[] = $return;
	}

	if ($send && $current_area == 'C') {
		$mail_smarty->assign("returns", $send);
		$userinfo = func_userinfo($login, $login_type);
		$mail_smarty->assign("userinfo", $userinfo);
		if ($config['RMA']['eml_rma_request_created'] == 'Y') {
			func_send_mail($config["Company"]["orders_department"], "mail/rma_request_created_subj.tpl", "mail/rma_request_created.tpl", $userinfo["email"], false);
		}
	}

	if ($current_area == 'C' && !empty($send)) {
		$top_message['content'] = func_get_langvar_by_name("txt_rma_add_message");
	}

	func_header_location("order.php?orderid=".$orderid);
}

$return_products = array();
if (is_array($order_data['products']) && !empty($order_data['products'])) {
	foreach ($order_data['products'] as $k => $v) {
		$v['amount'] -= (int)func_query_first_cell("SELECT SUM(amount) FROM $sql_tbl[returns] WHERE itemid = '$v[itemid]' AND status <> 'E'");

		if($v['amount'] > 0 && ((($order_data['order']['date']+$v['return_time']*86400) > time() && $v['return_time'] > 0) || $current_area != 'C')) {
			$return_products[] = $v;
		}
	}

	if (!empty($return_products))
		$smarty->assign("return_products", $return_products);
}

$smarty->assign("reasons", func_get_rma_reasons());
$smarty->assign("actions", func_get_rma_actions());
?>
