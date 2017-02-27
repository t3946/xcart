<?php /* MODIFIED: random:20766 [2010 May 11 13:18][Custom development ("Additional categories" feature)] */ ?>
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
$trusted_post_variables = array("description","category_lng", "SEO_h2");

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','category','image');

x_session_register("file_upload_data");

#
# Update category or create new
#

if (empty($mode))
	$mode = "";

require $xcart_dir."/include/categories.php";

if ($REQUEST_METHOD == "POST") {
#
# Add/update/process category data
#

	if ($mode == "update_lng") {

		#
		# Process multilingual descriptions
		#

		if (!empty($active_modules['Fancy_Categories'])) {
			$old_data = func_fc_save_category_data($cat);
		}

		$category_lng['code'] = $shop_language;
		$category_lng['categoryid'] = $cat;
		func_array2insert("categories_lng", $category_lng, true);

		# Update categories data cache for Fancy categories module
		if (!empty($active_modules['Fancy_Categories'])) {
			$cats = func_fc_check_rebuild($cat, 'C', $old_data);
			if (!empty($cats))
				func_fc_build_categories($cats, 1, false, array($shop_language));
		}

		$top_message = array(
			"content" => func_get_langvar_by_name("msg_adm_category_int_upd"),
			"type" => "I"
		);
		func_header_location("category_modify.php?section=lng&cat=$cat&lng_updated");

	} elseif ($mode == "update" || $mode == "add") {

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
			func_header_location("category_modify.php?mode=$mode&cat=".($mode == 'add' ? $parent : $cat));

		}

/*
	        $clean_url = trim(stripslashes($clean_url));

	        // Check Clean URL format.
        	$current_clean_url = NULL;

	        if ($cat > 0) {
        	    $current_clean_url = func_clean_url_get_raw_resource_url('C', $cat);
	        }

	        if ($config['SEO']['clean_urls_enabled'] == 'N' || $mode != 'add' && $cat > 0 && !zerolen($current_clean_url) && $current_clean_url == $clean_url) {
        	    $clean_url_check_result = true;
	        } else {
        	    list($clean_url_check_result, $check_url_error_code) = func_clean_url_validate($clean_url);
	        }

	        if ($clean_url_check_result == false) {
        	    $errors[] = func_get_langvar_by_name('err_'.strtolower($check_url_error_code));
	            $clean_url_fill_error = true;
	        }

	        if (!empty($errors)) {

	            $top_message = array(
        	        'type' => 'E',
                	'content' => implode("<br /><br />", $errors),
	                'clean_url_fill_error' => $clean_url_fill_error
        	    );

	            func_header_location("category_modify.php?mode=$mode&cat=".($mode == 'add' ? $parent : $cat));
        	}
*/
		#
		# Check permissions
		#
		$perms_C = func_check_image_storage_perms($file_upload_data, 'C');
		if ($perms_C !== true) {
			$top_message = array(
				"content" => $perms_C['content'],
				"type" => "E"
			);
		
			func_header_location("category_modify.php?mode=$mode&cat=".($mode == 'add' ? $parent : $cat));
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
			$query = array(
					'parentid' => $parent,
					'description' => '',
			);

			$sf_error = false;

			if (!empty($active_modules['Multiple_Storefronts'])) {
				$query['storefrontid'] = $current_storefront;
				if ($parent != 0 && func_get_category_sf($parent) != $current_storefront) {
					$sf_error = true;
				}
			}

			if (!$sf_error) {
			$cat = func_array2insert('categories', $query);

			if ($parent == 0) {
				$parent_categoryid_path = $cat;
			} else {
				$parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$parent'")."/".$cat;
			}

			func_array2update("categories", array("categoryid_path" => $parent_categoryid_path), "categoryid = '$cat'");
			$top_message = array(
				"content" => func_get_langvar_by_name("msg_adm_category_add"),
				"type" => "I"
			);
			} else {
				$top_message = array(
					'content' => func_get_langvar_by_name('err_adm_category_add_sf'),
					'type' => 'E'
				);
			}

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

		if (empty($supplemental_category)){
			$supplemental_category = "N";
		}

		db_query("UPDATE $sql_tbl[categories] SET category='$category_name', description='$description', meta_descr='$meta_descr', meta_keywords='$meta_keywords', avail='$avail', order_by='$order_by', is_bold='$is_bold', pc_ready_to_classify='$pc_ready_to_classify', title_tag='$title_tag', SEO_category_name='$SEO_category_name', SEO_h2='".trim($SEO_h2)."', google_product_category='$google_product_category', prevent_index_products='$prevent_index_products', prevent_index_category_page='$prevent_index_category_page', supplemental_category='$supplemental_category' WHERE categoryid='$cat'");

        	    // Autogenerate clean URL.
	        $clean_url = func_clean_url_autogenerate('C', $cat, array('category' => $category_name));
        	$clean_url_save_in_history = false;
		db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$cat'");
	        func_clean_url_add($clean_url, 'C', $cat);



		#
		# Icon processing
		#
		if (func_check_image_posted($file_upload_data, "C")) {
			func_save_image($file_upload_data, "C", $cat);
		}

        if (is_array($_FILES) && isset($_FILES['edit_image'])) {
            $id = $cat;
            $from_parent_window = 'Y';
            $source = 'L';
            $filename = 'edit_image';
            $userfile = '';
            $type = 'C';

            $userfile = $_FILES[$filename]['name'];
            $userfile_size = $_FILES[$filename]['size'];
            $userfile_type = $_FILES[$filename]['type'];
            
            if (!empty($userfile)) {
                include $xcart_dir . '/include/image_selection.php';
            }
        }

		# Update categories data cache for Fancy categories module
		if (!empty($active_modules['Fancy_Categories'])) {
			$cats = func_fc_check_rebuild($cat, 'C', $old_data);
			if (!empty($cats))
				func_fc_build_categories($cats, 1);
		}

		# Update subcategories and products count for selected category and parent categories
		$path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid = '$cat'"));
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
		$old_path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid = '$cat'"));

		$new_parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$cat_location'");
		$current_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$cat'");
		if (!empty($new_parent_categoryid_path)) {
			$new_parent_categoryid_path .= "/";
		}

		$sf_error = false;
			
		if (!empty($active_modules['Multiple_Storefronts'])) {
			$parent_sf = func_get_category_sf($cat_location);
			if ($cat_location > 0 && ($parent_sf != func_get_category_sf($cat) || $parent_sf != $current_storefront)) {
				$sf_error = true;
			}
		}

		if (!$sf_error) {
		if (!empty($current_categoryid_path)) {
			db_query("UPDATE $sql_tbl[categories] SET parentid='$cat_location', categoryid_path='$new_parent_categoryid_path$cat' WHERE categoryid='$cat'");
			db_query("UPDATE $sql_tbl[categories] SET categoryid_path=CONCAT('$new_parent_categoryid_path$cat/', SUBSTRING(categoryid_path, ".(strlen($current_categoryid_path."/")+1).")) WHERE categoryid_path LIKE '$current_categoryid_path/%'");
		}
		}

# START: random:20766 [2010 May 11 13:18] 
		if (!empty($additional_cat_location)) {
			
			$sf_error = false;
			
			$additional_cat_location = explode(',', $additional_cat_location);
			if ($additional_cat_location) {
				foreach ($additional_cat_location as $k=>$v) {
					$additional_cat_location[$k] = intval($v);
					
					if (!empty($active_modules['Multiple_Storefronts']) && !empty($additional_cat_location[$k])) {
						$add_parent_sf = func_get_category_sf($additional_cat_location[$k]);
						if ($add_parent_sf != func_get_category_sf($cat) || $add_parent_sf != $current_storefront) {
							$sf_error = true;
						}
					}
				}
				# Validate categories ids
				if (!func_query_first_cell("SELECT count(*) FROM $sql_tbl[categories] WHERE categoryid IN ('".join("','",$additional_cat_location)."')")) {
					$additional_cat_location = false;
				}
			}
		}
		
			
		if ((!empty($additional_cat_location) || $additional_cat_location !== false) && !$sf_error) {
			db_query("DELETE FROM $sql_tbl[categories_parents] WHERE categoryid='$cat'");
		}
		if (!empty($additional_cat_location) && !$sf_error) {
			foreach ($additional_cat_location as $add_cat) {
				$insert = array();
				$insert["categoryid"] = $cat;
				$insert["parentid"] = $add_cat;
				func_array2insert("categories_parents", $insert);
			}
		}

# END: random:20766 [2010 May 11 13:18] 
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
		$path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid = '$cat'"));
		if (!empty($path) && !empty($old_path)) {
			$path = array_merge($path, $old_path);
			func_recalc_subcat_count(array_unique($path));
		}

		if (!$sf_error) {
		$top_message = array(
			"content" => func_get_langvar_by_name("msg_adm_category_move"),
			"type" => "I"
		);
		} else {
			$top_message = array(
				'content' => func_get_langvar_by_name('err_adm_category_upd_sf'),
				'type' => 'W'
			);
		}

	} elseif ($mode == 'clean_urls_history') {

	        if (empty($clean_urls_history) || !is_array($clean_urls_history)) {
        	    $top_message['content'] = func_get_langvar_by_name('err_clean_urls_history_empty');
	            $top_message['type'] = 'E';

	            func_header_location("category_modify.php?cat=$cat");
        	}

	        if (func_clean_url_history_delete(array_keys($clean_urls_history))) {
        	    $top_message['content'] = func_get_langvar_by_name('txt_clean_urls_history_deleted');
	            $top_message['type'] = 'I';
	        } else {
        	    $top_message['content'] = func_get_langvar_by_name('err_clean_urls_history_delete');
	            $top_message['type'] = 'E';
	        }
	} 
#
##
###
	elseif ($mode == 'mode_add_one_keyphrase_per_line') {

		$add_one_keyphrase_per_line = trim($add_one_keyphrase_per_line);

		if (!empty($add_one_keyphrase_per_line)){

			$add_one_keyphrase_per_line_arr = explode("\n", $add_one_keyphrase_per_line);

			if (!empty($add_one_keyphrase_per_line_arr) && is_array($add_one_keyphrase_per_line_arr)){
				foreach ($add_one_keyphrase_per_line_arr as $k => $v){

					$keyphrase_line = trim($v);

					if (!empty($keyphrase_line)){
						$is_such_keyphrase_line = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[seo_categories_keyphrases] WHERE sfid='$current_storefront' AND keyphrase='$keyphrase_line'");
						if (empty($is_such_keyphrase_line)){
							db_query("INSERT INTO $sql_tbl[seo_categories_keyphrases] (categoryid, keyphrase) VALUES ('$cat', '$keyphrase_line')");
						}
					}
				}
			}

	                $top_message['content'] = "Done";
        	        $top_message['type'] = 'I';
		}
		
		$add_anchor = "#seo_module";
	}
	elseif ($mode == 'mode_update_categories_keyphrases' && !empty($post_seo_categories_keyphrases["keyphrases"]) && is_array($post_seo_categories_keyphrases["keyphrases"])) {

		foreach ($post_seo_categories_keyphrases["keyphrases"] as $k => $v){
	
			$keyphrase_line = trim($v);

                        if (!empty($keyphrase_line)){
				$is_such_keyphrase_line = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[seo_categories_keyphrases] WHERE sfid='$current_storefront' AND keyphrase='$keyphrase_line'");
				if (empty($is_such_keyphrase_line)){
					db_query("UPDATE $sql_tbl[seo_categories_keyphrases] SET keyphrase='$keyphrase_line' WHERE id='$k'");
				}
			}
		}

                $top_message['content'] = "Done";
                $top_message['type'] = 'I';
		$add_anchor = "#seo_module";
	}
        elseif ($mode == 'mode_delete_categories_keyphrases' && !empty($post_seo_categories_keyphrases["select"]) && is_array($post_seo_categories_keyphrases["select"])) {

                foreach ($post_seo_categories_keyphrases["select"] as $k => $v){
			db_query("DELETE FROM $sql_tbl[seo_categories_keyphrases] WHERE id='$k'");
                }

                $top_message['content'] = "Done";
                $top_message['type'] = 'I';
		$add_anchor = "#seo_module";
	}
	elseif ($mode == 'mode_update_linked_out_category') {

		foreach ($linked_out_category_indexes as $k => $v){

			$linked_out_category_id = "linked_out_category_id_".$v;
			$linked_out_category_id = $$linked_out_category_id;

			if (!empty($linked_out_category_id)){

				$update_linked_out_category_id = true;

				$categoryid_path_arr = explode("/", $current_category["categoryid_path"]);

				if (in_array($linked_out_category_id, $categoryid_path_arr)){
					$update_linked_out_category_id = false;
				} else {
				
					$linked_out_category_id_parentid = func_query_first_cell("SELECT parentid FROM $sql_tbl[categories] WHERE categoryid='$linked_out_category_id'");

        	                        if ($linked_out_category_id == $cat || $linked_out_category_id_parentid == $current_category["parentid"]){
                	                        /*$update_linked_out_category_id = false;*/
                        	        }
				}

				if ($update_linked_out_category_id){

					foreach ($linked_out_category_indexes as $kk => $vv){

						$is_such_linked_out_category_id = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[categories] WHERE linked_out_category_id_$vv='$linked_out_category_id' AND categoryid='$cat'");
						if (!empty($is_such_linked_out_category_id)){
							$update_linked_out_category_id = false;
							break;
						}
					}
				}

				if ($update_linked_out_category_id){
					db_query("UPDATE $sql_tbl[categories] SET linked_out_category_id_$v='$linked_out_category_id' WHERE categoryid='$cat'");
				}
				else {
					$add_text_message = "Duplicate linked-out category!";
				}
			}

			$linked_out_category_keyphrase_id = "linked_out_category_keyphrase_id_".$v;
			$linked_out_category_keyphrase_id = $$linked_out_category_keyphrase_id;

			if (!empty($linked_out_category_keyphrase_id) && is_array($linked_out_category_keyphrase_id)){
				$linked_out_category_keyphrase_id_arr = array();

				foreach ($linked_out_category_keyphrase_id as $kk => $vv){
					$is_free_keyphrase = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[seo_categories_keyphrases] WHERE id='$vv' AND used='N'");
					if ($is_free_keyphrase){
//						$linked_out_category_keyphrase_id_arr[] = $vv;
						db_query("UPDATE $sql_tbl[seo_categories_keyphrases] SET used='Y' WHERE id='$vv'");
						db_query("UPDATE $sql_tbl[categories] SET linked_out_category_keyphrase_id_$v='$vv' WHERE categoryid='$cat'");
					}
				}

/*
				if (!empty($linked_out_category_keyphrase_id_arr)){
					$linked_out_category_keyphrase_id_str = implode(",", $linked_out_category_keyphrase_id_arr);
					if (!empty($linked_out_category_keyphrase_id_str)){
						$linked_out_category_keyphrase_id_str = ",".$linked_out_category_keyphrase_id_str.",";
					}
		                        db_query("UPDATE $sql_tbl[categories] SET linked_out_category_keyphrase_id_$v='$linked_out_category_keyphrase_id_str' WHERE categoryid='$cat'");
				}

				unset($linked_out_category_keyphrase_id_arr);
*/
			}
		}

                $top_message['content'] = "Done.<br />".$add_text_message;
                $top_message['type'] = 'I';
		$add_anchor = "#seo_module";
	}
	elseif ($mode == 'mode_clear_linked_out_category') {

                foreach ($linked_out_category_indexes as $k => $v){

                        $post_linked_out_category_clear = "post_linked_out_category_clear_".$v;
                        $post_linked_out_category_clear = $$post_linked_out_category_clear;

			if ($post_linked_out_category_clear == "Y"){

				$linked_out_category_keyphrase_ids = func_query_first_cell("SELECT linked_out_category_keyphrase_id_$v FROM $sql_tbl[categories] WHERE categoryid='$cat'");
				$linked_out_category_keyphrase_ids_arr = explode(",", $linked_out_category_keyphrase_ids);
				if (!empty($linked_out_category_keyphrase_ids_arr) && is_array($linked_out_category_keyphrase_ids_arr)){
					foreach ($linked_out_category_keyphrase_ids_arr as $kk => $vv){
						if (!empty($vv)){
							db_query("UPDATE $sql_tbl[seo_categories_keyphrases] SET used='N' WHERE id='$vv'");
						}
					}
				}

				db_query("UPDATE $sql_tbl[categories] SET linked_out_category_id_$v='0', linked_out_category_keyphrase_id_$v='' WHERE categoryid='$cat'");
			}
                }

                $top_message['content'] = "Done";
                $top_message['type'] = 'I';
		$add_anchor = "#seo_module";

//func_print_r($_POST);
//die();
	}
###
##
#

	func_header_location("category_modify.php?cat=".$cat.$add_anchor);

} # /$REQUEST_METHOD == "POST"

if ($mode == "del_lang") {
	#
	# Delete multilingual dscription
	#
	if (!empty($active_modules['Fancy_Categories'])) {
		$old_data = func_fc_save_category_data($cat);
	}

	db_query("DELETE FROM $sql_tbl[categories_lng] WHERE categoryid = '$cat' AND code = '$shop_language'");

	if (!empty($active_modules['Fancy_Categories'])) {
		$cats = func_fc_check_rebuild($cat, 'C', $old_data);
		if (!empty($cats))
			func_fc_build_categories($cats, 1, false, array($shop_language));
	}

	$top_message = array(
		"content" => func_get_langvar_by_name("msg_adm_category_int_del"),
		"type" => "I"
	);
	func_header_location("category_modify.php?section=lng&cat=".$cat);
}

if ($REQUEST_METHOD == "GET" && $mode == "delete_icon" && !empty($cat)) {
#
# Delete icon
#
	func_delete_image($cat, "C");
	$top_message = array(
		"content" => func_get_langvar_by_name("msg_adm_category_icon_del"),
		"type" => "I"
	);
	func_header_location("category_modify.php?cat=$cat");
}

#
# Assign page location
#
$location[] = array(func_get_langvar_by_name("lbl_categories_management"), "categories.php");

if ($mode == "add") {
	$location[] = array(func_get_langvar_by_name("lbl_add_category"), "category_modify.php?mode=add&cat=$cat");
	if (!empty($current_category)) {
		$current_category['SEO_category_name'] = '';
		$current_category['SEO_h2'] = '';
		$smarty->assign('current_category', $current_category);
	}
}
else {
	$location[] = array(func_get_langvar_by_name("lbl_modify_category"), "category_modify.php?cat=$cat");
	if ($section == 'lng') {
		$location[] = array(func_get_langvar_by_name("txt_international_descriptions"), "category_modify.php?section=lng&cat=$cat");
		$dialog_tools_data["left"][] = array("link" => "category_modify.php?cat=".$cat, "title" => func_get_langvar_by_name("lbl_modify_category"));
	} else {
		$dialog_tools_data["left"][] = array("link" => "category_modify.php?section=lng&cat=".$cat, "title" => func_get_langvar_by_name("txt_international_descriptions"));
	}

}


//require $xcart_dir."/include/categories.php";

require "./location_ajust.php";

if ($mode != "add" && !empty($current_category) && !empty($all_categories)) {
#
# Correct the all_categories array: 'moving_enabled' field
#
	foreach ($all_categories as $k=>$v) {
		if ($k != $cat && !preg_match("|^".preg_quote($current_category["categoryid_path"])."\/|S", $v["categoryid_path"])) {
			$all_categories[$k]["moving_enabled"] = 1;
		}
# START: random:20766 [2010 May 11 13:18] 
		if (!empty($current_category["additional_parentids"])) {
			$all_categories[$k]["additional_parent_selected"] = (in_array($k, $current_category["additional_parentids"]));
		}
# END: random:20766 [2010 May 11 13:18] 
	}
	$smarty->assign("allcategories", $all_categories);
}

#
# Prepare multi languages
#
if ($section == 'lng') {
	$category_lng = func_query_first("SELECT $sql_tbl[categories_lng].* FROM $sql_tbl[categories_lng] WHERE $sql_tbl[categories_lng].categoryid='$cat' AND $sql_tbl[categories_lng].code = '$shop_language'");

	$smarty->assign("category_lng", $category_lng);
}

#
# Check if image selected is not expired
#
if (!empty($file_upload_data)){
  if ($file_upload_data["counter"] == 1) {
	$file_upload_data["counter"]++;

	$smarty->assign("file_upload_data", $file_upload_data);
  }
  else {
	if ($file_upload_data["source"] == "L")
		@unlink($file_upload_data["file_path"]);
	x_session_unregister("file_upload_data");
  }
} else {
        x_session_unregister("file_upload_data");
}


$replacements = func_query('SELECT `what`, `by` FROM ' . $sql_tbl['replacements']);
if (!empty($replacements)) {
	$smarty->assign('replacements', $replacements);
}

if (!in_array($mode, array("add", "update")))
	$mode = "update";

#
##
###
$seo_categories_keyphrases = func_query("SELECT * FROM $sql_tbl[seo_categories_keyphrases] WHERE categoryid='$cat' ORDER BY id");

if (!empty($seo_categories_keyphrases)){
	foreach ($seo_categories_keyphrases as $k => $v){

		$cat_ids_arr = array();
		$cat_id_name_arr = array();

		foreach ($linked_out_category_indexes as $kk => $vv){

                        $linked_out_category_keyphrase_id = "linked_out_category_keyphrase_id_".$vv;
//			$linked_out_category_id = "linked_out_category_id_".$vv;

			$cat_id = func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE $linked_out_category_keyphrase_id='".$v["id"]."'");

//func_print_r($qqq, $cat_id);

			if (!empty($cat_id) && !in_array($cat_id, $cat_ids_arr)){
				$cat_ids_arr[] = $cat_id;

				$categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$cat_id'");
				$categoryid_path_data = func_categoryid_path2category_path($categoryid_path);
				$categoryid_path_data_full = implode("/", $categoryid_path_data);
				$cat_id_name_arr[$cat_id] = $categoryid_path_data_full;
			}
		}

		if (!empty($cat_id_name_arr)){
			$seo_categories_keyphrases[$k]["cat_id_name_arr"] = $cat_id_name_arr;
		}

		unset($cat_ids_arr);
		unset($cat_id_name_arr);
	}
}

$smarty->assign("seo_categories_keyphrases", $seo_categories_keyphrases);
//func_print_r($seo_categories_keyphrases);
###
##
#

if (empty($supplemental_category_section)){
	$supplemental_category_section = "N";
}
$smarty->assign("supplemental_category_section", $supplemental_category_section);

$smarty->assign("query_string", urlencode($QUERY_STRING));
$smarty->assign("rand", rand());
$smarty->assign("mode", $mode);
$smarty->assign("section", $section);
$smarty->assign("main","category_modify");

$smarty->assign("image", func_image_properties("C", $cat));

x_session_save();

# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
