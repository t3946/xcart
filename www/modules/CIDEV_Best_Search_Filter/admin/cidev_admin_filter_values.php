<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }

$f_id = intval($f_id);
$count_f_id_in_table = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[cidev_filters] WHERE f_id='$f_id' AND storefrontid='$current_storefront'");

if (empty($count_f_id_in_table)){
	 func_header_location("cidev_admin_filters.php");
}

if ($REQUEST_METHOD == 'POST')
 {
    if ($mode == 'add') {

        $query_data = array(
        'f_id'		=> $f_id,
        'fv_name'	=> $fv_name,
        'fv_order_by'	=> $fv_order_by,
        'fv_active'	=> $fv_active,
        );

        func_array2insert('cidev_filter_values', $query_data);

        $top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_value_added');

    } elseif ($mode == 'delete' && !empty($to_delete) && is_array($to_delete)) {

        // Delete selected

        $ids = func_query_column("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE fv_id IN ('" . implode("','", array_keys($to_delete)) . "') ");

        $implodeIds =  ' IN (\'' . implode("','", $ids) . '\')';

        if (!empty($ids)) {

		db_query("DELETE FROM $sql_tbl[cidev_filter_values] WHERE fv_id" . $implodeIds);
		db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE fv_id" . $implodeIds);

    		$top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_value_deleted');
        }

    } elseif ($mode == 'update') {

        // Update list

        if (is_array($records)) {

		foreach ($records as $k => $v) {

	                func_array2update(
				'cidev_filter_values',
				array(
					'fv_name'   => $v['fv_name'],
					'fv_active'     => empty($v['fv_active']) ? 'N' : 'Y',
					'fv_order_by'   => intval($v['fv_order_by']),
				),
				"fv_id = '" . $k . "' "
                	);
		}

		$top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_value_updated');
        }

    }

    func_header_location("cidev_admin_filter_values.php?f_id=".$f_id);
}


$f_name = func_query_first_cell("SELECT f_name FROM $sql_tbl[cidev_filters] WHERE f_id='$f_id'");
$smarty->assign('f_name', $f_name);

$smarty->assign('f_id', $f_id);

$total_items = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");

if ($total_items > 0) {
	$cidev_filter_values = func_query("SELECT * FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id' ORDER BY fv_order_by, fv_name");
	$smarty->assign('cidev_filter_values', $cidev_filter_values);
}

$location[] = array(func_get_langvar_by_name('lbl_cidev_best_search_filter'), 'cidev_admin_filters.php');
$location[] = array(func_get_langvar_by_name('lbl_cidev_filter_name') . ": " . $f_name, '');
$smarty->assign('location', $location);
?>
