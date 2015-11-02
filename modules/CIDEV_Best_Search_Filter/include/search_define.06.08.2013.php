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
	    elseif ($current_area == 'A' || $current_area == 'P'){

//func_print_r($search_data);
//die("search_define.php");

		if (!empty($search_data['products']['filter_name_id']) && is_array($search_data['products']['filter_name_id']) && !empty($search_data['products']['filter_value_id']) && is_array($search_data['products']['filter_value_id'])){

			if (empty($search_data['products']['sorted_filter_values_id'])){

				$all_filter_name_id = $search_data['products']['filter_name_id'];
				$all_filter_name_id = array_unique($all_filter_name_id);
				$all_filter_name_id = array_values($all_filter_name_id);
	
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
				}
			}else {
				$sorted_filter_values_id = $search_data['products']['sorted_filter_values_id'];
			}

			if (!empty($sorted_filter_values_id)){

        	                $left_joins['cidev_filter_products'] = array(
                	            'on' => "$sql_tbl[cidev_filter_products].productid = $sql_tbl[products].productid"
	                        );

	                        $left_joins['cidev_filter_values'] = array(
        	                    'on' => "$sql_tbl[cidev_filter_values].fv_id = $sql_tbl[cidev_filter_products].fv_id"
                	        );

				$fv_id_where_condition = array();
				foreach ($sorted_filter_values_id as $f_id => $fv_ids){
					if (!empty($fv_ids) && is_array($fv_ids)){
						$fv_id_where_condition[$f_id] = "$sql_tbl[cidev_filter_products].fv_id IN (" . implode(",", $fv_ids) . ")";
					}
				}

				$where[] = " ( " . implode(" OR ", $fv_id_where_condition) . " ) ";
			}

//func_print_r($search_data, $left_joins, $fields, $where);
//die();

		}
	}
    }
}
?>
