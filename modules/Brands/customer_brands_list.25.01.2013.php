<?php /* ADDED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2009 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2009           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# customer_brands_list.php, random
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

require $xcart_dir."/include/categories.php";

if (!empty($active_modules['Multiple_Storefronts'])) {
	$sf_join = " LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid=$sql_tbl[brands].brandid";
	$sf_condition = " AND $sql_tbl[brands_sf].sfid=$current_storefront";
} else {
	$sf_join = '';
	$sf_condition = '';
}

if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";

$location[] = array(func_get_langvar_by_name("lbl_brands"), "");

if ($brandid) {
	#
	# Get products data for current category and store it into $products array
	#
	$old_search_data = $search_data["products"];
	$old_mode = $mode;

	$search_data["products"] = array();
	$search_data["products"]["brands"] = array($brandid);
	$search_data["products"]["forsale"] = 'Y';
	if (!isset($sort)) {
		$search_data["products"]['sort_field'] = $config["Appearance"]["products_order"];
	}
	else {
		$search_data["products"]['sort_field'] = $sort;
	}

	if (!isset($sort_direction)) {
		$search_data["products"]['sort_direction'] = 0;
	}
	else {
		$search_data["products"]['sort_direction'] = $sort_direction;
	}

	$mode = "search";

	include $xcart_dir."/include/search.php";

	$smarty->assign("sort",$search_data["products"]['sort_field']);
	$smarty->assign("sort_direction",$search_data["products"]['sort_direction']);
	$search_data["products"] = $old_search_data;
	$mode = $old_mode;

	if (!empty($active_modules["Subscriptions"]))
		include $xcart_dir."/modules/Subscriptions/subscription.php";

	$smarty->assign("products",$products);

	$brand = func_query_first("SELECT $sql_tbl[brands].*, IF($sql_tbl[images_B].id IS NULL, '', 'Y') as is_image, $sql_tbl[images_B].image_path, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand, IFNULL($sql_tbl[brands_lng].descr, $sql_tbl[brands].descr) as descr FROM $sql_tbl[brands] $sf_join LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language' LEFT JOIN $sql_tbl[images_B] ON $sql_tbl[brands].brandid = $sql_tbl[images_B].id WHERE $sql_tbl[brands].brandid = '$brandid'$sf_condition ORDER BY $sql_tbl[brands].orderby");
	if (!empty($brand['image_path']))
		$brand['image_path'] = func_get_image_url($brandid, "B", $brand['image_path']);

	if (!empty($brand) && is_array($brand)) {
		$brand['meta_descr'] = trim(preg_replace('/\s+/',' ', strip_tags($brand['descr'])));
		$brand['meta_keywords'] = trim(strip_tags(preg_replace('/\s+/',' ', $brand['brand']) . ', ' . $config['Company']['company_name']));
	}

	$smarty->assign("brand", $brand);

	$smarty->assign("main","brand_products");

	$location[count($location)-1][1] = "brands.php";
	$location[] = array($brand['brand'], "");
}
else {
	$total_items = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[brands] $sf_join WHERE avail = 'Y'$sf_condition");
	if ($total_items > 0) {
		$objects_per_page = $config["Brands"]["brands_per_page"];
		$total_nav_pages = ceil($total_items/$objects_per_page)+1;
		include $xcart_dir."/include/navigation.php";
		$brands = func_query("SELECT $sql_tbl[brands].*, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand, IFNULL($sql_tbl[brands_lng].descr, $sql_tbl[brands].descr) as descr FROM $sql_tbl[brands] $sf_join LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language' WHERE avail = 'Y'$sf_condition ORDER BY $sql_tbl[brands].orderby, brand LIMIT $first_page, $objects_per_page");
		$smarty->assign("brands", $brands);
	}

	$smarty->assign("main","brands_list");
}

$smarty->assign("navigation_script","brands.php?brandid=".$brandid."&sort=".$sort."&sort_direction=".$sort_direction);

$smarty->assign("brandid", $brandid);
?>
