<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }


//func_print_r($current_storefront);


if ($REQUEST_METHOD == 'POST')
 {
    if ($mode == 'add') {

        $query_data = array(
        'f_name'	=> $f_name,
        'f_order_by'	=> $f_order_by,
        'f_active'	=> $f_active,
        'storefrontid'	=> $current_storefront,
        );

	$f_id = func_array2insert('cidev_filters', $query_data);

        $top_message['content'] = func_get_langvar_by_name('lbl_cidev_filter_added');

    } elseif ($mode == 'delete' && !empty($to_delete) && is_array($to_delete)) {

        // Delete selected

        $ids = func_query_column("SELECT f_id FROM $sql_tbl[cidev_filters] WHERE f_id IN ('" . implode("','", array_keys($to_delete)) . "') ");

        $implodeIds =  ' IN (\'' . implode("','", $ids) . '\')';

        if (!empty($ids) && is_array($ids)) {

		db_query("DELETE FROM $sql_tbl[cidev_filters] WHERE f_id" . $implodeIds);
		
		foreach ($ids as $k => $v){
			$fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$v'");
			if (!empty($fv_ids) && is_array($fv_ids)){
				foreach ($fv_ids as $kk => $vv){
					$fv_id = $vv['fv_id'];
					db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE fv_id='$fv_id'");
				}
			}
		}

		db_query("DELETE FROM $sql_tbl[cidev_filter_values] WHERE f_id" . $implodeIds);

    		$top_message['content'] = func_get_langvar_by_name('lbl_cidev_filter_deleted');
        }

    } elseif ($mode == 'update') {

        // Update list

        if (is_array($records)) {

		foreach ($records as $k => $v) {

	                func_array2update(
				'cidev_filters',
				array(
					'f_name'   => $v['f_name'],
					'f_active'     => empty($v['f_active']) ? 'N' : 'Y',
					'f_order_by'   => intval($v['f_order_by']),
				),
				"f_id = '" . $k . "' "
                	);
		}

		$top_message['content'] = func_get_langvar_by_name('lbl_cidev_filter_updated');
        }

    }

    func_header_location("cidev_admin_filters.php");
}


// Get and display the filters list

$total_items = func_query_first_cell ("SELECT COUNT(*) FROM " . $sql_tbl['cidev_filters'] . " WHERE storefrontid='$current_storefront'");

if ($total_items > 0) {
	$cidev_filters = func_query("SELECT * FROM $sql_tbl[cidev_filters] WHERE storefrontid='$current_storefront' ORDER BY f_order_by, f_name");
	$smarty->assign('cidev_filters', $cidev_filters);
}

$location[] = array(func_get_langvar_by_name('lbl_cidev_best_search_filter'), '');
$smarty->assign('location', $location);
?>
