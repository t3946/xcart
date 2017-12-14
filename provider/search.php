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
# $Id: search.php,v 1.58 2006/01/11 06:56:25 mclap Exp $
#

define("NUMBER_VARS", "posted_data[price_min],posted_data[price_max],posted_data[avail_min],posted_data[avail_max],posted_data[weight_min],posted_data[weight_max]");
require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data", []);

#
# Define data for the navigation within section
#
$dialog_tools_data["left"][] = array("link" => "search.php", "title" => func_get_langvar_by_name("lbl_search_products"));

$dialog_tools_data["left"][] = array("link" => "product_modify.php", "title" => func_get_langvar_by_name("lbl_add_product"));

if ($current_area == "A" or !empty($active_modules["Simple_Mode"]))
    $dialog_tools_data["right"][] = array("link" => $xcart_catalogs['admin']."/categories.php", "title" => func_get_langvar_by_name("lbl_categories"));

if (!empty($active_modules["Product_Configurator"]))
    $dialog_tools_data["right"][] = array("link" => "pconf.php", "title" => func_get_langvar_by_name("lbl_product_configurator"));

if (!empty($active_modules["Manufacturers"]))
    $dialog_tools_data["right"][] = array("link" => "manufacturers.php", "title" => func_get_langvar_by_name("lbl_manufacturers"));

if (!empty($active_modules["Brands"]))
	$dialog_tools_data["right"][] = array("link" => "brands.php", "title" => func_get_langvar_by_name("lbl_brands"));

#   
# Use this condition when single mode is disabled
#
if (!$single_mode) {
	$search_data["products"]["provider"] = $login;
	x_session_save("search_data");
}

#
##
###
if ($mode == 'search'){
        if ($REQUEST_METHOD == "POST") {
/*
                if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
                        foreach ($filter_name_id as $k => $v){
                                if (empty($v)){
                                        unset($filter_name_id[$k]);
                                        unset($filter_value_id[$k]);
                                }
                        }
                }

                if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
                        $search_data['products']['filter_name_id'] = $filter_name_id;
                        $search_data['products']['filter_value_id'] = $filter_value_id;
                }
                else {
                        unset($search_data['products']['filter_name_id']);
                        unset($search_data['products']['filter_value_id']);
                }
*/

                if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
                        foreach ($filter_name_id as $k => $v){
                                if (empty($v)){
                                        unset($filter_name_id[$k]);
                                        unset($filter_value_id[$k]);
                                }
                        }
                }

                if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
                        $search_data['products']['filter_name_id'] = $filter_name_id;
                        $search_data['products']['filter_value_id'] = $filter_value_id;
                }
                else {
//                      unset($search_data['products']['filter_name_id']);
//                      unset($search_data['products']['filter_value_id']);
                        $search_data['products']['filter_name_id'] = "";
                        $search_data['products']['filter_value_id'] = "";
                }


		if (!empty($filter_name_id) && is_array($filter_name_id)){
	                $all_filter_name_id = array_unique($filter_name_id);
        	        $all_filter_name_id = array_values($all_filter_name_id);
		}

                $sorted_filter_values_id = array();
		if (!empty($filter_value_id) && is_array($filter_value_id))
                foreach ($filter_value_id as $kid => $fv_id){

                        $f_id = $filter_name_id[$kid];

			if (!empty($all_filter_name_id) && is_array($all_filter_name_id))
                        foreach ($all_filter_name_id as $kk_f_id => $vv_f_id){

                                if ($vv_f_id == $f_id){

                                        if (empty($fv_id)){
                                                $all_fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
                                                if (!empty($all_fv_ids) && is_array($all_fv_ids)){
                                                        foreach ($all_fv_ids as $kkk => $vvv){
                                                                $sorted_filter_values_id[$f_id][] = $vvv["fv_id"];
                                                        }
                                                }
                                        }
                                        else {
                                                $sorted_filter_values_id[$f_id][] = $fv_id;
                                        }
                                }
                        }
                }
                $search_data['products']['sorted_filter_values_id'] = $sorted_filter_values_id;


                $search_data['products']['filter_replace_query'] = $filter_replace_query;


        }
}
###
##
#

include $xcart_dir."/include/search.php";

#
##
###
$cidev_filters_tree = func_cidev_filters_tree();
$smarty->assign('cidev_filters_tree', $cidev_filters_tree);
###
##
#

if(!$single_mode) {
	unset($search_data["products"]["provider"]);
	if(empty($search_data["products"]))
		$search_data["products"] = '';
	$smarty->assign("search_prefilled", $search_data["products"]);
	$search_data["products"]["provider"] = $login;
	x_session_save("search_data");
}

$location[] = array(func_get_langvar_by_name("lbl_products_management"), "search.php");

# Assign the current location line
$smarty->assign("location", $location);

$smarty->assign('current_area', 'P');

# Assign the section navigation data
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("provider/home.tpl",$smarty);

?>
