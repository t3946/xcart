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
# $Id: func.php,v 1.2.2.2 2006/12/18 07:12:36 max Exp $
#

if (!defined('XCART_START')) { header("Location: ../../"); die("Access denied"); }

#
# This function checks if gift certificate is valid for applying
#
function func_giftcert_check($gcid) {
	global $cart;

	if (empty($gcid))
		return 1;

	if ($cart["applied_giftcerts"]) {
		foreach ($cart["applied_giftcerts"] as $k => $v)
			if (strcasecmp($v["giftcert_id"], $gcid) == 0)
				return 2;
	}

	return 0;
}

#
# This function gather the gift certificate data
#
function func_giftcert_data($gcid, $unblock=false) {
	global $config, $sql_tbl;
	
	if ($unblock) {
		# Unblock GC after $config["Gift_Certificates"]["gc_blocking_period"] minutes of blocking
		$gc_blocking_period = $config["Gift_Certificates"]["gc_blocking_period"] * 60;
		db_query("UPDATE $sql_tbl[giftcerts] SET status='A' WHERE gcid='$gcid' AND status='B' AND block_date+'$gc_blocking_period' < '".time()."' AND debit > '0'");
	}
	
	$gc = func_query_first("SELECT * FROM $sql_tbl[giftcerts] WHERE gcid='$gcid' AND status='A' AND debit > '0'");
	
	# If Gift certificate does not exists
	if (empty($gc))
		return false;

	return $gc;

}

#
# This function applies the Gift certificate to the cart
#
function func_giftcert_apply($gc_data) {
	global $cart, $sql_tbl;

	$cart['applied_giftcerts'][] = array(
		'giftcert_id' => $gc_data['gcid'],
		'giftcert_cost' => $gc_data['debit']
	);
	
	# Block the Gift certificate
	db_query("UPDATE $sql_tbl[giftcerts] SET status='B', block_date='".time()."' WHERE gcid='$gc_data[gcid]'");

	if ($gc_data['debit'] < $cart['total_cost'])
		return false;

	return true;

}

#
# Remove Gift certificate from the cart
#
function func_giftcert_unset($gcid) {
	global $cart, $sql_tbl;

	if (empty($cart["applied_giftcerts"]) || !is_array($cart["applied_giftcerts"]))
		return false;

	foreach ($cart["applied_giftcerts"] as $k=>$v) {
		if ($v["giftcert_id"] != $gcid)
			continue;

		$cart["total_cost"] = $cart["total_cost"] + $v["giftcert_cost"];

		db_query("UPDATE $sql_tbl[giftcerts] SET status='A' WHERE gcid='$gcid'");
		unset($cart["applied_giftcerts"][$k]);
	}
	
	$cart["applied_giftcerts"] = array_values($cart["applied_giftcerts"]);
	
	return true;
}

#
# Get the gift certificate printable template
#
function func_gc_get_templates($base_dir) {
	$basedir = $base_dir.'/modules/Gift_Certificates';
	$result = array();

	$dp = opendir($basedir);
	if ($dp !== false) {
		while ($file = readdir($dp)) {
			if (!preg_match('!^template_.*\.tpl$!S', $file))
				continue;

			if (!is_file($basedir.'/'.$file))
				continue;

			$result[] = $file;
		}

		closedir($dp);
	}

	return $result;
}

?>
