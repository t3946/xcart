<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../"); die("Access denied"); }

if (!empty($active_modules['CIDEV_Best_Search_Filter']) && !empty($productid)) {

	$avail_sections[] = 'cidev_filter';

/*
	$cidev_pm_link = "product_modify.php?productid=$productid".$redirect_geid;

	$dialog_tools_data["left"][] = array(
		'link'  => $cidev_pm_link . '&section=cidev_filter', 
		'title' => func_get_langvar_by_name('lbl_cidev_best_search_filter')
	);
*/

//	if ($section == "cidev_filter"){

/*
		if (($REQUEST_METHOD == 'POST') && ($mode == 'cidev_filter_modify')) {
			db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid'");
			if (!empty($posted_filter_values) && is_array($posted_filter_values)){
				foreach ($posted_filter_values as $fv_id => $pf_value){
					if ($fv_id > 0) {
						db_query("INSERT INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
					}
				}
			}
		}
*/

                if (($REQUEST_METHOD == 'POST') && ($mode == 'cidev_filter_delete')) {


			
			if ($geid && !empty($fv_id_fields["del_fv_ids"]) && is_array($fv_id_fields["del_fv_ids"]) && !empty($posted_filter_values) && is_array($posted_filter_values)){

				$all_productid_in_pid = array();
	                        while ($pid = func_ge_each($geid, 1, $productid)) {
					$all_productid_in_pid[] = $pid;
        	                }
				$all_productid_in_pid[] = $productid;	

				foreach ($all_productid_in_pid as $k_pid => $productid)		{
					foreach ($fv_id_fields["del_fv_ids"] as $fv_id => $pf_value){
                                                if ($fv_id > 0 && $posted_filter_values[$fv_id] == $pf_value) {
                                                        db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid' AND fv_id='$fv_id'");
                                                }
					}
				}
//func_print_r($_POST, $all_productid_in_pid);
//die();
			} else {

        	                if (!empty($posted_filter_values) && is_array($posted_filter_values)){
	                                foreach ($posted_filter_values as $fv_id => $pf_value){
                                	        if ($fv_id > 0) {
							db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid' AND fv_id='$fv_id'");
                	                        }
        	                        }
	                        }
			}

			func_refresh("","#section_cidev_filter");
                }

                if (($REQUEST_METHOD == 'POST') && ($mode == 'cidev_filter_add')) {

                        if ($geid && $fv_id_fields["add"] == "Y" && !empty($filter_value_id) && is_array($filter_value_id)){

                                $all_productid_in_pid = array();
                                while ($pid = func_ge_each($geid, 1, $productid)) {
                                        $all_productid_in_pid[] = $pid;
                                }
                                $all_productid_in_pid[] = $productid; 

                                foreach ($all_productid_in_pid as $k_pid => $productid)         {
                                        foreach ($filter_value_id as $k => $fv_id){
                                                if ($fv_id > 0) {
                                                        db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
                                                }
                                        }
                                }
                        } else {

	                        if (!empty($filter_value_id) && is_array($filter_value_id)){
        	                        foreach ($filter_value_id as $k => $fv_id){
                	                        if ($fv_id > 0) {
							db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
                                	        }
	                                }
        	                }
			}

			func_refresh("","#section_cidev_filter");
                }


		$cidev_filters_tree = func_cidev_filters_tree(); 

		if (!empty($cidev_filters_tree)) {
			$cidev_filter_product = func_query("SELECT * FROM $sql_tbl[cidev_filter_products] USE INDEX (pid) WHERE productid='$productid'");
			if (!empty($cidev_filter_product)) {
				$smarty->assign('cidev_filter_product', $cidev_filter_product);
			}

//func_print_r($cidev_filters_tree, $cidev_filter_product);

			$smarty->assign('cidev_filters_tree', $cidev_filters_tree);
		}
//	}
}
?>
