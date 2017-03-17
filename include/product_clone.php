<?php /* MODIFIED: random:536881009 [2010 Mar 22 14:23][Custom development ("Ability to upload several files at once" and "Modifications to products clone")] */ ?>
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
# $Id: product_clone.php,v 1.39.2.4 2006/08/23 13:06:24 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('category');

define('DEFAULT_SHIPPING_FREIGHT', 0.01);

#
# This is the array of tables that should be affected by clone procedure
#
$tables_array = array(
	array("table"=>"delivery","key_field"=>"productid"),
	array("table"=>"products_lng","key_field"=>"productid"),
	array("table"=>"subscriptions","key_field"=>"productid"),
	array("table"=>"products_categories","key_field"=>"productid"),
	array("table"=>"product_taxes","key_field"=>"productid"),
	array("table"=>"product_memberships","key_field"=>"productid")
);

# START: random:536881009 [2010 Mar 22 14:23] 
if (is_array($clone)) {
	if ($clone['thumbnail'] == 'Y') {
		$tables_array[] = array('table'=>'images_T','key_field'=>'id');
	}

	if ($clone['product_image'] == 'Y') {
		$tables_array[] = array('table'=>'images_P','key_field'=>'id');
	}

	if ($active_modules['Detailed_Product_Images'] && $clone['detailed_images'] == 'Y') {
		$tables_array[] = array('table'=>'images_D','key_field'=>'id');
	}
}

# END: random:536881009 [2010 Mar 22 14:23] 
if ($active_modules["Product_Configurator"]) {
	$tables_array[] = array("table"=>"pconf_products_classes","key_field"=>"productid");
	$tables_array[] = array("table"=>"pconf_wizards","key_field"=>"productid");
	$tables_array[] = array("table"=>"languages_alt","key_field"=>"name");
}

if ($active_modules["Product_Options"]) {
	$tables_array[] = array("table"=>"product_options_js","key_field"=>"productid");
}

if ($active_modules["Extra_Fields"]) {
	$tables_array[] = array("table"=>"extra_field_values","key_field"=>"productid");
}

if ($active_modules["Feature_Comparison"]) {
	$tables_array[] = array("table"=>"product_features","key_field"=>"productid");
	$tables_array[] = array("table"=>"product_foptions","key_field"=>"productid");
}

#
# Make copying data for specified product in another table
#
function func_copy_tables($table, $key_field, $productid, $new_productid) {
	global $sql_tbl;
	global $xcart_dir;
	global $language_var_names;

	if (empty($table))
		return false;

	$table_name = $sql_tbl[$table];

	$error_string = "";

	$res = db_query("SHOW COLUMNS FROM $table_name");
	while ($row = db_fetch_array($res)) {
		$name  = $row['Field'];
		$flags = $row['Extra'];
		$fields[$name] = $flags;
	}

	db_free_result($res);

	$result = func_query("SELECT * FROM $table_name WHERE $key_field='$productid'");

	if (!$result)
		return false;

	foreach ($result as $key=>$row) {
		if (!$row) continue;

		$str = "INSERT INTO $table_name (";
		foreach ($row as $k=>$v) {
			if (is_numeric($k)) continue;

			if ($k==$key_field || !preg_match("/auto_increment/i",$fields[$k]))
				$str .= "$k,";
		}

		$str = preg_replace("/,$/", ") VALUES (", $str);
		foreach ($row as $k=>$v) {
			if (is_numeric($k)) continue;

			if ($k==$key_field || !preg_match("/auto_increment/i",$fields[$k])) {
				if ($k == $key_field) {
					if (is_numeric($new_productid))
						$str .= "$new_productid,";
					else
						$str .= "'".addslashes($new_productid)."',";
				}
				else {
					$str .= "'".addslashes($v)."',";
				}
			}
		}

		$str = preg_replace("/,$/Ss", ")", $str);
		$res = db_query($str);

		if (db_affected_rows($res) < 0) {
			$error_string .= "$str<br />";
		}
		else {
			#
			# Create additional records in the linked tables
			#
			if ($table == "pconf_products_classes") {
				$new_classid = db_insert_id();
				$old_classid = $row["classid"];
				func_copy_tables("pconf_class_specifications", "classid", $old_classid, $new_classid);
				func_copy_tables("pconf_class_requirements", "classid", $old_classid, $new_classid);
			}

			if ($table == "pconf_wizards") {
				$new_stepid = db_insert_id();
				$old_stepid = $row["stepid"];

				$old_stepname = $language_var_names["step_name"].$old_stepid;
				$old_stepdescr = $language_var_names["step_descr"].$old_stepid;
				$new_stepname = $language_var_names["step_name"].$new_stepid;
				$new_stepdescr = $language_var_names["step_descr"].$new_stepid;

				db_query("UPDATE $sql_tbl[pconf_wizards] SET step_name='$new_stepname', step_descr='$new_stepdescr' WHERE stepid='$new_stepid'");
				func_copy_tables("languages_alt", "name", $old_stepname, $new_stepname);
				func_copy_tables("languages_alt", "name", $old_stepdescr, $new_stepdescr);
				func_copy_tables("pconf_slots", "stepid", $old_stepid, $new_stepid);
			}

			if ($table == "pconf_slots") {
				$new_slotid = db_insert_id();
				$old_slotid = $row["slotid"];

				$old_slotname = $language_var_names["slot_name"].$old_slotid;
				$old_slotdescr = $language_var_names["slot_descr"].$old_slotid;
				$new_slotname = $language_var_names["slot_name"].$new_slotid;
				$new_slotdescr = $language_var_names["slot_descr"].$new_slotid;

				db_query("UPDATE $sql_tbl[pconf_slots] SET slot_name='$new_slotname', slot_descr='$new_slotdescr' WHERE slotid='$new_slotid'");
				func_copy_tables("languages_alt", "name", $old_slotname, $new_slotname);
				func_copy_tables("languages_alt", "name", $old_slotdescr, $new_slotdescr);
				func_copy_tables("pconf_slot_rules", "slotid", $old_slotid, $new_slotid);
				func_copy_tables("pconf_slot_markups", "slotid", $old_slotid, $new_slotid);
			}
		}
	}

	return $error_string;
}


#
# Get product info
#
if ($productid!="") {
	$product_info = func_query_first("SELECT * FROM $sql_tbl[products] WHERE productid='$productid'");
}

//if ($product_info["provider"]==$login || $single_mode || $current_area == "A") {
if ($current_area == "P" || $single_mode || $current_area == "A") {

	$c_login = ($current_area == "A" ? $product_info["provider"] : $login);
	#
	# Get unique productcode (SKU) value
	#
# START: random:536881009 [2010 Mar 22 14:23] 


  if (empty($productcode)){

	$found_sku = false;
	for ($i = 1; $i <= 1000; $i++) {
		$productcode = $product_info["productcode"] . '-CLON' . ($i > 1 ? $i : '');
		if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode='$productcode'") == 0) {
			$found_sku = true;
			break;
		}
	}

	if (!$found_sku) {
		# Old style SKU generation
# END: random:536881009 [2010 Mar 22 14:23] 
	$max_productcode = 0;
	if (defined("X_MYSQL_VERSION") && func_version_compare(X_MYSQL_VERSION, "4.0.2") >= 0) {
		$max_productcode = func_query_first_cell("SELECT MAX(CAST(SUBSTRING(productcode, 4) AS UNSIGNED)) FROM $sql_tbl[products] WHERE productcode REGEXP '^SKU[0-9]+$'");

	} else {
		$res = db_query("SELECT productcode FROM $sql_tbl[products] WHERE productcode REGEXP '^SKU[0-9]+$'");

		if ($res) {
			while ($row = db_fetch_array($res)) {
				$row = intval(substr(array_pop($row), 3));
				if ($row > $max_productcode)
					$max_productcode = $row;
			}

			db_free_result($res);
		}
	}

	$max_productcode = empty($max_productcode) ? 1000 : intval($max_productcode);

	while (1) {
		$productcode = "SKU".rand($max_productcode, $max_productcode+99);

		if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode='$productcode'") == 0)
			break;
	}
# START: random:536881009 [2010 Mar 22 14:23] 
	}
# END: random:536881009 [2010 Mar 22 14:23] 


  }
  else {

	$cidev_tmp_productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='".addslashes($productcode)."'");
	
	if (!empty($cidev_tmp_productid)){
		func_header_location("product_modify.php?productid=$cidev_tmp_productid");
	}

  }


	#
	# Create a new product
	#
	$query = "INSERT INTO $sql_tbl[products] (provider, add_date, productcode) VALUES ('$c_login', '".time()."', '$productcode')";
	$res = db_query($query);
	$new_productid = db_insert_id();

	if (!empty($active_modules["Magnifier"])) {
		include $xcart_dir."/modules/Magnifier/clone.php";
	}

	if (db_affected_rows($res) < 0)
		$error_string = "$query<br />";

	if ($new_productid) {
		#
		# Update just created product by values from existing product
		#
		$product_info['shipping_freight'] = DEFAULT_SHIPPING_FREIGHT;
		$query = "UPDATE $sql_tbl[products] SET ";
		foreach ($product_info as $k=>$v) {
			if (!is_numeric($k) && $k!="productid" && $k!="productcode" && $k!="provider" && $k!="add_date" && $k!="views_stats" && $k!="del_stats" && $k!="sales_stats" && $k!="avail" ) {
				if ($k=="product") $v="$v (CLON)";

#
##
###
				if ($k=="upc") $v="";
//                                if ($k=="avail") $v="0";
                                if ($k=="r_avail") $v="1000000";
                                if ($k=="low_avail_limit") $v="1000";
###
##
#

				$query .= "`$k`='".addslashes($v)."', ";
			}
		}

		$query = preg_replace("/, $/", " WHERE productid='$new_productid'", $query);

		$res = db_query($query);

		if (db_affected_rows($res) < 0)
			$error_string = "$query<br />";
		else {
			#
			# Update products counter for categories in which product is placed
			#
			$product_categories = func_query_column("SELECT categoryid FROM $sql_tbl[products_categories] WHERE productid = '$productid'");
			func_recalc_product_count(func_get_category_parents($product_categories));

			#
			# Copy product options
			#
			if ($active_modules['Product_Options']) {
				$hash = array();
				$classes = func_query("SELECT * FROM $sql_tbl[classes] WHERE productid = '$productid'");
				if (!empty($classes)) {
					foreach ($classes as $v) {
						$options = func_query("SELECT * FROM $sql_tbl[class_options] WHERE classid = '$v[classid]'");
						$old_classid = $v['classid'];
						unset($v['classid']);
						$v['productid'] = $new_productid;
						$v = func_addslashes($v);
						$classid = func_array2insert('classes', $v);
						if ($options) {
							foreach ($options as $o) {
								$old_optionid = $o['optionid'];
								unset($o['optionid']);
								$o['classid'] = $classid;
								$o = func_addslashes($o);
								$optionid = func_array2insert('class_options', $o);
								$hash[$old_optionid] = $optionid;
								func_copy_tables("product_options_lng", "optionid", $old_optionid, $optionid);
							}
						}

						func_copy_tables("class_lng", "classid", $old_classid, $classid);
					}
				}

				# Clone product option exceptions
				if (!empty($hash)) {
					$hash_ex = array();
					$exceptions = func_query("SELECT * FROM $sql_tbl[product_options_ex] WHERE optionid IN ('".implode("','", array_keys($hash))."')");
					if (!empty($exceptions)) {
						foreach ($exceptions as $v) {
							if (empty($hash[$v['optionid']]))
								continue;

							$v['optionid'] = $hash[$v['optionid']];
							if (empty($hash_ex[$v['exceptionid']]))
								$hash_ex[$v['exceptionid']] = func_query_first_cell("SELECT MAX(exceptionid) FROM $sql_tbl[product_options_ex]")+1;
							$v['exceptionid'] = $hash_ex[$v['exceptionid']];
							func_array2insert('product_options_ex', $v);
						}
					}

					unset($hash_ex);
				}

				# Clone product option variants
				$variants = db_query("SELECT * FROM $sql_tbl[variants] WHERE productid = '$productid' ORDER BY variantid");
				if ($variants) {
					while ($v = db_fetch_array($variants)) {
						$old_variantid = $v['variantid'];
						$v['productid'] = $new_productid;
						unset($v['variantid']);
						$cnt = 0;
						while (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[variants] WHERE productcode = '".addslashes($v['productcode'].$cnt)."'") > 0) {
							$cnt++;
						}

						$v['productcode'] .= $cnt;
						$v = func_addslashes($v);
						$variantid = func_array2insert('variants', $v);

						# Add Variant items
						$items = func_query("SELECT optionid FROM $sql_tbl[variant_items] WHERE variantid = '$old_variantid'");
						if (!empty($items)) {
							foreach($items as $i) {
								if (isset($hash[$i['optionid']])) {
									db_query("INSERT INTO $sql_tbl[variant_items] (variantid, optionid) VALUES ('$variantid', '".$hash[$i['optionid']]."')");
								}
							}
						}

						# Add Variant prices
# START: random:536881009 [2010 Mar 22 14:23] 
						$prices = func_query("SELECT * FROM $sql_tbl[pricing] WHERE variantid = '$old_variantid' AND productid = '$productid' AND quantity = 1");
# END: random:536881009 [2010 Mar 22 14:23] 
						if ($prices) {
							foreach($prices as $p) {
								unset($p['priceid']);
 #
 ## Do not Clone price value
 ###
                                                                unset($p['price']);
 ###
 ##
 #

								$p['variantid'] = $variantid;
								$p['productid'] = $new_productid;
								func_array2insert("pricing", $p);
							}
						}

						# Add Variant thumbnails & variant images
						$error_string .= func_copy_tables("images_W", "id", $old_variantid, $variantid);
					}
					db_free_result($variants);
				}
			}

			# Add product files
			if ($active_modules['Upselling_Products'] && is_array($clone) && $clone['product_files'] == 'Y') {
				$product_files = func_query_column('SELECT filename FROM ' . $sql_tbl['product_files'] . ' WHERE productid="' . $productid . '"');

				foreach ($product_files as $filename) {

					# Create category for the new product

					$path = func_allowed_path($root_dir, $product_files_dir . DIRECTORY_SEPARATOR . $new_productid);

					$op_status = false;

					if ($path === false || empty($new_productid)) {
						# Path is not allowed or empty new dir name
						$top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
						$top_message["type"] = "E";
					} elseif (is_dir($path)) {
						$op_status = true;
					} else {
						if (!@mkdir($path, 0777)) {
							# Creation of the directory is failed
							$top_message["content"] = func_get_langvar_by_name("msg_err_file_operation");
							$top_message["type"] = "E";
						} else {
							# Success
							$op_status = true;
						}
					}
		
					if (!empty($filename) && $op_status) {
						$path = func_allowed_path($root_dir, $product_files_dir . DIRECTORY_SEPARATOR 
							. $new_productid . DIRECTORY_SEPARATOR . $filename);
						if (file_exists ($path)) {
							$path = func_allowed_path($root_dir, $product_files_dir . DIRECTORY_SEPARATOR 
								. $new_productid . DIRECTORY_SEPARATOR . time() . '-' . $filename);
						}
						$path_from = func_allowed_path($root_dir, $product_files_dir . DIRECTORY_SEPARATOR 
							. $productid . DIRECTORY_SEPARATOR . $filename);
					}

					if (empty($filename) || $path_from === false || $path === false || !func_is_allowed_file($filename) || file_exists ($path)) {
						# Path is not allowed or empty new dir name
						$top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
						$top_message["type"] = "E";
					} else {
						if (!@copy($path_from, $path)) {
							# File operation is failed
							$top_message["content"] = func_get_langvar_by_name("msg_err_file_operation");
							$top_message["type"] = "E";
						}
					}
				}

				$error_string .= func_copy_tables('product_files', 'productid', $productid, $new_productid);
			}
			if ($active_modules['Upselling_Products'] && is_array($clone) && $clone['upselling'] == 'Y') {
				$tables_array[] = array('table'=>'product_links','key_field'=>'productid1');
				$upselling_products = func_query_column('SELECT productid2 FROM ' . $sql_tbl['product_links'] 
					. ' WHERE productid1="' . $productid . '"');

				if (is_array($upselling_products) && !empty($upselling_products)) {
					$bd_links = func_query('SELECT productid1, orderby FROM ' . $sql_tbl['product_links'] 
						. ' WHERE productid1 IN ("' . implode('","', $upselling_products) . '")' 
						. ' AND productid2="' . $productid . '" GROUP BY productid1');
					
					if (is_array($bd_links) && !empty($bd_links)) {
						foreach($bd_links as $l) {
							$l['productid2'] = $new_productid;
							func_array2insert('product_links', $l);
						}
					}
				}
			}

			#
			# Copy records that are linked with this product in the other tables
			#
			foreach ($tables_array as $k=>$v) {
				$error_string .= func_copy_tables($v["table"], $v["key_field"], $productid, $new_productid);
			}

			# Clone prices
# START: random:536881009 [2010 Mar 22 14:23] 
			$prices = func_query("SELECT * FROM $sql_tbl[pricing] WHERE productid = '$productid' AND variantid = 0");
# END: random:536881009 [2010 Mar 22 14:23] 
			if (!empty($prices)) {
				foreach ($prices as $v) {
					unset($v['priceid']);
 #
 ## Do not Clone price value
 ###
                                        unset($v['price']);
 ###
 ##
 #

					$v['productid'] = $new_productid;
					func_array2insert("pricing", $v);
				}
			}

		}

		# Rebuild product's cache tables
		func_build_quick_flags($new_productid);
		func_build_quick_prices($new_productid);

		if (!empty($active_modules['Fancy_Categories'])) {
			$cats = func_fc_check_rebuild($productid, "P");
			if (!empty($cats))
				func_fc_build_categories($cats, 10);
		}

        func_rebuild_brand_sf($product_info['brandid']);
        func_rebuild_product_sf($new_productid);

		if (empty($error_string))
			func_header_location("product_modify.php?productid=$new_productid");
	}

}

#
# Display error message if operation failed
#
echo "<b>ERROR: Product #$new_productid has not been created!</b><br />$error_string";
exit();

?>
