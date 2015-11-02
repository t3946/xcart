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
# $Id: process_product.php,v 1.46 2006/01/11 06:55:59 mclap Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('export','product');

if ($REQUEST_METHOD == "POST" || $mode == 'clone' || ($mode == 'delete' && !empty($productid))) {

	#
	# Get the productid (the first selected item)
	#
	if(empty($productids) && !empty($productid))
		$productids = array($productid => true);
	if (is_array($productids)) {
		foreach ($productids as $k=>$v) {
			$productid = $k;
			break;
		}

		reset($productids);
	}
	else
		$productid = 0;

	if ($mode == "export" && !empty($productids)) {
		func_export_range_save("PRODUCTS", array_keys($productids));
		$top_message['content'] = func_get_langvar_by_name("lbl_export_products_add");
		$top_message['type'] = 'I';
		func_header_location("import.php?mode=export");

	}
	elseif ($mode == "update") {
		#
		# Update the products
		#
		if (is_array($posted_data)) {
			$not_exist_cats = '';
			foreach ($posted_data as $k=>$v) {

				$k = intval($k);
				$update = array();

				# Include 'avail' field into the updating list
/*
				if (isset($v["avail"]) && is_numeric($v["avail"]))
					$update[] = "avail='".intval($v["avail"])."'";
*/

                                if (isset($v["r_avail"]) && is_numeric($v["r_avail"]))
                                        $update[] = "r_avail='".intval($v["r_avail"])."'";

				if (isset($v["list_price"])) {
					$v['list_price'] = func_convert_number($v['list_price']);
					$update[] = "list_price='".doubleval($v['list_price'])."'";
				}

				if (isset($v["map_price"])) {
					$v['map_price'] = func_convert_number($v['map_price']);
					$update[] = "map_price='".doubleval($v['map_price'])."'";
				}


#
##
###
                                if (isset($v["cost_to_us"])) {
                                        $v['cost_to_us'] = func_convert_number($v['cost_to_us']);
                                        $update[] = "cost_to_us='".doubleval($v['cost_to_us'])."'";
                                }

                                if (isset($v["new_map_price"])) {
                                        $v['new_map_price'] = func_convert_number($v['new_map_price']);
                                        $update[] = "new_map_price='".doubleval($v['new_map_price'])."'";
                                }
###
##
#

				if (isset($v["weight"])) {
					$update[] = "weight='".str_replace(',','',$v["weight"])."'";
				}
				if (isset($v['shipping_freight'])) {
					$v['shipping_freight'] = func_convert_number($v['shipping_freight']);
					$update[] = "shipping_freight='".doubleval($v['shipping_freight'])."'";
				}

				if (isset($v['forsale'])) {
					$update[] = "forsale='".$v['forsale']."'";
				}
				
				if (isset($v['productcode'])) {
				    $sku_is_exist = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productcode='".$v['productcode']."' AND productid!='$k'");

					if (empty($sku_is_exist)) {
						$update[] = "productcode='".$v['productcode']."'";
					} else {
						$exist_sku[] = $sku_is_exist;
					}
				}

				if (isset($v["product"])) {
						$update[] = "product='".$v['product']."'";
				}
				if (isset($v['product_froogle'])) {
						$update[] = "product_froogle='$v[product_froogle]'";
				}
				# Perform SQL query to update products
				
				if (!empty($update)){

#
##
###
	                                $current_product_info = func_query_first("SELECT * FROM $sql_tbl[products] WHERE productid='$k'");

                                        if (
	                                        $current_product_info["update_search_index"] == "N" &&
        	                                (
                	                         (stripslashes($v["product"]) != $current_product_info["product"] && isset($v["product"])) ||
                        	                 ($v["productcode"] != $current_product_info["productcode"] && isset($v["productcode"])) ||
                                	         ($v["brandid"] != $current_product_info["brandid"] && isset($v["brandid"])) ||
                                        	 (stripslashes($v["fulldescr"]) != $current_product_info["fulldescr"] && isset($v["fulldescr"])) ||
	                                         ($v["upc"] != $current_product_info["upc"] && isset($v["upc"]))
        	                                )
                                        ){
	                                        $update[] = "update_search_index='Y'";
                                        }

                                        if (isset($v["forsale"]) && $v["forsale"] == "N" && $current_product_info["update_search_index"] == "N"){
	                                        $update[] = "update_search_index='D'";
                                        }

###
##
#

					db_query("UPDATE $sql_tbl[products] SET ".implode(",", $update)." WHERE productid='$k'");
				}

				# Perform SQL query to update product prices
				if (isset($v["price"])) {
					$v["price"] = func_convert_number($v["price"]);
					db_query("UPDATE $sql_tbl[pricing] SET price='".doubleval($v["price"])."' WHERE productid='$k' AND quantity='1' AND membershipid = 0 AND $sql_tbl[pricing].variantid = 0");
				}
				
				# Perform SQL query to update product categories
				$cats_query = array();
				if (isset($v['main_category'])) {
					$v['main_category'] = intval($v['main_category']);
					$cat_exists = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['categories'] . ' WHERE categoryid="' . $v['main_category'] . '"');
					if (is_numeric($cat_exists) && $cat_exists > 0) {
						$main_orderby = func_query_first_cell('SELECT orderby FROM ' . $sql_tbl['products_categories'] . ' WHERE (categoryid=' . $v['main_category'] . ' OR main = "Y") AND productid=' . $k);
						$cats_query = array(
							'categoryid'	=> $v['main_category'],
							'productid'		=> $k,
							'orderby'		=> intval($main_orderby),
							'main'			=> 'Y',
						);
					db_query('DELETE FROM ' . $sql_tbl['products_categories'] . ' WHERE (categoryid=' . $v['main_category'] . ' OR main = "Y") AND productid=' . $k);
					func_array2insert('products_categories', $cats_query);
					} else {
						if (!empty($not_exist_cats)) {
							$not_exist_cats .= ', ';
						}
						$not_exist_cats .= $v['main_category'];
					}
				}

				if (isset($v['add_cats'])) {
					$orderbys = func_query_hash('SELECT categoryid, productid, orderby FROM ' . $sql_tbl['products_categories'] . ' WHERE main <> "Y" AND productid=' . $k, array('categoryid', 'productid'), false, true);
					
					$add_cats = array_unique(func_trim(explode(',',$v['add_cats'])));
					if (in_array($v['main_cat'], $add_cats)) {
						$add_cats = array_flip($add_cats);
						unset($add_cats[$v['main_cat']]);
						$add_cats = array_flip($add_cats);
					}
					if (!empty($add_cats)) {
						foreach ($add_cats as $ac) {
							$ac = intval($ac);
							$cat_exists = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['categories'] . ' WHERE categoryid="' . $ac . '"');
							if (is_numeric($cat_exists) && $cat_exists > 0) {
								$cats_query = array(
									'categoryid'	=> $ac,
									'productid'		=> $k,
									'main'			=> 'N',
									'orderby'		=> $orderbys[$ac][$k],
								);
								db_query('DELETE FROM ' . $sql_tbl['products_categories'] . ' WHERE main <> "Y" AND productid=' . $k);
							func_array2insert('products_categories', $cats_query, true);
							} else {
								if (!empty($not_exist_cats)) {
									$not_exist_cats .= ', ';
								}
								$not_exist_cats .= $ac;
							}
						}
					}
				}
				
				if (!empty($active_modules['Multiple_Storefronts'])) {
					func_rebuild_product_sf($k);
                    if (!empty($active_modules['Brands'])) {
                        $brand = func_query_first_cell('SELECT brandid FROM ' . $sql_tbl['products'] 
                            . ' WHERE productid = "' . $k . '"');
                        if (!empty($brand)) {
                            func_rebuild_brand_sf($brand);
                        }
                    }

        		}
	
				# Include 'avail' field into the updating list
				if (isset($v["orderby"]) && is_numeric($v["orderby"])) {
					$cat = intval($cat);
					db_query("UPDATE $sql_tbl[products_categories] SET orderby='".intval($v["orderby"])."' WHERE productid='$k' AND categoryid='$cat'");
				}

			}
			if (!empty($exist_sku))	{
				$top_message["content"] = 	func_get_langvar_by_name("msg_adm_product_n_sku_exist")."<BR />".implode("<BR />", $exist_sku);
				$top_message["type"] = "W";
			} elseif (!empty($not_exist_cats)) {
				$top_message["content"] = func_get_langvar_by_name("msg_categories_do_not_exist") . ": $not_exist_cats" . '<br/>' . func_get_langvar_by_name("msg_adm_products_upd");
				$top_message["type"] = "I";
			} else {
			#
			# Prepare the information message
			#

			$top_message["content"] = func_get_langvar_by_name("msg_adm_products_upd");
			$top_message["type"] = "I";
		}
		}
	} # /if ($mode == "update")
	elseif ($mode == "delete") {
		#
		# Delete the selected products
		#
		x_session_register("products_to_delete");

		if ($confirmed=="Y") {
			# Deleting is confirmed

			require $xcart_dir."/include/safe_mode.php";

			if (is_array($products_to_delete["products"])) {


#
##
###
                die("Forbidden. Access denied. You cannot delete products!");
                return false;
###
##
#

/*
				foreach ($products_to_delete["products"] as $k=>$v)
					func_delete_product($k);

				if (isset($section) && $section == 'section_clone') {
					$force_return = 'search.php?mode=search';
				} else {
				$force_return = $products_to_delete["search_return"];
				}
				#
				# Prepare the information message
				#
				$top_message["content"] = func_get_langvar_by_name("msg_adm_products_del");
				$top_message["type"] = "I";
				x_log_flag('log_products_delete', 'PRODUCTS', "Login: $login\nIP: $REMOTE_ADDR\nOperation: delete products (".implode(',', array_keys($products_to_delete["products"])).")", true);
*/
			}
			else {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_warn_products_del");
				$top_message["type"] = "W";
			}
		}
		else {
			$products_to_delete["products"] = $productids;
			$products_to_delete["navpage"] = $navpage;
			$products_to_delete["section"] = @$section;
			if ($REQUEST_METHOD == 'POST')
				$products_to_delete["search_return"] = $HTTP_REFERER;

			$products_to_delete["cat"] = @$cat;
			func_header_location("process_product.php?mode=delete");
		}
	} # /if ($mode == "delete")
	elseif ($mode == "links" && !empty($productid)) {
		#
		# Generate HTML-links
		#
		func_header_location("product_links.php?productid=$productid");
	}
	elseif ($mode == "clone" && !empty($productid)) {
		#
		# Clone product
		#
		include $xcart_dir."/include/product_clone.php";
	}
	elseif($mode=="details" && !empty($productid)) {
		#
		# Show product details
		#
		func_header_location("product.php?productid=$productid");
	}

	if ($section == "category_products") {
		func_header_location("category_products.php?cat=$cat".(intval($navpage)>1 ? "&page=$navpage" : ""));
	}
	else {
		if(!empty($force_return)) {
			func_header_location($force_return);
		}
		elseif($mode == 'clone' || $mode == "details") {
			func_header_location($HTTP_REFERER);
		}

		func_header_location("search.php?mode=search".(intval($navpage)>1 ? "&page=$navpage" : ""));
	}

} # /if ($REQUEST_METHOD == "POST")

if ($mode == "delete" && $REQUEST_METHOD == "GET") {
	#
	# Prepare for deleting products
	#
	x_session_register("products_to_delete");
	$force_return = $products_to_delete["search_return"];


#
##
###
        $top_message["content"] = func_get_langvar_by_name("msg_adm_delete_product_message");
        $top_message["type"] = "I";
        func_header_location($force_return);
###
##
#


	if (is_array($products_to_delete["products"])) {

		$location[] = array(func_get_langvar_by_name("lbl_products_management"), "search.php");
		$location[] = array(func_get_langvar_by_name("lbl_delete_products"), "");
		$smarty->assign("location", $location);

		foreach ($products_to_delete["products"] as $k=>$v) {
			$condition[] = "productid='".addslashes($k)."'";
		}

		$search_condition = implode(" OR ", $condition);

		$products = func_query("SELECT productid, productcode, product, provider FROM $sql_tbl[products] WHERE $search_condition ORDER BY product, productcode");
		if (is_array($products)) {
			foreach ($products as $k=>$v) {
				$products[$k]["price"] = func_query_first_cell("SELECT MIN(price) FROM $sql_tbl[pricing] WHERE productid='$v[productid]' AND quantity='1' AND membershipid = 0 AND $sql_tbl[pricing].variantid = 0");
				$products[$k]["category"] = func_query_first_cell("SELECT $sql_tbl[categories].category FROM $sql_tbl[categories], $sql_tbl[products_categories] WHERE $sql_tbl[products_categories].productid = '$v[productid]' AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[products_categories].main = 'Y'");
			}

			$smarty->assign("products", $products);

			if (!empty($products_to_delete["navpage"]))
				$smarty->assign("navpage", $products_to_delete["navpage"]);

			if (!empty($products_to_delete["section"])) {
				$smarty->assign("section", $products_to_delete["section"]);
				$smarty->assign("cat", $products_to_delete["cat"]);
			}

			$smarty->assign("search_return", $products_to_delete["search_return"]);

			$smarty->assign("main","product_delete_confirmation");

			@include $xcart_dir."/modules/gold_display.php";
			if ($current_area == "A")
				func_display("admin/home.tpl",$smarty);
			else
				func_display("provider/home.tpl",$smarty);

			exit;
		}
	}

	$top_message["content"] = func_get_langvar_by_name("msg_adm_warn_products_del");
	$top_message["type"] = "W";
}

if (!empty($force_return)) {
	func_header_location($force_return);
}
elseif ($mode == 'clone' || $mode == "details") {
	func_header_location($HTTP_REFERER);
}

func_header_location("search.php?mode=search".(intval($navpage)>1 ? "&page=$navpage" : ""));

?>
