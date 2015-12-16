<?php /* MODIFIED: random:19530 [2009 Nov 12 13:25][Custom development (Add an option to search for duplicated SKUs)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
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
# $Id: search.php,v 1.141.2.25 2007/01/25 07:04:50 max Exp $
#

x_load('product');

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_session_register('bulk_search_query');
x_session_register('bulk_search_query_ids');

if ($REQUEST_METHOD == 'POST' && $mode == "search_gen_discounts" && $current_area != "C") {
	$mode = "search";
	$search_gen_discounts = true;
}

#
##
###
if ($REQUEST_METHOD == 'POST' && $mode == "search_reset" && $current_area != "C") {
	$search_data = "";
	x_session_save("search_data");
	func_header_location("search.php");
}
###
##
#

# START: random:19530 [2009 Nov 12 13:25] 
$advanced_options = array(
    'productcode', 'productid', 'providers', 'price_max', 'avail_max', 'weight_max', 'forsale', 'flag_free_ship', 
    'flag_ship_freight', 'flag_global_disc', 'flag_free_tax', 'flag_min_amount', 'flag_low_avail_limit', 
    'flag_list_price', 'flag_vat', 'flag_gstpst', 'manufacturers', 'empty_discount_slope', 'discount_table', 
    'brands','duplicate_sku', 'outdated_discount_table', 'froogle_differs', 'date_period', 
    'StartDay', 'StartMonth', 'StartYear', 'EndDay', 'EndMonth', 'EndYear'
);
# END: random:19530 [2009 Nov 12 13:25] 

if ($current_area == "C"){
 $sort_fields = array(
//      "productcode"   => func_get_langvar_by_name("lbl_sku"),
        "title"                 => func_get_langvar_by_name("lbl_product_name"),
    "price"             => func_get_langvar_by_name("lbl_price"),
        "orderby"               => func_get_langvar_by_name("lbl_default")
 );
}
else{
 $sort_fields = array(
      "productcode"   => func_get_langvar_by_name("lbl_sku"),
        "title"                 => func_get_langvar_by_name("lbl_product"),
    "price"             => func_get_langvar_by_name("lbl_price"),
        "orderby"               => func_get_langvar_by_name("lbl_default")
 );
}


if ($config["Appearance"]["display_productcode_in_list"] != "Y" && ($current_area == 'C' || $current_area == 'B'))
	unset($sort_fields["productcode"]);

if($current_area == 'A' || $current_area == 'P') {
    $sort_fields["quantity"] = func_get_langvar_by_name("lbl_in_stock");
}

if (empty($search_data)) {
	$search_data = array();
}

if ($REQUEST_METHOD == "POST" && $mode == 'search' && empty($e_mode) && $cidev_filter_mode != "load_more_products" && $cidev_filter_mode != "load_more_e_products" && $cidev_filter_mode != "load_more_products_SKU") {

	$fast_search_parameter = (!empty($fast_search)) ? '&fast_search=' . $fast_search : '';
	
	#
	# Update the session $search_data variable from $posted_data
	#
	if (!empty($posted_data)) {
		$need_advanced_options = false;
		foreach ($posted_data as $k=>$v) {
			if (!is_array($v) && !is_numeric($v))
				$posted_data[$k] = stripslashes($v);

			if (in_array($k, $advanced_options) && $v !== "")
				$need_advanced_options = true;
		}

		# Update the search statistics
		if ($posted_data["substring"]) {
			if (!empty($active_modules['Multiple_Storefronts'])) {
				/*unusefull*/
				/*db_query("INSERT INTO $sql_tbl[stats_search] (search, date, storefrontid) VALUES ('".addslashes($posted_data["substring"])."', '".time()."', $current_storefront)");*/
			} else {
			db_query("INSERT INTO $sql_tbl[stats_search] (search, date) VALUES ('".addslashes($posted_data["substring"])."', '".time()."')");
		}
		}

		if (!$need_advanced_options)
			$need_advanced_options = (doubleval($posted_data["discount_slope"]) != 0 || doubleval($posted_data["price_min"]) != 0 || intval($posted_data["avail_min"]) != 0 || doubleval($posted_data["weight_min"]) != 0);
		if (!$need_advanced_options && $current_area == "C" && !empty($posted_data["categoryid"]))
			$need_advanced_options = true;

		$posted_data["need_advanced_options"] = $need_advanced_options;

		# START: random:18298_18304_18324 [2009 Jun 08 09:50]
		if ($need_advanced_options) {
			$search_args_str = '';
		}
		# END: random:18298_18304_18324 [2009 Jun 08 09:50]
		
        if ($StartMonth) {
			$posted_data['start_date'] = mktime(0, 0, 0, $StartMonth, $StartDay, $StartYear);
			$posted_data['end_date'] = mktime(23, 59, 59, $EndMonth, $EndDay, $EndYear);
		}

		#
		# Data convertation for Feature comparison module
		#
		if(!empty($active_modules['Feature_Comparison'])) {
			include $xcart_dir."/modules/Feature_Comparison/search_define.php";
		}

		if (empty($search_data["products"]["sort_field"])) {
			if ($current_area == 'C' && !empty($config['Appearance']['products_order'])) {
				$posted_data["sort_field"] = $config['Appearance']['products_order'];
				$posted_data["sort_direction"] = 1;

			} else {
				$posted_data["sort_field"] = "title";
				$posted_data["sort_direction"] = 0;
			}

		} else {
			$posted_data["sort_field"] = $search_data["products"]["sort_field"];
			$posted_data["sort_direction"] = $search_data["products"]["sort_direction"];
		}

        if (is_array($posted_data["extra_sku"]) || $current_area != "C") {
            foreach ($posted_data["extra_sku"] as $k => $v) 
                if ($v == "")
                    unset($posted_data["extra_sku"][$k]);
            $posted_data["extra_sku"] = array_values($posted_data["extra_sku"]);
            if (empty($posted_data["extra_sku"]))
                unset($posted_data["extra_sku"]);
            if (count($posted_data["extra_sku"]) == 1) {
                $posted_data["productcode"] = trim($posted_data["extra_sku"][0]);
				unset($posted_data["extra_sku"]);
	        } else {
		        unset($posted_data["productcode"]);
        	}
        } else {
           	unset($posted_data["extra_sku"]);
        }

		$posted_data['is_modify'] = $posted_data['is_modify'];
		$posted_data['is_export'] = $posted_data['is_export'];
		func_unset($posted_data, '_');

        $posted_data['froogle_titles'] = $froogle_titles;
		
        if (isset($posted_data['providers']) && is_array($posted_data['providers'])) {
            $providers = array();
            foreach ($posted_data['providers'] as $provider) {
                $providers[$provider] = $provider;
            }
            $posted_data['providers'] = $providers;
        }

#
##
###
if ( (!empty($active_modules['CIDEV_Best_Search_Filter']) && $current_area == 'C') || ($current_area != 'C') ) {

	if (!empty($search_data['products']['filter_name_id']) && is_array($search_data['products']['filter_name_id']) && !empty($search_data['products']['filter_value_id']) && is_array($search_data['products']['filter_value_id'])){
                        foreach ($search_data['products']['filter_name_id'] as $k => $v){
                                if (empty($v)){
                                        unset($search_data['products']['filter_name_id'][$k]);
                                        unset($search_data['products']['filter_value_id'][$k]);
                                }
                        }
	}

        if (!empty($search_data['products']['filter_name_id']) && is_array($search_data['products']['filter_name_id'])){
                $posted_data['filter_name_id'] = $search_data['products']['filter_name_id'];
        }
	else {
		unset($posted_data['filter_name_id']);
	}

        if (!empty($search_data['products']['filter_value_id']) && is_array($search_data['products']['filter_value_id'])){
                $posted_data['filter_value_id'] = $search_data['products']['filter_value_id'];
        }
	else {
		unset($posted_data['filter_value_id']);
	}
	if (!empty($search_data['products']['sorted_filter_values_id'])){
		$posted_data['sorted_filter_values_id'] = $search_data['products']['sorted_filter_values_id'];
	}
/*
	if ($search_data['products']['filter_replace_query'] == "Y"){
                $posted_data['filter_replace_query'] = $search_data['products']['filter_replace_query'];
	}
	else {
                unset($posted_data['filter_replace_query']);
        }
*/
}
###
##
#

        
        $search_data["products"] = $posted_data;
	}


	if (!$search_gen_discounts) {

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
	  if ($filter_mode == "search"){
		func_header_location("cidev_admin_add_filter_to_products.php?mode=search&page=1".((!empty($search_args_str)) ? $search_args_str : '') . $fast_search_parameter);
	  } else {
//	    if ($ajax_load_more_products != "Y"){
		func_header_location("search.php?mode=search&page=1".((!empty($search_args_str)) ? $search_args_str : '') . $fast_search_parameter);
//	    }
	  }
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
	}
}


if ($mode == "search") {
	x_session_unregister('colnames');
	x_session_unregister('changes');
	x_session_unregister('userfile');

	#
	# Perform search and display results
	#
	$data = array();

	$flag_save = false;

	#
	# Initialize service arrays
	#
	$fields = array();
	$fields_count = array();
	$from_tbls = array();
	$inner_joins = array();
	$left_joins = array();
	$where = array();
	$groupbys = array();
	$having = array();
	$orderbys = array();

	#
	# Prepare the search data
	#
	if (!empty($sort) && isset($sort_fields[$sort])) {
		# Store the sorting type in the session
		$search_data["products"]["sort_field"] = $sort;
		$flag_save = true;
	}

	if (isset($sort_direction)) {
		# Store the sorting direction in the session
		$search_data["products"]["sort_direction"] = $sort_direction;
		$flag_save = true;
	}


	if ($current_area == 'C' && !empty($config['Appearance']['products_order']) && empty($search_data["products"]["sort_field"])) {
		$search_data["products"]["sort_field"] = $config['Appearance']['products_order'];
		$search_data["products"]["sort_direction"] = 0;
	}

	if (!empty($page) && $search_data["products"]["page"] != intval($page)) {
		# Store the current page number in the session
		$search_data["products"]["page"] = $page;
		$flag_save = true;
	}

	if (is_array($search_data["products"])) {
		$data = $search_data["products"];
		foreach ($data as $k=>$v)
			if (!is_array($v) && !is_numeric($v))
				$data[$k] = addslashes($v);
	}

	#
	# Translate service data to inner service arrays
	#
	if (!empty($data['_'])) {
		foreach ($data['_'] as $saname => $sadata) {
			if (isset($$saname) && is_array($$saname) && empty($$saname))
				$$saname = $sadata;
		}
	}

	if (!empty($active_modules['Multiple_Storefronts']) && !$search_all_website) {
		if (($current_area == 'P' || $current_area == 'A') && $config['Search_products']['search_by_sku_from_all_sf'] == 'N') {
			$inner_joins['products_sf'] = array(
			  'on'	=> "$sql_tbl[products].productid=$sql_tbl[products_sf].productid AND $sql_tbl[products_sf].sfid = $current_storefront"
			);
		} else if (($current_area == 'P' || $current_area == 'A') && $config['Search_products']['search_by_sku_from_all_sf'] == 'Y') {
	            $fields[] = "$sql_tbl[storefronts].domain";
        	    $left_joins['products_sf'] = array(	
                	'on'	=> "$sql_tbl[products].productid=$sql_tbl[products_sf].productid"
		    );
	            $left_joins['storefronts'] = array(
        	        'on'    => "$sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid"
	            );
	        } else if ($current_area == 'C') {
        		$inner_joins['products_sf'] = array(
			  'on'	=> "$sql_tbl[products].productid=$sql_tbl[products_sf].productid AND $sql_tbl[products_sf].sfid = $current_storefront"
			);

                        $inner_joins['pc_options'] = array(
                          'on'  => "$sql_tbl[pc_options].storefrontid=$sql_tbl[products_sf].sfid"
                        );

			$fields[] = "$sql_tbl[pc_options].disable_AC_products";
			$where[] = "(($sql_tbl[pc_options].disable_AC_products='N') OR ($sql_tbl[pc_options].disable_AC_products='Y' AND $sql_tbl[products].pc_classify_status!='AC'))";
        	}
	}

	if ($search_all_website) {
		$url_parts = parse_url($config['Company']['company_website']);
		unset($url_parts['scheme']);
		$default_sf = implode('', $url_parts);

		$fields[] = "IF($sql_tbl[storefronts].domain IS NULL, '$default_sf', $sql_tbl[storefronts].domain) AS domain";
	        $left_joins['products_sf'] = array(	
        	    'on'	=> "$sql_tbl[products].productid=$sql_tbl[products_sf].productid"
		);
	        $left_joins['storefronts'] = array(
        	    'on'    => "$sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid"
	        );
	}

	$sort_string = "";
	$membershipid_string = ($user_account['membershipid'] == 0 || empty($active_modules['Wholesale_Trading'])) ? "= 0" : "IN ('$user_account[membershipid]', 0)";

	$fields[] = "$sql_tbl[products].*";
	$from_tbls[] = "pricing";
	$left_joins['quick_flags'] = array(
		"on" => "$sql_tbl[quick_flags].productid = $sql_tbl[products].productid"
	);
	$fields[] = "$sql_tbl[quick_flags].*";

	$inner_joins['quick_prices'] = array(
		"on" => "$sql_tbl[quick_prices].productid = $sql_tbl[products].productid /*AND $sql_tbl[quick_prices].membershipid $membershipid_string*/"
	);
//	$where[] = "$sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid and $sql_tbl[pricing].quantity = 1";
	$where[] = "$sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid /*and $sql_tbl[pricing].quantity<=$sql_tbl[products].min_amount*/";
	$fields[] = "$sql_tbl[quick_prices].variantid";
/*
	if ($user_account['membershipid'] == 0) {
		$fields[] = "$sql_tbl[pricing].price";
	} else {
//		$fields[] = "MIN($sql_tbl[pricing].price) as price";
		$fields[] = "$sql_tbl[pricing].price";
	}
*/

	$fields[] = "$sql_tbl[pricing].price";


/* speed optimization
	if ($current_area == 'C' && empty($active_modules['Product_Configurator'])) {
		$where[] = "$sql_tbl[products].product_type <> 'C'";
		$where[] = "$sql_tbl[products].forsale <> 'B'";
	}
	if ($current_area == 'C' && defined('SO_CUSTOMER_OFFERS')) {
		# Display all products (including hidden)
		$where[] = "$sql_tbl[products].forsale <> 'N'";
	}
*/

/*
	if (!$single_mode && AREA_TYPE != 'A' && AREA_TYPE != 'P') {
		$inner_joins['ACHECK'] = array(
			"tblname" => 'customers',
			"on" => "$sql_tbl[products].provider = ACHECK.login"
		);
	}
*/

	$data["substring"] = trim($data["substring"]);

	$search_by_variants = false;

	if (!empty($data["substring"])) {

		$condition = array();
		$search_string_fields = array();
		if (empty($data["by_title"]) && empty($data["by_shortdescr"]) && empty($data["by_fulldescr"]) && empty($data["extra_fields"]) && empty($data["by_sku"]) && empty($data['by_froogle_title'])) {
			$search_data["products"]["by_title"] = $data["by_title"] = "Y";
			$flag_save = true;
		}

		# Search for substring in some fields...

		if (!empty($data["by_title"])) {
			$search_string_fields[] = "product";
		}

		if (!empty($data["by_keywords"])) {
			$search_string_fields[] = "keywords";
		}

		if (!empty($data["by_shortdescr"])) {
			$search_string_fields[] = "descr";
		}

		if (!empty($data["by_fulldescr"])) {
			$search_string_fields[] = "fulldescr";
		}

		if (!empty($data["by_froogle_title"])) {
			$search_string_fields[] = "product_froogle";
		}

		if ((!empty($data["by_shortdescr"]) || !empty($data["by_fulldescr"])) && $current_area == 'C' && !in_array("keywords", $search_string_fields)) {
			$search_string_fields[] = "keywords";
		}

		$search_words = array();
		if ($config['General']['allow_search_by_words'] == 'Y' && in_array($data['including'], array("all", "any"))) {
			$tmp = trim($data["substring"]);
			if (preg_match_all('/"([^"]+)"/', $tmp, $match)) {
				$search_words = $match[1];
				$tmp = str_replace($match[0], "", $tmp);
			}
			$tmp = explode(" ", $tmp);
			$tmp = func_array_map("trim", $tmp);
			$search_words = array_merge($search_words, $tmp);
			unset($tmp);

			# Check word length limit
			if ($search_word_length_limit > 0) {
				$search_words = preg_grep("/^..+$/", $search_words);
			}

			# Check stop words
			x_load("product");
			$stopwords = func_get_stopwords();
			if (!empty($stopwords) && is_array($stopwords)) {
				$tmp = preg_grep("/^(".implode("|", $stopwords).")$/i", $search_words);
				if (!empty($tmp) && is_array($tmp)) {
					$search_words = array_diff($search_words, $tmp);
					$search_words = array_values($search_words);
				}
				unset($tmp);
			}

			# Check word count limit
			if ($search_word_limit > 0 && count($search_words) > $search_word_limit) {
				$search_words = array_splice($search_words, $search_word_limit-1);
			}
		}

		foreach ($search_string_fields as $ssf) {
			if ($config['General']['allow_search_by_words'] == 'Y' && !empty($search_words) && in_array($data['including'], array("all", "any"))) {
				if ($data['including'] == 'all') {
					$tmp = array();
					foreach ($search_words as $sw) {
						if ($current_area == 'C' || $current_area == 'B') {
							if ($ssf == 'keywords') {
								$tmp[] = "($sql_tbl[products_lng].$ssf LIKE '%".$sw."%' OR $sql_tbl[products].$ssf LIKE '%".$sw."%')";

							} else
								$tmp[] = "IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].$ssf, $sql_tbl[products].$ssf) LIKE '%".$sw."%'";
	

						} else {
							$tmp[] = "$sql_tbl[products].$ssf LIKE '%".$sw."%'";
						}
					}
					if (!empty($tmp))
						$condition[] = "(".implode(" AND ", $tmp).")";
					unset($tmp);

				} else {
					if ($current_area == 'C' || $current_area == 'B') {
						if ($ssf == 'keywords') {
							$condition[] = "$sql_tbl[products_lng].$ssf REGEXP '".implode("|", $search_words)."'";
							$condition[] = "$sql_tbl[products].$ssf REGEXP '".implode("|", $search_words)."'";

						} else
							$condition[] = "IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].$ssf, $sql_tbl[products].$ssf) REGEXP '".implode("|", $search_words)."'";
					} else {
						$condition[] = "$sql_tbl[products].$ssf REGEXP '".implode("|", $search_words)."'";
					}
				}

			} elseif ($current_area == 'C' || $current_area == 'B') {
				$condition[] = "IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].$ssf, $sql_tbl[products].$ssf) LIKE '%".$data["substring"]."%'";

			} else {
				$condition[] = "$sql_tbl[products].$ssf LIKE '%".$data["substring"]."%'";
			}
		}

		if (!empty($data["by_sku"])) {
			$search_by_variants = true;
			$condition[] = (empty($active_modules['Product_Options']) ? "$sql_tbl[products].productcode" : "IFNULL(search_variants.productcode, $sql_tbl[products].productcode)")." LIKE '%".$data["substring"]."%'";
		}

		if (!empty($data["extra_fields"]) && $active_modules['Extra_Fields']) {
			foreach ($data["extra_fields"] as $k => $v)
				$condition[] = "($sql_tbl[extra_field_values].value LIKE '%".$data["substring"]."%' AND $sql_tbl[extra_fields].fieldid = '$k')";

			$left_joins['extra_field_values'] = array(
				"on" => "$sql_tbl[products].productid = $sql_tbl[extra_field_values].productid"
			);
			$left_joins['extra_fields'] = array(
				"on" => "$sql_tbl[extra_field_values].fieldid = $sql_tbl[extra_fields].fieldid AND $sql_tbl[extra_fields].active = 'Y'"
			);
		}

		if (!empty($condition))
			$where[] = "(".implode(" OR ", $condition).")";
		unset($condition);

	} # /if (!empty($data["substring"]))

	#
	# Search by product features
	#
	if (!empty($active_modules['Feature_Comparison'])) {
		include $xcart_dir."/modules/Feature_Comparison/search_define.php";
	}

	#
	# Internation names & descriptions
	#
	if ($current_area == 'C' || $current_area == 'B') {
		$fields[] = "/*IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].product,*/( $sql_tbl[products].product) as product";
		$fields[] = "/*IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].descr,*/ ( $sql_tbl[products].descr) as descr";
		$fields[] = "/*IF($sql_tbl[products_lng].productid != '', $sql_tbl[products_lng].fulldescr,*/ ( $sql_tbl[products].fulldescr) as fulldescr";
		/*
		if (!empty($data["by_title"]) || !empty($data["by_keywords"]) || !empty($data["by_shortdescr"]) || !empty($data["by_fulldescr"]) || !empty($data['by_froogle_title']) ) {
			$left_joins['products_lng'] = array(
				"on" => "$sql_tbl[products_lng].productid = $sql_tbl[products].productid AND $sql_tbl[products_lng].code = '$shop_language'"
			);
		} else {
			$left_joins['products_lng'] = array(
				"on" => "$sql_tbl[products_lng].productid = $sql_tbl[products].productid AND $sql_tbl[products_lng].code = '$shop_language'",
				"only_select" => true
			);
		}
		*/
	}

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	if (!empty($data["manufacturers"]) && $active_modules['Manufacturers']) {
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
		$where[] = "$sql_tbl[products].manufacturerid IN ('".implode("','", $data["manufacturers"])."')";
	}

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
	if (!empty($data["brands"]) && $active_modules['Brands']) {
		$where[] = "$sql_tbl[products].brandid IN ('".implode("','", $data["brands"])."')";
	}

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
	if ($current_area == 'C' && 1==2) {
		if ($user_account['membershipid'] == 0) {
			$where[] = "$sql_tbl[category_memberships].membershipid IS NULL AND $sql_tbl[product_memberships].membershipid IS NULL";
		} else {
			$where[] = "($sql_tbl[category_memberships].membershipid IS NULL OR $sql_tbl[category_memberships].membershipid = '$user_account[membershipid]')";
			$where[] = "($sql_tbl[product_memberships].membershipid IS NULL OR $sql_tbl[product_memberships].membershipid = '$user_account[membershipid]')";
		}

		$where[] = "$sql_tbl[categories].avail = 'Y' and $sql_tbl[categories].storefrontid = '$current_storefront'";
	}

	$inner_joins['products_categories'] = array(
		"on" => "$sql_tbl[products_categories].productid = $sql_tbl[products].productid"
	);

if ($current_storefront == ""){ // https://basecamp.com/2070980/projects/1577907/messages/39190109
        $inner_joins['categories'] = array(
                "on" => "$sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid"
        );
}else{
	$inner_joins['categories'] = array(
		"on" => "$sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid and $sql_tbl[categories].storefrontid = $current_storefront"
	);
}
	if ($current_area != 'C') {
		$left_joins['category_memberships'] = array(
		"on" => "$sql_tbl[category_memberships].categoryid = $sql_tbl[categories].categoryid",
		"parent" => "categories"
		);
		$left_joins['product_memberships'] = array(
		"on" => "$sql_tbl[product_memberships].productid = $sql_tbl[products].productid"
		);
	}
	$left_joins_bulk['manufacturers'] = array(
		'on' => "$sql_tbl[products].manufacturerid = $sql_tbl[manufacturers].manufacturerid"
	);

	if (!empty($data["categoryid"])) {
		# Search by category...

		$data["categoryid"] = intval($data["categoryid"]);

		$category_sign = "";

		if (empty($data["category_main"]) && empty($data["category_extra"])) {
			$category_sign = "NOT";
		}

		if (!empty($data["search_in_subcategories"])) {
			# Search also in all subcategories
			$categoryid_path = addslashes(func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE avail='Y' and categoryid='".$data["categoryid"]."' and $sql_tbl[categories].storefrontid = ".$current_storefront));
			$categoryids = func_query_column("SELECT categoryid FROM $sql_tbl[categories] WHERE  avail='Y' and (categoryid='".$data["categoryid"]."' OR categoryid_path LIKE '$categoryid_path/%') and $sql_tbl[categories].storefrontid = ".$current_storefront);

			if (is_array($categoryids) && !empty($categoryids)) {
				$where[] = "$sql_tbl[products_categories].categoryid $category_sign IN (".implode(",", $categoryids).")";
			}
		}
		else {
			$where[] = "$category_sign $sql_tbl[products_categories].categoryid='$data[categoryid]'";
		}

		$condition = array();

		if (!empty($data["category_main"]))
			$condition[] = "$sql_tbl[products_categories].main='Y'";

	if ($current_area != 'C') {
		if (!empty($data["category_extra"]))
			$condition[] = "$sql_tbl[products_categories].main!='Y'";
	}

		if (!empty($condition))
			$where[] = "(".implode(" OR ", $condition).")";
	}
	# /if (!empty($data["categoryid"]))


    if (count($data["extra_sku"]) == 1) {
		$data["productcode"] = $data["extra_sku"][0];
		unset($data["extra_sku"]);
	}	


	if (!empty($data["productcode"])) {
		$search_by_variants = true;
		$productcode_cond_string = empty($active_modules['Product_Options']) ? "$sql_tbl[products].productcode" : "IFNULL(search_variants.productcode, $sql_tbl[products].productcode)";
		$where[] = "$productcode_cond_string LIKE '%".$data["productcode"]."%'";
	} elseif (is_array($data["extra_sku"]) && !empty($data["extra_sku"])) {
        $search_by_variants = true;
        $productcode_cond_string = empty($active_modules['Product_Options']) ? "$sql_tbl[products].productcode" : "IFNULL(search_variants.productcode, $sql_tbl[products].productcode)";
        $where[] = $productcode_cond_string." IN ('".implode("','",$data["extra_sku"])."')";
	}



	if (!empty($data["productid"])) {
		$where[] = "$sql_tbl[products].productid ".(is_array($data["productid"]) ? " IN ('".implode("','", $data["productid"])."')": "= '".$data["productid"]."'");
	}

	if (!empty($data['providers'])) {
		if (is_array($data['providers'])) {
			
            $where[] = $sql_tbl['products'] . '.provider IN ("' . implode('", "', $data['providers']) . '")';
		
        } else {
				if ($login_type == 'P') {
					$selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
					if (!empty($selected_manufacturers)) {
						$selected_manufacturers = unserialize($selected_manufacturers);
					}

					$tmp = '';
					if (is_array($selected_manufacturers)) {
						if (!empty($data["manufacturers"])) {
							foreach ($selected_manufacturers as $k => $v) {
								if (!in_array($v, $data["manufacturers"])) {
									unset($selected_manufacturers[$k]);
								}
							}
						}
						if ($selected_manufacturers) {
							$tmp = "OR $sql_tbl[products].manufacturerid IN ('".implode("','", $selected_manufacturers)."')";
						}
					}

					$where[] = "($sql_tbl[products].provider = '" . $data['providers'] . "' $tmp)";
				} else {
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					$where[] = $sql_tbl['products'] . '.provider = "' . $data['providers'] . '"';
				}
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
			}	
	}
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	
    #
	# Search by date condition
	#
	if (!empty($data['date_period'])) {
		if ($data['date_period'] == 'C') {
			# ...within specified period
			$start_date = $data['start_date'] - $config['Appearance']['timezone_offset'];
			$end_date = $data['end_date'] - $config['Appearance']['timezone_offset'];
		} else {
			# ...within this month
			$end_date = time() + $config['Appearance']['timezone_offset'];
			if ($data['date_period'] == 'M') {
				$start_date = mktime(0, 0, 0, date('n', $end_date), 1, date('Y', $end_date));
			} elseif ($data['date_period'] == 'D') {
				$start_date = mktime(0, 0, 0, date('n', $end_date), date('j', $end_date), date('Y', $end_date));
			} elseif ($data["date_period"] == 'W') {
				$first_weekday = $end_date - (date('w', $end_date) * 86400);
				$start_date = mktime(0, 0, 0, date('n', $first_weekday), date('j', $first_weekday), date('Y', $first_weekday));
			}

			$start_date -= $config['Appearance']['timezone_offset'];
			$end_date = time();
		}

		$where[] = $sql_tbl['products'] . '.add_date >= "' . $start_date . '"';
		$where[] = $sql_tbl['products'] . '.add_date <= "' . $end_date . '"';
	}
	
	if (!empty($data["price_min"])) {
		$where[] = "$sql_tbl[pricing].price >= '".$data["price_min"]."'";
	}
	$data["discount_slope"] = floatval($data["discount_slope"]);
	if (!empty($data["empty_discount_slope"])) {
		$where[] = "$sql_tbl[products].discount_slope = '0'";
	} else {
		if (!empty($data["discount_slope"])) {
			$where[] = "$sql_tbl[products].discount_slope = '".price_format($data["discount_slope"])."'";
		}
		if (!empty($data["discount_table"])) {
			$where[] = "$sql_tbl[products].discount_table LIKE '".$data["discount_table"]."'";
		}
	}

	if (strlen(@$data["price_max"]) > 0) {
		$where[] = "$sql_tbl[pricing].price <= '".$data["price_max"]."'";
	}

	if (!empty($data["price_min"]) || strlen(@$data["price_max"]) > 0) {

		# If price limitation is enabled, dont show configurable products (configurable product has zero price always)
//		$where[] = "$sql_tbl[products].product_type != 'C'";
	}

	$avail_cond_string = empty($active_modules['Product_Options']) ? "$sql_tbl[products].avail" : "IFNULL(search_variants.avail, $sql_tbl[products].avail)";
	if (!empty($data["avail_min"])) {
		$search_by_variants = true;
		$where[] = "$avail_cond_string >= '".$data["avail_min"]."'";
	}

	if (strlen(@$data["avail_max"]) > 0) {
		$search_by_variants = true;
		$where[] = "$avail_cond_string <= '".$data["avail_max"]."'";
	}

	$weight_cond_string = empty($active_modules['Product_Options']) ? "$sql_tbl[products].weight" : "IFNULL(search_variants.weight, $sql_tbl[products].weight)";
	if (!empty($data["weight_min"])) {
		$search_by_variants = true;
		$where[] = "$weight_cond_string >= '".$data["weight_min"]."'";
	}

	if (strlen(@$data["weight_max"]) > 0) {
		$search_by_variants = true;
		$where[] = "$weight_cond_string <= '".$data["weight_max"]."'";
	}

	if (!empty($data["forsale"]))
		$where[] = "$sql_tbl[products].forsale = '".$data["forsale"]."'";

	if (!empty($data["flag_free_ship"]))
		$where[] = "$sql_tbl[products].free_shipping = '".$data["flag_free_ship"]."'";

	if (!empty($data["flag_ship_freight"]))
		$where[] = "$sql_tbl[products].shipping_freight = '".$data["flag_ship_freight"]."'";

	if (!empty($data["flag_ship_freight"])) {
		if ($data["flag_ship_freight"] == "Y")
			$where[] = "$sql_tbl[products].shipping_freight > 0";
		else
			$where[] = "$sql_tbl[products].shipping_freight = 0";
	}

	if (!empty($data["flag_global_disc"]))
		$where[] = "$sql_tbl[products].discount_avail = '".$data["flag_global_disc"]."'";

	if (!empty($data["flag_free_tax"]))
		$where[] = "$sql_tbl[products].free_tax = '".$data["flag_free_tax"]."'";

	if (!empty($data["flag_min_amount"])) {
		if ($data["flag_min_amount"] == "Y")
			$where[] = "$sql_tbl[products].min_amount != '1'";
		else
			$where[] = "$sql_tbl[products].min_amount = '1'";
	}

	if (!empty($data["flag_low_avail_limit"])) {
		if ($data["flag_low_avail_limit"] == "Y")
			$where[] = "$sql_tbl[products].low_avail_limit != '10'";
		else
			$where[] = "$sql_tbl[products].low_avail_limit = '10'";
	}

	if (!empty($data["flag_list_price"])) {
		if ($data["flag_list_price"] == "Y")
			$where[] = "$sql_tbl[products].list_price != '0'";
		else
			$where[] = "$sql_tbl[products].list_price = '0'";
	}

	if(!empty($active_modules['Product_Options'])) {
		if ($search_by_variants) {
			$left_joins["search_variants"] = array(
				"tblname" => "variants",
				"on" => "search_variants.productid = $sql_tbl[products].productid",
			);
		}
		$left_joins["variants"] = array(
			"on" => "$sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid",
			"parent" => "quick_prices"
		);
		foreach ($variant_properties as $property) {
			$fields[] = "IFNULL($sql_tbl[variants].$property, $sql_tbl[products].$property) as ".$property;
		}
	}

# START: random:19530 [2009 Nov 12 13:25] 
	if (!empty($data["duplicate_sku"])) {
		# Get list of duplicate SKU
		$duplicates = func_query_column("SELECT productcode, COUNT(*) AS cnt FROM $sql_tbl[products] GROUP BY productcode HAVING cnt>1");
		if (!empty($duplicates)) {
			$where[] = "$sql_tbl[products].productcode IN ('".join("','",$duplicates)."')";
		} else {
			$where = array("0");
		}
	}

# END: random:19530 [2009 Nov 12 13:25] 
	
	if ($data['empty_froogle_title'] == 'Y') {
		$where[] = 'TRIM('.$sql_tbl['products'].'.product_froogle) = ""';
	}

	if ($data['froogle_differs'] == 'Y') {
		$where[] = '(TRIM(LEFT(' . $sql_tbl['products'] . '.product, LENGTH(' . $sql_tbl['products'] . '.product_froogle))) <> TRIM(' . $sql_tbl['products'] . '.product_froogle) || LENGTH(' . $sql_tbl['products'] . '.product_froogle) = 0)';
	}

	if ($data['no_thumbnail'] == 'Y') {
		$thumbnail_exists = func_query_column('SELECT id FROM '.$sql_tbl['images_T'], 'id');
		if (is_array($thumbnail_exists) && !empty($thumbnail_exists)) {
			$thumbnail_exists = implode(', ', $thumbnail_exists);
			$where[] = $sql_tbl['products'].'.productid NOT IN ('.$thumbnail_exists.')';
		} else {
			$where = array('0');
		}
	}

	if ($data['no_product_image'] == 'Y') {
		$p_image_exists = func_query_column('SELECT id FROM '.$sql_tbl['images_P'], 'id');
		if (is_array($p_image_exists) && !empty($p_image_exists)) {
			$p_image_exists = implode(', ', $p_image_exists);
			$where[] = $sql_tbl['products'].'.productid NOT IN ('.$p_image_exists.')';
		} else {
			$where = array('0');
		}
	}

	if ($data['no_detailed_images'] == 'Y') {
		$d_images_exists = func_query_column('SELECT id FROM '.$sql_tbl['images_D'], 'id');
		if (is_array($d_images_exists) && !empty($d_images_exists)) {
			$d_images_exists = implode(', ', $d_images_exists);
			$where[] = $sql_tbl['products'].'.productid NOT IN ('.$d_images_exists.')';
		} else {
			$where = array('0');
		}
	}

	if ($data['broken_images'] == 'Y') {
		$broken_images = '';
		foreach ($config['available_images'] as $type => $v) {
			$have_broken_images = func_query_column('SELECT id FROM '.$sql_tbl['images_'.$type].' WHERE LOCATE("#", filename) > 0 OR (filename = "" AND image_path = "" AND image_size = 0 AND image = "")', 'id');
			if (is_array($have_broken_images) && !empty($have_broken_images)) {
				$broken_images .= implode(', ', $have_broken_images);
			}
		}
		if (!empty($broken_images)) {
			$where[] = $sql_tbl['products'].'.productid IN ('.$broken_images.')';
		} else {
			$where = array('0');
		}
	}

	if (!empty($data["sort_field"])) {
		# Sort the search results...

		$direction = ($data["sort_direction"] ? "DESC" : "ASC");

		if ($config["Appearance"]["display_productcode_in_list"] != "Y" && ($current_area == 'C' || $current_area == 'B') && $data["sort_field"] == 'productcode')
			$data["sort_field"] = 'orderby';

		switch ($data["sort_field"]) {
			case "productcode":
				$sort_string = "$sql_tbl[products].productcode $direction";
				break;
			case "title":
				$sort_string = "$sql_tbl[products].product $direction";
				break;
			case "orderby":
				$sort_string = "$sql_tbl[products_categories].orderby $direction";
				break;
			case "quantity":
				$sort_string = "$sql_tbl[products].avail $direction";
				break;
			case "price":
				if (!empty($active_modules["Special_Offers"]) && !empty($search_data["products"]["show_special_prices"])) {
					$sort_string = "x_special_price $direction, price $direction";
				}
				else {
					$sort_string = "price $direction";
				}
				break;
			default:
				$sort_string = "$sql_tbl[products].product";
		}
	}
	else {
		$sort_string = "$sql_tbl[products].product";
	}

	if(!empty($data['sort_condition'])) {
		$sort_string = $data['sort_condition'];
	}

	if (($current_area == "C" || $current_area == "B") && $config["General"]["disable_outofstock_products"] == "Y") {
		if (!empty($active_modules['Product_Options'])) {
			$where[] = "(IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) > 0 /*OR $sql_tbl[products].product_type NOT IN ('','N'))*/";
		} else {
			$where[] = "($sql_tbl[products].avail > 0 /*OR $sql_tbl[products].product_type NOT IN ('','N'))*/";
		}
	}

	$groupbys[] = "$sql_tbl[products].productid";
	$orderbys[] = $sort_string;
	$orderbys[] = "$sql_tbl[products].product ASC";


###################################################################################
### Search_Filter ###
###################################################################################
if ( (!empty($active_modules['CIDEV_Best_Search_Filter']) && $current_area == 'C') || ($current_area != 'C') ) {

        $left_joins['cidev_filter_products'] = array(
 	       'on' => "$sql_tbl[cidev_filter_products].productid = $sql_tbl[products].productid"
        );
        $left_joins['cidev_filter_values'] = array(
        	'on' => "$sql_tbl[cidev_filter_values].fv_id = $sql_tbl[cidev_filter_products].fv_id"
        );
        $left_joins['cidev_filters'] = array(
        	'on' => "$sql_tbl[cidev_filters].f_id = $sql_tbl[cidev_filter_values].f_id"
        );

	if (!empty($search_data['products']['filter_selected_brandids']) && is_array($search_data['products']['filter_selected_brandids'])) {
		$imploded_filter_selected_brandids = implode(",", $search_data['products']['filter_selected_brandids']);
		$where[] = "$sql_tbl[products].brandid IN ($imploded_filter_selected_brandids)";
	}

//$search_data['products']['sorted_filter_values_id'] = "";
	if (!empty($search_data['products']['sorted_filter_values_id']) && is_array($search_data['products']['sorted_filter_values_id'])) {
//func_print_r($search_data['products']['sorted_filter_values_id']);

		$count_selected_filters = count($search_data["products"]["sorted_filter_values_id"]);

        	$fv_ids_arr = array();
	        foreach ($search_data['products']['sorted_filter_values_id'] as $f_id => $fv_ids){
        	        if (!empty($fv_ids) && is_array($fv_ids)){
				$fv_ids_arr[] = implode(",", $fv_ids);

	                }       
        	}       
		$all_imploded_fv_ids = implode(",", $fv_ids_arr);

	        $where[] = "$sql_tbl[cidev_filter_products].fv_id IN ($all_imploded_fv_ids)";

		$having[] = "COUNT(DISTINCT xcart_cidev_filters.f_id) = '$count_selected_filters'";
	}


        if (!empty($search_data['products']['filter_prices']) && is_array($search_data['products']['filter_prices'])) {
		$filter_pricing_arr = array();
		foreach ($search_data['products']['filter_prices'] as $k => $v){
			if ($v["selected"]){
	                	$filter_pricing_arr[] = "($sql_tbl[pricing].price >= '".$v["min_price"]."' AND $sql_tbl[pricing].price <= '".$v["max_price"]."')";
			}
		}

		if (!empty($filter_pricing_arr)){
			$filter_pricing_imploded = implode(" OR ", $filter_pricing_arr); 
			$where[] = "(". $filter_pricing_imploded . ")";

                	# If price limitation is enabled, dont show configurable products (configurable product has zero price always)
	//		$where[] = "$sql_tbl[products].product_type != 'C'";
		}
	}
}
###################################################################################
### Search_Filter ###
###################################################################################


	#
	# Generate search query
	#
	foreach ($inner_joins as $j) {
		if (!empty($j['fields']) && is_array($j['fields']))
			$fields = func_array_merge($fields, $j['fields']);
	}
	foreach ($left_joins as $j) {
		if (!empty($j['fields']) && is_array($j['fields']))
			$fields = func_array_merge($fields, $j['fields']);
	}

	$fields_count[] = 'COUNT(*)';

#
## Search_Filter
###
        if ($current_area == "C") {
		$fields_count[] = "$sql_tbl[products].productid";

        }
###
##
# 
	$search_query = "SELECT ".implode(", ", $fields)." FROM ";
	$search_query_count = "SELECT ".implode(", ", $fields_count)." FROM ";
	$search_query_brandids = "SELECT DISTINCT $sql_tbl[products].brandid FROM ";

#
## Search_Filter
###
//	$search_query_fv_ids = "SELECT DISTINCT $sql_tbl[cidev_filter_values].fv_id, $sql_tbl[cidev_filters].f_id FROM ";
###
##
#	
	if (!empty($from_tbls)) {
		foreach ($from_tbls as $k => $v) {
			$from_tbls[$k] = $sql_tbl[$v];
		}
		$search_query .= implode(", ", $from_tbls).", ";
		$search_query_count .= implode(", ", $from_tbls).", ";
		$search_query_brandids .= implode(", ", $from_tbls).", ";

#
## Search_Filter
###
//	        $search_query_fv_ids .= implode(", ", $from_tbls).", ";
###
##
#       

	}
	$search_query .= $sql_tbl['products'];
	$search_query_count .= $sql_tbl['products'];
	$search_query_brandids .= $sql_tbl['products'];

#
## Search_Filter
###
//        $search_query_fv_ids .= $sql_tbl['products'];
###
##
#       


	$joins = array();
	$joins_count = array();
    foreach ($left_joins as $ljname => $lj) {
		if (!$lj['only_select'])
			$joins_count[$ljname] = $lj;
        $joins[$ljname] = $lj;
    }
	foreach ($inner_joins as $ijname => $ij) {
		$ij['is_inner'] = true;
		if (!$ij['only_select'])
			$joins_count[$ijname] = $ij;
		$joins[$ijname] = $ij;
	}

	$search_query .= func_generate_joins($joins);
	$search_query_count .= func_generate_joins($joins_count);
	$search_query_brandids .= func_generate_joins($joins_count);

#
## Search_Filter
###
//        $search_query_fv_ids .= func_generate_joins($joins_count);
###
##
# 


	if (in_array($current_area, array('A', 'P'))) {
		
		$search_query_productids = '';
		
		if (!empty($from_tbls)) {
			$search_query_productids .= implode(', ', $from_tbls) . ', ';
		}
		$bulk_search_query = $search_query_productids . $sql_tbl['products'] . func_generate_joins($joins_count) . func_generate_joins($left_joins_bulk) . ' WHERE ' . implode(' AND ', $where); 
		$search_query_productids .= $sql_tbl['products'] . func_generate_joins($joins_count) . ' WHERE ' . implode(' AND ', $where);
		if (!empty($groupbys)) {
			$search_query_productids .= ' GROUP BY ' . implode(', ', $groupbys);
			$bulk_search_query .= ' GROUP BY ' . implode(', ', $groupbys);
		}
		if (!empty($having)) {
			$search_query_productids .= ' HAVING ' . implode(' AND ', $having);
			$bulk_search_query .= ' HAVING ' . implode(' AND ', $having);
		}

        $bulk_search_query_ids = $bulk_search_query;

		if (!empty($orderbys)) {
			$search_query_productids .= ' ORDER BY ' . implode(', ', $orderbys);
			$bulk_search_query .= ' ORDER BY ' . implode(', ', $orderbys);
		}

		$bulk_fields = "$sql_tbl[products].*, $sql_tbl[pricing].price, $sql_tbl[categories].categoryid, $sql_tbl[categories].categoryid_path, $sql_tbl[manufacturers].manufacturer";
		
		$bulk_search_query_ids = 'SELECT ' . $sql_tbl['products'] .'.productid FROM '.  $bulk_search_query_ids;
		$bulk_search_query = 'SELECT ' . $bulk_fields . ' FROM '.  $bulk_search_query;
		$search_query_productids = 'SELECT ' . $sql_tbl['products'] . '.productid FROM ' . $search_query_productids;
	}

	if (in_array($current_area, array('A', 'P')) && empty($data['empty_discount_slope']) && !empty($data['outdated_discount_table'])) {
		ini_set('memory_limit', '128M');
	
		$ids = array_flip(func_query_column($search_query_productids));

		if (is_array($ids) && !empty($ids)) {
	
			// Get discount data & generate wholesale prices
	
			$discount_data_sql = db_query('SELECT productid, discount_slope, discount_table FROM ' . $sql_tbl['products'] . ' WHERE productid IN ("' . implode('","', array_keys($ids)).'")');
			if ($discount_data_sql) {
				while($p = db_fetch_array($discount_data_sql)) {
					$productid = $p['productid'];
					$prs = func_query_hash('SELECT price, quantity FROM ' . $sql_tbl['pricing'] . ' WHERE productid=' . $productid . ' AND membershipid = "" AND variantid=0', 'quantity', false, true);
					$equal = true;

					if (!empty($p['discount_table'])) {
						$quantities = explode(',',$p['discount_table']);
					} else {
						$quantities = array();
					}
					sort($quantities);

					$base = $prs[1];
					unset($prs[1]);
					$prs_quantities = array_keys($prs);
					sort($prs_quantities);

					if (count($prs_quantities) != count($quantities) || $quantities != $prs_quantities) {
						$equal = false;
					} else {
						$wholesale_prices = array();
						foreach ($quantities as $v) {
							if (intval($v)) {
								$wholesale_prices[$v] = price_format((1 - $p['discount_slope'] * log($v,2) / 100) * $base);
								if ($wholesale_prices[$v] != $prs[$v]) {
									$equal = false;
									break;
								}
							}
						}
					}
					if ($equal) {
						unset($ids[$productid]);
					}
				}
			}

			if (!empty($ids)) {
				$where[] = $sql_tbl['products'] . '.productid IN (' . implode(', ', array_keys($ids)) . ')';
			} else {
				$where = array(0);
			}
		}
	}

	$search_query .= " WHERE ".implode(" AND ", $where);
	$search_query_count .= " WHERE ".implode(" AND ", $where);
	$search_query_brandids .= " WHERE ".implode(" AND ", $where);
	$search_query_brandids .= " AND $sql_tbl[products].brandid > 0 ";

	if (!empty($groupbys)) {
		$search_query .= " GROUP BY ".implode(", ", $groupbys);
		$search_query_count .= " GROUP BY ".implode(", ", $groupbys);
		$search_query_brandids .= " GROUP BY ".implode(", ", $groupbys);
	}
	if (!empty($having)) {
		$search_query .= " HAVING ".implode(" AND ", $having);
		$search_query_count .= " HAVING ".implode(" AND ", $having);
		$search_query_brandids .= " HAVING ".implode(" AND ", $having);
	}
		$search_query_count_NEW = $search_query_count;
	if (!empty($orderbys)) {
		$search_query .= " ORDER BY ".implode(", ", $orderbys);
		$search_query_count .= " ORDER BY ".implode(", ", $orderbys);
	}

	#
	# Calculate the number of rows in the search results
	#

/*	print($search_query."<br><br>");
	print($search_query_count."<br><br>");
	print($search_query_brandids."<br><br>");
*/	
	db_query("SET OPTION SQL_BIG_SELECTS=1");
        $_res = db_query($search_query_count_NEW);

	$total_items = db_num_rows($_res);
        db_free_result($_res);

//print($search_query_count_NEW . "<br /><br />");
//die();

#
## Search_Filter
###
        $search_query_count_NEW =  preg_replace('/ {2,}/',' ',$search_query_count_NEW);
        preg_match('/xcart_products_categories.categoryid IN \((.*?)\)/is', $search_query_count_NEW, $matches);
        $cat_ids_str = $matches[1];

        if (
		$current_area == "C" && $total_items > 0 && !empty($active_modules['CIDEV_Best_Search_Filter']) && 
		(!empty($cat_ids_str) || (empty($cat_ids_str) && !empty($brandid)))
	) {

		$xcart_products_brandid_IN = "";
		$xcart_pricing_price_IN = "";
		$inner_join_productids = "";
		$filter_products_fv_id_IN = "";
		$having_count_distinct_f_id = "";
		$left_join_price = "";

		$all_brandids_in_products_arr = func_query($search_query_brandids);

                if (!empty($all_brandids_in_products_arr)){

			$all_brandids_in_products = array();
			foreach ($all_brandids_in_products_arr as $k => $v){
				$all_brandids_in_products[] = $v["brandid"];
			}

			$imploded_brandids = implode(",", $all_brandids_in_products);
			$filter_found_brands = func_query("SELECT brandid, brand FROM $sql_tbl[brands] WHERE brandid IN ($imploded_brandids) ORDER BY brand");

                        $smarty->assign("filter_found_brands", $filter_found_brands);

			$count_filter_found_brands = count($filter_found_brands);
                }

		if (strpos($search_query_count_NEW, "xcart_products.brandid IN") !== false){
		        preg_match('/xcart_products.brandid IN \((.*?)\)/is', $search_query_count_NEW, $matches);
		        $brand_ids_str = $matches[1];
			$xcart_products_brandid_IN = " AND xcart_products.brandid IN ($brand_ids_str) ";
		}

/*
		if (strpos($search_query_count_NEW, "AND xcart_pricing.price >=") !== false){

                        preg_match("/AND xcart_pricing.price >= \'(.*?)\'/is", $search_query_count_NEW, $matches);
                        $pricing_str1 = $matches[1];

                        preg_match("/AND xcart_pricing.price <= \'(.*?)\'/is", $search_query_count_NEW, $matches);
                        $pricing_str2 = $matches[1];

			$xcart_pricing_price_IN = " AND PR.price >= '$pricing_str1' AND PR.price <= '$pricing_str2'";

			$left_join_price = " LEFT JOIN xcart_pricing PR ON PR.productid = xcart_products.productid and PR.quantity = 1 ";
		}
*/

//func_print_r($filter_prices, $filter_min_price_selected, $filter_max_price_selected);

		if (!empty($filter_prices) && is_array($filter_prices)){
			$xcart_pricing_price_IN_arr = array();
			foreach ($filter_prices as $k_f_p => $v_f_p){
				if ($v_f_p["selected"] == "Y"){
					$xcart_pricing_price_IN_arr[] = "(PR.price >= '$v_f_p[min_price]' AND PR.price <= '$v_f_p[max_price]')";
				}
			}

			if (!empty($xcart_pricing_price_IN_arr)){
				$xcart_pricing_price_IN = " AND (". implode(" OR ", $xcart_pricing_price_IN_arr). ") ";
				$left_join_price = " LEFT JOIN xcart_pricing PR ON PR.productid = xcart_products.productid and PR.quantity = 1 ";
			}
		} 

		if (empty($xcart_pricing_price_IN) && !empty($filter_max_price_selected)){
			$xcart_pricing_price_IN = " AND (PR.price >= '$filter_min_price_selected' AND PR.price <= '$filter_max_price_selected') ";
			$left_join_price = " LEFT JOIN xcart_pricing PR ON PR.productid = xcart_products.productid and PR.quantity = 1 ";
		}

		if (strpos($search_query_count_NEW, "HAVING COUNT(DISTINCT xcart_cidev_filters.f_id)") !== false && strpos($search_query_count_NEW, "xcart_cidev_filter_products.fv_id IN") !== false){
		        preg_match('/xcart_cidev_filter_products.fv_id IN \((.*?)\)/is', $search_query_count_NEW, $matches);
		        $fv_ids_str = $matches[1];
			$filter_products_fv_id_IN = " AND FP.fv_id IN ($fv_ids_str) ";

                        preg_match("/HAVING COUNT\(DISTINCT xcart_cidev_filters.f_id\) = \'(.*?)\'/is", $search_query_count_NEW, $matches);
                        $f_id_count = $matches[1];
			$having_count_distinct_f_id = " HAVING COUNT(DISTINCT F.f_id) = '$f_id_count' ";
		}

//		if (!empty($filter_products_fv_id_IN) || !empty($xcart_products_brandid_IN) || !empty($xcart_pricing_price_IN)){


			if (!empty($cat_ids_str)) {
				$categoryid_IN_arr_str = " AND xcart_products_categories.categoryid IN ($cat_ids_str) ";
			} else {
				$categoryid_IN_arr_str = "";
			}


                        $inner_join_productids = "
			INNER JOIN (
		 	SELECT 
				xcart_products.productid 
			FROM 
				xcart_products  
				LEFT JOIN xcart_cidev_filter_products FP ON FP.productid = xcart_products.productid  
				LEFT JOIN xcart_cidev_filter_values FV ON FV.fv_id = FP.fv_id  
				LEFT JOIN xcart_cidev_filters F ON F.f_id = FV.f_id  
				INNER JOIN xcart_products_sf ON xcart_products.productid=xcart_products_sf.productid AND xcart_products_sf.sfid='$current_storefront'
				INNER JOIN xcart_products_categories ON xcart_products_categories.productid = xcart_products.productid  AND (xcart_products_categories.main='Y' OR xcart_products_categories.main!='Y')  
				INNER JOIN xcart_categories ON xcart_products_categories.categoryid = xcart_categories.categoryid and xcart_categories.storefrontid = '$current_storefront'
				$left_join_price 
			WHERE  
				xcart_categories.avail = 'Y'
				$categoryid_IN_arr_str  
				AND xcart_products.forsale = 'Y' 
				$filter_products_fv_id_IN
				$xcart_products_brandid_IN
				$xcart_pricing_price_IN
			GROUP BY xcart_products.productid
			$having_count_distinct_f_id
			) as SQ ON SQ.productid = xcart_products.productid";
//		}

                $igor_query_filter_count_search = "
		SELECT 
			FP.fv_id, COUNT(distinct xcart_products.productid) as count  
		FROM 
			xcart_products  
		LEFT JOIN xcart_cidev_filter_products FP ON FP.productid = xcart_products.productid  
		LEFT JOIN xcart_cidev_filter_values FV ON FV.fv_id = FP.fv_id  
		LEFT JOIN xcart_cidev_filters F ON F.f_id = FV.f_id  
		$inner_join_productids
		GROUP BY FP.fv_id";

//print($igor_query_filter_count_search);

/*

                $igor_query_filter_count_search = "
                SELECT 
                        FP.fv_id, COUNT(distinct xcart_products.productid) as count  
                FROM 
                        xcart_products  
                LEFT JOIN xcart_cidev_filter_products FP ON FP.productid = xcart_products.productid  
                LEFT JOIN xcart_cidev_filter_values FV ON FV.fv_id = FP.fv_id  
                LEFT JOIN xcart_cidev_filters F ON F.f_id = FV.f_id  
                INNER JOIN xcart_products_sf ON xcart_products.productid=xcart_products_sf.productid AND xcart_products_sf.sfid = '$current_storefront'  
                INNER JOIN xcart_products_categories ON xcart_products_categories.productid = xcart_products.productid  
                INNER JOIN xcart_categories ON xcart_products_categories.categoryid = xcart_categories.categoryid  
                $inner_join_productids
                WHERE  
                        xcart_categories.avail = 'Y'  
                        AND xcart_products_categories.categoryid IN ($cat_ids_str)  
                        AND (xcart_products_categories.main='Y' OR xcart_products_categories.main!='Y'  )
                        AND xcart_products.forsale = 'Y' 
                GROUP BY FP.fv_id";
*/

//print($igor_query_filter_count_search);
//die();
		$igor_query_filter_count_search_result = func_query($igor_query_filter_count_search);
		if (!empty($igor_query_filter_count_search_result)){
			unset($filter_found_fv_ids_count);
			foreach ($igor_query_filter_count_search_result as $k => $v){
				$filter_found_fv_ids_count[$v["fv_id"]] = $v["count"];
				$filter_found_fv_ids[$k] = $v["fv_id"];
			}

                        $smarty->assign("filter_found_fv_ids_count", $filter_found_fv_ids_count);
                        $smarty->assign("filter_found_fv_ids", $filter_found_fv_ids);
		}
	}
###
##
#



#
##
###
	if ($current_area == "C" && $new_featured_functionality == "Y" && $page > 1){
        	$total_items += 12;
	}
###
##
#

	if ($total_items > 0) {

		$page = $search_data["products"]["page"];

		#
		# Prepare the page navigation
		#
		if (isset($objects_per_page)) {
			$objects_per_page = intval($objects_per_page);
			if ($objects_per_page <= 0)
				unset($objects_per_page);

		}

		if (!isset($objects_per_page)) {
			if ($current_area == "C" || $current_area == "B")
				$objects_per_page = intval($config["Appearance"]["products_per_page"]);
			else
				$objects_per_page = intval($config["Appearance"]["products_per_page_admin"]);

			if ($objects_per_page <= 0)
				$objects_per_page = 10;
		}

		$total_nav_pages = ceil($total_items/$objects_per_page)+1;

        if ($source != 'XML_Sitemap') {

		include $xcart_dir."/include/navigation.php";

		#
		# Perform the SQL query and getting the search results
		#

		if (!empty($data["is_modify"])) {
			#
			# Get the products and go to modify them
			#
			$res = db_query($search_query);
			if ($res) {
				$geid = false;
				$productid = false;
				x_load("product");
				while ($pid = db_fetch_row($res)) {
					if (empty($productid))
						$productid = $pid[0];
					$geid = func_ge_add($pid[0], $geid);
				}
				func_header_location("product_modify.php?productid=$productid&geid=".$geid);
			}

		}
		elseif ($data["is_export"] == "Y" || $export == 'export_found') {

			x_load("export");
			# Save the SQL query and go to export them
			func_export_range_save("PRODUCTS", $search_query);
			$top_message['content'] = func_get_langvar_by_name("lbl_export_products_add");
			$top_message['type'] = 'I';
			func_header_location("import.php?mode=export");

		}
		else {
			if ($search_gen_discounts) {
				$products = func_query_hash($search_query,"productid",false);
				$i = 1;
				foreach ($products as $productid => $p) {
					if (!empty($p["discount_table"]) && floatval($p["discount_slope"]) > 0) {
						db_query("DELETE FROM $sql_tbl[pricing] WHERE productid ='$productid' AND membershipid = '' AND quantity > 1 AND variantid = '0'");
						foreach (explode(",",$p["discount_table"]) as $v) {
							if(intval($v)) {
								$price = (1 - $p["discount_slope"] * log($v,2) / 100) * $p["price"];
								if ($price > 0) {
									$query_data = array(
										"productid" => $productid,
										"quantity" => intval($v),
										"price" => $price>0 ? $price : 0,
										"membershipid" => ''
									);
									func_array2insert("pricing", $query_data);
								}
							}
						}
					}
					if ($i % 5 == 0) {
						func_flush("o ");
					}
					$i++;
				}
				$top_message["content"] = func_get_langvar_by_name("msg_adm_discounts_gen");
				$top_message["type"] = "I";

				if ($filter_mode == "search"){
					func_header_location("cidev_admin_add_filter_to_products.php?mode=search&page=1".$fast_search_parameter);
				} else {
					func_header_location("search.php?mode=search&page=1$fast_search_parameter");
				}
			}


#
##
###
if ($current_area == "C" && $first_page >= 12 && $new_featured_functionality == "Y" && $page > 1){
        $first_page -= 12;
}
###
##
#


			$search_query .= " LIMIT $first_page, $objects_per_page";
			$products = func_query($search_query);
		}

		# Clear service arrays
		unset($fields, $fields_count, $from_tbls, $inner_joins, $left_joins, $where, $groupbys, $having, $orderbys);
		if (!empty($products) && $current_area != 'C') {
			foreach($products as $k => $v) {
				$add_cats = func_query_column('SELECT categoryid FROM ' . $sql_tbl['products_categories'] . ' WHERE productid="' . $v['productid'] . '" AND main<>"Y"');
				if (is_array($add_cats) && !empty($add_cats)) {
					$products[$k]['add_cats'] = implode(',', $add_cats);
				}
				$products[$k]['main_cat'] = func_query_first_cell('SELECT categoryid FROM ' . $sql_tbl['products_categories'] . ' WHERE productid="' . $v['productid'] . '" AND main="Y"');
			}
		}

		if (!empty($products) && $current_area == "C") {
			x_session_register("cart");

			# Get tax rates cache
			$ids = array();
			foreach ($products as $v) {
				if ($v['is_taxes'] == 'Y')
					$ids[] = $v;
			}

			$_taxes = array();
			if (!empty($ids)) {
				x_load("taxes");
				$_taxes = func_get_product_tax_rates($products, $login);
			}
			unset($ids);

			if (!empty($active_modules['Extra_Fields'])) {

				# Get Extra fields cache
				$ids = array();
				foreach ($products as $k => $v) {
					$ids[] = intval($v['productid']);
				}

				$products_ef = func_query_hash("SELECT $sql_tbl[extra_fields].*, $sql_tbl[extra_field_values].*, IF($sql_tbl[extra_fields_lng].field != '', $sql_tbl[extra_fields_lng].field, $sql_tbl[extra_fields].field) as field FROM $sql_tbl[extra_field_values], $sql_tbl[extra_fields] LEFT JOIN $sql_tbl[extra_fields_lng] ON $sql_tbl[extra_fields].fieldid = $sql_tbl[extra_fields_lng].fieldid AND $sql_tbl[extra_fields_lng].code = '$shop_language' WHERE $sql_tbl[extra_fields].fieldid = $sql_tbl[extra_field_values].fieldid AND $sql_tbl[extra_field_values].productid IN (".implode(",", $ids).") AND $sql_tbl[extra_fields].active = 'Y' ORDER BY $sql_tbl[extra_fields].orderby", "productid");
				unset($ids);
			}

			if (!empty($active_modules['Product_Options'])) {

				# Get Product options markups cache
				$ids = array();
				foreach ($products as $v) {
					if (!empty($v['is_product_options']))
						$ids[$v['productid']] = doubleval($v['price']);
				}

				$options_markups = array();
				if (!empty($ids))
					$options_markups = func_get_default_options_markup_list($ids);
				unset($ids);
			}

			# Get thumbnails dimensions
			$ids = array();
			foreach ($products as $k => $v)
				$ids[] = $v['productid'];
			$thumb_dims = func_query_hash("SELECT id, image_x, image_y FROM $sql_tbl[images_T] WHERE id IN ('".implode("','", $ids)."')", "id", false);
			unset($ids);

			$manufacturerids_in_found_products = array();

			foreach ($products as $k => $v) {
				if (empty($v['descr'])) {
					$products[$k]['descr'] = func_get_product_descr($v['fulldescr']);
				}
				
				if (trim(strtoupper(substr($products[$k]['descr'], 0, 10))) == 'FEATURES:.') {
					$products[$k]['descr'] = trim(substr_replace($products[$k]['descr'], '', 0, 10));
				}
				if (!empty($active_modules['Feature_Comparison']) && $v['fclassid']) {
					$products_has_fclasses = true;
				}

				$products[$k]['taxed_price'] = $v['taxed_price'] = $v['price'];

				if (!empty($active_modules['Product_Options']) && !empty($v['is_product_options']) && !empty($options_markups[$v['productid']])) {

					# Add product options markup
					$products[$k]['price'] += $options_markups[$v['productid']];
					$products[$k]['taxed_price'] = $products[$k]['price'];
					$v = $products[$k];
				}

				$in_cart = 0;
				if (!empty($cart['products']) && is_array($cart['products'])) {

					# Modify product's quantity based the cart data
					foreach ($cart['products'] as $cv) {
						if ($cv['productid'] == $v['productid'] && intval($v['variantid']) == intval($cv['variantid']))
							$in_cart += $cv['amount'];
					}

					$products[$k]['in_cart'] = $in_cart;
					$products[$k]['avail'] -= $in_cart;
					if ($products[$k]['avail'] < 0) {
						$products[$k]['avail'] = 0;
					}
				}

				if (!empty($active_modules['Extra_Fields']) && isset($products_ef[$v['productid']])) {

					# Get extra fields data
					$products[$k]['extra_fields'] = $products_ef[$v['productid']];
				}

				# Get thumbnail URL
				$products[$k]["tmbn_url"] = false;
				if (!is_null($v['image_path_T'])) {
					$products[$k]['is_image_T'] = true;
					if (!empty($v['image_path_T'])) {
						x_load("files");
//						$products[$k]["tmbn_url"] = func_get_image_url($v['productid'], "T", $v['image_path_T']);
						$products[$k]["tmbn_url"] = func_get_image_url($v['productid'], "T");
					}

					if (isset($thumb_dims[$v['productid']])) {
						$products[$k] = func_array_merge($products[$k], $thumb_dims[$v['productid']]);
						unset($thumb_dims[$v['productid']]);

						$config['Appearance']['thumbnail_width'] = intval($config['Appearance']['thumbnail_width']);
						$products[$k]['tmbn_x'] = $products[$k]['image_x'];
						$products[$k]['tmbn_y'] = $products[$k]['image_y'];
						if ($config['Appearance']['thumbnail_width'] > 0) {
							$products[$k]['tmbn_x'] = intval($config['Appearance']['thumbnail_width']);
							if (!empty($products[$k]['image_x']) && !empty($products[$k]['image_y']))
								$products[$k]['tmbn_y'] = round($config['Appearance']['thumbnail_width']/$products[$k]['image_x']*$products[$k]['image_y'], 0);
						}
					}

				} 
//				else {
				if (empty($products[$k]["tmbn_url"])) {
					$products[$k]["tmbn_url"] = func_get_default_image("T");
				}

				unset($products[$k]['image_path_T']);

				# Calculate product taxes
				if (!empty($active_modules["Special_Offers"]) && !empty($search_data["products"]["show_special_prices"])) {
					include $xcart_dir."/modules/Special_Offers/search_results_calculate.php";
				}
				elseif ($v['is_taxes'] == 'Y' && isset($_taxes[$v['productid']])) {
					$products[$k]["taxes"] = func_get_product_taxes($products[$k], $login, false, $_taxes[$v['productid']]);
				}

				if ($products[$k]['descr'] == strip_tags($products[$k]['descr']))
					$products[$k]['descr'] = str_replace("\n", "<br />", $products[$k]['descr']);
				if ($products[$k]['fulldescr'] == strip_tags($products[$k]['fulldescr']))
					$products[$k]['fulldescr'] = str_replace("\n", "<br />", $products[$k]['fulldescr']);


#
##
###
###
			        if ($v["new_map_price"]>0){

			                if ($v["new_map_price"] > $products[$k]["price"]){
			                        $products[$k]["price"] = $v["new_map_price"];
                        			$products[$k]['taxed_price'] = $products[$k]['price'];
			                }

			                $products[$k]["discount_avail"] = "N";
			                $products[$k]["discount_slope"] = "";
			                $products[$k]["discount_table"] = "";
			        }
###
##
#


#
## https://basecamp.com/2070980/projects/1577907-x-cart/messages/13257251-internal-sf-tasks
###
				$cidev_warning_code = 0;

				if ($v["list_price"] > 0){
				        if (($v["price"]/$v["list_price"]) < 0.1){
				                $cidev_warning_code = "101";
				        }
				}

				if ($v["cost_to_us"] > $v["price"]){
				        $cidev_warning_code = "102";
				}

				if ($cidev_warning_code > 0){
					if ($v["warning_code"] != $cidev_warning_code){
					        db_query("UPDATE $sql_tbl[products] SET warning_code='$cidev_warning_code' WHERE productid='$v[productid]'");
						$products[$k]["warning_code"] = $cidev_warning_code;
					}
					$products[$k]["avail"] = 0;
				}
###
##
#
				$manufacturerids_in_found_products[] = $v["manufacturerid"];

			}

			if (!empty($active_modules["Special_Offers"]) && empty($search_data["products"]["show_special_prices"])) {
				func_offers_check_products($login, $current_area, $products);
			}


#
##
###

			$manufacturerids_in_found_products = array_unique($manufacturerids_in_found_products);
			$manufacturerids_in_found_products = array_values($manufacturerids_in_found_products);

			$manufacturers_in_found_products = func_query_hash("SELECT manufacturerid, allow_pre_orders, reverse_sku, remove_dashes, lead_time_message FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('".implode("','", $manufacturerids_in_found_products)."')", 'manufacturerid', false);
			

                        foreach ($products as $k => $v) {

				$products[$k]["allow_pre_orders"] = $manufacturers_in_found_products[$v["manufacturerid"]]["allow_pre_orders"];	

                                if ($manufacturers_in_found_products[$v["manufacturerid"]]["remove_dashes"] == "Y"){
                                        $products[$k]["productcode"] = str_replace("-", ".", $products[$k]["productcode"]);
                                }

                                if ($manufacturers_in_found_products[$v["manufacturerid"]]["reverse_sku"] == "Y"){

                                        $cidev_strlen = strlen($products[$k]["productcode"]) - 1;

                                        $new_sku = "";
                                        for($i=0;$i<strlen($products[$k]["productcode"]);$i++){
                                                $new_sku .= substr($products[$k]["productcode"],$cidev_strlen,1);
                                                $cidev_strlen--;
                                        }
                                        $products[$k]["productcode"] = $new_sku;
                                }

###
                               if (!empty($v["eta_date_mm_dd_yyyy"])){
                                       if ($products[$k]["eta_date_mm_dd_yyyy"] > time()){
                                               $products[$k]["eta_date_in_future"] = "Y";
 
                                               if ($current_area == 'C' && $manufacturers_in_found_products[$v["manufacturerid"]]["allow_pre_orders"] != "Y"){
                                                       $products[$k]["avail"] = "0";
                                               }
                                       }
                               }
###

				if (empty($v["lead_time_message"])){
					$products[$k]["lead_time_message"] = $manufacturers_in_found_products[$v["manufacturerid"]]["lead_time_message"];
				}


#
## Calculate correct price
###
				$products[$k]["product_availability"] = func_product_availability(false,false,false,false,false,$products[$k]);
				$products[$k]["supplier_feeds_enabled"] = func_query_first_cell("SELECT enabled FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_file_name='$v[provider]' AND feed_type = 'I'");
				$products[$k]["price"] = $products[$k]["taxed_price"] = func_product_price($products[$k]);

				if ($products[$k]["supplier_feeds_enabled"] == "Y" && empty($v["is_variants"]) && $products[$k]["product_availability"] == "out of stock"){
					$new_notify_in_stock_price = $products[$k]["price"];
					$products[$k]["new_notify_in_stock_price"] = $new_notify_in_stock_price;
				}
###
##
#

#
## Clean URLs
###
				if ($index_sku_search == "Y"){
					$tmp_absolute_path = false;
				} else {
					$tmp_absolute_path = true;
				}

				$clean_url = func_clean_url_get("P", $v["productid"], $tmp_absolute_path);

				if ($index_sku_search == "Y"){
					$clean_url = "http://".$v["domain"]."/".$clean_url;

                                        if (strpos($v["tmbn_url"], "cdn") !== false && strpos($v["tmbn_url"], "http") === false){
	                                        $products[$k]["tmbn_url"] = "http://".$v["tmbn_url"];
                                        }
				}

				if (substr($clean_url, -1) != "/"){
					$clean_url .= "/";
				}
				$products[$k]["clean_url"] = $clean_url;
###
##
#
                        }

		}

#
##
###
if ( (!empty($active_modules['CIDEV_Best_Search_Filter']) && $current_area == 'C') || ($current_area != 'C') ) {

		if (is_array($products) && !empty($products)) {
			foreach ($products as $k=>$v) {
		                $products[$k]["cidev_filter_products"] = func_query("SELECT $sql_tbl[cidev_filter_products].fv_id, $sql_tbl[cidev_filter_values].fv_name, $sql_tbl[cidev_filter_values].f_id FROM $sql_tbl[cidev_filter_products] LEFT JOIN $sql_tbl[cidev_filter_values] ON $sql_tbl[cidev_filter_values].fv_id=$sql_tbl[cidev_filter_products].fv_id WHERE productid='$v[productid]' ORDER BY $sql_tbl[cidev_filter_values].fv_order_by, $sql_tbl[cidev_filter_values].fv_name");

				$products[$k]["prevent_search_indexing"] = func_prevent_search_indexing($v);
			}
		}
}
###
##
#


//func_print_r($products);

		if (isset($products_ef))
			unset($products_ef);

		if (isset($options_markups))
			unset($options_markups);

		if (!$_inner_search) {
			# Assign the Smarty variables
# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
# START: random:18591_18598 [2009 Jul 29 10:36] 
#			$smarty->assign("navigation_script","search.php?mode=search".(!empty($search_args_str)?$search_args_str:''));
			if ($search_all_website) {
				$smarty->assign('navigation_script', $xcart_web_dir . "/index.php?mode=search&substring=$sku");
			} else {
				$smarty->assign("navigation_script","search.php?mode=search");
			}
# END: random:18591_18598 [2009 Jul 29 10:36] 

			$smarty->assign("products", $products);
			$smarty->assign("first_item", $first_page+1);
			$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
			$smarty->assign('froogle_titles', ((isset($data['froogle_titles']) && $data['froogle_titles'] == 'Y') ? 'Y' : 'N' ));

			if (!empty($active_modules['Feature_Comparison']))
				$smarty->assign("products_has_fclasses", $products_has_fclasses);
		}
	}
    }

	if (!$_inner_search) {
		$smarty->assign("total_items",$total_items);
		$smarty->assign("mode", $mode);

	if ($flag_save)
		x_session_save("search_data");
	}
}

if ($source != 'XML_Sitemap') {

    if (!isset($search_data["products"]['substring']) && $current_area == 'C') {
	$search_data["products"]['price_min'] = preg_replace("/-.*$/", "", $config['Search_products']['search_products_price_d']);
	$search_data["products"]['price_max'] = preg_replace("/^.*-/", "", $config['Search_products']['search_products_price_d']);
	$search_data["products"]['weight_min'] = preg_replace("/-.*$/", "", $config['Search_products']['search_products_weight_d']);
	$search_data["products"]['weight_max'] = preg_replace("/^.*-/", "", $config['Search_products']['search_products_weight_d']);
	$search_data["products"]['categoryid'] = '';
    }

    if (!empty($active_modules['Feature_Comparison']) && $current_area != 'C' && $current_area != 'P' && !$_inner_search) {
	$fclasses = func_query("SELECT $sql_tbl[feature_classes].*, IFNULL($sql_tbl[feature_classes_lng].class, $sql_tbl[feature_classes].class) as class FROM $sql_tbl[feature_classes] LEFT JOIN $sql_tbl[feature_classes_lng] ON $sql_tbl[feature_classes].fclassid = $sql_tbl[feature_classes_lng].fclassid AND $sql_tbl[feature_classes_lng].code = '$shop_language' WHERE $sql_tbl[feature_classes].avail = 'Y' ORDER BY $sql_tbl[feature_classes].orderby");
	if(!empty($fclasses)) {
		$smarty->assign("fclasses", $fclasses);
	}
    }

    if ($current_area != "C") {
       if ($search_data["products"]["productcode"]) {
               $search_data["products"]["extra_sku"] = array();
               $search_data["products"]["extra_sku"][] = $search_data["products"]["productcode"];
               unset($search_data["products"]["productcode"]);
       } elseif (empty($search_data["products"]["extra_sku"])) {
               $search_data["products"]["extra_sku"] = array("");
       }
    }


    if (!$_inner_search) {
	$smarty->assign("search_prefilled", $search_data["products"]);
    }

    # START: random:18298_18304_18324 [2009 Jun 08 09:50] 
    if (!empty($active_modules['Multiple_Storefronts']) && $current_area == 'C' && !$search_all_website) {
	$sf_b_join = " LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid= $sql_tbl[brands].brandid";
	$sf_b_condition = 'AND ' . $sql_tbl['brands_sf'] . '.sfid = ' . $current_storefront;
    } else {
	$sf_b_join = '';
	$sf_b_condition = '';
    }

    if ($active_modules['Brands'] && !(!empty($products) && $mode == 'search') && !$_inner_search) {
	if ($current_area == "C") {
		$brands = func_query("SELECT $sql_tbl[brands].*, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand, IFNULL($sql_tbl[brands_lng].descr, $sql_tbl[brands].descr) as descr FROM $sql_tbl[brands] USE INDEX (avail) $sf_b_join LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language' WHERE avail = 'Y' $sf_b_condition ORDER BY orderby, brand");
	}
	else {
        $brands = func_query("SELECT b.*, IFNULL(l.brand, b.brand) as brand  FROM $sql_tbl[brands] AS b"
            . " LEFT JOIN $sql_tbl[brands_lng] AS l ON b.brandid = l.brandid AND l.code = '$shop_language'"
            . " WHERE b.avail = 'Y' ORDER BY b.orderby, b.brand");
	}

	if ($brands) {
		array_unshift($brands, array("brandid" => '0', "brand" => func_get_langvar_by_name("lbl_no_brand")));
		$tmp = explode("\n", $config['Search_products']['search_products_brands_d']);
		foreach ($brands as $k => $v) {
			if (@in_array($v['brandid'], (array)$search_data["products"]['brands']) || (in_array($v['brandid'], $tmp) && $current_area == 'C'))
				$brands[$k]['selected'] = 'Y';
		}

#
##
###
                if (!empty($search_data["products"]['brands']) && is_array($search_data["products"]['brands']) && !empty($brands) && is_array($brands) && $current_area != "C"){

                        $first_list_brands = array();
                        $count_brands = count($brands);
                        $brands_counter = 0 - $count_brands;

                        foreach ($brands as $k => $v){
                                if ($v["selected"] == "Y"){
                                        $first_list_brands[$brands_counter] = $v;
                                        $brands_counter++;
                                        unset($brands[$k]);
                                }
                        }

                        if (!empty($first_list_brands)){
                                $new_brands_list = array_merge($first_list_brands, $brands);
                                $brands = array_values($new_brands_list);

                        }
                }
###
##
#


		if ($brands)
			$smarty->assign("brands", $brands);
	}
    }

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 

    if ($active_modules['Manufacturers'] && !(!empty($products) && $mode == 'search') && !$_inner_search) {
	if ($current_area == "C") {
		$manufacturers = func_query("SELECT $sql_tbl[manufacturers].*, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer, IFNULL($sql_tbl[manufacturers_lng].descr, $sql_tbl[manufacturers].descr) as descr FROM $sql_tbl[manufacturers] USE INDEX (avail) LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' WHERE avail = 'Y' ORDER BY orderby, manufacturer");
	}
	else {
		$manufacturers = func_query("SELECT * FROM $sql_tbl[manufacturers] WHERE avail = 'Y' ORDER BY orderby, manufacturer");
	}

	if ($manufacturers) {
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
		if ($login_type == 'P' and !empty($login)) {
			$selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
			if (!empty($selected_manufacturers)) {
				$selected_manufacturers = unserialize($selected_manufacturers);
				foreach ($manufacturers as $k=>$v) {
					if (!in_array($v['manufacturerid'], $selected_manufacturers)) {
						unset($manufacturers[$k]);
					}   

				}
			} else {
				$manufacturers = array();
			}
		}

# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
		array_unshift($manufacturers, array("manufacturerid" => '0', "manufacturer" => func_get_langvar_by_name("lbl_no_manufacturer")));
		$tmp = explode("\n", $config['Search_products']['search_products_manufacturers_d']);
		foreach ($manufacturers as $k => $v) {
			if (@in_array($v['manufacturerid'], (array)$search_data["products"]['manufacturers']) || (in_array($v['manufacturerid'], $tmp) && $current_area == 'C'))
				$manufacturers[$k]['selected'] = 'Y';
		}

#
##
###
		if (!empty($search_data["products"]['manufacturers']) && is_array($search_data["products"]['manufacturers']) && !empty($manufacturers) && is_array($manufacturers) && $current_area != "C"){

			$first_list_manufacturers = array();
			$count_manufacturers = count($manufacturers);
			$manufacturers_counter = 0 - $count_manufacturers;

			foreach ($manufacturers as $k => $v){
				if ($v["selected"] == "Y"){
					$first_list_manufacturers[$manufacturers_counter] = $v;
					$manufacturers_counter++;
					unset($manufacturers[$k]);
				}
			}

			if (!empty($first_list_manufacturers)){
				$new_manufacturers_list = array_merge($first_list_manufacturers, $manufacturers);
				$manufacturers = array_values($new_manufacturers_list);

			}
		}
###
##
#

		if ($manufacturers)
			$smarty->assign("manufacturers", $manufacturers);
	}
    }

    if ($data['froogle_titles'] == 'Y' && $current_area != 'C') {
    $replacements = func_query('SELECT `what`, `by` FROM ' . $sql_tbl['replacements']);
    if (!empty($replacements)) {
	    $smarty->assign('replacements', $replacements);
    }

    $smarty->assign('froogle_titles', $data['froogle_titles']);
    }

    if ($active_modules['Extra_Fields'] && !(!empty($products) && $mode == 'search') && !$_inner_search) {
	$extra_fields = func_query("SELECT $sql_tbl[extra_fields].*, IF($sql_tbl[extra_fields_lng].field != '', $sql_tbl[extra_fields_lng].field, $sql_tbl[extra_fields].field) as field FROM $sql_tbl[extra_fields] LEFT JOIN $sql_tbl[extra_fields_lng] ON $sql_tbl[extra_fields].fieldid = $sql_tbl[extra_fields_lng].fieldid AND $sql_tbl[extra_fields_lng].code = '$shop_language' WHERE active = 'Y' ORDER BY field");
	if ($extra_fields) {
		$tmp = explode("\n", $config['Search_products']['search_products_extra_fields']);
		foreach ($extra_fields as $k => $v) {
			if (!in_array($v['fieldid'], $tmp) && $current_area == 'C') {
				unset($extra_fields[$k]);
				continue;
			}

			if ($search_data["products"]['extra_fields'][$v['fieldid']])
				$extra_fields[$k]['selected'] = 'Y';
		}

		if ($extra_fields)
			$smarty->assign("extra_fields", $extra_fields);
	}
    }

    if (!$_inner_search) {
	if ($current_area != 'C')
		include $xcart_dir."/include/categories.php";

	$search_categories = $smarty->get_template_vars("allcategories");
	if ($current_area == "C" && !empty($active_modules["Fancy_Categories"])) {
		if (!function_exists("func_categories_sort_abc")) {
			function func_categories_sort_abc($a, $b) {
				return strcmp($a["category_path"], $b["category_path"]);
			}
		}

		usort($search_categories, "func_categories_sort_abc");
	}

	$smarty->assign("search_categories", $search_categories);
	unset($search_categories);

	$smarty->assign("sort_fields", $sort_fields);
	$smarty->assign("main","search");
}

if ($current_area == 'A') {
    $providers = func_query('SELECT login, firstname, lastname FROM ' . $sql_tbl['customers'] . ' WHERE usertype = "P" ORDER BY login');
    if (!empty($providers)) {

	if (!empty($search_data["products"]["providers"]) && is_array($search_data["products"]["providers"])){
		foreach ($providers as $k => $v){
			foreach ($search_data["products"]["providers"] as $kk => $vv){
				if ($v["login"] == $vv){
					$providers[$k]["selected"] = "Y";
				}
			}
		}


                $first_list_providers = array();
                $count_providers = count($providers);
                $providers_counter = 0 - $count_providers;

                foreach ($providers as $k => $v){
                                if ($v["selected"] == "Y"){
                                        $first_list_providers[$providers_counter] = $v;
                                        $providers_counter++;
                                        unset($providers[$k]);
                                }
                }

                if (!empty($first_list_providers)){
                                $new_providers_list = array_merge($first_list_providers, $providers);
                                $providers = array_values($new_providers_list);

                }
	}

        $smarty->assign('providers', $providers);
    }
}

    if ($fast_search == 'Y' && $total_items == 1 && isset($products) && 
		trim($products[0]['productcode']) == trim($search_data['products']['extra_sku'][0]) && 
		isset($search_data['products']['extra_sku'])) {
		
		$productid = $products[0]['productid'];
		$p_sf = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid = '$productid'");
		if ($p_sf != $current_storefront) {
		
			$current_storefront = $p_sf;
			
		}
		
		func_header_location('product_modify.php?productid=' . $products[0]['productid'] . '&switch_sf=true');
    }
}

?>
