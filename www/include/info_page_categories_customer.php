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
# $Id: page_categories.php,v 1.93.2.3 2007/01/09 11:39:03 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('files');

#
# Functions definition
#

#
# This function builds the page_categories list within specified category ($page_cat)
#


function func_get_page_categories_list($page_cat=0, $short_list=true, $flag=NULL) {
	global $current_area, $sql_tbl, $shop_language, $active_modules, $config, $xcart_dir;

	$page_cat = intval($page_cat);

	$all_page_categories = array();
	$page_categories = array();
	$page_subcategories = array();

	$search_condition = array();

	if ($flag == "root")
		$search_condition[] = "$sql_tbl[info_pages_categories].parentid='0'";
	elseif ($flag == "level")
		$search_condition[] = "$sql_tbl[info_pages_categories].parentid='$page_cat'";
	elseif ($flag == "current")
		$search_condition[] = "$sql_tbl[info_pages_categories].parentid IN ('0','$page_cat')";

	if ($current_area == "C" || $current_area == "B") {
		global $user_account;
		$search_condition[] = "$sql_tbl[info_pages_categories].avail='Y'";
		$search_condition[] = "($sql_tbl[category_memberships].membershipid IS NULL OR $sql_tbl[category_memberships].membershipid = '$user_account[membershipid]')";
		if ($flag == "all")
			$sort_condition = " ORDER BY $sql_tbl[info_pages_categories].category";
		else
			$sort_condition = " ORDER BY $sql_tbl[info_pages_categories].order_by, $sql_tbl[info_pages_categories].category";
	} elseif (defined('MANAGE_CATEGORIES')) {
		$sort_condition = " ORDER BY $sql_tbl[info_pages_categories].order_by, $sql_tbl[info_pages_categories].category";
	}

	if ($short_list) {
		$to_search = "$sql_tbl[info_pages_categories].categoryid,$sql_tbl[info_pages_categories].parentid,$sql_tbl[info_pages_categories].categoryid_path,$sql_tbl[info_pages_categories].category,$sql_tbl[info_pages_categories].avail,$sql_tbl[info_pages_categories].order_by";
	} else {
		$to_search = "$sql_tbl[info_pages_categories].*";
	}

	$join_tbl = '';
	if ($current_area == "C" || $current_area == "B") {
		$join_tbl .= " LEFT JOIN $sql_tbl[categories_lng] USE INDEX (PRIMARY) ON $sql_tbl[categories_lng].code='$shop_language' AND $sql_tbl[categories_lng].categoryid=$sql_tbl[info_pages_categories].categoryid ";
		$join_tbl .= " LEFT JOIN $sql_tbl[category_memberships] ON $sql_tbl[category_memberships].categoryid = $sql_tbl[info_pages_categories].categoryid ";
		$to_search .= ", IF($sql_tbl[categories_lng].categoryid IS NOT NULL AND $sql_tbl[categories_lng].category != '', $sql_tbl[categories_lng].category, $sql_tbl[info_pages_categories].category) as category";
	}

	#
	# Count the page_subcategories for "root" and "level" flag values
	#
	if ($flag == "level" || $flag == "root" || $flag == "current" || is_null($flag)) {
		if ($current_area == "C" || $current_area == "B") {
			$join_tbl .= " LEFT JOIN $sql_tbl[categories_subcount] USE INDEX (PRIMARY) ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[info_pages_categories].categoryid AND $sql_tbl[categories_subcount].membershipid = '$user_account[membershipid]' ";
			$to_search .= ",$sql_tbl[categories_subcount].subcategory_count, $sql_tbl[categories_subcount].product_count";
		} else {
			$join_tbl .= " LEFT JOIN $sql_tbl[categories_subcount] USE INDEX (PRIMARY) ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[info_pages_categories].categoryid ";
			$to_search .= ",MAX($sql_tbl[categories_subcount].subcategory_count) as subcategory_count, MAX($sql_tbl[categories_subcount].product_count) as product_count";
		}

	} elseif ($short_list) {
		$to_search .= ",$sql_tbl[info_pages_categories].product_count";
	}

	#
	# Check category icons
	#
	if ($flag == "level" || $flag == "root" || $flag == "current" || is_null($flag)) {
		$to_search .= ", IF($sql_tbl[images_C].id IS NOT NULL, 'Y', '') as is_icon";
		if ($config["setup_images"]['C']["location"] == "FS") {
			$to_search .= ", $sql_tbl[images_C].image_path";
		}
		$join_tbl .= " LEFT JOIN $sql_tbl[images_C] ON $sql_tbl[info_pages_categories].categoryid = $sql_tbl[images_C].id ";
	}

	if (defined('NEED_PRODUCT_CATEGORIES')) {
		global $productid;
		$_page_categories = func_query_hash("SELECT $to_search, $sql_tbl[products_categories].productid, $sql_tbl[products_categories].main FROM $sql_tbl[info_pages_categories] $join_tbl LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[info_pages_categories].categoryid=$sql_tbl[products_categories].categoryid AND $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main != 'Y' GROUP BY $sql_tbl[info_pages_categories].categoryid", "categoryid", false);
	} else {
		$_page_categories = func_query_hash("SELECT $to_search FROM $sql_tbl[info_pages_categories] USE INDEX (am) $join_tbl ".(!empty($search_condition)?"WHERE ".implode(" AND ", $search_condition):"")." GROUP BY $sql_tbl[info_pages_categories].categoryid ".$sort_condition, "categoryid", false);
	}

	if (!is_array($_page_categories) || empty($_page_categories))
		return array("all_page_categories" => array(), "page_categories" => array(), "page_subcategories" => array());

	foreach ($_page_categories as $k => $category) {
		$category['categoryid'] = $_page_categories[$k]['categoryid'] = $k;

		#
		# Get the full path for category name
		#
		if ($flag == "all" || is_null($flag)) {
			$path = explode("/", $category["categoryid_path"]);
			$category_path = array();
			foreach ($path as $catid) {
				if (empty($_page_categories[$catid]))
					break;
				$category_path[] = $_page_categories[$catid]['category'];
			}
			if (count($category_path) != count($path))
				continue;

			$category["category_path"] = implode("/",$category_path);
			unset($category_path);

		}

		if ($config["setup_images"]['C']["location"] == "FS" && $category['is_icon'] == "Y") {
			$category["icon_url"] = func_get_image_url($k, 'C', $category["image_path"]);
		}

		$all_page_categories[$k] = $category;

		if (($flag == "root" || $flag == "current" || is_null($flag)) && $category["parentid"] == 0)
			$page_categories[$k] = $category;

		if (($flag == "level" || $flag == "current" || is_null($flag)) && $category["parentid"] == $page_cat)
			$page_subcategories[$k] = $category;

	}
	unset($_page_categories);

	if (($flag == "all" || is_null($flag)) && !empty($all_page_categories) && (($current_area != "C" && $current_area != "B") || empty($active_modules["Fancy_Categories"]))) {
		if (!function_exists("func_categories_sort")) {
		function func_categories_sort($a, $b) {
			return strcmp($a["category_path"], $b["category_path"]);
		}
		}
		uasort($all_page_categories, "func_categories_sort");
	}

	return array("all_page_categories" => $all_page_categories, "page_categories" => $page_categories, "page_subcategories" => $page_subcategories);
}

#
# This function gathering the current category data
#
function func_get_page_category_data($page_cat) {
	global $current_area, $sql_tbl, $shop_language, $user_account;
	global $xcart_dir, $current_location, $config;

	$page_cat = intval($page_cat);

	if ($config["setup_images"]['C']["location"] == "FS") {
		$image_field .= ",IF($sql_tbl[images_C].image_path != '','Y','') as is_icon, $sql_tbl[images_C].image_path";
	} else {
		$image_field .= ",IF($sql_tbl[images_C].image != '','Y','') as is_icon";
	}

	$join_tbl = " LEFT JOIN $sql_tbl[images_C] ON $sql_tbl[images_C].id = $sql_tbl[info_pages_categories].categoryid LEFT JOIN $sql_tbl[categories_subcount] ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[info_pages_categories].categoryid".(($current_area == "C" || $current_area == "B")?" AND $sql_tbl[categories_subcount].membershipid = '".@$user_account['membershipid']."'":"")." LEFT JOIN $sql_tbl[category_memberships] ON $sql_tbl[category_memberships].categoryid = $sql_tbl[info_pages_categories].categoryid ";
	$to_search = ", $sql_tbl[info_pages_categories].category $image_field ";
	if ($current_area == "C" || $current_area == "B") {
		$to_search .= ", $sql_tbl[categories_subcount].subcategory_count";
	} else {
		$to_search .= ", MAX($sql_tbl[categories_subcount].subcategory_count) as subcategory_count";
	}

	if ($current_area == "C" || $current_area == "B") {
		$join_tbl .= " LEFT JOIN $sql_tbl[categories_lng] ON $sql_tbl[categories_lng].code='$shop_language' AND $sql_tbl[categories_lng].categoryid=$sql_tbl[info_pages_categories].categoryid ";
		$to_search .= ",IF(($sql_tbl[categories_lng].category IS NOT NULL AND $sql_tbl[categories_lng].category != ''), $sql_tbl[categories_lng].category, $sql_tbl[info_pages_categories].category) as category, IF(($sql_tbl[categories_lng].description IS NOT NULL AND $sql_tbl[categories_lng].description != ''), $sql_tbl[categories_lng].description, $sql_tbl[info_pages_categories].description) as description, $sql_tbl[info_pages_categories].category as category_name_orig";
		$search_condition = "AND $sql_tbl[info_pages_categories].avail='Y' AND ($sql_tbl[category_memberships].membershipid = '".$user_account["membershipid"]."' OR $sql_tbl[category_memberships].membershipid IS NULL)";
	}

	$category = func_query_first("SELECT $sql_tbl[info_pages_categories].* $to_search FROM $sql_tbl[info_pages_categories] $join_tbl WHERE $sql_tbl[info_pages_categories].categoryid='$page_cat' $search_condition GROUP BY $sql_tbl[info_pages_categories].categoryid LIMIT 1");

	if (!empty($category)) {

		$tmp = func_query("SELECT membershipid FROM $sql_tbl[category_memberships] WHERE categoryid = '$page_cat'");
		if (!empty($tmp)) {
			$category['membershipids'] = array();
			foreach ($tmp as $v) {
				$category['membershipids'][$v['membershipid']] = 'Y';
			}
		}

		#
		# Get the array of all parent page_categories
		#
		$_cat_sequense = explode("/", $category["categoryid_path"]);

		#
		# Generate category sequence, i.e.
		# Books, Books/Poetry, Books/Poetry/Philosophy ...
		#
		if(!empty($_cat_sequense)) {
			$search_condition_2 = "";
			if ($current_area == "C" || $current_area == "B") {
				$search_condition_2 = " AND $sql_tbl[info_pages_categories].avail = 'Y'";
			}

			$_cat_names = func_query_hash("SELECT $sql_tbl[info_pages_categories].categoryid $to_search FROM $sql_tbl[info_pages_categories] $join_tbl WHERE $sql_tbl[info_pages_categories].categoryid IN ('".implode("','", $_cat_sequense)."')".$search_condition_2." GROUP BY $sql_tbl[info_pages_categories].categoryid", "categoryid", false);
			if(count($_cat_names) != count($_cat_sequense))
				return false;

			foreach ($_cat_sequense as $_catid) {
				$_cat_name = $_cat_names[$_catid];
				$category["category_location"][] = array($_cat_name["category"], "home.php?page_cat=$_catid");
				if ($category['is_icon'] != 'Y' && $_cat_name['is_icon'] == 'Y') {
					$category['is_icon'] = $_cat_name['is_icon'];
					$category['image_x'] = $_cat_name['image_x'];
					$category['image_y'] = $_cat_name['image_y'];
				}
			}
		}

		if ($config["setup_images"]['C']["location"] == "FS" && $category['is_icon'] == "Y") {
			$category["icon_url"] = func_get_image_url($category['categoryid'], 'C', $category["image_path"]);
		}

		if ($current_area == "C" || $current_area == "B") {
			if ($category["description"] == strip_tags($category["description"])) {
				$category["description"] = str_replace("\n", "<br />", $category["description"]);
			}
		}

		return $category;
	}

	return false;
}

#
# Main code
#

$page_cat = intval($page_cat);


if ($page_cat > 0) {
#
# Get the current category data
#
	if ($current_category = func_get_page_category_data($page_cat))
		$smarty->assign("current_page_category", $current_category);
	else {
		if ($current_area == "A") {
			$top_message["content"] = func_get_langvar_by_name("msg_category_not_exist");
			$top_message["type"] = "E";
			func_header_location("page_categories.php");
		}
		elseif($main != "product")
			func_header_location("home.php");
	}
}


#
# Gather the array of page_categories and extract into separated arrays:
# $all_page_categories, $page_categories and $page_subcategories
#
if (($current_area == "C" && defined("GET_ALL_CATEGORIES")) || defined('MANAGE_CATEGORIES')) {
	$_page_categories = func_get_page_categories_list($page_cat);
} elseif($current_area == "C") {
	$_page_categories = func_get_page_categories_list($page_cat, true, "current");
} else {
	$_page_categories = func_get_page_categories_list($page_cat, true, "all");
}

extract($_page_categories);
unset($_page_categories);

#
# Prepare data for FancyCategories module
#
if ($current_area == "C" && !empty($active_modules["Fancy_Categories"]))
	@include $xcart_dir."/modules/Fancy_Categories/fancy_page_categories.php";


$smarty->assign("all_page_categories", $all_page_categories);

$smarty->assign("page_categories", empty($page_categories)?"":$page_categories);

if ($page_cat == 0)
	$page_subcategories = $page_categories;

#
# Override subcategory_count for Admin area
#
if(!empty($page_subcategories) && ($current_area == 'A' || ($current_area == 'P' && $active_modules['Simple_Mode']))) {
	$product_counts = func_query_hash("SELECT categoryid, COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid IN ('".implode("','", array_keys($page_subcategories))."') GROUP BY categoryid", "categoryid", false, true);
	foreach($page_subcategories as $k => $v) {
		$page_subcategories[$k]['subcategory_count'] = func_query_first_cell("SELECT COUNT(subcat.categoryid) as subc FROM $sql_tbl[info_pages_categories] USE INDEX (PRIMARY) LEFT JOIN $sql_tbl[info_pages_categories] as subcat ON subcat.categoryid_path LIKE CONCAT($sql_tbl[info_pages_categories].categoryid_path, '/%') WHERE $sql_tbl[info_pages_categories].categoryid = '$k' GROUP BY $sql_tbl[info_pages_categories].categoryid");
		$page_subcategories[$k]['product_count_global'] = $page_subcategories[$k]['product_count'];
		$page_subcategories[$k]['product_count'] = isset($product_counts[$k]) ? intval($product_counts[$k]) : 0;
	}
}

if (!empty($page_subcategories))
	$smarty->assign("page_subcategories", $page_subcategories);

$smarty->assign("page_cat", $page_cat);

?>
