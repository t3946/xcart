<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../"); die("Access denied"); }

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {

	if (($mode == 'search') && ($current_area == 'C') && ($search_data['products']['cidev_do_not_use_filter'] != "Y") ) {

                #
                # For filter products
                #
		$cidev_search_query = $search_query_count;
		$cidev_tmp_search_query_arr = explode('FROM', $cidev_search_query);
		$cidev_tmp_strlen = strlen($cidev_tmp_search_query_arr[0]);
		$cidev_search_query = substr_replace($cidev_search_query, "SELECT $sql_tbl[products].productid ", 0, $cidev_tmp_strlen);
		$cidev_tmp_search_query_arr = explode('GROUP BY', $cidev_search_query);
		$cidev_search_query = $cidev_tmp_search_query_arr[0] . " AND xcart_cidev_filter_products.fv_id IS NOT NULL GROUP BY " . $cidev_tmp_search_query_arr[1];

		$cidev_search_query_count = $search_query_count;
		$cidev_tmp_search_query_count_arr = explode('GROUP BY', $cidev_search_query_count);
		$cidev_search_query_count = $cidev_tmp_search_query_count_arr[0] . " AND xcart_cidev_filter_products.fv_id IS NOT NULL GROUP BY " . $cidev_tmp_search_query_count_arr[1];
		$cidev_count_res = db_query($cidev_search_query_count);
		$cidev_total_items = db_num_rows($cidev_count_res);
		db_free_result($cidev_count_res);
		
		$cidev_counter = 0;
		$cidev_count_products_per_time = 300;
		$cidev_productids = array();
		$cidev_all_avail_fv_ids_for_found_products = array();

		$cidev_products = db_query($cidev_search_query);
		while ($cidev_product = db_fetch_array($cidev_products)) {

			$cidev_productids[] = $cidev_product['productid'];

			$cidev_counter++;

			if ($cidev_counter == $cidev_count_products_per_time || $cidev_total_items == $cidev_counter){
				$cidev_counter = 0;

### Search in all categories
//				$cidev_tmp_fpv_query = "SELECT DISTINCT(fv_id) FROM $sql_tbl[cidev_filter_products] USE INDEX (fv_id, pid) WHERE productid IN ('" . implode("','", $cidev_productids) . "')";
###

### Search products for selected categories
				$cidev_tmp_fpv_query = "SELECT DISTINCT($sql_tbl[cidev_filter_products].fv_id) FROM $sql_tbl[cidev_filter_products] USE INDEX (fv_id, pid)
							LEFT JOIN $sql_tbl[products_categories]
								ON $sql_tbl[products_categories].productid = $sql_tbl[cidev_filter_products].productid 
                                                        LEFT JOIN $sql_tbl[cidev_filter_values]
                                                                ON $sql_tbl[cidev_filter_values].fv_id=$sql_tbl[cidev_filter_products].fv_id
							LEFT JOIN $sql_tbl[cidev_filter_categories]
								ON $sql_tbl[cidev_filter_categories].fc_categoryid = $sql_tbl[products_categories].categoryid
							LEFT JOIN $sql_tbl[cidev_filters]
								ON $sql_tbl[cidev_filters].f_id = $sql_tbl[cidev_filter_categories].f_id
							WHERE 
							$sql_tbl[cidev_filter_products].productid IN ('" . implode("','", $cidev_productids) . "')
							AND $sql_tbl[products_categories].main='Y' 
							AND $sql_tbl[cidev_filter_values].f_id = $sql_tbl[cidev_filter_categories].f_id
							";
###

				if (!empty($cidev_all_avail_fv_ids_for_found_products)){
					$cidev_tmp_fpv_query .= " AND $sql_tbl[cidev_filter_products].fv_id NOT IN ('" . implode("','", $cidev_all_avail_fv_ids_for_found_products) . "')";
				}

				$cidev_filter_products_values = func_query($cidev_tmp_fpv_query);

//x_load("debug");
//func_print_r($cidev_filter_products_values);

				if (!empty($cidev_filter_products_values) && is_array($cidev_filter_products_values)){
					foreach ($cidev_filter_products_values as $k => $v){
						$cidev_all_avail_fv_ids_for_found_products[] = $v["fv_id"];
					}
				}

				unset($cidev_productids);
				$cidev_productids = array();		
			}
		}
		db_free_result($cidev_products);

       	        $filter_values_are_found = false;
		$fv_active_found_str_arr = array();
		$cidev_filters_tree = func_cidev_filters_tree(true);

		if (!empty($cidev_all_avail_fv_ids_for_found_products) && (!empty($cidev_filters_tree) && is_array($cidev_filters_tree))){
			foreach ($cidev_filters_tree as $k => $v){
				if (!empty($v['filter_values']) && is_array($v['filter_values'])){
					foreach ($v['filter_values'] as $kk => $vv){
						foreach ($cidev_all_avail_fv_ids_for_found_products as $k_fpv => $v_fpv){
							if ($v_fpv == $vv['fv_id']){
								$fv_active_found_str_arr[] = $vv['fv_id'];
								$cidev_filters_tree[$k]['f_active_found'] = 'Y';
								$cidev_filters_tree[$k]['filter_values'][$kk]['fv_active_found'] = 'Y';
								$filter_values_are_found = true;
							}
						}
					}
				}
			}
		}

		$cidev_selected_fv_str = implode(",", $fv_active_found_str_arr);
		$smarty->assign('cidev_selected_fv_str', $cidev_selected_fv_str);

		if ($filter_values_are_found){
			$cidev_count_filters_in_tree = count($cidev_filters_tree);
			$smarty->assign('cidev_count_filters_in_tree', $cidev_count_filters_in_tree);
			$smarty->assign('cidev_filters_tree', $cidev_filters_tree);
		}


                #
                # For manufacturers
                #
		if ($config['CIDEV_Best_Search_Filter']['cidev_disable_manufacturers'] != 'Y') {

		    if ($search_data['products']['cidev_main_value'] != "manufacturer_products"){

        	        $cidev_search_query = $search_query_count;
	                $cidev_tmp_search_query_arr = explode('FROM', $cidev_search_query);
	                $cidev_tmp_strlen = strlen($cidev_tmp_search_query_arr[0]);
        	        $cidev_search_query = substr_replace($cidev_search_query, "SELECT DISTINCT($sql_tbl[products].manufacturerid) ", 0, $cidev_tmp_strlen);

			$cidev_all_avail_manuf_ids_for_found_products = array();

        	        $cidev_manufacturer_ids = db_query($cidev_search_query);
	                while ($cidev_manufacturer_id = db_fetch_array($cidev_manufacturer_ids)) {
                        	$cidev_all_avail_manuf_ids_for_found_products[] = $cidev_manufacturer_id['manufacturerid'];
                	}
        	        db_free_result($cidev_products);

			$cidev_manufacturer_ids_arr_str = implode("','", $cidev_all_avail_manuf_ids_for_found_products);

                        $cidev_manufacturers = func_query("SELECT $sql_tbl[manufacturers].manufacturerid, $sql_tbl[manufacturers].manufacturer FROM $sql_tbl[manufacturers] WHERE avail = 'Y' AND $sql_tbl[manufacturers].manufacturerid IN ('".$cidev_manufacturer_ids_arr_str."') ORDER BY $sql_tbl[manufacturers].orderby, $sql_tbl[manufacturers].manufacturer");

        	        $manuf_values_are_found = false;
			$manuf_active_found_str_arr = array();

			if (!empty($cidev_all_avail_manuf_ids_for_found_products) && !empty($cidev_manufacturers) && is_array($cidev_manufacturers)){
				foreach ($cidev_manufacturers as $k => $v){
					foreach ($cidev_all_avail_manuf_ids_for_found_products as $kk => $vv){
						if ($v["manufacturerid"] == $vv){
							$manuf_values_are_found = true;
							$cidev_manufacturers[$k]['manuf_active_found'] = 'Y';
							$manuf_active_found_str_arr[] = $v["manufacturerid"];
						}
					}
				}
			}

                        $cidev_selected_manuf_str = implode(",", $manuf_active_found_str_arr);
       	                $smarty->assign('cidev_selected_manuf_str', $cidev_selected_manuf_str);
		
			if ($manuf_values_are_found){
				$smarty->assign('cidev_manufacturers', $cidev_manufacturers);

				$cidev_count_manufacturers_in_menu = count($cidev_manufacturers);
				$smarty->assign('cidev_count_manufacturers_in_menu', $cidev_count_manufacturers_in_menu);

			}
		    }
		}// End for manufacturers
	}
}
?>
