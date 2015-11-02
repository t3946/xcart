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
# $Id: init.php,v 1.3.2.3 2006/11/28 09:35:38 max Exp $
#

if (!defined('XCART_START')) { header("Location: ../../"); die("Access denied"); }

if (!defined("AREA_TYPE") || constant("AREA_TYPE") != "C")
	return true;

# Define confugaration variables for current skin
$fc_config = array();
foreach ($config["Fancy_Categories"] as $k => $v) {
	if (strpos($k, $fancy_prefix) === 0) {
		$fc_config[substr($k, strlen($fancy_prefix))] = $k;
	}
}

$tmp = func_query_hash("SELECT name, type FROM $sql_tbl[config] WHERE name IN ('".implode("','", $fc_config)."')", "name", false, true);
if ($tmp) {
	foreach ($fc_config as $k => $v) {
		if (!isset($tmp[$v])) {
			unset($fc_config[$k]);
			continue;
		}

		$fc_config[$k] = array("name" => $v, "type" => $tmp[$v]);
	}
}
else {
	unset($fc_config);
}

unset($tmp);

# Get current skin inner properties
$ini = parse_ini_file($fancy_config_path, true);
if ($ini['layer'] == 'Y') {
	$fancy_layer = true;
	$smarty->assign("fancy_layer", $fancy_layer);
}

if ($ini['download'] == 'Y') {
	$fancy_download = true;
	$smarty->assign("fancy_download", $fancy_download);
}

# Check 'download' property
if ($fancy_download && $config["Fancy_Categories"]['fancy_download'] == 'Y') {
	if (
		defined("IS_ROBOT") || 
		($config['UA']['platform'] == 'MacPPC' && in_array($config['UA']['browser'], array("MSIE","Safari"))) ||
		($config['UA']['browser'] == 'Netscape' && $config['UA']['version'] < 8) ||
		($config['UA']['browser'] == 'Opera' && $config['UA']['version'] < 8)
	) {
		$config["Fancy_Categories"]['fancy_download'] = 'N';
		$config["Fancy_Categories"]['fancy_cache'] = 'N';
	}
}

# Check 'layer' property
if ($fancy_layer && ($config['UA']['browser'] == 'Netscape' && $config['UA']['version'] < 5)) {
	$config["Fancy_Categories"]['fancy_js'] = 'N';
}

# Check 'js' property
if (isset($js_enabled) && $js_enabled != 'Y' && $config["Fancy_Categories"]['fancy_js'] == 'Y') {
	$config["Fancy_Categories"]['fancy_js'] = 'N';
}

# Define Fancy cateogries work mode (with all categories or with only root categories)
if ((!$fancy_layer || $config["Fancy_Categories"]['fancy_download'] != 'Y' || $config["Fancy_Categories"]['fancy_js'] != 'Y') && !defined("GET_ALL_CATEGORIES")) {
	define("GET_ALL_CATEGORIES", true);
}

if (!empty($ini['onload']) && $config["Fancy_Categories"]['fancy_js'] == 'Y') {
	$body_onload .= " ".$ini['onload']." ";
}

$body_onload .= " if (window.fancy_body_is_load != undefined) fancy_body_is_load = true;";

unset($ini);

if (!empty($fc_config)) {
	$smarty->assign("fc_config", $fc_config);
}

$smarty->assign("catexp", $catexp);
$smarty->assign("config", $config);
$smarty->assign("body_onload", $body_onload);
?>
