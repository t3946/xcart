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
# $Id: categories.php,v 1.31 2006/02/14 14:45:23 max Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Empty categories", "categories.php");

define('MANAGE_CATEGORIES', 1);

require $xcart_dir."/include/categories.php";

$cidev_all_cats = $all_categories;

//x_load("debug");
//func_print_r($cidev_all_cats);


if (!empty($cidev_all_cats) && is_array($cidev_all_cats)){
                foreach ($cidev_all_cats as $k => $v){

			if ($v["main_order_by"] > "500" || $v["product_count"] > 0 || $v["pc_ready_to_classify"] == "Y"){
				unset($cidev_all_cats[$k]);
				continue;
			}


			$cidev_count_subcats = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[categories] WHERE parentid='$v[categoryid]'");
			if ($cidev_count_subcats > 0){
				unset($cidev_all_cats[$k]);
				continue;
			}


			$cidev_count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid='$v[categoryid]'");
                        $cidev_all_cats[$k]["cidev_count_products"] =  $cidev_count_products;

			if (empty($cidev_count_products) || ($cidev_count_products == "")){
				$cidev_all_cats[$k]["catids_arr"] = explode("/", $v["categoryid_path"]);	
				$cidev_all_cats[$k]["category_arr"] = explode("/", $v["category_path"]);	
			}else{
				unset($cidev_all_cats[$k]);
				continue;
			}
                }
}
$cidev_all_cats = array_values($cidev_all_cats);


//x_load("debug");
//func_print_r($cidev_all_cats);

$smarty->assign("cidev_all_cats", $cidev_all_cats);

#
# Ajust category_location array
#
require "./location_ajust.php";

$category_location[count($category_location)-1][1] = "";
$smarty->assign("category_location", $category_location);

$smarty->assign("main","empty_categories");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
