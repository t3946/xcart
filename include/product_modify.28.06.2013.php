<?php /* MODIFIED: random:20460 [2010 Mar 18 13:43][Custom development (Free shipping modifications)] */ ?>
<?php /* MODIFIED: random:19885 [2010 Jan 11 11:55][Custom development (Re-design Category selectors on Product Add/Modify)] */ ?>
<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# $Id: product_modify.php,v 1.171.2.15 2006/12/25 07:51:23 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','category','image','product');


#
##
###
if ($distributor_err == "y" && !empty($productid)){
	$top_message = array(
		"content" => func_get_langvar_by_name("lbl_cidev_distributor_err"),
		"type" => "I"
	);
	func_header_location("product_modify.php?productid=".$productid);
}

if ($distributor_sku_exist == "y" && !empty($productid)){
        $top_message = array(
                "content" => func_get_langvar_by_name("lbl_cidev_distributor_sku_exist"),
                "type" => "I"
        );
        func_header_location("product_modify.php?productid=".$productid);
}

if ($add_new_product == "y" && !empty($productcode) && !empty($manufacturerid)){
        $top_message = array(
                "content" => func_get_langvar_by_name("lbl_cidev_add_new_product_below"),
                "type" => "I"
        );
	func_header_location("product_modify.php?productcode=".addslashes($productcode)."&manufacturerid=".$manufacturerid."&mode_add_product=y");
}
###
##
#



$__ge_res = false;

#
# Special redirect function
#
function func_refresh($section = '', $added = '') {
	global $productid, $geid;

	if (!empty($section))
		$section = "&section=".$section;
	if (!empty($geid))
		$redirect_geid = "&geid=".$geid;
	func_header_location("product_modify.php?productid=".$productid.$redirect_geid.$section.$added);
}

function func_generate_discounts($productids) {
	global $sql_tbl;

	$prs = func_query_hash("SELECT p.productid, p.discount_slope, p.discount_table, pr.price FROM $sql_tbl[products] as p LEFT JOIN $sql_tbl[pricing] as pr ON p.productid = pr.productid AND pr.membershipid = '' AND pr.quantity = 1 AND pr.variantid = '0'WHERE p.productid IN ('".implode("','",$productids)."')", "productid");
	db_query("DELETE FROM $sql_tbl[pricing] WHERE productid IN ('".implode("','",$productids)."') AND membershipid = '' AND quantity > 1 AND variantid = '0'");
	foreach ($prs as $productid => $p) {
		foreach (explode(",",$p[0]["discount_table"]) as $v) {
			if (intval($v)) {
				$query_data = array(
						    "productid" => $productid,
						    "quantity" => intval($v),
						    "price" => (1 - $p[0]["discount_slope"] * log($v,2) / 100) * $p[0]["price"],
						    "membershipid" => ''
				);
				func_array2insert("pricing", $query_data);
			}
		}
	}
}


if (!empty($geid)) {
	if (func_ge_count($geid) == 0)
		$geid = false;
}
$redirect_geid = "";
if (!empty($geid)) {
	$redirect_geid = "&geid=".$geid;
	if (!func_ge_check($geid, $productid)) {
		if (!empty($productid)) {
			$top_message = array(
				"content" => func_get_langvar_by_name("lbl_trying_access_product_not_selected"),
				"type" => "W"
			);
		}
		$productid = func_ge_each($geid);
		func_refresh();
	}
}

x_session_register("product_modified_data");

if (x_session_is_registered("search_data"))
	$smarty->assign("flag_search_result", 1);

#
# Define the location line
#
$location[] = array(func_get_langvar_by_name("lbl_adm_product_management"), "search.php");

$avail_sections = array("main","lng");
if (!empty($active_modules['Subscriptions']))
	$avail_sections[] = "subscr";
if (!empty($active_modules['Product_Options'])) {
	$avail_sections[] = "options";
	$avail_sections[] = "variants";
}
if (!empty($active_modules['Wholesale_Trading']))
	$avail_sections[] = "wholesale";
if (!empty($active_modules['Upselling_Products']))
	$avail_sections[] = "upselling";
if (!empty($active_modules['Detailed_Product_Images'])) {
	$avail_sections[] = "images";
	$avail_sections[] = 'product_files';
}
$avail_sections[] = 'thumb';
if (!empty($active_modules['Customer_Reviews']))
	$avail_sections[] = "reviews";
if (!empty($active_modules['Feature_Comparison']))
    $avail_sections[] = "feature_class";
if (!empty($active_modules['Magnifier']))
    $avail_sections[] = "zoomer";
if (!empty($active_modules['Product_Configurator']))
	$avail_sections[] = "pclass";
$avail_sections[] = 'clone';

#
# Define the current section
#
if (!in_array($section, $avail_sections))
	$section = "main";

#
# Add, modify product
# Get product information
#
if ($mode == 'list') {
	if (empty($productids)) {
		$top_message = array(
			"content" => func_get_langvar_by_name("lbl_please_select_products_for_editing"),
			"type" => "I"
		);
		if (!empty($HTTP_REFERER)) {
			func_header_location($HTTP_REFERER);
		} else {
			func_header_location("search.php");
		}
	} else {
		$productids = array_keys($productids);
		$geid = func_ge_add($productids);
		$productid = $productids[0];
		func_refresh();
	}

} elseif ($mode == 'gen_discounts' && !empty($active_modules['Wholesale_Trading'])) {
	if (empty($productids)) {
		$top_message = array(
			"content" => func_get_langvar_by_name("lbl_please_select_products_for_editing"),
			"type" => "I"
		);
	} else {
		$productids = array_keys($productids);
		func_generate_discounts($productids);
		$top_message["content"] = func_get_langvar_by_name("msg_adm_discounts_gen");
		$top_message["type"] = "I";
	}
	if (!empty($HTTP_REFERER)) {
		func_header_location($HTTP_REFERER);
	} else {
		func_header_location("search.php");
	}
}

if ($productid != "") {
	if(empty($edit_lng)) {
		$edit_lng = $shop_language;
	}
	# Get the product info or display 'Access denied' message if not exists
	
	$product_info = func_select_product($productid, $user_account['membershipid']);

	$product_info['image'] = array(
		"T" => func_image_properties("T", $productid),
		"P" => func_image_properties("P", $productid)
	);

	# Correct the location line
	$location[] = array($product_info["product"], "product_modify.php?productid=$productid");

	# Get the product international descriptions
	$product_languages = func_query_first ("SELECT $sql_tbl[products_lng].* FROM $sql_tbl[products_lng] WHERE $sql_tbl[products_lng].productid='$productid' AND $sql_tbl[products_lng].code = '$edit_lng'");

	$smarty->assign("page_title", func_get_langvar_by_name("lbl_adm_product_management"));

}
else {
	$smarty->assign("page_title", func_get_langvar_by_name("lbl_adm_add_product"));
	$location[] = array(func_get_langvar_by_name("lbl_add_product"), "");
}

if (empty($product_info)) {

	if ($login_type == "A") {
		$providers = func_query("SELECT login, title, firstname, lastname FROM $sql_tbl[customers] WHERE usertype='P' ORDER BY login, lastname, firstname");
		if (!empty($providers)) {
			$smarty->assign("providers", $providers);
		} else {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_warn_no_providers");
			$top_message["type"] = "W";
			$smarty->assign("top_message", $top_message);
			$top_message = "";
			$section = "error";
		}
	}
	else
		$product_owner = $login;

}
else
	$product_owner = addslashes($product_info["provider"]);

if (!empty($product_owner)) {
	$provider_info = func_query_first("SELECT login, title, firstname, lastname FROM $sql_tbl[customers] WHERE login='$product_owner' AND usertype IN ('P','A')");
	$smarty->assign("provider_info", $provider_info);
}

if ($REQUEST_METHOD == "POST") {

	#
	# Delete product thumbnail
	#
	if ($mode == "delete_thumbnail" && !empty($productid)) {
		func_delete_image($productid, "T");
        func_build_quick_flags($productid);
		
		if ($fields['thumbnail'] == 'Y' && !empty($geid)) {
			while ($pid = func_ge_each($geid, 100, $productid)) {
				func_delete_image($pid, "T");
                func_build_quick_flags($pid);
			}
		}
		func_refresh();

	#
	# Delete product image
	#
	} elseif ($mode == "delete_product_image" && !empty($productid)) {
		func_delete_image($productid, "P");
        func_build_quick_flags($productid);
		if ($fields['product_image'] == 'Y' && !empty($geid)) {
			while ($pid = func_ge_each($geid, 100, $productid)) {
				func_delete_image($pid, "P");
                func_build_quick_flags($pid);
			}
		}
		func_refresh();

	#
	# Update international descriptions
	#
	} elseif ($mode == "update_lng") {
		if ($product_lng) {
			db_query("DELETE FROM $sql_tbl[products_lng] WHERE code='$edit_lng' AND productid='$productid'");

			if ($edit_lng == $config['default_admin_language'])
				func_array2update("products", $product_lng, "productid = '$productid'");

			$product_lng['code'] = $edit_lng;
			$product_lng['productid'] = $productid;
			func_array2insert("products_lng", $product_lng);

			if (!empty($fields['languages']) && $geid) {
				$product_lng_ge = array();
				foreach($fields['languages'] as $k => $v) {
					if(isset($product_lng[$k])) {
						$product_lng_ge[$k] = $product_lng[$k];
					}
				}
				if(!empty($product_lng_ge)) {
					$product_lng_ge['code'] = $edit_lng;
					while ($pid = func_ge_each($geid, 1, $productid)) {
						db_query("DELETE FROM $sql_tbl[products_lng] WHERE code='$edit_lng' AND productid='$pid'");
						func_unset($product_lng_ge, 'productid');

						if ($edit_lng == $config['default_admin_language'])
							func_array2update("products", $product_lng_ge, "productid = '$pid'");

						$product_lng_ge['productid'] = $pid;
						func_array2insert("products_lng", $product_lng_ge);
					}
				}
			}
			$top_message = array(
				"content" => func_get_langvar_by_name("msg_adm_product_int_upd"),
				"type" => "I"
			);
		}

		func_refresh("lng");
	} elseif ($mode == "del_lang") {
	#
	# Delete selected international description
	#
		db_query ("DELETE FROM $sql_tbl[products_lng] WHERE productid='$productid' AND code='$edit_lng'");
		if (!empty($del_lang_all)) {
			while ($pid = func_ge_each($geid, 100, $productid)) {
				db_query ("DELETE FROM $sql_tbl[products_lng] WHERE productid IN ('".implode("','", $pid)."') AND code='$edit_lng'");
			}
		}

		$top_message["content"] = func_get_langvar_by_name("msg_adm_product_int_del");
		$top_message["type"] = "I";
		func_refresh("lng");
	}

}


$smarty->assign("main", "product_modify");

$pm_link = "product_modify.php?productid=$productid".$redirect_geid;
#
# Define data for the navigation within section
#
$dialog_tools_data["left"][] = array("link" => $pm_link, "title" => func_get_langvar_by_name("lbl_product_details"));

if (!empty($product_info)) {
	if (!empty($avail_languages))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=lng", "title" => func_get_langvar_by_name("txt_international_descriptions"));
	if (!empty($active_modules["Product_Options"])) {
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=options", "title" => func_get_langvar_by_name("lbl_product_options"));
		if ($product_info['is_variants'] == 'Y') {
			$dialog_tools_data["left"][] = array("link" => $pm_link."&section=variants", "title" => func_get_langvar_by_name("lbl_product_variants"));
		}
	}
	if (!empty($active_modules["Product_Configurator"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=pclass", "title" => func_get_langvar_by_name("lbl_pconf_product_classification"));
	if (!empty($active_modules["Subscriptions"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=subscr", "title" => func_get_langvar_by_name("lbl_subscriptions"));
	if (!empty($active_modules["Wholesale_Trading"]) && $product_info['is_variants'] != 'Y')
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=wholesale", "title" => func_get_langvar_by_name("lbl_wholesale_prices"));
	if (!empty($active_modules["Upselling_Products"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=upselling", "title" => func_get_langvar_by_name("lbl_upselling_links"));
	if (!empty($active_modules["Detailed_Product_Images"])) {
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=product_files", "title" => func_get_langvar_by_name("lbl_product_files"));
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=images", "title" => func_get_langvar_by_name("lbl_detailed_images"));
	}
	$dialog_tools_data["left"][] = array("link" => $pm_link."&section=thumb", "title" => func_get_langvar_by_name("lbl_product_thumbnail"));
	if (!empty($active_modules["Magnifier"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=zoomer", "title" => func_get_langvar_by_name("lbl_zoom_images"));
	if (!empty($active_modules["Customer_Reviews"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=reviews", "title" => func_get_langvar_by_name("lbl_customer_reviews"));
	if (!empty($active_modules["Feature_Comparison"]))
		$dialog_tools_data["left"][] = array("link" => $pm_link."&section=feature_class", "title" => func_get_langvar_by_name("lbl_feature_class"));
	$dialog_tools_data["left"][] = array("link" => $pm_link."&section=clone", "title" => func_get_langvar_by_name("lbl_product_clone"));
}

$dialog_tools_data["right"][] = array("link" => "search.php", "title" => func_get_langvar_by_name("lbl_search_products"));
$dialog_tools_data["right"][] = array("link" => "product_modify.php", "title" => func_get_langvar_by_name("lbl_add_product"));

if (!empty($active_modules["Product_Configurator"]) && ($current_area == "P" || !empty($active_modules["Simple_Mode"])))
		$dialog_tools_data["right"][] = array("link" => "pconf.php", "title" => func_get_langvar_by_name("lbl_product_configurator"));

if ($current_area == "A" || !empty($active_modules["Simple_Mode"]))
	$dialog_tools_data["right"][] = array("link" => $xcart_catalogs["admin"]."/categories.php", "title" => func_get_langvar_by_name("lbl_categories"));
if (!empty($active_modules["Manufacturers"]))
	$dialog_tools_data["right"][] = array("link" => "manufacturers.php", "title" => func_get_langvar_by_name("lbl_manufacturers"));
# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if (!empty($active_modules["Brands"]))
	$dialog_tools_data["right"][] = array("link" => "brands.php", "title" => func_get_langvar_by_name("lbl_brands"));
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($current_area != 'P') {
	$dialog_tools_data["right"][] = array("link" => "orders.php", "title" => func_get_langvar_by_name("lbl_orders"));
}


#
# This flag means that this product is configurator
#
$is_pconf = false;

#
# Product Configurator module
#
if (!empty($active_modules["Product_Configurator"]))
	include $xcart_dir."/modules/Product_Configurator/product_modify.php";


#
# Update product details or create product
#
if (($REQUEST_METHOD == "POST") && ($mode == "product_modify")) {

	$sku_is_exist = (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode='$productcode' AND productid!='$productid' AND provider = '".addslashes($login_type == "A" ? $provider : $login)."'") ? true : false);
	# Check if form filled with errors
	$is_variant = false;
	if (!empty($productid) && !empty($active_modules["Product_Options"]))
		$is_variant = (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productid = '$productid'") > 0);

	$_POST['price'] = $price = abs((float)$price);


#
##
###
        if ($_POST["calculate_price_for_new_product"] == "Y"){
//                $_POST["calculate_price_for_new_product"] = $price = (1.2 * $_POST["cost_to_us"] + 0.5)/0.94;
                $_POST["calculate_price_for_new_product"] = $price = (1.15 * $cost_to_us + 0.3)/0.97;
	} else {

		$current_in_db_cost_to_us = func_query_first_cell("SELECT cost_to_us FROM $sql_tbl[products] WHERE productid='$productid'");

		if ($current_in_db_cost_to_us!=$cost_to_us && $cost_to_us>0){
			$_POST["calculate_price_for_new_product"] = $price = (1.15 * $cost_to_us + 0.3)/0.97;
		} else {
                        $top_message["content"] = "Not Saved. Check 'Cost to us' field.";
                        $top_message["type"] = "E";
			func_header_location("product_modify.php?productid=".$productid);
		}
	}
###
##
#


	$category_exists = func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='$categoryid'");
	$fillerror = (($categoryid == "") ||
		(!$category_exists) ||
		empty($product) ||
		empty($fulldescr) ||
		($avail == "" && !$is_variant) ||
		empty($low_avail_limit) ||
		($productcode == '') ||
#
##
###
		empty($cost_to_us) || $cost_to_us == "" || $cost_to_us < 0 ||
###
##
#

		$sku_is_exist);

	if (!$fillerror) {
	#
	# If no errors
	#
		if (empty($productid)) {
		#
		# Create a new product
		#

			$provider = ($login_type == "A" ? $provider : $login);

			#
			# Insert new product into the database and get its productid
			#
            if (!empty($active_modules['Multiple_Storefronts']) && isset($current_storefront)) {
                $source_sfid = $current_storefront;
            } else {
                $source_sfid = 0;
            }
			$time = time();
			db_query("INSERT INTO $sql_tbl[products] (productcode, provider, original_provider, add_date, mod_date, source_sfid) VALUES ('$productcode', '$provider', '$provider','" . $time . "', '" . $time . "', '$source_sfid')");

			$productid = db_insert_id();

			# Insert price and image
			db_query("INSERT INTO $sql_tbl[pricing] (productid, quantity, price) VALUES ('$productid', '1', '".abs($price)."')");

			$status = "created";

		} else {
			#
			# Update the existing product
			#

			if (!empty($productid) && !empty($active_modules["Product_Options"])) {
				$is_variant = (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productid = '$productid'") > 0);
			}

			# Update the default price
			if (!$is_variant)
				func_array2update("pricing", array("price" => $price), "productid='$productid' AND quantity='1' AND membershipid = 0 AND variantid = 0");

			if ($fields['price'] == 'Y' && $geid && !$is_variant) {
				while ($pid = func_ge_each($geid, 1, $productid)) {
					if (
						empty($active_modules["Product_Options"]) ||
						func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productid = '$pid'") == 0
					) {
						func_array2update("pricing", array("price" => $price), "productid='$pid' AND quantity='1' AND membershipid = 0 AND variantid = 0");
					}
				}
			}

			$status = "modified";
		}

		# For existing product: get the categories list before updating
		if ($product_info) {
			if (!empty($active_modules['Multiple_Storefronts'])) {
				$profucts_sf = array();
			}
			$old_product_categories = func_query_column("SELECT categoryid FROM $sql_tbl[products_categories] WHERE productid='$productid'");
		}

# START: random:19885 [2010 Jan 11 11:55] 
		$categoryid = intval($categoryid);
		
		# Validate categoryid
		if (func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='$categoryid'")) {
# END: random:19885 [2010 Jan 11 11:55] 

		# Prepare and update categories associated with product...
		$query_data_cat = array(
			"categoryid" => $categoryid,
			"productid" => $productid,
			"main" => "Y"
		);
		if(!func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid = '$categoryid' AND productid = '$productid' AND main = 'Y'")) {
			db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid = '$productid' AND (main = 'Y' OR categoryid = '$categoryid')");
			func_array2insert("products_categories", $query_data_cat);
		}
		if($geid && $fields['categoryid']) {
			while ($pid = func_ge_each($geid, 1, $productid)) {
				if(!func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid = '$categoryid' AND productid = '$pid' AND main = 'Y'")) {
					db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid = '$pid' AND (main = 'Y' OR categoryid = '$categoryid')");
					$query_data_cat['productid'] = $pid;
					func_array2insert("products_categories", $query_data_cat);
				}
			}
		}
# START: random:19885 [2010 Jan 11 11:55] 
		}

		if ($categoryids) {
			$categoryids = explode(',', $categoryids);
			if ($categoryids) {
				foreach ($categoryids as $k=>$v) {
					$categoryids[$k] = intval($v);
				}
				# Validate categories ids
				if (!func_query_first_cell("SELECT count(*) FROM $sql_tbl[categories] WHERE categoryid IN ('".join("','",$categoryids)."')")) {
					$categoryids = false;
				}
			}
		}
# END: random:19885 [2010 Jan 11 11:55] 

		if ($categoryids) {
			foreach ($categoryids as $k=>$v) {
				
				$query_data_cat = array(
					"categoryid" => $v,
					"productid" => $productid,
					"main" => "N"
				);
				if (!func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid = '$v' AND productid = '$productid'")) {
					func_array2insert("products_categories", $query_data_cat);
				}
				if($geid && $fields['categoryids']) {
					while ($pid = func_ge_each($geid, 1, $productid)) {
						if(!func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products_categories] WHERE categoryid = '$v' AND productid = '$pid'")) {
							$query_data_cat['productid'] = $pid;
							func_array2insert("products_categories", $query_data_cat);
						}
					}
				}
			}
			db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid = '$productid' AND main != 'Y' AND categoryid NOT IN ('".implode("','", $categoryids)."')");
			if ($geid && $fields['categoryids']) {
				while ($pid = func_ge_each($geid, 100, $productid)) {
					db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid IN ('".implode("','",$pid)."') AND main != 'Y' AND categoryid NOT IN ('".implode("','", $categoryids)."')");
				}
			}

		} else {
			db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid = '$productid' AND main != 'Y'");
			if($geid && $fields['categoryids']) {
				while ($pid = func_ge_each($geid, 100, $productid)) {
					db_query("DELETE FROM $sql_tbl[products_categories] WHERE productid IN ('".implode("','",$pid)."') AND main != 'Y'");
				}
			}
		}

		if (!empty($active_modules['Multiple_Storefronts'])) {
			func_rebuild_product_sf($productid);
			if ($geid && ($fields['categoryid'] || $fields['categoryids'])) {
				while ($pid = func_ge_each($geid, 1, $productid)) {
					func_rebuild_product_sf($pid);
				}	
			}
		}
		# Check discount table update
		$rebuild_discount = false;
		$old_discount = func_query_first("SELECT discount_slope, discount_table FROM $sql_tbl[products] WHERE productid='$productid'");
		if (($old_discount && ($old_discount['discount_slope'] != $discount_slope || $old_discount['discount_table'] != $discount_table))
		     || ($product_info && $product_info['price'] != $price)) {
			$rebuild_discount = true;
		}

		# Correct the min_amount
		if (empty($min_amount) || intval($min_amount) == 0)
			$min_amount = 1;

#
##
###
		if ($min_amount > 1){

			$tmp_mult_order_quantity_arr = explode(",", $discount_table);

			if (!empty($tmp_mult_order_quantity_arr) && is_array($tmp_mult_order_quantity_arr)){
				foreach ($tmp_mult_order_quantity_arr as $k_dt => $v_dt){

					if ($v_dt < $min_amount){
						unset($tmp_mult_order_quantity_arr[$k_dt]);
						continue;
					}

					 if ($mult_order_quantity == "Y"){
						$cidev_tmp_result = $v_dt % $min_amount;
						if ($cidev_tmp_result != 0){
							unset($tmp_mult_order_quantity_arr[$k_dt]);
						}
					}
				}
			}

			$discount_table = implode(",", $tmp_mult_order_quantity_arr);
		}
###
##
#


		#
		# Update product data
		#
		$query_data = array(
			"product" => $product,
			//"keywords" => $keywords,
			"descr" => $descr,
			"fulldescr" => $fulldescr,
			"list_price" => $list_price,
			'map_price' => $map_price,
			'new_map_price' => $new_map_price,
			'cost_to_us' => $cost_to_us,
			"productcode" => $productcode,
			"forsale" => $forsale,
			"distribution" => $distribution,
# START: random:20460 [2010 Mar 18 13:43] 
			"free_ship_zone" => $free_ship_zone,
			"free_ship_text" => $free_ship_text,
# END: random:20460 [2010 Mar 18 13:43] 
			"shipping_freight" => $shipping_freight,
			"discount_avail" => $discount_avail,
			"min_amount" => $min_amount,
			"return_time" => $return_time,
			"low_avail_limit" => $low_avail_limit,
			"free_tax" => $free_tax,
			"discount_slope" => $discount_slope,
			"discount_table" => $discount_table,
			"upc" => $upc,
            'google_search_term' => $google_search_term,
			'mult_order_quantity' => $mult_order_quantity == 'Y' ? 'Y' : 'N',
		);
		if (($status == 'created' && $product_froogle == '') || ($status != 'created' && !isset($product_froogle))) {
			if (strlen($product) > FROOGLE_TITLE_LENGTH) {
				$query_data['product_froogle'] = substr($product, 0, FROOGLE_TITLE_LENGTH - 3).'###';
			} else {
				$query_data['product_froogle'] = $product;
			}
		} else {
			$query_data['product_froogle'] = $product_froogle;
		}
		if (!$is_variant) {
			$query_data['weight'] = $weight;
			$query_data['avail'] = $avail;
		}
		
        if (!empty($dimensions)) {
			$dimensions = explode(',', $dimensions);
			if (count($dimensions) >= 3) {
				rsort($dimensions);
				$query_data['dim_x'] = $dimensions[0];
				$query_data['dim_y'] = $dimensions[1];
				$query_data['dim_z'] = $dimensions[2];
			}
		}
		func_array2update("products", $query_data, "productid = '$productid'");

		if ($rebuild_discount) {
			func_generate_discounts(array($productid));
		}

		# Update memberships
		func_membership_update("product", $productid, $membershipids);
		if ($geid && $fields['membershipids'] == 'Y') {
			while($pid = func_ge_each($geid, 1, $productid)) {
				func_membership_update("product", $pid, $membershipids);
			}
		}

		# Update taxes
		db_query("DELETE FROM $sql_tbl[product_taxes] WHERE productid='$productid'");
		if($geid && $fields['taxes']) {
			while ($pid = func_ge_each($geid, 100, $productid)) {
				db_query("DELETE FROM $sql_tbl[product_taxes] WHERE productid IN ('".implode("','", $pid)."')");
			}
		}

		if (!empty($taxes) && is_array($taxes)) {
			foreach ($taxes as $k=>$v) {
				if (intval($v) > 0) {
					$query_data = array(
						"productid" => $productid,
						"taxid" => intval($v)
					);
					func_array2insert("product_taxes", $query_data, true);
					if($geid && $fields['taxes']) {
						while ($pid = func_ge_each($geid, 1, $productid)) {
							$query_data['productid'] = $pid;
							func_array2insert("product_taxes", $query_data, true);
						}
					}
				}
			}
		}

		# Group editing of products functionality
		if ($geid && !empty($fields)) {
			$query_data = array();
			foreach($fields as $k => $v) {
				if (
# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
					!in_array($k, array("efields", "price", "thumbnail", "product_image", "categoryid", "categoryids", "taxes", "membershipids","manufacturer","valid_for_gcheckout", "brand", "dimensions")) &&
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
					(!$is_variant || !in_array($k, array("avail", "weight")))
				) {
					$query_data[$k] = $$k;
				}
			}
			
            if (in_array("dimensions", $fields) && !empty($dimensions)) {
				$dimensions = explode(',', $dimensions);
				if (count($dimensions) >= 3) {
					rsort($dimensions);
					$query_data['dim_x'] = $dimensions[0];
					$query_data['dim_y'] = $dimensions[1];
					$query_data['dim_z'] = $dimensions[2];
				}
			}
			if (!empty($query_data)) {
				$is_variant_request = !$is_variant && (isset($query_data['avail']) || isset($query_data['weight'])) && !empty($active_modules["Product_Options"]);

				while ($pid = func_ge_each($geid, $is_variant_request ? 1 : 100, $productid)) {
					$query_data_sub = $query_data;
					if ($is_variant_request) {
						if (
							!empty($active_modules["Product_Options"]) &&
							func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productid = '$pid'") > 0
						) {
							func_unset($query_data_sub, "avail", "weight");
						}
						func_array2update("products", $query_data_sub, "productid = '$pid'");

					} else {
						func_array2update("products", $query_data, "productid IN ('".implode("','", $pid)."')");
					}
				}
			}
		}


		# Update categories data cache
		if (!empty($active_modules['Fancy_Categories'])) {
			
			if (!is_array($old_product_categories) || empty($old_product_categories)) {

				# Update in productid-based	mode (new product)
				$cats = func_fc_check_rebuild($productid, "P");
				if (!empty($cats))
					func_fc_build_categories($cats, 10);

			} else {

				# Update old product categories
				if ($config['Appearance']['count_products'] == 'Y') {
					$_categoryids = func_array_merge($categoryids, array($categoryid));
					$diff = array_diff($old_product_categories, $_categoryids);
					if (!empty($diff)) {
						$cats = func_fc_check_rebuild($old_product_categories);
						if (!empty($cats))
							func_fc_build_categories($cats, 10);
					}
				}
				$cats = func_fc_check_rebuild($productid, "P");
				if (!empty($cats))
					func_fc_build_categories($cats, 10);
			}

		}

		#
		# Update products counter for selected categories
		#
		if (is_array($old_product_categories))
			$categoryids = func_array_merge($old_product_categories, $categoryids);
		$categoryids = func_array_merge($categoryids, array($categoryid));
		func_recalc_product_count(func_get_category_parents($categoryids));

		if ($status == "created") {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_product_add");
			$top_message["type"] = "I";
		}
		elseif ($status == "modified") {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_product_upd");
			$top_message["type"] = "I";
		}

		if ($active_modules["Extra_Fields"]) {
			include $xcart_dir."/modules/Extra_Fields/extra_fields_modify.php";
		}

		if ($active_modules["Manufacturers"]) {
			@include $xcart_dir."/modules/Manufacturers/product_manufacturer.php";
		}

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
		if ($active_modules["Brands"]) {
			@include $xcart_dir."/modules/Brands/product_brand.php";
		}

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
		if ($active_modules["Google_Checkout"] && $gcheckout_enabled) {
			@include $xcart_dir."/modules/Google_Checkout/product_modify.php";
		}

		func_build_quick_flags($productid);
		func_build_quick_prices($productid);

		if ($geid && !empty($fields)) {
			while ($pid = func_ge_each($geid, 100, $productid)) {
				func_build_quick_flags($pid);
				func_build_quick_prices($pid);
			}
		}

		if ($current_area == 'P' && $status == 'modified' && $user_account['membership'] != 'Fulfillment') {
			db_query("UPDATE $sql_tbl[products] SET provider='$login', mod_date='" . time() . "' WHERE productid='$productid'");
		}

	} else {

		#
		# Form filled with errors
		#
		$top_message = array(
			"content" => "",
			"type" => "E",
		);
		if ($sku_is_exist) {
			$top_message['content'] = func_get_langvar_by_name("msg_adm_err_sku_exist");
			$top_message['fillerror'] = true;

		} else {
			$top_message['content'] = func_get_langvar_by_name("msg_adm_err_product_upd");
			$top_message['fillerror'] = true;
		}

		$product_modified_data = $_POST;
		foreach ($product_modified_data as $k => $v) {
			if (!is_array($v))
				$product_modified_data[$k] = stripslashes($v);
		}
		if (!empty($active_modules['Extra_Fields']) && !empty($product_modified_data['efields'])) {
			$product_modified_data['efields'] = array_map("stripslashes", $product_modified_data['efields']);
		}

		$product_modified_data["productid"] = $productid;

		if (!empty($product_modified_data['membershipids'])) {
			if (in_array("-1", $product_modified_data['membershipids'])) {
				$product_modified_data['membershipids'] = false;

			} else {
				$product_modified_data['membershipids'] = array_flip($product_modified_data['membershipids']);
				foreach ($product_modified_data['membershipids'] as $mid => $m) {
					$product_modified_data['membershipids'][$mid] = true;
				}
				
			}

		} else {
			$product_modified_data['membershipids'] = false;
		}

	}

	func_refresh();
}

include $xcart_dir . '/include/product_thumb_image.php';
include $xcart_dir . '/admin/product_clone.php';

#
# Detailed_Product_Images module
#
if ($active_modules["Detailed_Product_Images"]) {
	include $xcart_dir."/modules/Detailed_Product_Images/product_images_modify.php";
	include $xcart_dir."/modules/Detailed_Product_Images/product_images.php";
}

#
# Magnifier module
#
if ($active_modules["Magnifier"]) {
	include $xcart_dir."/modules/Magnifier/product_magnifier_modify.php";
}  

if (empty($active_modules["Product_Configurator"]) || !$is_pconf) {

	#
	# Subscription module
	#
	if ($active_modules["Subscriptions"]) {
		include $xcart_dir."/modules/Subscriptions/subscription_modify.php";
	}

	#
	# Wholesale trading module
	#
	if ($active_modules["Wholesale_Trading"] && $product_info['is_variants'] != 'Y') {
		include $xcart_dir."/modules/Wholesale_Trading/product_wholesale.php";
	}

	#
	# Product Configurator module
	#
	if ($active_modules["Product_Configurator"])
		include $xcart_dir."/modules/Product_Configurator/pconf_classification.php";
} #/ if ($mode != "pconf")

#
# Manufacturers module
#
if ($active_modules["Manufacturers"]) {
	@include $xcart_dir."/modules/Manufacturers/product_manufacturer.php";
}

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"]) {
	@include $xcart_dir."/modules/Brands/product_brand.php";
}
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
#
# Extra fields module
#
if ($active_modules["Extra_Fields"]) {
	$extra_fields_provider = ( $current_area == "A" ? $product_info["provider"] : $login );
	include $xcart_dir."/modules/Extra_Fields/extra_fields.php";
}

#
# Product options module
#
if ($active_modules["Product_Options"]) {
	if($section == 'options' || $config['General']['display_all_products_on_1_page'] == 'Y')
		@include $xcart_dir."/modules/Product_Options/product_options.php";
	if($section == 'variants' || $config['General']['display_all_products_on_1_page'] == 'Y')
		include $xcart_dir."/modules/Product_Options/product_variants.php";
}

#
# Feature comparision module
#
if ($active_modules["Feature_Comparison"])
    @include $xcart_dir."/modules/Feature_Comparison/product_class.php";

#
# Upselling products module
#
if ($active_modules["Upselling_Products"])
	include $xcart_dir."/modules/Upselling_Products/edit_upsales.php";

#
# Customer Reviews module
#
include $xcart_dir."/include/reviews.php";

if (($productid != "") && !$fillerror) {

    $product_info = func_select_product($productid, $user_account['membershipid']);

    $product_info['image'] = array(
        "T" => func_image_properties("T", $productid),
        "P" => func_image_properties("P", $productid)
    );
}

#
# Obtain VAT rates
#
if ($single_mode)
	$provider_condition = "";
elseif ($current_area == "A")
	$provider_condition = "AND provider='$product_info[provider]'";
else
	$provider_condition = "AND provider='$login'";

# random, 2010-05-07, use default provider instead:
if (!empty($provider_condition) && !empty($config['General']['default_provider_name'])) {
	 $provider_condition = "AND provider='".$config['General']['default_provider_name']."'";
}
#

#
# Check if image selected is not expired
#
if (!empty($file_upload_data["imtype"])){
  if ($file_upload_data["imtype"] == "T") {

	if ($file_upload_data["counter"] == 1) {
		$file_upload_data["counter"]++;

		$smarty->assign("file_upload_data", $file_upload_data);
	}
	else {
		if ($file_upload_data["source"] == "L")
			@unlink($file_upload_data["file_path"]);
		x_session_unregister("file_upload_data");
	}
  }
}

if (empty($product_info))
	$smarty->assign("new_product", 1);

if (!empty($product_modified_data)) {

	# Restore saved product data
	$product_info = $product_modified_data;

	if (!empty($active_modules['Product_Options']) && !empty($product_info['productid'])) {
		$product_info['is_variants'] = (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productid = '$product_info[productid]'") > 0) ? "Y" : "";
	}

	if (!empty($active_modules['Extra_Fields']) && !empty($product_info['efields']) && !empty($extra_fields)) {
		foreach ($extra_fields as $fid => $f) {
			if (isset($product_info['efields'][$f['fieldid']])) {
				$extra_fields[$fid]['is_value'] = 'Y';
				$extra_fields[$fid]['field_value'] = $product_info['efields'][$f['fieldid']];
			}
		}
		$smarty->assign("extra_fields", $extra_fields);
		unset($product_info['efields']);
	}

	if (!empty($product_info['categoryids']) && is_array($product_info['categoryids'])) {
		$product_info['add_categoryids'] = array_flip($product_info['categoryids']);
		foreach ($product_info['add_categoryids'] as $k => $v)
			$product_info['add_categoryids'][$k] = true;
	}

	if ($product_modified_data['is_image_T'] && $file_upload_data['T'] && $file_upload_data['T']['is_redirect']) {
		$file_upload_data['T']['is_redirect'] = false;
	}

	if ($product_modified_data['is_image_P'] && $file_upload_data['P'] && $file_upload_data['P']['is_redirect']) {
		$file_upload_data['P']['is_redirect'] = false;
	}


}

if (empty($product_info)) {

	# Define default SKU value
	$sku_prefix = 'SKU';
	$product_info['productcode'] = func_query_first_cell("SELECT MAX(productid) FROM $sql_tbl[products]");
	$plus = 0;
	while (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode='".$sku_prefix.($product_info['productcode']+(++$plus))."'")) {
	}

	$product_info['productcode'] = $sku_prefix.($product_info['productcode']+$plus);
}
else {
	define('NEED_PRODUCT_CATEGORIES', 1);
}

$taxes = func_query("SELECT $sql_tbl[taxes].*, COUNT($sql_tbl[product_taxes].productid) as selected FROM $sql_tbl[taxes] LEFT JOIN $sql_tbl[product_taxes] ON $sql_tbl[product_taxes].taxid = $sql_tbl[taxes].taxid AND $sql_tbl[product_taxes].productid = '$productid' GROUP BY $sql_tbl[taxes].taxid");
if (!empty($product_modified_data['taxes']) && !empty($taxes)) {
	foreach ($taxes as $k => $v) {
		if (in_array($v['taxid'], $product_modified_data['taxes']))
			$taxes[$k]['selected'] = 1;
	}

}

$smarty->assign("taxes", $taxes);

$replacements = func_query('SELECT `what`, `by` FROM ' . $sql_tbl['replacements']);
if (!empty($replacements)) {
	$smarty->assign('replacements', $replacements);
}

if (!empty($product_info)) {
	$product_info['mod_date'] = $product_info['mod_date'] == 0 ? $product_info['add_date'] : $product_info['mod_date'];
}

$smarty->assign("location", $location);
$smarty->assign("section", $section);

$smarty->assign("query_string", urlencode($QUERY_STRING));


#
##
###
if (empty($productid) && $mode_add_product == "y" && !empty($productcode)){
	if (!empty($manufacturerid)){
		$tmp_prod_info = func_query_first("SELECT $sql_tbl[manufacturers].cost_to_us_coef_x, $sql_tbl[manufacturers].price_coef_x, $sql_tbl[manufacturers].price_coef_y, $sql_tbl[manufacturers].price_coef_z, $sql_tbl[manufacturers].map_price_coef_x, $sql_tbl[manufacturers].new_map_price_coef_x FROM $sql_tbl[manufacturers] WHERE $sql_tbl[manufacturers].manufacturerid='$manufacturerid'");
		$product_info["cost_to_us_coef_x"] = $tmp_prod_info["cost_to_us_coef_x"];
		$product_info["price_coef_x"] = $tmp_prod_info["price_coef_x"];
		$product_info["price_coef_y"] = $tmp_prod_info["price_coef_y"];
		$product_info["price_coef_z"] = $tmp_prod_info["price_coef_z"];
		$product_info["map_price_coef_x"] = $tmp_prod_info["map_price_coef_x"];
		$product_info["new_map_price_coef_x"] = $tmp_prod_info["new_map_price_coef_x"];
		$product_info["manufacturerid"] = $manufacturerid;
	}
	$product_info["productcode"] = $productcode;
}
###
##
#


$smarty->assign("product", $product_info);
$smarty->assign("productid", $product_info["productid"]);

# START: random:20460 [2010 Mar 18 13:43] 
$shipping_zones = func_query("SELECT zoneid, zone_name FROM $sql_tbl[zones] WHERE 1 $provider_condition ORDER BY zone_name");
$smarty->assign("shipping_zones", $shipping_zones);

# END: random:20460 [2010 Mar 18 13:43] 
if (!empty($geid)) {
	$objects_per_page = $config["Appearance"]["products_per_page_admin"];
	$total_items = func_ge_count($geid);
	$total_nav_pages = ceil($total_items/$objects_per_page)+1;
	include $xcart_dir."/include/navigation.php";
	$smarty->assign("products", func_query("SELECT $sql_tbl[products].product, $sql_tbl[products].productcode, $sql_tbl[products].productid FROM $sql_tbl[products], $sql_tbl[ge_products] WHERE $sql_tbl[products].productid = $sql_tbl[ge_products].productid AND $sql_tbl[ge_products].geid = '$geid' LIMIT $first_page, $objects_per_page"));
	$smarty->assign("first_item", $first_page+1);
	$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
	$smarty->assign("redirect_geid", str_replace("&", "&amp;", $redirect_geid));
}

$smarty->assign("navigation_script", "product_modify.php?section=$section&productid=".$productid.$redirect_geid);

$product_modified_data = "";

$smarty->assign("fillerror", $fillerror);
if (!$category_exists) {
	$smarty->assign('category_exists', 'N');
}

x_session_save();

if (!empty($categoryid))
	$smarty->assign("default_categoryid", intval($categoryid));

if ($config['General']['display_all_products_on_1_page'] == 'Y') {
	foreach ($dialog_tools_data['left'] as $k => $v) {
		if ($k == 0) {
			$dialog_tools_data['left'][$k]['link'] .= "#section_main";
		}
		else {
			$dialog_tools_data['left'][$k]['link'] = preg_replace("/^.+&section=(.+)$/S", "#section_\\1", $v['link']);
		}
	}
}

$smarty->assign("product_languages", $product_languages);
$memberships = func_get_memberships('C');
if (!empty($memberships))
	$smarty->assign("memberships", $memberships);

if (!empty($active_modules["Product_Options"]) && $product_info['is_variants'] == 'Y') {
	$smarty->assign("variant_href", $pm_link."&section=variants");
}

$smarty->assign("geid", $geid);
?>
