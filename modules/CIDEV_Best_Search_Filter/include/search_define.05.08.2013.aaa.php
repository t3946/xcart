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

	if ($mode == 'search'){
	    if ($current_area == 'C' && ($search_data['products']['cidev_do_not_use_filter'] != "Y") ) {

		if (!empty($search_data['products']['cidev_selected_filter_values']) && is_array($search_data['products']['cidev_selected_filter_values'])) 
		{
			$where[] = "$sql_tbl[cidev_filter_products].fv_id IN (" . implode(",", array_keys($search_data['products']['cidev_selected_filter_values'])) . ")";

			$smarty->assign('cidev_selected_filter_values', $search_data['products']['cidev_selected_filter_values']);
		}

	        $fields[] = "IF($sql_tbl[cidev_filter_products].fv_id IS NULL,'','Y') as is_filter_products";
	        $left_joins['cidev_filter_products'] = array(
        	    'on' => "$sql_tbl[cidev_filter_products].productid = $sql_tbl[products].productid"
	        );

//                if (array_key_exists('cidev_selected_filter_values', $search_data['products']) && ($search_data["products"]["cidev_main_value"] == "catalog") && !empty($where) && is_array($where)){
                if (!empty($search_data) && is_array($search_data) && array_key_exists('cidev_selected_filter_values', $search_data['products']) && ($search_data["products"]["cidev_main_value"] == "catalog") && !empty($where) && is_array($where)){
			foreach ($where as $k_w => $v_w){
				if ($v_w == "xcart_products_categories.main = 'Y'"){
					unset($where[$k_w]);
					break;
				}
			}
		}

		if ($config['CIDEV_Best_Search_Filter']['cidev_disable_manufacturers'] != 'Y') {

//			if (array_key_exists('cidev_selected_filter_values', $search_data['products'])){
			if (!empty($search_data) && is_array($search_data) && array_key_exists('cidev_selected_filter_values', $search_data['products'])){

				$cidev_new_selected_manuf_values = array();

				if (!empty($search_data['products']['manufacturers']) && is_array($search_data['products']['manufacturers'])){
					foreach ($search_data['products']['manufacturers'] as $cidev_k => $cidev_v){
						$cidev_new_selected_manuf_values[$cidev_v] = "Y";
					}
				}

				$smarty->assign('cidev_selected_manuf_values', $cidev_new_selected_manuf_values);
			}
		}
	    } 
#########################################################################################################
	    elseif ($current_area == 'A' || $current_area == 'P'){



		if (!empty($search_data['products']['filter_name_id']) && is_array($search_data['products']['filter_name_id']) && !empty($search_data['products']['filter_value_id']) && is_array($search_data['products']['filter_value_id'])){


			$all_filter_name_id = $search_data['products']['filter_name_id'];
			$all_filter_name_id = array_unique($all_filter_name_id);
			$all_filter_name_id = array_values($all_filter_name_id);

//			$full_filter_values_id = array();
			$sorted_filter_values_id = array();
			foreach ($search_data['products']['filter_value_id'] as $kid => $fv_id){
	
				$f_id = $search_data['products']['filter_name_id'][$kid];

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
/*
				if (empty($fv_id)){
					$all_fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
                                        if (!empty($all_fv_ids) && is_array($all_fv_ids)){
	                                        foreach ($all_fv_ids as $kkk => $vvv){
							$full_filter_values_id[] = $vvv["fv_id"];
                                                }
                                        }
					unset($search_data['products']['filter_value_id'][$kid]);
				}
				else {
					$full_filter_values_id[] = $fv_id;
				}
*/
			}

func_print_r($sorted_filter_values_id);

			if (!empty($sorted_filter_values_id)){

				$count_filters = count($sorted_filter_values_id);

				foreach ($sorted_filter_values_id as $f_id => $fv_ids){
					$imploded_fv_ids = implode(', ', $fv_ids);
					$f_where_condition_array[$f_id] = " ($sql_tbl[cidev_filters].f_id = $f_id AND $sql_tbl[cidev_filter_values].fv_id IN ($imploded_fv_ids)) ";
				}

				$f_where_condition = implode(" OR ", $f_where_condition_array);
				$full_where_condition = "SELECT productid FROM ( SELECT * FROM xcart_cidev_filters JOIN xcart_cidev_filter_values USING(f_id) JOIN xcart_cidev_filter_products p USING(fv_id) WHERE $f_where_condition GROUP BY productid, f_id ORDER BY f_id ) aaa GROUP BY productid HAVING COUNT(*) = $count_filters";

				$where[] = "$sql_tbl[products].productid IN ($full_where_condition)";
			}

		}
	}
    }
}
?>
