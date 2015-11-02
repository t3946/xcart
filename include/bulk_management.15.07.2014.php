<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: bulk_management.php,v 1.0 2010/10/20 18:25:55 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if (isset($bulk_delimiter)) {
	if ($bulk_delimiter == 'tab') {
		$bulk_delimiter = "\t";
	}
	define('CSV_DELIMITER', $bulk_delimiter);
} else {
	define('CSV_DELIMITER', ',');
}

ini_set('memory_limit', '512M');

x_load('category', 'db', 'product', 'gd');

x_session_register('bulk_search_query');
x_session_register('bulk_search_query_ids');
x_session_register('bulk_db_name_tmp', 'xcart_bulk_tmp');
x_session_register('colnames', array());
x_session_register('changes');
x_session_register('userfile');

function func_drop_tmp_table($name, $login) {
	$tables = func_query_column('SHOW TABLES');
	if (in_array($name, $tables)) {
        db_query('DELETE FROM ' . $name . ' WHERE login="' . $login . '"');
        $items = func_query_first_cell('SELECT COUNT(*) FROM ' . $name);
        if (empty($items)) {
    		db_query('DROP TABLE IF EXISTS `' . $name . '`');
        }
	}
}

function func_create_tmp_table($name) {
    global $login;

	func_drop_tmp_table($name, $login);

	$query = 'CREATE TABLE IF NOT EXISTS `' . $name . '` (' 
		. ' `key` varchar(32) NOT NULL default "",'
		. ' `data` mediumtext NOT NULL,'
		. ' `name` varchar(64) NOT NULL default "",'
		. ' `login` varchar(32) NOT NULL default "",'
		. ' PRIMARY KEY  (`key`, `name`, `login`),'
        . ' INDEX `login` (`login`)'
		. ') ENGINE=MyISAM;';

	db_query($query);
}

function func_save_array_in_db(&$array, $array_name, $db_name, $login) {

	if (is_array($array) && !empty($array)) {
		foreach ($array as $key => $val) {
			db_query('REPLACE INTO ' . $db_name 
				. ' SET `key`="' . $key . '", `data`="' . mysql_real_escape_string(serialize($val)) 
				. '", `name`="' . $array_name . '", `login`="' . mysql_real_escape_string($login) . '"');
		}
	}
}
function func_upload_images($id, $type, $imgs, $prod_type, $productcode = '', $product_name = '') {
    global $xcart_dir, $nf, $config, $file_upload_data, $top_message, $sql_tbl;

    $from_parent_window = 'Y';
    $source = 'U';
    $from_bulk = 'Y';
    $REQUEST_METHOD = 'POST';

    if (!empty($imgs) && is_array($imgs)) {
        
        if ($type == 'D') {
            $checked_imgs = array();
        }

        foreach ($imgs as $img) {
            $fileurl = $img;

            if ($type == 'D') {
                $msg = '';
            }
            
            include $xcart_dir . '/include/image_selection.php';

            if ($type == 'D') {
                
                $image_error = false;

                if (!empty($file_upload_data['D']) && is_array($file_upload_data['D'])) {

                    $image_perms = func_check_image_storage_perms($file_upload_data, 'D');
                    
                    if ($image_perms === true) {
                    
                        $image_posted = func_check_image_posted($file_upload_data, 'D');

                        if ($image_posted) {
                            $checked_imgs[] = $file_upload_data;
                        } else {
                            $image_error = true;
                        }
                    } else {
                        $image_error = true;
                    }
                } else {
                    $image_error = true;
                }
                if ($image_error && !empty($productcode)) {
                    $msg .= ' ' . func_get_langvar_by_name('err_image_not_uploaded', array(
                        'FILEPATH'  =>  $fileurl), false, true);
                }
                    
            }

            if (!empty($msg)) {
                if ($prod_type == 'n') {
                    $str_to_file = '<tr><td><font style="color: #ff00ff;">' . $productcode . '</font>';
                } else {
                    $str_to_file = '<tr><td>' . $productcode;
                }
                $str_to_file .= ' - ' . str_replace('<br />', ' ', $msg) . '</td></tr>' . "\n";
                fwrite($nf, $str_to_file);
            }
        }

        if (!empty($checked_imgs) && is_array($checked_imgs)) {
            $old_images = func_query_column('SELECT imageid FROM ' . $sql_tbl['images_D'] 
                . ' WHERE id = "' . $id . '"');
            if (!empty($old_images) && is_array($old_images)) {
                foreach ($old_images as $old_img) {
    			    func_delete_image($old_img, 'D', true);
                }
            }
            
            $tmbn_generated = false;

            foreach ($checked_imgs as $_file_upload_data) {
                if (empty($product_name)) {
                    $product_name = $productcode;
                }
                $image_id = func_save_image($_file_upload_data, 'D', $id, array('alt' => $product_name));
                    
                if (!empty($image_id) && !$tmbn_generated) {
                    if (func_generate_image($id, 'D', 'T', false, false, $image_id)) {
                        func_save_product_thumb_image($id, 'T');
                    }
                    $tmbn_generated = true;
                }
                
                if (empty($image_id)) {
                    $msg .= ' ' . func_get_langvar_by_name('err_image_not_uploaded', array(
                        'FILEPATH'  =>  $_file_upload_data['file_path']), false, true);
                    
                    if ($prod_type == 'n') {
                        $str_to_file = '<tr><td><font style="color: #ff00ff;">' . $productcode . '</font>';
                    } else {
                        $str_to_file = '<tr><td>' . $productcode;
                    }
                    
                    $str_to_file .= ' - ' . str_replace('<br />', ' ', $msg) . '</td></tr>' . "\n";
                    fwrite($nf, $str_to_file);
                }

            }
        }
    }
}

$max_line_size = 65536 * 3;	# Max CSV file line length

$table_fields = func_query_first('SELECT * FROM ' . $sql_tbl['products'] . ' LIMIT 1');
$table_fields = array_keys($table_fields);

$additional_fields = array('price', 'categoryid', 'category', 'taxes', 'manufacturer', 'product_image_url', 'detailed_images_url');

$available_columns = array_merge($table_fields, $additional_fields);

$additional_colnames = array(); // for discontinued products (avail, forsale fields)

$required_columns = array('productcode', 'categoryid', 'provider');

$dialog_tools_data['left'][] = array('link' => 'bulk_management.php#hnew', 'title' => func_get_langvar_by_name('lbl_b_new_products'));
$dialog_tools_data['left'][] = array('link' => 'bulk_management.php#hexisting', 'title' => func_get_langvar_by_name('lbl_b_existing_products'));
$dialog_tools_data['left'][] = array('link' => 'bulk_management.php#hdiscontinued', 'title' => func_get_langvar_by_name('lbl_b_discontinued_products'));
		
if ($REQUEST_METHOD == 'POST') {
	if ($mode == 'compare') {
		func_drop_tmp_table($bulk_db_name_tmp, $login);
		if (empty($bulk_search_query)) {
			$top_message['content'] = func_get_langvar_by_name('txt_empty_query', null, false, true);
			$top_message['type'] = 'E';
			func_header_location($xcart_web_dir . '/admin/search.php?mode=search');
		}
		// Check the source file
		$userfile = func_move_uploaded_file('userfile');
		
		#
		# Open import file
		#
		if (isset($userfile)) {
			$fp = @func_fopen($userfile, 'r', true);
			if (!@func_filesize($userfile) || $fp === false) {
				if ($fp !== false) {
					fclose($fp);
					$fp = false;
				}
			}
		}

		if (empty($userfile)) {
        	# File cannot be opened: display error
			$top_message['content'] = func_get_langvar_by_name('msg_err_file_wrong');
			$top_message['type'] = 'E';
			func_header_location('search.php?mode=search');
		}
		
		#
		# PROCESS THE CSV-FILE ROWS
		#

		$fromfile = array();
		$file_skus = array();

		if ($available_columns) {
			$colnames = array();

			while ($columns = fgetcsv($fp, $max_line_size, CSV_DELIMITER)) {
				$colnames_row = false;
				# Delete empty column names from the end of the array
				if (empty($colnames)) { 
					$col_count = count($columns);
					for ($i = $col_count; $i >= 0; $i--) {
						if ($columns[$i] == '') {
							unset($columns[$i]);
						} else {
							break;
						}
					}
				}
				# Get column names (header within section)...
				# e.g. !ZONE;!COUNTRY;!STATE;!COUNTY;!CITY;!ADDRESS;!ZIP
				if (empty($colnames) && count(preg_grep('/^\s*"?\!\s*([\w\d_]+)\s*"?\s*$/S', $columns)) == count($columns)) {
					$columns_count = count($columns);
					for ($i = 0; $i < $columns_count; $i++) {
						$colnames[$i] = strtolower(substr(trim($columns[$i]), 1));
					}
					$colnames_row = true;
					$missed_columns = array();
					foreach ($required_columns as $rc) {
						if (!in_array($rc, $colnames)) {
							$missed_columns[] = '!' . strtoupper($rc);
						}
					}
					if (!empty($missed_columns)) {
						$top_message['content'] = func_get_langvar_by_name('txt_missed_columns', array('COLS' => implode(', ', $missed_columns)), false, true);
						$top_message['type'] = 'E';
						func_header_location($xcart_web_dir . '/admin/search.php?mode=search');
					}
				}
			
				# Next row if column names was not defined...
				if (empty($colnames) || $colnames_row) {
					continue;
				}
	
				# Generate the array of values...
				$orig_values = array();
				$columns_count = count($columns);
				
				for ($i = 0; $i < $columns_count; $i++) {
					$columns[$i] = preg_replace("/^[ ]+/S", "", preg_replace("/[ ]+$/S", "", $columns[$i]));
	
					# Save the original value from the current row
					# (is used within some import modules)
					if (!empty($colnames[$i])) {
						if (in_array($colnames[$i], $available_columns)) {
							$orig_values[$colnames[$i]] = trim($columns[$i]);
							if ($colnames[$i] == 'productcode') {
								$file_skus[] = trim($columns[$i]);
							}
						} else {
							unset($colnames[$i]);
						}
					}
				}
		
				$fromfile[] = $orig_values;

			}
		}
		
		if (empty($colnames)) {
			$top_message['content'] = func_get_langvar_by_name('txt_no_columns_found', null, false, true);
			$top_message['type'] = 'E';
			func_header_location($xcart_web_dir . '/admin/search.php?mode=search');
		}

		if (!empty($bulk_search_query)) {
			$dbsr_res = db_query($bulk_search_query);
			$dbsr_skus = array();
			$dbsr = array();

            if (in_array('product_image_url', $colnames) || in_array('detailed_images_url', $colnames)) {
                // Get product and detailed images for the products
                $productids = func_query_column($bulk_search_query_ids);
                $productids_str = implode('", "', $productids);
                if (in_array('product_image_url', $colnames)) {
                    $product_images = func_query_hash('SELECT filename, id FROM ' . $sql_tbl['images_P']
                        . ' WHERE id IN ("' . $productids_str . '")', 'id', false, true);
                }
                if (in_array('detailed_images_url', $colnames)) {
                    $detailed_images = func_query_hash('SELECT filename, id FROM ' . $sql_tbl['images_D']
                        . ' WHERE id IN ("' . $productids_str . '")', 'id', true, true);
                }
            }

			if (!empty($dbsr_res)) {
				while ($product = db_fetch_array($dbsr_res)) {
					$product['category'] = implode('/', func_categoryid_path2category_path($product['categoryid_path']));
                    if (isset($product_images) && !empty($product_images[$product['productid']])) {
                        $product['product_image_url'] = $product_images[$product['productid']];
                    }
                    if (isset($detailed_images) && !empty($detailed_images[$product['productid']]) && is_array($detailed_images[$product['productid']])) {
                        $product['detailed_images_url'] = implode('|', $detailed_images[$product['productid']]);
                    }
					$taxes = func_query_column('SELECT t.tax_name FROM ' . $sql_tbl['product_taxes'] . ' as pt LEFT JOIN ' . $sql_tbl['taxes'] . ' as t ON t.taxid=pt.taxid WHERE pt.productid=' . $product['productid']);
					if (is_array($taxes)) {
						$product['taxes'] = implode(', ', $taxes);
					}
					unset($product['categoryid_path']);
					$dbsr[trim($product['productcode'])] = $product;
					$dbsr_skus[] = trim($product['productcode']);
				}
			}
			unset($dbsr_res);
			unset($productids);
			unset($productids_str);
			unset($products_images);
			unset($detailed_images);
		}
	
		if (empty($file_skus)) {
			$discontinued = $dbsr;
		}

		if (empty($dbsr_skus)) {
		 	$new = $fromfile;
		}

		if (!empty($file_skus) && !empty($dbsr_skus)) {

			// Get new products
			if (is_array($file_skus) && is_array($dbsr_skus)) {
				$new_skus = array_diff($file_skus, $dbsr_skus);
			}
			
			if (is_array($fromfile)) {
				$existing = array();
				$new = array();
				$existing_skus = array();
				foreach ($fromfile as $product) {
					if (in_array($product['productcode'], $new_skus)) {
						$new[] = $product;
					} else {
						$existing[$product['productcode']]['csv'] = $product;
						$existing[$product['productcode']]['dbsr'] = $dbsr[$product['productcode']];
						$existing_skus[] = $product['productcode'];
					}
				}

				if (!empty($existing_skus)) {
					$discontinued = array();
					if (!empty($dbsr) && is_array($dbsr)) {
						foreach ($dbsr as $product) {
							if (!in_array($product['productcode'], $existing_skus)) {
								$discontinued[] = $product;
							}
						}
					}
				} else {
					$discontinued = $dbsr;
				}
			}
		}

		func_create_tmp_table($bulk_db_name_tmp);

		func_save_array_in_db($new, 'new', $bulk_db_name_tmp, $login);
		func_save_array_in_db($existing, 'existing', $bulk_db_name_tmp, $login);
		func_save_array_in_db($discontinued, 'discontinued', $bulk_db_name_tmp, $login);

		unset($new);
		unset($existing);
		unset($discontinued);
		
		$smarty->assign('main', 'bulk_manage');
	}

	if ($mode == 'review') {
		if (!empty($new_sel)) {
			$changes['new'] = $new_sel;
			$changes['new']['productcode'] = 'Y';
		} elseif (!empty($new_all)) {
			$changes['new']['productcode'] = 'Y';
		}
		if (!empty($existing_sel)) {
			$changes['existing'] = $existing_sel;
		}

		if (isset($avail_disabled)) {
			$changes['discontinued']['forsale'] = $avail_disabled;
			if (is_array($colnames) && !in_array('forsale', $colnames)) {
				$additional_colnames[] = 'forsale';
			}
		} else {
			$changes['discontinued']['forsale'] = 'N';
		}
		
		if (isset($qis_zero)) {
			$changes['discontinued']['avail'] = $qis_zero;
			if (is_array($colnames) && !in_array('avail', $colnames)) {
				$additional_colnames[] = 'avail';
			}
		} else {
			$changes['discontinued']['avail'] = 'N';
		}
		
		if (isset($change_catid) && $change_catid == 'Y' && isset($newcatid) && is_numeric($newcatid)) {
			$changes['discontinued']['categoryid'] = $newcatid;
		} else {
			$changes['discontinued']['categoryid'] = 'N';
		}

		$smarty->assign('changes', $changes);
		$smarty->assign('main', 'bulk_review');
	}

	if ($mode == 'apply') {


//func_print_r($_POST);
//die();


		$bulk_tmp_file = $var_dirs['tmp'] . '/bulk_tmp_' . $login . '.txt';
		$nf = fopen($bulk_tmp_file, 'a+');
        	fwrite($nf, '<table cellpadding="0" cellspacing="0">' . "\n");

		$bulk_table_exists = false;
		$tables = func_query_column('SHOW TABLES');
		if (in_array($bulk_db_name_tmp, $tables)) {
			$bulk_table_exists = true;
		}




		if ($bulk_table_exists) {
			$new_db = db_query('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="new" AND login="' . $login .'"');
		} else {
			$new_db = array();
		}

		if (!empty($new_db) && !empty($changes['new'])) {
			if (isset($changes['new']['productcode']) && isset($changes['new']['categoryid'])) {

				while ($row = db_fetch_array($new_db)) {
					$nproduct = unserialize(stripslashes($row['data']));
			                $msg_result = '';
    				
                    			if (isset($changes['new']['manufacturer']) && !empty($nproduct['manufacturer'])) {
	    				# 
		    			# Check if [csv][manufacturer] fits [csv][manufacturerid].
			    		# if not set [csv][manufacturerid] = [dbsr][manufacturerid] and show warning
				    	# else change the manufacturer
					    #
							
    						if (!empty($changes['new']['manufacturerid'])) {
								
	    						$manufacturer = func_query_first_cell('SELECT manufacturer FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid=' . $nproduct['manufacturerid']);
		    					if ($manufacturer != $nproduct['manufacturer']) {
			    					$msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>'
				                                    . ' - ' . func_get_langvar_by_name('txt_manufacturer_import_error', null, false, true) 
                                				    . '</td></tr>' . "\n";
				                        }

    						} else {
								
	    					$manufacturerid = func_query_first_cell('SELECT manufacturerid FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturer="' . trim($nproduct['manufacturer']) . '"');
			    				if (!empty($manufacturerid)) {
				    				$nproduct['manufacturerid'] = $manufacturerid;
					    			$nquery['manufacturerid'] = $manufacturerid;
						    	} else {
			    					$msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>'
	                                		    . ' - ' . func_get_langvar_by_name('txt_wrong_manufacturer', array(
        	                	                	'X' => $nproduct['manufacturer']), false, true)
	                		                    . '</td></tr>' . "\n";
		    					}
			    			}
                    			} elseif (isset($changes['new']['manufacturerid']) && !empty($nproduct['manufacturerid'])) {
			                        $manufacturer = func_query_first_cell('SELECT manufacturer FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid=' . $nproduct['manufacturerid']);
                        			if (empty($manufacturer)) {
			                            $msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>'
                        			        . ' - ' . func_get_langvar_by_name('txt_wrong_manufacturer', array(
			                                    'X' =>  func_get_langvar_by_name('lbl_id') . ': ' . $nproduct['manufacturerid']), false, true) 
			                                . '</td></tr>' . "\n";
			                        } else {
                        			    $nquery['manufacturerid'] = $manufacturerid;
			                        }
			                }
					
					$categoryid_exist = func_query_first_cell('SELECT COUNT(categoryid) FROM ' . $sql_tbl['categories'] . ' WHERE categoryid="' . $nproduct['categoryid'] . '"');
					$sku_not_exist = func_query_first_cell('SELECT COUNT(productcode) FROM ' . $sql_tbl['products'] . ' WHERE productcode="'.$nproduct['productcode'].'"');

					if (!empty($nproduct['provider'])) {
						$provider_exist = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['customers'] . ' WHERE login="' . mysql_real_escape_string($nproduct['provider']) . '" AND usertype = "P"');
					} else {
						$provider_exist = false;
					}
					
					if (!$categoryid_exist || $categoryid_exist <= 0) {
						$msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>' 
                            . ' - ' . func_get_langvar_by_name('lbl_as_category_not_exists', array('CAT' => $nproduct['categoryid']), false, true) 
                            . '</td></tr>' . "\n";
					}
					if ($sku_not_exist && $sku_not_exist > 0) {
						$domains = func_query_column('SELECT s.domain FROM ' . $sql_tbl['storefronts'] . ' as s'
							. ' LEFT JOIN ' . $sql_tbl['products_sf'] . ' as ps ON s.storefrontid=ps.sfid'
							. ' LEFT JOIN ' . $sql_tbl['products'] . ' as p ON p.productid=ps.productid'
							. ' WHERE p.productcode = "' . $nproduct['productcode'] . '"');
						if (is_array($domains) && !empty($domains)) {
							$domains = implode(', ', $domains);
						} else {
							$domains = MAIN_SF_DOMAIN;
						}
						$msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>' 
                            . ' - ' . func_get_langvar_by_name('lbl_as_sku_exists', array('DOMAIN' => $domains), false, true) 
                            . '</td></tr>' . "\n";
					}
					if (empty($provider_exist)) {
						$msg_result .= '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>' 
                            . ' - ' . func_get_langvar_by_name('lbl_as_provider_not_exists', array('PROV' => $nproduct['provider']), false, true) 
                            . '</td></tr>' . "\n";
					}

                    if (empty($msg_result)) {

                        if (
                            isset($changes['new']['dim_x']) && !empty($nproduct['dim_x'])
                            && isset($changes['new']['dim_y']) && !empty($nproduct['dim_y'])
                            && isset($changes['new']['dim_z']) && !empty($nproduct['dim_z'])
                        ) {
                            $dimensions = array();
                            $dimensions[] = $nproduct['dim_x'];
                            $dimensions[] = $nproduct['dim_y'];
                            $dimensions[] = $nproduct['dim_z'];
                            
                            rsort($dimensions);

                            $nproduct['dim_x'] = $dimensions[0];
                            $nproduct['dim_y'] = $dimensions[1];
                            $nproduct['dim_z'] = $dimensions[2];
                        }
						
						$nquery = array();
						if (is_array($changes['new'])) {
							foreach ($changes['new'] as $col => $v) {
								if ($col != 'productid' && $col != 'categoryid' && !in_array($col, $additional_fields)) {
									if (in_array($col, array('product', 'descr', 'fulldescr', 'product_froogle', 'provider'))) {
				 } elseif ($col == 'map_price') {
                                        $nquery[$col] = floatval($nproduct[$col]);										$nquery[$col] = mysql_real_escape_string($nproduct[$col]);
                                    } elseif ($col == 'upc') {
                                        $nproduct[$col] = func_get_normalize_upc($nproduct[$col]);
                                        if ($nproduct[$col]) {
                                            $nquery[$col] = $nproduct[$col];
                                        } else {
                                            $str_to_file = '<tr><td style="color: #ff00ff;">'
                                                . $nproduct['productcode'] . ' - ' . func_get_langvar_by_name('err_froogle_wrong_upc', null, false, true) 
                                                . '</td></tr>' . "\n";
                                            fwrite($nf, $str_to_file);
                                        }
									} else {
										$nquery[$col] = $nproduct[$col];
                                    }
                                }
                            }
							$time = time();
							$nquery['mod_date'] = $time;
							$nquery['add_date'] = $time;
							if (!empty($active_modules['Multiple_Storefronts']) && isset($current_storefront)) {
								$nquery['source_sfid'] = $current_storefront;
							} else {
								$nquery['source_sfid'] = 0;
							}

#
##
###
                                        if (!isset($cnt))
                                                $cnt = 0;
                                        $cnt++;
                                        if ($cnt % 100 == 0) {
                                               func_flush(".");
                                               if($cnt % 5000 == 0) {
                                                      func_flush("<br />\n");
                                               }

                                                func_flush();
                                        }
###
##
#

							
							$productid = func_array2insert('products', $nquery);
                            if (!empty($productid)) {
                                $msg_result = '<tr><td><font style="color: #ff00ff;">' . $nproduct['productcode'] . '</font>' 
                                    . ' - ' . func_get_langvar_by_name('lbl_was_added', null, false, true) 
                                    . '</td></tr>' . "\n";
            
                                $price = (isset($changes['new']['price'])) ? abs(doubleval($nproduct['price'])) : 0;
            
                                db_query('INSERT INTO ' . $sql_tbl['pricing'] . ' (productid, quantity, price) VALUES (' . $productid . ', "1", "' . $price . '")');
                                $priceid = db_insert_id();
                                db_query('INSERT INTO ' . $sql_tbl['quick_prices'] . ' (productid, priceid) VALUES (' . $productid . ', ' .  $priceid . ')');
                                db_query('INSERT INTO ' . $sql_tbl['quick_flags'] . ' (productid) VALUES (' . $productid .')');
                                db_query('INSERT INTO ' . $sql_tbl['products_categories'] . ' (productid, categoryid, main) VALUES (' . $productid . ', ' . $nproduct['categoryid'] . ', "Y")');
                                
                                if (!empty($active_modules['Multiple_Storefronts'])) {
                                    func_rebuild_product_sf($productid);
                                    func_rebuild_brand_sf($nproduct['brandid']);
                                }

                                # Insert taxes
                                if (isset($changes['new']['taxes'])) {
                                    $tax_warning = false;
                                    $taxes = explode(',', $nproduct['taxes']);
                                    if (is_array($taxes)) {
                                        foreach ($taxes as $k => $tax) {
                                            $taxes[$k] = mysql_real_escape_string(trim($tax));
                                        }
                                        $taxes = implode('", "', $taxes);
                                        $taxids = func_query_column('SELECT taxid FROM ' . $sql_tbl['taxes'] . ' WHERE tax_name IN ("'. $taxes .'")');
                                        if (is_array($taxids)) {
                                            foreach ($taxids as $taxid) {
                                                $query_data = array(
                                                    'productid' => $productid,
                                                    'taxid' => $taxid
                                                );
                                                func_array2insert('product_taxes', $query_data, true);
                                            }
                                        }
                                    } else {
                                        $str_to_file = '<tr><td style="color: #ff00ff;">'
                                            . $nproduct['productcode'] . ' - ' . func_get_langvar_by_name('err_bulk_new_tax_error', null, false, true) 
                                            . '</td></tr>' . "\n";
                                        fwrite($nf, $str_to_file);
                                    }
                                        

                                    if (count($taxids) != count($taxes)) {
                                        $str_to_file = '<tr><td style="color: #ff00ff;">'
                                            . $nproduct['productcode'] . ' - ' . func_get_langvar_by_name('err_bulk_new_tax_error', null, false, true)
                                            . '</td></tr>' . "\n";
                                        fwrite($nf, $str_to_file);
                                    }

                                }

                                # Upload product image
                                if (isset($changes['new']['product_image_url']) && !empty($nproduct['product_image_url'])) {
                                    func_upload_images($productid, 'P', array($nproduct['product_image_url']), 'n', $nproduct['productcode']);
                                }

                                # Upload detailed images
                                if (isset($changes['new']['detailed_images_url']) && !empty($nproduct['detailed_images_url'])) {
                                    $det_imgs = explode('|', $nproduct['detailed_images_url']);
                                    $product_name = (empty($nproduct['product'])) ? '' : $nproduct['product'];
                                    func_upload_images($productid, 'D', $det_imgs, 'n', $nproduct['productcode'], $product_name);
                                }
                            }

						} // is_array($changes['new'])
					} // $msg_result
		                    fwrite($nf, $msg_result);
				} // while
			} else {
				$top_message['content'] = func_get_langvar_by_name('err_need_necessary_columns', null, false, true);
				$top_message['type'] = 'E';
				func_header_location('search.php?mode=search');
			}

			db_free_result($new_db);
		}

		if ($bulk_table_exists) {
			$existing_db = db_query('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="existing" AND login="' . $login .'"');
		} else {
			$existing_db = array();
		}

		if (!empty($existing_db) && !empty($changes['existing'])) {
			while ($row = db_fetch_array($existing_db)) {
				$eproduct = unserialize(stripslashes($row['data']));
			
//func_print_r($_POST, $changes, $additional_fields, $row, $eproduct);
//die();


	
				$equery = array();
				$ewhere = '';
				if (is_array($changes['existing'])) {
					$productid = func_query_first_cell('SELECT productid FROM ' . $sql_tbl['products'] . ' WHERE productcode="' . $eproduct['csv']['productcode'] . '"');
					if (is_numeric($productid)) {
                			        $msg_result = '';
						if (isset($changes['existing']['manufacturer']) && !empty($eproduct['csv']['manufacturer'])) {
							# 
							# Check if [csv][manufacturer] fits [csv][manufacturerid].
							# if not set [csv][manufacturerid] = [dbsr][manufacturerid] and show warning
							# else change the manufacturer
							#
								
							if (!empty($changes['existing']['manufacturerid'])) {
									
								$manufacturer = func_query_first_cell('SELECT manufacturer FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid=' . $eproduct['csv']['manufacturerid']);
								if ($manufacturer != $eproduct['csv']['manufacturer']) {
										$msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
                                            . ' - ' . func_get_langvar_by_name('txt_manufacturer_import_error', null, false, true) 
                                            . '</td></tr>' . "\n";
								}

							} else {
								
								$manufacturerid = func_query_first_cell('SELECT manufacturerid FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturer="' . trim($eproduct['csv']['manufacturer']) . '"');
								if (!empty($manufacturerid)) {
									$eproduct['csv']['manufacturerid'] = $manufacturerid;
									$equery['manufacturerid'] = $manufacturerid;
								} else {
									$msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
                                        . ' - ' . func_get_langvar_by_name('txt_wrong_manufacturer', array(
                                            'X' => trim($eproduct['csv']['manufacturer'])), false, true) 
                                        . '</td></tr>' . "\n";
								}
							}
                        		} elseif (isset($changes['existing']['manufacturerid']) && $eproduct['csv']['manufacturerid']) {
		                            $manufacturer = func_query_first_cell('SELECT manufacturer FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid=' . $eproduct['csv']['manufacturerid']);
                		            if (empty($manufacturer)) {
                                		$msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
		                                    . ' - ' . func_get_langvar_by_name('txt_wrong_manufacturer', array(
                		                        'X' => func_get_langvar_by_name('lbl_id') . ': ' . $eproduct['csv']['manufacturerid']), false, true) 
                                		    . '</td></tr>' . "\n";
		                            } else {
                		                $equery['manufacturerid'] = $manufacturerid;
		                            }
					}
                        
                		        if (
		                            isset($changes['existing']['dim_x']) && !empty($eproduct['csv']['dim_x'])
                		            && isset($changes['existing']['dim_y']) && !empty($eproduct['csv']['dim_y'])
		                            && isset($changes['existing']['dim_z']) && !empty($eproduct['csv']['dim_z'])
		                        ) {
                		            $dimensions = array();
		                            $dimensions[] = $eproduct['csv']['dim_x'];
                		            $dimensions[] = $eproduct['csv']['dim_y'];
		                            $dimensions[] = $eproduct['csv']['dim_z'];
                            
                		            rsort($dimensions);

		                            $eproduct['csv']['dim_x'] = $dimensions[0];
                		            $eproduct['csv']['dim_y'] = $dimensions[1];
		                            $eproduct['csv']['dim_z'] = $dimensions[2];
                		        }
						
									
						foreach ($changes['existing'] as $col => $v) {
							if ($col == 'categoryid') {
								$categoryid_exist = func_query_first_cell('SELECT COUNT(categoryid) FROM ' . $sql_tbl['categories'] . ' WHERE categoryid='.$eproduct['csv'][$col]);
								if ($categoryid_exist && $categoryid_exist > 0) {
									db_query('UPDATE ' . $sql_tbl['products_categories'] . ' SET categoryid=' . $eproduct['csv']['categoryid'] . ' WHERE productid='.$productid.' AND main="Y"');
								} else {
				                                    $msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
                                				        . ' - ' . func_get_langvar_by_name('lbl_exist_cat_not_exist', array(
				                                          'CAT' => $eproduct['csv']['categoryid']), false, true) 
                                				        . '</td></tr>' . "\n";
				                                }
							} elseif ($col == 'provider') {
								if (!empty($eproduct['csv']['provider'])) {
									$provider_exist = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['customers'] . ' WHERE login="' . mysql_real_escape_string($eproduct['csv']['provider']) . '" AND usertype = "P"');
								} else {
									$provider_exist = false;
								}
								if (!empty($provider_exist)) {
									$equery['provider'] = mysql_real_escape_string($eproduct['csv']['provider']);
								} else {
				                                    $msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
                                				        . ' - ' . func_get_langvar_by_name('lbl_exist_provider_not_exist', array(
				                                            'PROV' => $eproduct['csv']['provider']), false, true) 
                                				        . '</td></tr>' . "\n";
                                				}
							} elseif ($col == 'price') {
									db_query('UPDATE ' . $sql_tbl['pricing'] . ' SET price=' . abs(doubleval($eproduct['csv']['price'])) . ' WHERE productid='.$productid);


#
##
###
        db_query("DELETE FROM $sql_tbl[pricing] WHERE productid = '$productid' AND membershipid = '' AND quantity > 1 AND variantid = '0'");
        $cidev_discount_slope = func_query_first_cell("SELECT discount_slope FROM $sql_tbl[products] WHERE productid='$productid'");
        $cidev_discount_table = func_query_first_cell("SELECT discount_table FROM $sql_tbl[products] WHERE productid='$productid'");
        if (!empty($cidev_discount_table)){
                foreach (explode(",",$cidev_discount_table) as $cidev_v) {
                        if(intval($cidev_v)) {
                                $cidev_query_data = array(
                                "productid" => $productid,      
                                "quantity" => intval($cidev_v),
                                "price" => (1 - $cidev_discount_slope * log($cidev_v,2) / 100) * abs(doubleval($eproduct['csv']['price'])),
                                "membershipid" => ''
                            );                                  
                            func_array2insert("pricing", $cidev_query_data);
                }       }                                               
        }
###
##
#


							} elseif ($col == 'map_price') {
				                                db_query('UPDATE ' . $sql_tbl['products'] . ' SET map_price=' . abs(doubleval($eproduct['csv']['map_price'])) . ' WHERE productid='.$productid);
							} elseif ($col == 'taxes') {
	
								$taxes = explode(',', $eproduct['csv']['taxes']);
								if (is_array($taxes)) {
									foreach ($taxes as $k => $tax) {
										$taxes[$k] = mysql_real_escape_string(trim($tax));
									}
									$taxes = implode('", "', $taxes);
									$taxids = func_query_column('SELECT taxid FROM ' . $sql_tbl['taxes'] . ' WHERE tax_name IN ("'. $taxes .'")');
									if (is_array($taxids)) {
										db_query("DELETE FROM $sql_tbl[product_taxes] WHERE productid='$productid'");
										foreach ($taxids as $taxid) {
											$query_data = array(
												'productid' => $productid,
												'taxid' => $taxid
											);
											func_array2insert('product_taxes', $query_data, true);
										}
									}
								} else {
									$msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
				                                        . ' - ' . func_get_langvar_by_name('err_bulk_new_tax_error', null, false, true) 
                                				        . '</td></tr>' . "\n";
								}
								
								if (count($taxids) != count($taxes)) {
									$msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
				                                        . ' - ' . func_get_langvar_by_name('err_bulk_new_tax_error', null, false, true) 
				                                        . '</td></tr>' . "\n";
								}
                            				} elseif ($col == 'upc') {
				                                $eproduct['csv'][$col] = func_get_normalize_upc($eproduct['csv'][$col]);
				                                if ($eproduct['csv'][$col]) {
				                                    $equery[$col] = $eproduct['csv'][$col];
				                                } else {
				                                    $msg_result .= '<tr><td>' . $eproduct['csv']['productcode'] 
				                                        . ' - ' . func_get_langvar_by_name('err_froogle_wrong_upc', null, false, true) 
				                                        . '</td></tr>' . "\n";
				                                }
                            				} elseif ($col == 'product_image_url' && !empty($eproduct['csv'][$col])) {
				                                # Upload product image
				                                func_upload_images($productid, 'P', array($eproduct['csv'][$col]), 'e', $eproduct['csv']['productcode']);

                            				} elseif ($col == 'detailed_images_url' && !empty($eproduct['csv'][$col])) {
				                                # Upload product image
                                				$det_imgs = explode('|', $eproduct['csv'][$col]);
                                				if (empty($eproduct['csv']['product'])) {
				                                    $product_name = $eproduct['dbsr']['product'];
                                				} else {
				                                    $product_name = $eproduct['csv']['product'];
				                                }
                                				func_upload_images($productid, 'D', $det_imgs, 'e', $eproduct['csv']['productcode'], $product_name);

							} elseif (!in_array($col, $additional_fields)) {
								if ($col == 'add_date') {
									$eproduct['csv'][$col] = strtotime($eproduct['csv'][$col]);
								}
								if ($col != 'productid') {
									$equery[$col] = mysql_real_escape_string($eproduct['csv'][$col]);
								}
			    				}
						}
                        
			                        $time = time();
						$equery['mod_date'] = $time;
						//$equery['provider'] = $login;
					} // is_numeric($productid)

					if (!empty($active_modules['Multiple_Storefronts'])) {
	                        		$old_brand = func_query_first_cell('SELECT brandid FROM ' . $sql_tbl['products'] 
		        	                    . ' WHERE productcode = "' . $eproduct['dbsr']['productcode'] . '"');
                		        }
					
					$ewhere = 'productcode = "' . $eproduct['dbsr']['productcode'] . '"';


//func_print_r($equery, $ewhere);
//die();
#
##
###
					if (!isset($cnt))
						$cnt = 0;
		                        $cnt++;
                		        if ($cnt % 100 == 0) {
			                       func_flush(".");
         			               if($cnt % 5000 == 0) {
                 	        		      func_flush("<br />\n");
		                               }

                		        	func_flush();
		                        }		
###
##
#


					func_array2update('products', $equery, $ewhere);
                    
		                        if (empty($msg_result)) {
                		            $msg_result = '<tr><td>' . $eproduct['csv']['productcode'] 
		                            . ' - ' . func_get_langvar_by_name('lbl_exist_updated_succ', null, false, true) 
                		            . '</td></tr>' . "\n";
		                        }
                		        fwrite($nf, $msg_result);
									
					if (!empty($active_modules['Multiple_Storefronts'])) {
						func_rebuild_product_sf($productid);
						func_rebuild_brand_sf($eproduct['csv']['brandid']);

		                        	if (!empty($old_brand) && is_numeric($old_brand)) {
						    func_rebuild_brand_sf($old_brand);
	                        		}
					}

				} // is_array($changes['existing'])
			} // while
			
			db_free_result($existing_db);
		}

		if ($bulk_table_exists) {
			$discontinued_db = db_query('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="discontinued" AND login="' . $login .'"');
		} else {
			$discontinued_db = array();
		}

		if (!empty($discontinued_db) && !empty($changes['discontinued'])) {
			$dp_skus = '';
			while ($row = db_fetch_array($discontinued_db)) {
				$dproduct = unserialize(stripslashes($row['data']));
				
				$dp_skus .= '"' . $dproduct['productcode'] . '", ';
			}
			$dp_skus = substr($dp_skus, 0, count($dp_skus) - 3);
			$set_str = '';
	                $msg_result = '';
			if ($changes['discontinued']['avail'] == 'Y') {
				$set_str .= 'avail = 0';
            			$msg_result .= '<tr><td>' . func_get_langvar_by_name('lbl_disc_avail_changed', null, false, true) . '</td></tr>' . "\n";
			}
			if ($changes['discontinued']['avail'] == 'Y' && $changes['discontinued']['forsale'] == 'Y') {
				$set_str .= ', ';
			}
			if ($changes['discontinued']['forsale'] == 'Y') {
				$set_str .= 'forsale = "N"';
		                $msg_result .= '<tr><td>' . func_get_langvar_by_name('lbl_disc_forsale_changed', null, false, true) . '</td></tr>' . "\n";
			}

			if (!empty($set_str) && !empty($dp_skus)) {
				db_query('UPDATE ' . $sql_tbl['products'] . ' SET ' . $set_str . ' WHERE productcode IN (' . $dp_skus . ')');
			}

			if ($changes['discontinued']['categoryid'] != 'N') {
				$categoryid_exist = func_query_first_cell('SELECT COUNT(categoryid) FROM ' . $sql_tbl['categories'] . ' WHERE categoryid='.$changes['discontinued']['categoryid']);
				if ($categoryid_exist && $categoryid_exist > 0) {
					$productids = func_query_column('SELECT productid FROM ' . $sql_tbl['products'] . ' WHERE productcode IN (' . $dp_skus . ')');
					db_query('DELETE FROM ' . $sql_tbl['products_categories'] . ' WHERE productid IN (' . implode(', ', $productids) 
						. ') AND categoryid=' .  $changes['discontinued']['categoryid'] . ' AND main<>"Y"');
					db_query('UPDATE ' . $sql_tbl['products_categories'] . ' SET categoryid=' . $changes['discontinued']['categoryid'] .  ' WHERE productid IN (' . implode(', ', $productids) . ') AND main="Y"');
					if (!empty($active_modules['Multiple_Storefronts']) && is_array($productids)) {
						foreach ($productids as $pid) {
							func_rebuild_product_sf($pid);
						}
					}

		                        $msg_result .= '<tr><td>' . func_get_langvar_by_name('lbl_disc_category_changed', array(
		                        'CAT'   => $changes['discontinued']['categoryid']), false, true) . '</td></tr>' . "\n";
				} else {
					x_session_unregister('colnames');
					x_session_unregister('changes');
					x_session_unregister('userfile');

					$msg_result .= '<tr><td>' . func_get_langvar_by_name('lbl_disc_category_not_exists', array('CAT' => $changes['discontinued']['categoryid']), false, true) . '</td></tr>' . "\n";
				}

			}
			fwrite($nf, $msg_result . "\n");
			
			db_free_result($discontinued_db);
		}

		x_session_unregister('colnames');
		x_session_unregister('changes');
		x_session_unregister('userfile');

        if ($nf) {
            fwrite($nf, '</table>');
            fclose($nf);
        }

        if (file_exists($bulk_tmp_file)) {
            $fcontent = file_get_contents($bulk_tmp_file);
            unlink($bulk_tmp_file);
        }
        $fcontent = func_get_langvar_by_name('lbl_result', null, false, true) . "\n" . $fcontent; 

        if (!empty($fcontent)) {
            $smarty->assign('log', $fcontent);
            $smarty->assign('main', 'bulk_review');
        }
	}

	if ($mode == 'cancel') {
		x_session_unregister('changes');
		$smarty->assign('main', 'bulk_manage');
	}

	$bulk_table_exists = false;
	$tables = func_query_column('SHOW TABLES');
	if (in_array($bulk_db_name_tmp, $tables)) {
		$bulk_table_exists = true;
	}
    
	if ($bulk_table_exists) {
		$new = func_query_hash('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="new" AND login="' . $login .'"', 'key', false, true);
		if (is_array($new) && !empty($new)) {
			foreach ($new as $k => $v) {
				$new[$k] = unserialize(stripslashes($v));
			}
		}
		$existing = func_query_hash('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="existing" AND login="' . $login .'"', 'key', false, true);
		if (is_array($existing) && !empty($existing)) {
			foreach ($existing as $k => $v) {
				$existing[$k] = unserialize(stripslashes($v));
			}
		}
		$discontinued = func_query_hash('SELECT `key`, `data` FROM ' . $bulk_db_name_tmp . ' WHERE `name`="discontinued" AND login="' . $login .'"', 'key', false, true);
		if (is_array($discontinued) && !empty($discontinued)) {
			foreach ($discontinued as $k => $v) {
				$discontinued[$k] = unserialize(stripslashes($v));
			}
		}

		$smarty->assign('new', $new);
		$smarty->assign('existing', $existing);
		$smarty->assign('discontinued', $discontinued);

		unset($new);
		unset($existing);
		unset($discontinued);
	}
	
	# Product code should be the first column
	if (is_array($colnames) && count($colnames) > 1) {
		$cnms = array_flip($colnames);
		$productcode_key = $cnms['productcode'];
		$colnames[$productcode_key] = $colnames[0];
		$colnames[0] = 'productcode';
	}
	$smarty->assign('colnames', array_merge($colnames, $additional_colnames));
}

if ($mode == 'complete') {
	func_drop_tmp_table($bulk_db_name_tmp, $login);
	func_header_location('search.php?mode=search');
}

function func_get_normalize_upc($upc) {
    
    $upc = preg_replace('/[^0-9a-z]/i', '', $upc);

	if (empty($upc)) {
		return '';
	}

    $upc_length = strlen($upc);
	if ($upc_length != ISBN_LENGTH && $upc_length != UPC_LENGTH && $upc_length != EAN_ISBN_LENGTH && $upc_length != '8' && $upc_length != '14') {
        return false;
	}

	return $upc;
}

$smarty->assign('dialog_tools_data', $dialog_tools_data);
?>
