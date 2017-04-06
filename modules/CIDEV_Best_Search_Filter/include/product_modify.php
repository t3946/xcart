<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../"); die("Access denied"); }

if ( /* !empty($active_modules['CIDEV_Best_Search_Filter']) && */ !empty($productid)) {

	$avail_sections[] = 'cidev_filter';

//		if (($REQUEST_METHOD == 'POST') && ($mode == 'cidev_filter_delete' || $mode == 'cidev_filter_add') && $geid) {
		if ($geid) {
                        $all_productid_in_pid = array();
                        while ($pid = func_ge_each($geid, 1, $productid)) {
        	                $all_productid_in_pid[] = $pid;
                        }
	                $all_productid_in_pid[] = $productid;   
		}

                if (($REQUEST_METHOD == 'POST') && ($mode == 'cidev_filter_delete')) {

		    if (!empty($posted_filter_values) && is_array($posted_filter_values)){
			if ($geid && $fv_id_fields["delete"] == "Y" && !empty($all_productid_in_pid)){
				foreach ($all_productid_in_pid as $k_pid => $all_productid)		{
                                	foreach ($posted_filter_values as $fv_id => $pf_value){
                        	                if ($fv_id > 0) {
                	                                db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$all_productid' AND fv_id='$fv_id'");
        	                                }       
	                                }    
				}
//func_print_r($_POST, $all_productid_in_pid);
//die();
			} else {
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

		    if (!empty($filter_value_id) && is_array($filter_value_id)){
//                        if ($geid && $fv_id_fields["add"] == "Y" && !empty($all_productid_in_pid)){
                        if ($geid && $fv_id_fields["add"] == "Y"){

	                        while ($pid = func_ge_each($geid, 1, $productid)) {
                                        foreach ($filter_value_id as $k => $fv_id){
                                                if ($fv_id > 0) {
                                                        db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$pid', '$fv_id')");
                                                }
                                        }
                	        }

/*
                                foreach ($all_productid_in_pid as $k_pid => $productid)         {
                                        foreach ($filter_value_id as $k => $fv_id){
                                                if ($fv_id > 0) {
                                                        db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
                                                }
                                        }
                                }
*/
                        } 

      	                foreach ($filter_value_id as $k => $fv_id){
               	        	if ($fv_id > 0) {
					db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
                               	}
                        }
		    }
  		    func_refresh("","#section_cidev_filter");
                }


		$cidev_filters_tree = func_cidev_filters_tree(); 

		if (!empty($cidev_filters_tree)) {

                        if ($geid){
				$all_imploded_productids =  implode(",", $all_productid_in_pid);
				$cidev_filter_product = func_query("SELECT DISTINCT fv_id FROM $sql_tbl[cidev_filter_products] WHERE productid IN ($all_imploded_productids)");
                        } else {
				$cidev_filter_product = func_query("SELECT * FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid'");
			}

			if (!empty($cidev_filter_product)) {
				$smarty->assign('cidev_filter_product', $cidev_filter_product);
			}

//func_print_r($cidev_filters_tree, $cidev_filter_product);

			$smarty->assign('cidev_filters_tree', $cidev_filters_tree);
		}
//	}
}
?>
