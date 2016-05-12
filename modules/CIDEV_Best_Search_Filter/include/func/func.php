<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../../"); die("Access denied"); }

function func_cidev_filters_tree($active_only = false) 
{
	global $sql_tbl, $current_storefront;

	$query_filters = "SELECT * FROM $sql_tbl[cidev_filters] USE INDEX (f_aon) WHERE storefrontid='$current_storefront'"
		. ($active_only ? " AND f_active = 'Y'" : "")
		. " ORDER BY f_order_by, f_name";

	$cidev_filters = func_query($query_filters);

	if (!empty($cidev_filters) && is_array($cidev_filters)){

		foreach ($cidev_filters as $k => $v){

			$query_filter_values = "SELECT * FROM $sql_tbl[cidev_filter_values] "
				. " WHERE f_id = $v[f_id]"
				. ($active_only ? " AND fv_active = 'Y'" : "")
				. " ORDER BY fv_order_by, fv_name";
var_dump($query_filter_values);
			$cidev_filters[$k]["filter_values"] = func_query($query_filter_values);
		}
	}

	return $cidev_filters; 
}

?>
