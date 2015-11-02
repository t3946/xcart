<?php /* MODIFIED: random:20766 [2010 May 11 13:18][Custom development ("Additional categories" feature)] */ ?>
<?php /* MODIFIED: random:19961 [2010 Jan 19 17:09][Custom development (Investigation of optimization abilities)] */ ?>
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
# If the current category is an additional parent category for the subcategory, use the "add_order_by" field, else, use the "order_by" field
#

function func_subcat_order($a, $b) {
	global $cat;
	$cat = intval($cat);
	if (!empty($cat) && $a['additional_parentid'] == $cat) {
		$a_order = $a['add_order_by'];
	} else {
		$a_order = $a['order_by'];
	}
	if (!empty($cat) && $b['additional_parentid'] == $cat) {
		$b_order = $b['add_order_by'];
	} else {
		$b_order = $b['order_by'];
	}
	if ($a_order == $b_order) {
		$a_order = $a['category'];
		$b_order = $b['category'];
		if ($a_order == $b_order) {
			return 0;
		}
		return ($a_order< $b_order) ? -1 : 1;	
	}
	return ($a_order< $b_order) ? -1 : 1;
}

function func_check_category_path($path) {
	global $sql_tbl, $current_storefront;
	$alt_path_ids = implode(', ', $path);
	$alt_path_parent_ids = func_query_hash('SELECT categoryid, parentid FROM '.$sql_tbl['categories'].' WHERE categoryid IN ('.$alt_path_ids.')', 'categoryid', true, true);
	$alt_path_add_parent_ids = func_query_hash('SELECT categoryid, parentid FROM '.$sql_tbl['categories_parents'].' WHERE categoryid IN ('.$alt_path_ids.')', 'categoryid', true, true);
	foreach ($alt_path_parent_ids as $pid => $nids) {
		if (isset($alt_path_add_parent_ids[$pid])) {
			$alt_path_parent_ids[$pid] = array_merge($alt_path_parent_ids[$pid], $alt_path_add_parent_ids[$pid]);
			unset($alt_path_add_parent_ids[$pid]);
		}
	}
	foreach ($alt_path_add_parent_ids as $pid => $nids) {
		$alt_path_parent_ids[$pid] = $nids;
	}
	$len = count($path) - 1;
	$i = 0;
	foreach ($path as $cid) {
		if ($i == 0 && (!isset($alt_path_parent_ids[$cid]) || !in_array(0, $alt_path_parent_ids[$cid]))) {
			return false;	
		}
		if ($i < $len) {
			$next = next($path);
			if (!isset($alt_path_parent_ids[$next]) || !in_array($cid, $alt_path_parent_ids[$next])) {
				return false;			
			}
		}
		$i++;
	}
	return true;
}

#
# This function builds the categories list within specified category ($cat)
#
# START: random:19885 [2010 Jan 11 11:55] 
function func_get_categories_list($cat=0, $short_list=true, $flag=null, $max_depth=0, $keyphrase = '') {
# END: random:19885 [2010 Jan 11 11:55] 
	global $current_area, $sql_tbl, $shop_language, $active_modules, $config, $xcart_dir, $current_storefront;
	global $storefront_independant;

	$cat = intval($cat);

	$all_categories = array();
	$categories = array();
	$subcategories = array();

	$search_condition = array();

	if (!empty($active_modules['Multiple_Storefronts'])) {
		$search_condition[] = "$sql_tbl[categories].storefrontid = $current_storefront";
	}

    if (empty($keyphrase)) {
	if ($flag == "root")
		$search_condition[] = "$sql_tbl[categories].parentid='0'";
	elseif ($flag == "level")
    # START: random:20766 [2010 May 11 13:18] 
		$search_condition[] = "($sql_tbl[categories].parentid='$cat'".(($current_area == "C" || $current_area == "B")?" OR $sql_tbl[categories_parents].parentid='$cat'":"").")";
    # END: random:20766 [2010 May 11 13:18] 
	elseif ($flag == "current")
    # START: random:20766 [2010 May 11 13:18] 
		$search_condition[] = "($sql_tbl[categories].parentid IN ('0','$cat') ".(($current_area == "C" || $current_area == "B")?" OR $sql_tbl[categories_parents].parentid IN ('0','$cat')":"").")";
    # END: random:20766 [2010 May 11 13:18] 
    } else {
        $search_condition[] = $sql_tbl['categories'] . '.category LIKE "%' . $keyphrase . '%"'
            . ' AND ' . $sql_tbl['categories'] . '.parentid <> 0';
    }

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
# START: random:20766 [2010 May 11 13:18] 
	$join_tbl .= " LEFT JOIN $sql_tbl[categories_parents] ON $sql_tbl[categories_parents].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[categories_parents].parentid = '$cat'";
	$to_search .= ", IFNULL($sql_tbl[categories_parents].parentid, 0) as additional_parentid, $sql_tbl[categories_parents].is_bold as add_is_bold, $sql_tbl[categories_parents].order_by as add_order_by";
# END: random:20766 [2010 May 11 13:18] 

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

	$to_search .= ",$sql_tbl[categories].is_bold ";

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
		if (!empty($active_modules['Multiple_Storefronts']) && $storefront_independant != 'Y') {
			$_categories = db_query("SELECT $to_search, $sql_tbl[products_categories].productid, $sql_tbl[products_categories].main FROM $sql_tbl[categories] $join_tbl LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid AND $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main != 'Y' WHERE $sql_tbl[categories].storefrontid = $current_storefront GROUP BY $sql_tbl[categories].categoryid");
		} else {
			$_categories = db_query("SELECT $to_search, $sql_tbl[products_categories].productid, $sql_tbl[products_categories].main FROM $sql_tbl[categories] $join_tbl LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid AND $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main != 'Y' GROUP BY $sql_tbl[categories].categoryid");
		}
	} else {
		$_categories = db_query("SELECT $to_search FROM $sql_tbl[categories] USE INDEX (am) $join_tbl " . (!empty($search_condition) ? 'WHERE ' . implode(' AND ', $search_condition) : '') . " GROUP BY $sql_tbl[categories].categoryid " . $sort_condition);
	}

	if (empty($_categories)) {
		return array("all_categories" => array(), "categories" => array(), "subcategories" => array());
	}

# START: random:19961 [2010 Jan 19 17:09]
	$main_orderbys = array();
	while ($c = db_fetch_array($_categories)) {
		$all_categories[$c['categoryid']] = $c;
		if ($flag == "all" || is_null($flag)) {
			$path = explode("/", $c["categoryid_path"]);
			if (!in_array($path[0],$main_orderbys))	{
				$main_orderbys[] = $path[0];
			}
		}
	}
	db_free_result($_categories);

	if ($flag == "all" || is_null($flag)) {
		if (!empty($main_orderbys)) {
			$main_orderbys = func_query_hash("SELECT categoryid, order_by FROM $sql_tbl[categories] WHERE categoryid IN ('".join("','",$main_orderbys)."')", "categoryid", false, true);
		}
	}

    if ($current_area == 'C' && $config['Appearance']['show_seed_cats'] == 'Y') {
        
        $fields = array(
            'scatid',
            'scatid AS id',
            'catid AS categoryid',
            'title AS category',
            'keyphrase',
            'is_bold',
            'orderby AS order_by',
            'avail'
        );
        
        if (!empty($active_modules['Multiple_Storefronts'])) {
            $sf_condition = ' AND sfid = "' . $current_storefront . '"';
        } else {
            $sf_condition = '';
        }

        $seed_categories = func_query_hash('SELECT ' . implode(', ', $fields) 
            . ' FROM ' . $sql_tbl['seed_categories']
            . ' WHERE avail = "Y"' . $sf_condition 
            . ' ORDER BY orderby, title', 'id', false, false);
    }

# END: random:19961 [2010 Jan 19 17:09] 
	foreach ($all_categories as $k => $category) {
		$category['categoryid'] = $all_categories[$k]['categoryid'] = $k;

		#
		# Get the full path for category name
		#
		if ($flag == "all" || is_null($flag)) {
			$path = explode("/", $category["categoryid_path"]);
# START: random:19885 [2010 Jan 11 11:55] 
			$depth = count($path);
# END: random:19885 [2010 Jan 11 11:55]
			$category['category_path_sort'] = array();
			$category['category_path'] = array();
			foreach ($path as $catid) {
				if (isset($all_categories[$catid])) {
					$category['category_path_sort'][] = $all_categories[$catid]['category'];
# START: random:19885 [2010 Jan 11 11:55] 
					if (empty($max_depth) || ($depth <= $max_depth)) {
						$category['category_path'][] = $all_categories[$catid]['category'];
					} else {
						$category['category_path'][] = '...';
					}
					$depth--;
				}
# END: random:19885 [2010 Jan 11 11:55] 
			}
			if (count($category['category_path']) != count($path)) {
				unset($all_categories[$k]);
				continue;
			}

# START: random:19961 [2010 Jan 19 17:09] 
/*                        
# END: random:19961 [2010 Jan 19 17:09] 
			$cpath = $path[0];
			$category["main_order_by"] = func_query_first_cell("SELECT order_by FROM $sql_tbl[categories] WHERE categoryid='$cpath'");
# START: random:19961 [2010 Jan 19 17:09] 
*/
			$category["main_order_by"] = $main_orderbys[$path[0]];
# END: random:19961 [2010 Jan 19 17:09] 
			
# START: random:19885 [2010 Jan 11 11:55]
			$category["category_path_sort"] = implode("/",$category['category_path_sort']);
			$category["category_path"] = implode("/",$category['category_path']);
# END: random:19885 [2010 Jan 11 11:55] 
		}

		if ($config["setup_images"]['C']["location"] == "FS" && $category['is_icon'] == "Y") {
			$category["icon_url"] = func_get_image_url($k, 'C', $category["image_path"]);
		}

		$all_categories[$k] = $category;

		if (
            !($current_area == 'C' && $config['Appearance']['show_seed_cats'] == 'Y')
            && ($flag == "root" || $flag == "current" || is_null($flag)) 
            && $category["parentid"] == 0
        ) {
			$categories[$k] = $category;
        }

# START: random:20766 [2010 May 11 13:18] 
		if (($flag == "level" || $flag == "current" || is_null($flag)) && ($category["parentid"] == $cat || $category["additional_parentid"] == $cat))
# END: random:20766 [2010 May 11 13:18] 
			$subcategories[$k] = $category;

	}

    if ($current_area == 'C' && $config['Appearance']['show_seed_cats'] == 'Y') {
        $categories = $seed_categories;
    }

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
	global $current_area, $sql_tbl, $shop_language, $user_account, $current_storefront, $xcart_catalogs;
	global $xcart_dir, $current_location, $config, $previous_catid, $path, $active_modules;

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

	if (!empty($active_modules['Multiple_Storefronts'])) {
		$sf_condition = "AND $sql_tbl[categories].storefrontid=$current_storefront";
	} else {
		$sf_condition = '';
	}

	$category = func_query_first("SELECT $sql_tbl[categories].* $to_search FROM $sql_tbl[categories] $join_tbl WHERE $sql_tbl[categories].categoryid='$cat' $sf_condition $search_condition GROUP BY $sql_tbl[categories].categoryid LIMIT 1");

	if (!empty($category)) {

		$tmp = func_query("SELECT membershipid FROM $sql_tbl[category_memberships] WHERE categoryid = '$cat'");
		if (!empty($tmp)) {
			$category['membershipids'] = array();
			foreach ($tmp as $v) {
				$category['membershipids'][$v['membershipid']] = 'Y';
			}
		}

		#
		# Path for additional parent categories
		#
		
		if (!empty($previous_catid) && count($previous_catid) > 1) {
			if (is_numeric($path)) {
				array_splice($previous_catid, intval($path) + 1);	
			}
			$pclen = count($previous_catid);
			if ($pclen > 1 && is_numeric($previous_catid[$pclen - 1]) && is_numeric($previous_catid[$pclen - 2])) {
				$is_main_parent = func_query_first_cell('SELECT COUNT(*) FROM '.$sql_tbl['categories'].' as c WHERE c.categoryid='.$previous_catid[$pclen - 1] .' AND c.parentid='.$previous_catid[$pclen - 2]);
				$is_add_parent = func_query_first_cell('SELECT COUNT(*) FROM '.$sql_tbl['categories_parents'].' as cp WHERE cp.categoryid='.$previous_catid[$pclen - 1] . ' AND cp.parentid='. $previous_catid[$pclen - 2]);
			}
			if ($is_main_parent != 1 && $is_add_parent != 1) {
				$previous_catid = array($cat);
			}
		}

		$correct_path = false;
		if (!empty($previous_catid) && count($previous_catid) != 1 && ($path == 'alt' || is_numeric($path))) {
			#
			# Check the alternative path
			#
			$correct_path = func_check_category_path($previous_catid);
		}
		#
		# Get the array of all parent categories
		#
		$use_add_parent_categories = 'N';
		if ($correct_path) {
			$_cat_sequense = $previous_catid;
			$use_add_parent_categories = 'Y';
		} else {
			$_cat_sequense = explode("/", $category["categoryid_path"]);
			$previous_catid = $_cat_sequense;
		}

		#
		# Generate category sequence, i.e.
		# Books, Books/Poetry, Books/Poetry/Philosophy ...
		#
		if(!empty($_cat_sequense)) {
			$search_condition_2 = "";
			if ($current_area == "C" || $current_area == "B") {
				$search_condition_2 = " AND $sql_tbl[categories].avail = 'Y'";
			}

			$group_condition = '';
			if ($use_add_parent_categories != 'Y') {
				$group_condition = 'GROUP BY ' .$sql_tbl['categories'].'.categoryid';
			}

			$_cat_names = func_query_hash("SELECT $sql_tbl[categories].categoryid $to_search FROM $sql_tbl[categories] $join_tbl WHERE $sql_tbl[categories].categoryid IN ('".implode("','", $_cat_sequense)."')".$search_condition_2." $group_condition", "categoryid", false);
			if(count($_cat_names) != count($_cat_sequense) && $use_add_parent_categories != 'Y')
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
# START: random:20766 [2010 May 11 13:18] 
		$category["additional_parentids"] = func_query_column("SELECT parentid FROM $sql_tbl[categories_parents] WHERE categoryid='$cat'");
# END: random:20766 [2010 May 11 13:18] 
        $category['customer_url'] = ($HTTPS) ? 'https://' : 'http://';
        if (!empty($active_modules['Multiple_Storefronts'])) {
            $category['customer_url'] .= func_get_http_location_sf($current_storefront) . '/home.php?cat=' . $cat;
        } else {
            $category['customer_url'] .= $xcart_catalogs['customer'] . '/home.php?cat=' . $cat;
        }
		
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

if (!isset($keyphrase)) {
    $keyphrase = '';
}

if (($current_area == "C" && defined("GET_ALL_CATEGORIES")) || defined('MANAGE_CATEGORIES')) {
	$_categories = func_get_categories_list($cat, true, null, 0, $keyphrase);
} elseif($current_area == "C") {
	$_categories = func_get_categories_list($cat, true, "current", 0, $keyphrase);
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

if ($cat == 0 && empty($keyphrase)) {
	$subcategories = $categories;
}

if (!empty($active_modules['Multiple_Storefronts'])) {
    $sf_condition = 'AND subcat.storefrontid = ' . $current_storefront;
} else {
    $sf_condition = '';
}

#
# Override subcategory_count for Admin area
#
if(!empty($subcategories) && ($current_area == 'A' || ($current_area == 'P'/* && $active_modules['Simple_Mode']*/))) {
	$product_counts = func_query_hash("SELECT categoryid, COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid IN ('".implode("','", array_keys($subcategories))."') GROUP BY categoryid", "categoryid", false, true);
	foreach($subcategories as $k => $v) {
		$subcategories[$k]['subcategory_count'] = func_query_first_cell("SELECT COUNT(subcat.categoryid) as subc FROM $sql_tbl[categories] USE INDEX (PRIMARY) LEFT JOIN $sql_tbl[categories] as subcat ON subcat.categoryid_path LIKE CONCAT($sql_tbl[categories].categoryid_path, '/%') WHERE $sql_tbl[categories].categoryid = '$k' $sf_condition GROUP BY $sql_tbl[categories].categoryid");
		$subcategories[$k]['product_count_global'] = $subcategories[$k]['product_count'];
		$subcategories[$k]['product_count'] = isset($product_counts[$k]) ? intval($product_counts[$k]) : 0;
	}
}

#
# Override subcategory_count for Customer area
#
if (!empty($subcategories) && $current_area == 'C') {
	if ($config['General']['disable_outofstock_products'] == 'Y') {
		$outofstock = ' AND p.avail>0';
	} else {
		$outofstock = '';
	}

	foreach ($subcategories as $k => $v) {
		$subcategories[$k]['subcategory_count'] = func_query_first_cell("SELECT COUNT(subcat.categoryid) as subc FROM $sql_tbl[categories] USE INDEX (PRIMARY) LEFT JOIN $sql_tbl[categories] as subcat ON subcat.categoryid_path LIKE CONCAT($sql_tbl[categories].categoryid_path, '/%') WHERE $sql_tbl[categories].categoryid = '$k' AND subcat.avail = 'Y' $sf_condition GROUP BY $sql_tbl[categories].categoryid");
		$subcategories[$k]['product_count_global'] = $subcategories[$k]['product_count'];
//		$subcategories[$k]['product_count_global'] = func_query_first_cell("SELECT COUNT(pc.productid) FROM $sql_tbl[products_categories] as pc LEFT JOIN $sql_tbl[products] as p ON p.productid=pc.productid LEFT JOIN $sql_tbl[categories] as c ON c.categoryid=pc.categoryid WHERE c.avail='Y' AND (c.categoryid_path LIKE '%/$k/%' OR c.categoryid_path LIKE '$k/%' OR c.categoryid = '$k')$outofstock AND p.forsale = 'Y'");
	}
}

if (!empty($subcategories)) {
	uasort($subcategories,'func_subcat_order');
	$smarty->assign("subcategories", $subcategories);
	$smarty->assign("qsubcats", count($subcategories));
	$additional_parentid_arr = func_query_hash('SELECT categoryid, parentid, is_bold, order_by FROM '.$sql_tbl['categories_parents'].' WHERE categoryid IN ('.implode(', ', array_keys($subcategories)).')', 'categoryid', true, false);
	if (is_array($additional_parentid_arr)) {
		$additional_parentid = array();
		foreach ($additional_parentid_arr as $k=>$v) {
			$additional_parentid[$k]['add_parentids'] = '';
			if (is_array($v)) {
				foreach ($v as $item) {
					$additional_parentid[$k]['add_parentids'] .= $item['parentid'].',';
					$additional_parentid[$k][$item['parentid']]['is_bold'] = $item['is_bold'];
					$additional_parentid[$k][$item['parentid']]['order_by'] = $item['order_by'];
				}
			}
			$additional_parentid[$k]['add_parentids'] = substr($additional_parentid[$k]['add_parentids'], 0, strlen($additional_parentid[$k]['add_parentids']) - 1);
		}
		x_session_register('additional_parentid', $additional_parentid);
		$smarty->assign('additional_parentid', $additional_parentid);
	}
}
$smarty->assign("cat", $cat);

?>
