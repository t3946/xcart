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
# $Id: featured_products.php,v 1.22.2.1 2006/08/17 08:05:57 max Exp $
#
# Get featured products data and store it into $f_products array
# Get new products data and store it into $f_new_products array
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

#
# Select from featured products table
#


x_session_register('random_productids');
x_session_register('f_new_products');


if ($page > 1){
//func_print_r($random_productids);
}else{

  x_load("product");


  if (empty($f_new_products)){

	$f_new_prod_ids = func_query("
        SELECT 
                $sql_tbl[featured_products].productid 
        FROM $sql_tbl[featured_products] 

        LEFT JOIN $sql_tbl[products] 
        ON $sql_tbl[featured_products].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[products_categories] 
        ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

        LEFT JOIN $sql_tbl[categories] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

        LEFT JOIN $sql_tbl[category_memberships] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[category_memberships].categoryid  

        LEFT JOIN $sql_tbl[pricing] 
        ON $sql_tbl[products].productid = $sql_tbl[pricing].productid

        LEFT JOIN $sql_tbl[quick_flags] 
        ON $sql_tbl[products].productid = $sql_tbl[quick_flags].productid

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[product_memberships] 
        ON $sql_tbl[product_memberships].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[images_T] 
        ON $sql_tbl[images_T].id = $sql_tbl[products].productid

        WHERE 
                $sql_tbl[products].forsale='Y' 
                AND $sql_tbl[featured_products].avail='Y' 
                AND $sql_tbl[images_T].id != ''
                AND $sql_tbl[products].productid != ''
                AND $sql_tbl[products_sf].sfid='$current_storefront' 
                AND $sql_tbl[featured_products].storefrontid='$current_storefront' 
                AND ($sql_tbl[category_memberships].membershipid = '".$user_account['membershipid']."' OR $sql_tbl[category_memberships].membershipid IS NULL) 
                AND ($sql_tbl[product_memberships].membershipid = '".$user_account['membershipid']."' OR $sql_tbl[product_memberships].membershipid IS NULL)
        GROUP BY $sql_tbl[products].productid
	");

###

#        LEFT JOIN $sql_tbl[quick_prices] 
#        ON $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid

#                AND $sql_tbl[products].source_sfid='$current_storefront' 
		
#                AND $sql_tbl[quick_prices].membershipid ".((empty($user_account['membershipid']) || empty($active_modules['Wholesale_Trading'])) ? "= 0" : "IN ('".$user_account['membershipid']."', 0)")." 
#                AND $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid

###

/*
if ($qqq == "qqq"){
x_load("debug");
func_print_r($f_new_prod_ids);
}
*/

	$count_f_new_prod_ids = count($f_new_prod_ids);

	if (!empty($f_new_prod_ids) && is_array($f_new_prod_ids)){
	  if ($count_f_new_prod_ids >= 12){
        	$f_new_prod_ids_arr = array();
	        foreach ($f_new_prod_ids as $k => $v){
        	        if (!empty($v["productid"])){
                	        $f_new_prod_ids_arr[] = $v["productid"];
	                }
        	}

	        $tmp_rand_keys = array_rand($f_new_prod_ids_arr, 12);

        	foreach($tmp_rand_keys as $k => $v){
                	$f_new_prod_ids_random[$k] = $f_new_prod_ids_arr[$v];
	        }

	  } else {
		$f_new_products = array();
                foreach ($f_new_prod_ids as $k => $v){
                        if (!empty($v["productid"])){
				$tmp_f_new_products = func_select_product($v["productid"], @$user_account['membershipid'], false);
				if (!empty($tmp_f_new_products)){
					$f_new_products[] = $tmp_f_new_products;
				}
                        }
                }
	  }
	}

	$count_f_new_prod_ids_random = count($f_new_prod_ids_random);

	if (!empty($f_new_prod_ids_random) && is_array($f_new_prod_ids_random) && $count_f_new_prod_ids_random == "12"){
        	foreach ($f_new_prod_ids_random as $k => $v){
                	$f_new_products[$k] = func_select_product($v, @$user_account['membershipid'], false);

//			$valid_f_productids[$k] = $v;
	        }


		$valid_f_productids = array();
        	$delete_f_new_product = false;
	        foreach ($f_new_products as $k => $v){
        	        if (empty($v["productid"])){
                	        unset($f_new_products[$k]);
                        	$delete_f_new_product = true;
				continue;
	                }
			$valid_f_productids[] = $v["productid"]; 
        	}

	        if ($delete_f_new_product){
        	        $f_new_products = array_values($f_new_products);
	        }
//		$smarty->assign("f_products",$f_new_products);

	}

	if (!empty($valid_f_productids) && is_array($valid_f_productids)){
		$random_productids = "'".implode("','", $valid_f_productids)."'";
	}
/*
if ($qqq == "qqq"){
func_print_r($random_productids);
}
*/
	x_session_save("random_productids");
	x_session_save("f_new_products");


  }

  if (!empty($f_new_products)){
	$smarty->assign("f_products",$f_new_products);
  }


/*
	$user_account['membershipid'] = !empty($user_account['membershipid'])?$user_account['membershipid']:0;

	$old_search_data = $search_data["products"];
	$old_mode = $mode;
	$old_page = $page;

	$search_data["products"] = array();
	$search_data["products"]["forsale"] = "Y";

        $search_data["products"]['_']["where"][] = " $sql_tbl[featured_products].productid IN ($random_productids) ";
	$search_data["products"]["sort_condition"] = "$sql_tbl[featured_products].product_order";
	$search_data["products"]['_']['inner_joins']['featured_products'] = array(
        	"on" => "$sql_tbl[products].productid=$sql_tbl[featured_products].productid AND $sql_tbl[featured_products].avail='Y' AND $sql_tbl[featured_products].categoryid='".intval($cat)."'"
	);

	$REQUEST_METHOD = "GET";
	$mode = "search";

	include $xcart_dir."/include/search.php";

	$search_data["products"] = $old_search_data;
	x_session_save("search_data");
	$mode = $old_mode;
	$page = $old_page;
	unset($old_search_data, $old_mode, $old_page);

	if (!empty($active_modules["Subscriptions"])) {
	    include_once $xcart_dir."/modules/Subscriptions/subscription.php";
	}
	$smarty->clear_assign("products");

	$smarty->assign("f_products",$products);

	$search_data = '';
	$products = array();
	unset($search_data, $products);
*/
}


$user_account['membershipid'] = !empty($user_account['membershipid'])?$user_account['membershipid']:0;

$old_search_data = $search_data["products"];
$old_mode = $mode;
$old_page = $page;

$search_data["products"] = array();
$search_data["products"]["forsale"] = "Y";

if ($page > 1 && !empty($random_productids)){
	$search_data["products"]['_']["where"][] = " $sql_tbl[featured_products].productid NOT IN ($random_productids) ";
}
$search_data["products"]["sort_condition"] = "$sql_tbl[featured_products].product_order";
$search_data["products"]['_']['inner_joins']['featured_products'] = array(
	"on" => "$sql_tbl[products].productid=$sql_tbl[featured_products].productid AND $sql_tbl[featured_products].avail='Y' AND $sql_tbl[featured_products].categoryid='".intval($cat)."'"
);

$REQUEST_METHOD = "GET";
$mode = "search";

$new_featured_functionality = "Y";

include $xcart_dir."/include/search.php";

$search_data["products"] = $old_search_data;
x_session_save("search_data");
$mode = $old_mode;
$page = $old_page;
unset($old_search_data, $old_mode, $old_page);

if (!empty($active_modules["Subscriptions"])) {
    include_once $xcart_dir."/modules/Subscriptions/subscription.php";
}
$smarty->clear_assign("products");

//$smarty->assign("navigation_script","home.php?cat=$cat&sort=$sort&sort_direction=$sort_direction");

#
##
###
//x_load("debug");
//func_print_r($page, $_GET);
$cidev_script = "home.php";
$cidev_navigation_script = $cidev_script."?". (!empty($cat) ? "cat=".$cat : "") .($sort ? "&sort=".$sort : "").($sort_direction ? "&sort_direction=".$sort_direction : "");
if (substr($cidev_navigation_script, -1) == "?") $cidev_navigation_script = substr($cidev_navigation_script, 0, -1);
if (strpos($cidev_navigation_script, "?&") !== false) $cidev_navigation_script = str_replace("?&", "?", $cidev_navigation_script);
$smarty->assign("navigation_script", $cidev_navigation_script);
###
##
#

if ($page > 1){
	$smarty->assign("f_products",$products);
}
$search_data = '';
$products = array();
unset($search_data, $products);
?>
