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
# $Id: category_modify.php,v 1.93.2.2 2006/06/02 08:29:17 max Exp $
#

define("IS_MULTILANGUAGE", true);
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array("description","category_lng");

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','category','image');

x_session_register("file_upload_data");

#
# Update category or create new
#

if (empty($mode))
	$mode = "";

if ($REQUEST_METHOD == "POST") {
#
# Add/update/process category data
#

	 if ($mode == "update" || $mode == "add") {

		#
		# Add/Update category data
		#
		$category_name = trim($category_name);
		if (empty($category_name)) {
			#
			# Display the error message
			#
			$top_message = array(
				"content" => func_get_langvar_by_name("err_filling_form"),
				"type" => "E"
			);
			func_header_location("info_category_modify.php?mode=$mode&cat=".($mode == 'add' ? $parent : $cat));

		}

		#
		# Check permissions
		#
		$perms_C = func_check_image_storage_perms($file_upload_data, 'C');
		if ($perms_C !== true) {
			$top_message = array(
				"content" => $perms_C['content'],
				"type" => "E"
			);
		
			func_header_location("info_category_modify.php?mode=$mode&cat=".($mode == 'add' ? $parent : $cat));
		}

		if ($mode == "add") {
			#
			# Add new category
			#
			if (!empty($parent))
				$parent = intval($parent);

			#
			# Create a new category: add main data
			#
			$cat = func_array2insert(
				"info_pages_categories", 
				array(
					"parentid" => $parent,
					"description" => ''
				)
			);

			if ($parent == 0) {
				$parent_categoryid_path = $cat;
			} else {
				$parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid='$parent'")."/".$cat;
			}

			func_array2update("info_pages_categories", array("categoryid_path" => $parent_categoryid_path), "categoryid = '$cat'");
			$top_message = array(
				"content" => func_get_langvar_by_name("msg_adm_category_add"),
				"type" => "I"
			);

		} else {

			$top_message = array(
				"content" => func_get_langvar_by_name("msg_adm_category_upd"),
				"type" => "I"
			);
			if (!empty($active_modules['Fancy_Categories'])) {
				$old_data = func_fc_save_category_data($cat);
			}

		}

		#
		# Update general data of category
		#

		db_query("UPDATE $sql_tbl[info_pages_categories] SET category='$category_name', description='$description', meta_descr='$meta_descr', meta_keywords='$meta_keywords', avail='$avail', order_by='$order_by' WHERE categoryid='$cat'");

		func_membership_update("info_pages_categories", $cat, $membershipids);

		#
		# Icon processing
		#
		if (func_check_image_posted($file_upload_data, "C")) {
			func_save_image($file_upload_data, "C", $cat);
		}

		# Update categories data cache for Fancy categories module
		if (!empty($active_modules['Fancy_Categories'])) {
			$cats = func_fc_check_rebuild($cat, 'C', $old_data);
			if (!empty($cats))
				func_fc_build_categories($cats, 1);
		}

		# Update subcategories and products count for selected category and parent categories
		$path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid = '$cat'"));
		if (!empty($path)) {
			func_recalc_subcat_count($path);
		}

	} elseif ($mode == "move" && !empty($cat)) {

		#
		# Move category to another location
		#
		if (!empty($active_modules['Fancy_Categories'])) {
			$old_data = func_fc_save_category_data($cat);
			$cats_old = func_fc_check_rebuild($cat);
		}

		# Get old category path
		$old_path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid = '$cat'"));

		$new_parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid='$cat_location'");
		$current_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid='$cat'");
		if (!empty($new_parent_categoryid_path)) {
			$new_parent_categoryid_path .= "/";
		}

		if (!empty($current_categoryid_path)) {
			db_query("UPDATE $sql_tbl[info_pages_categories] SET parentid='$cat_location', categoryid_path='$new_parent_categoryid_path$cat' WHERE categoryid='$cat'");
			db_query("UPDATE $sql_tbl[info_pages_categories] SET categoryid_path=CONCAT('$new_parent_categoryid_path$cat/', SUBSTRING(categoryid_path, ".(strlen($current_categoryid_path."/")+1).")) WHERE categoryid_path LIKE '$current_categoryid_path/%'");
		}

		# Update categories data cache for Fancy categories module
		if (!empty($active_modules['Fancy_Categories'])) {
			$cats = func_fc_check_rebuild($cat, 'C', $old_data);
			if ($cats === true || $cats_old === true) {
				func_fc_build_categories(true, 10);

			} elseif (!empty($cats) || !empty($cats_old)) {
				if (empty($cats) && !empty($cats_old)) {
					$cats = $cats_old;
				} elseif (!empty($cats) && !empty($cats_old)) {
					$cats = array_merge($cats, $cats_old);
				}
				func_fc_build_categories($cats, 1);

			}
		}

		# Update subcategories and products count for selected category and parent categories
		$path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[info_pages_categories] WHERE categoryid = '$cat'"));
		if (!empty($path) && !empty($old_path)) {
			$path = array_merge($path, $old_path);
			func_recalc_subcat_count(array_unique($path));
		}

		$top_message = array(
			"content" => func_get_langvar_by_name("msg_adm_category_move"),
			"type" => "I"
		);

	}

	func_header_location("info_category_modify.php?cat=".$cat);

} # /$REQUEST_METHOD == "POST"

#
# Assign page location
#
$location[] = array(func_get_langvar_by_name("lbl_info_pages"), "info_pages.php");

if ($mode == "add")
	$location[] = array(func_get_langvar_by_name("lbl_add_category"), "info_category_modify.php?mode=add&cat=$cat");
else {
	$location[] = array(func_get_langvar_by_name("lbl_modify_category"), "info_category_modify.php?cat=$cat");
	if ($section == 'lng') {
		$location[] = array(func_get_langvar_by_name("txt_international_descriptions"), "info_category_modify.php?section=lng&cat=$cat");
		$dialog_tools_data["left"][] = array("link" => "info_category_modify.php?cat=".$cat, "title" => func_get_langvar_by_name("lbl_modify_category"));
	} else {
		$dialog_tools_data["left"][] = array("link" => "info_category_modify.php?section=lng&cat=".$cat, "title" => func_get_langvar_by_name("txt_international_descriptions"));
	}

}


require $xcart_dir."/include/info_page_categories.php";

require "./location2_ajust.php";

if ($mode != "add" && !empty($current_category) && !empty($all_categories)) {
#
# Correct the all_categories array: 'moving_enabled' field
#
	foreach ($all_categories as $k=>$v) {
		if ($k != $cat && !preg_match("|^".preg_quote($current_category["categoryid_path"])."\/|S", $v["categoryid_path"])) {
			$all_categories[$k]["moving_enabled"] = 1;
		}
	}
	$smarty->assign("allcategories", $all_categories);
}

#
# Check if image selected is not expired
#
if ($file_upload_data["counter"] == 1) {
	$file_upload_data["counter"]++;

	$smarty->assign("file_upload_data", $file_upload_data);
}
else {
	if ($file_upload_data["source"] == "L")
		@unlink($file_upload_data["file_path"]);
	x_session_unregister("file_upload_data");
}

if (!in_array($mode, array("add", "update")))
	$mode = "update";

$smarty->assign("query_string", urlencode($QUERY_STRING));
$smarty->assign("rand", rand());
$smarty->assign("mode", $mode);
$smarty->assign("section", $section);
$smarty->assign("main","info_category_modify");

$smarty->assign("memberships", func_get_memberships("C"));

$smarty->assign("image", func_image_properties("C", $cat));

x_session_save();

# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
