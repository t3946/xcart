<?php
use Modules\Core\Components\Profiler;
use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;

define('SET_EXPIRE', 1);

define('OFFERS_DONT_SHOW_NEW',1);
require "./auth.php";

$cat = isset($cat) ? abs(intval($cat)) : 0;


Profiler::getInstance()->addPoint();
//x_load("category");

#
##
###
x_session_register("e_search_data", []);
x_session_register("e_search_data_orig_substring");

if (!empty($purchase_order_selected)) {
	x_session_save('purchase_order_selected');
}
###
##
#
#
## Mobile
###
if ($top_btn == "Y"){
        $e_search_data["substring"] = [];
        x_session_save("e_search_data");


	if ($mobile_mode == "subcategories"){
		$redirect_url = "home.php?mobile_mode=subcategories";
	}
	elseif ($mobile_mode == "more"){
		$redirect_url = "home.php?mobile_mode=more";
	}
	else {
		$redirect_url = "/";
	}


	func_header_location($redirect_url);

}

if (
    $cat > 0
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('C', intval($cat));
}
Profiler::getInstance()->addPoint();
require $xcart_dir."/include/categories.php";


Profiler::getInstance()->addPoint('include/categories.php');
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
elseif ($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

Profiler::getInstance()->addPoint();

if (!empty($cat)){
	include "./products.php";
    Profiler::getInstance()->addPoint('include "./products.php"');
}

else {
#
##
###
//    if (!$detect->isMobile()){
    if (!$detect_isMobile_was_created) {
	if (strpos($QUERY_STRING, "page=") !== false){
//	    func_header_location("home.php", true, 301);
	    func_header_location("/", true, 301);
	}
    }
###
##
#
}

Profiler::getInstance()->addPoint();

#
##
###
if ($cidev_dispatched_request_arr[0] == "keyword" && !empty($cidev_dispatched_request_arr[1])){
	$e_search_data["substring"] = $cidev_dispatched_request_arr[1];
	$e_search_data["substring"] = str_replace("-", " ", $e_search_data["substring"]);
}

if ($REQUEST_METHOD == 'POST' && $e_mode == "e_search"){

    $e_search_data_orig_substring = $e_posted_data["substring"];
    x_session_save("e_search_data_orig_substring");

    $e_search_data["orig_substring"] = $e_posted_data["substring"];
    $e_search_data["substring"] = $e_posted_data["substring"];
    $e_search_data["substring"] = htmlspecialchars_decode($e_posted_data["substring"]);
    $e_search_data["substring"] = stripslashes($e_search_data["substring"]);
    $e_search_data["substring"] = trim($e_search_data["substring"]);
    $e_search_data["substring"] = str_replace("&#039;", "'", $e_search_data["substring"]);
    $e_search_data["substring"] = str_replace("&", " ", $e_search_data["substring"]);
    $e_search_data["orig_substring"] = $e_search_data["substring"];

    x_session_save("e_search_data");

    $redirect_substring = (\Cocur\Slugify\Slugify::create())->slugify($e_search_data["substring"]);

    func_header_location("/keyword/".$redirect_substring."/?mode_search=Y");
}


#
##
###
if (strpos($_SERVER["QUERY_STRING"], "request_uri=") !== false){
        $tmp_QUERY_STRING_arr = explode("request_uri=", $_SERVER["QUERY_STRING"]);
        $action_notify_url = array_pop($tmp_QUERY_STRING_arr);

        if (strpos($action_notify_url, "&") !== false){
                $action_notify_url_arr = explode("&", $action_notify_url);
                $action_notify_url = $action_notify_url_arr[0];
        }

        $smarty->assign("action_notify_url", $action_notify_url);
}

if (is_array($e_search_data) && !empty($e_search_data["substring"])){

        if ($cat != $e_search_data["current_categoryid"] && !empty($e_search_data["substring"])){

		if (empty($mode_search)) {
			$e_search_data = [];
		}
        }

}


if (is_array($e_search_data) && !empty($e_search_data["substring"])){

	if (empty($clean_url_data['resource_type'])){
		$redirect_substring = str_replace(array(' ','#',':'), '-', $e_search_data["substring"]);

		func_header_location("/keyword/".$redirect_substring."/");
	}

	include $xcart_dir."/elastic_search.php";

	if ($e_search_data["total"] > $config["Appearance"]["products_per_page"]){

		$load_all_e_products = true;

		include $xcart_dir."/elastic_search.php";

		$load_all_e_products = false;
	}

	$found_cat_ids = array();
	if (!empty($e_all_products) && is_array($e_all_products)){
		foreach ($e_all_products as $k_e => $v_e){

			if (empty($v_e["categoryid"])){
				continue;
			}

			$found_cat_ids[] = $v_e["categoryid"];

			if (empty($cats_products_count[$v_e["categoryid"]]["count"])){
				$cats_products_count[$v_e["categoryid"]]["count"] = 0;

				$categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='".$v_e["categoryid"]."'");

				if (strpos($categoryid_path, "/") !== false){
					$categoryid_path_arr = explode("/", $categoryid_path);
					$root_catid = $categoryid_path_arr[0];
				} else {
					$root_catid = $categoryid_path;
				}

				$root_cat = func_query_first_cell("SELECT category FROM $sql_tbl[categories] WHERE categoryid='$root_catid'");
				
				$cats_products_count[$v_e["categoryid"]]["root_catid"] = $root_catid;
				$cats_products_count[$v_e["categoryid"]]["root_cat"] = $root_cat;

			}

			$cats_products_count[$v_e["categoryid"]]["count"]++;
		}

		if (!empty($cats_products_count) && is_array($cats_products_count)){

			foreach ($cats_products_count as $k => $v){

				if (!isset($keyword_subcategories[$v["root_catid"]]["categoryid"])){
					$keyword_subcategories[$v["root_catid"]]["categoryid"] = $v["root_catid"];
					$keyword_subcategories[$v["root_catid"]]["category"] = $v["root_cat"];
					$keyword_subcategories[$v["root_catid"]]["count"] = $v["count"];
				} else {
					$keyword_subcategories[$v["root_catid"]]["count"] += $v["count"];
				}

				$keyword_subcategories[$v["root_catid"]]["orderby"] = $keyword_subcategories[$v["root_catid"]]["count"];
			}

			usort($keyword_subcategories, 'func_sort_arr_by_orderby_desc');

			$smarty->assign('keyword_subcategories', $keyword_subcategories);
		}
	}

}
else {
        $e_search_data = [];

	if (empty($products) && empty($keyphrase)) {
        	if (!empty($cat)){
                	include "./featured_products.php";
	        } else {
        	        include "./new_featured_products.php";
	        }
	}

	x_session_save("e_search_data");
}



if (!empty($keyphrase)) {
    include $xcart_dir . '/include/search_categories.php';
}



if (!empty($current_category) and is_array($current_category["category_location"])) {
	foreach ($current_category["category_location"] as $k => $v) {
		$location[] = $v;
	}
}

if (!empty($current_category) && is_array($location)) {
    $current_category['meta_keywords'] = '';
	foreach ($location as $l) {
		$current_category['meta_keywords'] = $l[0] . ', ' . $current_category['meta_keywords'];
	}
	$current_category['meta_keywords'] = trim(strip_tags(substr($current_category['meta_keywords'], 0, strlen($current_category['meta_keywords']) - 2)));
	$smarty->assign('current_category', $current_category);
}

$tmp_count_location = count($location);
if (!empty($current_category) && is_array($location) && (empty($page) || $page == "1") ) {
        $counter_location = 0;
        foreach ($location as $k => $v) {
                $counter_location++;
                if ($counter_location == $tmp_count_location){
                        unset($location[$k][1]);
                }
        }
}

if ((empty($cat) || $cat=="0") && (empty($page) || $page == "1")){
        include './newproducts.php';
}

#
## Brands
###
if ($config["Brands"]["enable_advanced_brands_block"] == "Y" && (empty($cat) || $cat=="0")){


	$menu_brands_query = "Select
        B.brandid, B.brand, COUNT(distinct P.productid) as count
from xcart_brands B
        inner join xcart_products P ON P.brandid = B.brandid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$current_storefront'
Group By B.brandid
Order By 3 desc;";

	$menu_brands_query = "SELECT xb.brandid, brand
							FROM $sql_tbl[brands_sf] xb
							INNER JOIN $sql_tbl[brands] b ON xb.brandid = b.brandid
							 WHERE xb.sfid = $current_storefront
							ORDER BY xb.products_count DESC
							LIMIT ".($config["Brands"]["brands_listed_count"]+1);


	$menu_brands = func_query($menu_brands_query, true);

	$count_menu_brands = count($menu_brands);

	if ($count_menu_brands > $config["Brands"]["brands_listed_count"]){
		$show_count_before_see_more = $config["Brands"]["brands_listed_count"];
		$smarty->assign("show_see_more", "Y");
	} else {
		$show_count_before_see_more = $count_menu_brands;
	}

	$smarty->assign("show_count_before_see_more", $show_count_before_see_more);
	$smarty->assign("menu_brands", $menu_brands);
	$smarty->assign("count_menu_brands", $count_menu_brands);

}
//if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
//
//	if (!empty($clean_url_data["resource_type"])){
//		$resource_type = $clean_url_data["resource_type"];
//		if ($resource_type == SurfPathModel::GOAL_TYPE_CHECKOUT){
//			$resource_type = SurfPathModel::GOAL_TYPE_SEARCH;
//		}
//	} else {
//		$resource_type = SurfPathModel::GOAL_TYPE_HOME_PAGE;
//	}
//
//	if ($detect_isMobile_was_created == 'Y' && $resource_type == SurfPathModel::GOAL_TYPE_HOME_PAGE) {
//
//	} else {
//        SurfingHelper::logSurfPath([
//        	'resource_type' => $resource_type,
//            'resource_id' => $clean_url_data["resource_id"],
//			'additional_data' => SurfingHelper::getSurfPathAdditionalData([
//				'resource_type' => $resource_type,
//				'cidev_filters_tree_sorted' => $cidev_filters_tree_sorted
//			]),
//		]);
//	}
//}

	if ( !(empty($cat) && empty($keyphrase)) && $cat_with_one_brand_filter != "Y"){
		$ga_page_name = "category_list";
	}
	elseif ($clean_url_data["resource_type"] == SurfPathModel::GOAL_TYPE_CHECKOUT){
		$ga_page_name = "search";
	}
	elseif ($cat_with_one_brand_filter == "Y"){
		$ga_page_name = "category_brand_list";
	}

	x_session_register("notify_email");
	$smarty->assign("notify_email", $notify_email);

	$smarty->assign("ga_page_name", $ga_page_name);

$smarty->assign("e_search_data_orig_substring", $e_search_data_orig_substring);

#
# Assign Smarty variables and show template
#
$smarty->assign("main","catalog");

# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("bench_name", "home.php");

func_display("customer/home.tpl",$smarty);

Profiler::getInstance()->addPoint();
Profiler::getInstance()->stop('trace');
Profiler::getInstance()->display();
