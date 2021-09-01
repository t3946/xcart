<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: combine_carts.php,v 1.0 2010/12/28 12:01:24 kate Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

$curtime = time();

$sessions = func_query_column('SELECT data FROM ' . $sql_tbl['sessions_data'] . ' WHERE expiry > "' . $curtime . '" AND sessid <> "' . $XCARTSESSID . '"');

// prolong the expired period of the sessions
db_query('UPDATE ' . $sql_tbl['sessions_data'] . ' SET expiry = "' . ($curtime + $config['Sessions']['session_length']) . '" WHERE expiry > "' . $curtime . '" AND sessid <> "' . $XCARTSESSID . '"');

// get carts of all storefronts from sessions
$multicart = array();
$multidata = array();

$stores = func_query_hash('SELECT storefrontid, domain FROM ' . $sql_tbl['storefronts'], 'storefrontid', false, true);

foreach ($sessions as $sess) {
	$sess = unserialize($sess);
	if (!empty($sess['cart']['products'])) {
		$multicart[$sess['current_storefront']] = $sess['cart'];
	}
	$multidata[$sess['current_storefront']]['domain'] = $stores[$sess['current_storefront']];
		$multidata[$sess['current_storefront']]['current_location'] = (($HTTPS) ? 'https://' :  'http://') .  $stores[$sess['current_storefront']] . $xcart_web_dir;
	$sf_configs = func_query_hash('SELECT name, value, category FROM ' . $sql_tbl['storefronts_config'] . ' WHERE storefrontid=' . $sess['current_storefront'] . ' AND type != "separator"', 'category', true, false);
	if (!empty($sf_configs) && is_array($sf_configs)) {
		foreach ($sf_configs as $c => $vs) {
			foreach ($vs as $v) {
				$multidata[$sess['current_storefront']]['config'][$c][$v['name']] = $v['value'];
			}
		}
	}
}

if (!empty($multicart)) {
	$smarty->assign('multicart', $multicart);
}
if (!empty($multidata)) {
	// Get main domain data
	if (isset($multidata[0])) {
		$multidata[0]['domain'] = MAIN_SF_DOMAIN;
		$multidata[0]['config']['Company']['company_name'] = func_query_first_cell('SELECT value FROM ' . $sql_tbl['config'] . ' WHERE name="company_name" AND category="Company"');
		 $multidata[0]['current_location'] = (($HTTPS) ? 'https://' :  'http://') . MAIN_SF_DOMAIN . $xcart_web_dir;
	}
	$smarty->assign('multidata', $multidata);
}
?>
