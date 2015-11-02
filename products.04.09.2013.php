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
# $Id: products.php,v 1.12 2006/01/11 06:55:57 mclap Exp $
#
# Navigation code
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

define('META_DATA_PARAM', 5);

if ($config["General"]["disable_outofstock_products"] == "Y") {
	$avail = ($config["General"]["unlimited_products"] =="N")? " AND $sql_tbl[products].avail>0 " : "";
	$current_category["product_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products], $sql_tbl[products_categories] WHERE $sql_tbl[products].productid=$sql_tbl[products_categories].productid AND $sql_tbl[products].forsale='Y' $avail AND $sql_tbl[products_categories].categoryid='$cat'");
	if (is_array($subcategories)) {
		foreach($subcategories as $k=>$v) {
			$subcategories[$k]["product_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products], $sql_tbl[products_categories] WHERE $sql_tbl[products].productid=$sql_tbl[products_categories].productid AND $sql_tbl[products].forsale='Y' $avail AND $sql_tbl[products_categories].categoryid='$v[categoryid]'");
		}
		$smarty->assign("subcategories",$subcategories);
	}
}

if ($active_modules["Advanced_Statistics"] && !defined("IS_ROBOT"))
    include $xcart_dir."/modules/Advanced_Statistics/cat_viewed.php";


#
# Get products data for current category and store it into $products array
#

$old_search_data = $search_data["products"];
$old_mode = $mode;

$search_data["products"] = array();
$search_data["products"]["categoryid"] = $cat;
$search_data["products"]["search_in_subcategories"] = "";
$search_data["products"]["category_main"] = "Y";
$search_data["products"]["category_extra"] = "Y";
$search_data["products"]["forsale"] = "Y";
if(!isset($sort))
	$sort = $config["Appearance"]["products_order"];
if(!isset($sort_direction))
    $sort_direction = 0;

$mode = "search";

include $xcart_dir."/include/search.php";
	
if (is_array($current_category)) {
	if (is_array($products)) {
		$fp_num = (count($products) >= META_DATA_PARAM) ? META_DATA_PARAM : count($products);
		$first_products = array_slice($products, 0, $fp_num);
		$q_first_products = count($first_products);
	} else {
		$first_products = array();
		$q_first_products = 0;
	}

	if ($q_first_products < META_DATA_PARAM && is_array($subcategories)) {
		$rest = META_DATA_PARAM - $q_first_products;
		$fs_num = (count($subcategories) >= $rest) ? $rest : count($subcategories);
		$first_subcats = array_slice($subcategories, 0, $fs_num);
	}
		
	if (!isset($first_subcats) || !is_array($first_subcats)) {
		$first_subcats = array();
	}

	$meta_descr = array();

	foreach ($first_products as $fp) {
		$meta_descr[] = $fp['product'];
	}
	foreach ($first_subcats as $fs) {
		$meta_descr[] = $fs['category'];
	}
	$meta_descr = implode(', ', $meta_descr);
		
	$current_category['meta_descr'] = trim(strip_tags($current_category['category'] . ': ' . $meta_descr));
}

$search_data["products"] = $old_search_data;
$mode = $old_mode;

if (!empty($active_modules["Subscriptions"])) {
    include $xcart_dir."/modules/Subscriptions/subscription.php";
}

$smarty->assign("products",$products);
//$smarty->assign("navigation_script","home.php?cat=$cat&sort=$sort&sort_direction=$sort_direction");

#
##
###
$cidev_script = "home.php";
$cidev_navigation_script = $cidev_script."?". (!empty($cat) ? "cat=".$cat : "") .($_GET["sort"] ? "&sort=".$sort : "").($sort_direction ? "&sort_direction=".$sort_direction : "");
if (substr($cidev_navigation_script, -1) == "?") $cidev_navigation_script = substr($cidev_navigation_script, 0, -1);
if (strpos($cidev_navigation_script, "?&") !== false) $cidev_navigation_script = str_replace("?&", "?", $cidev_navigation_script);
$smarty->assign("navigation_script", $cidev_navigation_script);
###
##
#


?>
