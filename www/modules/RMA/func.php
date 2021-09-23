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
# $Id: func.php,v 1.15 2006/01/23 06:56:01 max Exp $
#
# Functions for Stop list module
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

#
# Generic function to get reasons or actions
#
function func_get_rma_data_generic($conf_key, $lbl_pref) {
	global $shop_language, $config;

	$values = unserialize($config[$conf_key]);
	if (is_array($values) && !empty($values)) {
		foreach ($values as $k => $v) {
			$tmp = func_get_languages_alt($lbl_pref.$k, $shop_language);

			if ($tmp) $values[$k] = $tmp;

			$values[$k] = stripslashes($values[$k]);
		}
	}
	else {
		$values = array();
	}

	return $values;
}

#
# Get reasons
#
function func_get_rma_reasons() {
	return func_get_rma_data_generic('rma_reasons', 'lbl_rma_reason_');
}

#
# Get actions
#
function func_get_rma_actions() {
	return func_get_rma_data_generic('rma_actions', 'lbl_rma_action_');
}

#
# Get return record
#
function func_return_data($returnid) {
	global $sql_tbl, $active_modules;

	x_load('order');

	$return = func_query_first("SELECT * FROM $sql_tbl[returns] WHERE returnid = '$returnid'");
	if (empty($return))
		return false;

	$orderid = func_query_first_cell("SELECT orderid FROM $sql_tbl[order_details] WHERE $sql_tbl[order_details].itemid = '$return[itemid]'");
	$tmp = func_order_data($orderid);
	if (empty($tmp) || !is_array($tmp['products']) || empty($tmp['products']))
		return false;

	$return['order'] = $tmp['order'];
	foreach ($tmp['products'] as $v) {
		if ($v['itemid'] == $return['itemid']) {
			$return['product'] = $v;
			break;
		}
	}

	if (empty($return['product']))
		return false;

	return $return;
}

#
# Send email (authorize/decline) to customer
#
function func_rma_send($returnid) {
	global $config, $mail_smarty;

	x_load('mail','user');

	$return = func_return_data($returnid);
	$mail_smarty->assign("return", $return);
	$userinfo = func_userinfo($return['order']['login'], 'C');
	$mail_smarty->assign("userinfo", $userinfo);

	if ($return['status'] == 'A') {
		if ($config['RMA']['eml_rma_authorize'] == 'Y') {
			return func_send_mail($userinfo["email"], "mail/rma_authorize_subj.tpl", "mail/rma_authorize.tpl", $config["Company"]["orders_department"], false);
		}
	}
	elseif ($return['status'] == 'D') {
		if ($config['RMA']['eml_rma_decline'] == 'Y') {
			return func_send_mail($userinfo["email"], "mail/rma_decline_subj.tpl", "mail/rma_decline.tpl", $config["Company"]["orders_department"], false);
		}
	}

	return false;
}

?>
