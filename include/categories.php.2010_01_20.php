<?php /* MODIFIED: random:19885 [2010 Jan 11 11:55][Custom development (Re-design Category selectors on Product Add/Modify)] */ ?>
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
# $Id: categories.php,v 1.93.2.3 2007/01/09 11:39:03 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('files');

#
# Functions definition
#

#
# This function builds the categories list within specified category ($cat)
#
# START: random:19885 [2010 Jan 11 11:55] 
function func_get_categories_list($cat=0, $short_list=true, $flag=NULL, $max_depth=0) {
# END: random:19885 [2010 Jan 11 11:55] 
	global $current_area, $sql_tbl, $shop_language, $active_modules, $config, $xcart_dir;

	$cat = intval($cat);

	$all_categories = array();
	$categories = array();
	$subcategories = array();

	$search_condition = array();

	if ($flag == "root")
		$search_condition[] = "$sql_tbl[categories].parentid='0'";
	elseif ($flag == "level")
		$search_condition[] = "$sql_tbl[categories].parentid='$cat'";
	elseif ($flag == "current")
		$search_condition[] = "$sql_tbl[categories].parentid IN ('0','$cat')";

	if ($current_area == "C" || $current_area == "B") {
		global $user_account;
		$search_condition[] = "$sql_tbl[categories].avail='Y'";
		$search_condition[] = "($sql_tbl[category_memberships].membershipid IS NULL OR $sql_tbl[category_memberships].membershipid = '$user_account[membershipid]')";
		if ($flag == "all")
			$sort_condition = " ORDER BY $sql_tbl[categories].category";
		else
			$sort_condition = " ORDER BY $sql_tbl[categories].order_by, $sql_tbl[categories].category";
	} elseif (defined('MANAGE_CATEGORIES')) {
		$sort_condition = " ORDER BY $sql_tbl[categories].order_by, $sql_tbl[categories].category";
	}

	if ($short_list) {
		$to_search = "$sql_tbl[categories].categoryid,$sql_tbl[categories].parentid,$sql_tbl[categories].categoryid_path,$sql_tbl[categories].category,$sql_tbl[categories].avail,$sql_tbl[categories].order_by";
	} else {
		$to_search = "$sql_tbl[categories].*";
	}

	$join_tbl = '';
	if ($current_area == "C" || $current_area == "B") {
		$join_tbl .= " LEFT JOIN $sql_tbl[categories_lng] USE INDEX (PRIMARY) ON $sql_tbl[categories_lng].code='$shop_language' AND $sql_tbl[categories_lng].categoryid=$sql_tbl[categories].categoryid ";
		$join_tbl .= " LEFT JOIN $sql_tbl[category_memberships] ON $sql_tbl[category_memberships].categoryid = $sql_tbl[categories].categoryid ";
		$to_search .= ", IF($sql_tbl[categories_lng].categoryid IS NOT NULL AND $sql_tbl[categories_lng].category != '', $sql_tbl[categories_lng].category, $sql_tbl[categories].category) as category";
	}

	#
	# Count the subcategories for "root" and "level" flag values
	#
	if ($flag == "level" || $flag == "root" || $flag == "current" || is_null($flag)) {
		if ($current_area == "C" || $current_area == "B") {
			$join_tbl .= " LEFT JOIN $sql_tbl[categories_subcount] USE INDEX (PRIMARY) ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[categories_subcount].membershipid = '$user_account[membershipid]' ";
			$to_search .= ",$sql_tbl[categories_subcount].subcategory_count, $sql_tbl[categories_subcount].product_count";
		} else {
			$join_tbl .= " LEFT JOIN $sql_tbl[categories_subcount] USE INDEX (PRIMARY) ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[categories].categoryid ";
			$to_search .= ",MAX($sql_tbl[categories_subcount].subcategory_count) as subcategory_count, MAX($sql_tbl[categories_subcount].product_count) as product_count";
		}

	} elseif ($short_list) {
		$to_search .= ",$sql_tbl[categories].product_count";
	}

	#
	# Check category icons
	#
	if ($flag == "level" || $flag == "root" || $flag == "current" || is_null($flag)) {
		$to_search .= ", IF($sql_tbl[images_C].id IS NOT NULL, 'Y', '') as is_icon";
		if ($config["setup_images"]['C']["location"] == "FS") {
			$to_search .= ", $sql_tbl[images_C].image_path";
		}
		$join_tbl .= " LEFT JOIN $sql_tbl[images_C] ON $sql_tbl[categories].categoryid = $sql_tbl[images_C].id ";
	}

	if (defined('NEED_PRODUCT_CATEGORIES')) {
		global $productid;
		$_categories = func_query_hash("SELECT $to_search, $sql_tbl[products_categories].productid, $sql_tbl[products_categories].main FROM $sql_tbl[categories] $join_tbl LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid AND $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main != 'Y' GROUP BY $sql_tbl[categories].categoryid", "categoryid", false);
	} else {
		$_categories = func_query_hash("SELECT $to_search FROM $sql_tbl[categories] USE INDEX (am) $join_tbl ".(!empty($search_condition)?"WHERE ".implode(" AND ", $search_condition):"")." GROUP BY $sql_tbl[categories].categoryid ".$sort_condition, "categoryid", false);
	}

	if (!is_array($_categories) || empty($_categories))
		return array("all_categories" => array(), "categories" => array(), "subcategories" => array());

	foreach ($_categories as $k => $category) {
		$category['categoryid'] = $_categories[$k]['categoryid'] = $k;

		#
		# Get the full path for category name
		#
		if ($flag == "all" || is_null($flag)) {
			$path = explode("/", $category["categoryid_path"]);
# START: random:19885 [2010 Jan 11 11:55] 
			$depth = count($path);
# END: random:19885 [2010 Jan 11 11:55] 
			$category_path = array();
# START: random:19885 [2010 Jan 11 11:55] 
			$category_path_strip = array();
# END: random:19885 [2010 Jan 11 11:55] 
			foreach ($path as $catid) {
				if (empty($_categories[$catid]))
					break;
				$category_path[] = $_categories[$catid]['category'];
# START: random:19885 [2010 Jan 11 11:55] 
				if (empty($max_depth) || ($depth <= $max_depth)) {
					$category_path_strip[] = $_categories[$catid]['category'];
				} else {
					$category_path_strip[] = '...';
				}
				$depth--;
# END: random:19885 [2010 Jan 11 11:55] 
			}
			if (count($category_path) != count($path))
				continue;
                        
			$cpath = $path[0];
			$category["main_order_by"] = func_query_first_cell("SELECT order_by FROM $sql_tbl[categories] WHERE categoryid='$cpath'");
			
# START: random:19885 [2010 Jan 11 11:55] 
			$category["category_path_sort"] = implode("/",$category_path);
			$category["category_path"] = implode("/",$category_path_strip);
# END: random:19885 [2010 Jan 11 11:55] 
			unset($category_path);

		}

		if ($config["setup_images"]['C']["location"] == "FS" && $category['is_icon'] == "Y") {
			$category["icon_url"] = func_get_image_url($k, 'C', $category["image_path"]);
		}

		$all_categories[$k] = $category;

		if (($flag == "root" || $flag == "current" || is_null($flag)) && $category["parentid"] == 0)
			$categories[$k] = $category;

		if (($flag == "level" || $flag == "current" || is_null($flag)) && $category["parentid"] == $cat)
			$subcategories[$k] = $category;

	}
	unset($_categories);

	if (($flag == "all" || is_null($flag)) && !empty($all_categories) && (($current_area != "C" && $current_area != "B") || empty($active_modules["Fancy_Categories"]))) {
		if (!function_exists("func_categories_sort")) {
		function func_categories_sort($a, $b) {
# START: random:19885 [2010 Jan 11 11:55] 
			return strcmp($a["category_path_sort"], $b["category_path_sort"]);
# END: random:19885 [2010 Jan 11 11:55] 
		}
		}
		uasort($all_categories, "func_categories_sort");
	}

	return array("all_categories" => $all_categories, "categories" => $categories, "subcategories" => $subcategories);
}

#
# This function gathering the current category data
#
function func_get_category_data($cat) {
	global $current_area, $sql_tbl, $shop_language, $user_account;
	global $xcart_dir, $current_location, $config;

	$cat = intval($cat);

	if ($config["setup_images"]['C']["location"] == "FS") {
		$image_field .= ",IF($sql_tbl[images_C].image_path != '','Y','') as is_icon, $sql_tbl[images_C].image_path";
	} else {
		$image_field .= ",IF($sql_tbl[images_C].image != '','Y','') as is_icon";
	}

	$join_tbl = " LEFT JOIN $sql_tbl[images_C] ON $sql_tbl[images_C].id = $sql_tbl[categories].categoryid LEFT JOIN $sql_tbl[categories_subcount] ON $sql_tbl[categories_subcount].categoryid = $sql_tbl[categories].categoryid".(($current_area == "C" || $current_area == "B")?" AND $sql_tbl[categories_subcount].membershipid = '".@$user_account['membershipid']."'":"")." LEFT JOIN $sql_tbl[category_memberships] ON $sql_tbl[category_memberships].categoryid = $sql_tbl[categories].categoryid ";
	$to_search = ", $sql_tbl[categories].category, $sql_tbl[categories].categoryid_path, $sql_tbl[categories].order_by $image_field ";
	if ($current_area == "C" || $current_area == "B") {
		$to_search .= ", $sql_tbl[categories_subcount].subcategory_count";
	} else {
		$to_search .= ", MAX($sql_tbl[categories_subcount].subcategory_count) as subcategory_count";
	}

	if ($current_area == "C" || $current_area == "B") {
		$join_tbl .= " LEFT JOIN $sql_tbl[categories_lng] ON $sql_tbl[categories_lng].code='$shop_language' AND $sql_tbl[categories_lng].categoryid=$sql_tbl[categories].categoryid ";
		$to_search .= ",IF(($sql_tbl[categories_lng].category IS NOT NULL AND $sql_tbl[categories_lng].category != ''), $sql_tbl[categories_lng].category, $sql_tbl[categories].category) as category, IF(($sql_tbl[categories_lng].description IS NOT NULL AND $sql_tbl[categories_lng].description != ''), $sql_tbl[categories_lng].description, $sql_tbl[categories].description) as description, $sql_tbl[categories].category as category_name_orig";
		$search_condition = "AND $sql_tbl[categories].avail='Y' AND ($sql_tbl[category_memberships].membershipid = '".$user_account["membershipid"]."' OR $sql_tbl[category_memberships].membershipid IS NULL)";
	}

	$category = func_query_first("SELECT $sql_tbl[categories].* $to_search FROM $sql_tbl[categories] $join_tbl WHERE $sql_tbl[categories].categoryid='$cat' $search_condition GROUP BY $sql_tbl[categories].categoryid LIMIT 1");

	if (!empty($category)) {

		$tmp = func_query("SELECT membershipid FROM $sql_tbl[category_memberships] WHERE categoryid = '$cat'");
		if (!empty($tmp)) {
			$category['membershipids'] = array();
			foreach ($tmp as $v) {
				$category['membershipids'][$v['membershipid']] = 'Y';
			}
		}

		#
		# Get the array of all parent categories
		#
		$_cat_sequense = explode("/", $category["categoryid_path"]);

		#
		# Generate category sequence, i.e.
		# Books, Books/Poetry, Books/Poetry/Philosophy ...
		#
		if(!empty($_cat_sequense)) {
			$search_condition_2 = "";
			if ($current_area == "C" || $current_area == "B") {
				$search_condition_2 = " AND $sql_tbl[categories].avail = 'Y'";
			}

			$_cat_names = func_query_hash("SELECT $sql_tbl[categories].categoryid $to_search FROM $sql_tbl[categories] $join_tbl WHERE $sql_tbl[categories].categoryid IN ('".implode("','", $_cat_sequense)."')".$search_condition_2." GROUP BY $sql_tbl[categories].categoryid", "categoryid", false);
			if(count($_cat_names) != count($_cat_sequense))
				return false;

			foreach ($_cat_sequense as $_catid) {
				$_cat_name = $_cat_names[$_catid];
				$category["category_location"][] = array($_cat_name["category"], "home.php?cat=$_catid");
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
		
		$cpath = $category["categoryid_path"][0];
		$category["main_order_by"] = func_query_first_cell("SELECT order_by FROM $sql_tbl[categories] WHERE categoryid='$cpath'");
		
     		return $category;
	}

	return false;
}

#
# Main code
#

$cat = intval($cat);


if ($cat > 0) {
#
# Get the current category data
#
	if ($current_category = func_get_category_data($cat)) {
		$cat_path = explode("/", $current_category["categoryid_path"]);
		$cat_path = $cat_path[0];
    		$orderby = func_query_first_cell("SELECT order_by FROM $sql_tbl[categories] WHERE categoryid = '$cat_path'");
		$current_category["main_order_by"] = $orderby;
		$smarty->assign("current_category", $current_category);
	} else {
		if ($current_area == "A") {
			$top_message["content"] = func_get_langvar_by_name("msg_category_not_exist");
			$top_message["type"] = "E";
			func_header_location("categories.php");
		}
		elseif($main != "product")
			func_header_location("home.php");
	}
}


#
# Gather the array of categories and extract into separated arrays:
# $all_categories, $categories and $subcategories
#
if (($current_area == "C" && defined("GET_ALL_CATEGORIES")) || defined('MANAGE_CATEGORIES')) {
	$_categories = func_get_categories_list($cat);
} elseif($current_area == "C") {
	$_categories = func_get_categories_list($cat, true, "current");
} else {
# START: random:19885 [2010 Jan 11 11:55] 
	$_categories = func_get_categories_list($cat, true, "all", $config["Appearance"]["category_max_depth"]);
# END: random:19885 [2010 Jan 11 11:55] 
}

extract($_categories);
unset($_categories);

#
# Prepare data for FancyCategories module
#
if ($current_area == "C" && !empty($active_modules["Fancy_Categories"])) {
	@include $xcart_dir."/modules/Fancy_Categories/fancy_categories.php";
}

$smarty->assign("allcategories", $all_categories);

$smarty->assign("categories", empty($categories)?"":$categories);

if ($cat == 0)
	$subcategories = $categories;

#
# Override subcategory_count for Admin area
#
if(!empty($subcategories) && ($current_area == 'A' || ($current_area == 'P' && $active_modules['Simple_Mode']))) {
	$product_counts = func_query_hash("SELECT categoryid, COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid IN ('".implode("','", array_keys($subcategories))."') GROUP BY categoryid", "categoryid", false, true);
	foreach($subcategories as $k => $v) {
		$subcategories[$k]['subcategory_count'] = func_query_first_cell("SELECT COUNT(subcat.categoryid) as subc FROM $sql_tbl[categories] USE INDEX (PRIMARY) LEFT JOIN $sql_tbl[categories] as subcat ON subcat.categoryid_path LIKE CONCAT($sql_tbl[categories].categoryid_path, '/%') WHERE $sql_tbl[categories].categoryid = '$k' GROUP BY $sql_tbl[categories].categoryid");
		$subcategories[$k]['product_count_global'] = $subcategories[$k]['product_count'];
		$subcategories[$k]['product_count'] = isset($product_counts[$k]) ? intval($product_counts[$k]) : 0;
	}
}

if (!empty($subcategories))
	$smarty->assign("subcategories", $subcategories);

$smarty->assign("cat", $cat);

?>
