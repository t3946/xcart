<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($mode == 'update_request_availability' && $REQUEST_METHOD == 'POST'){

	db_query("DELETE FROM $sql_tbl[request_availability_options]");

	if (!empty($request_availability_options) && is_array($request_availability_options)){
		foreach ($request_availability_options as $k => $v){
			db_query("INSERT INTO $sql_tbl[request_availability_options] (name, date_mm_dd_yyyy, active) VALUES ('$v[name]', '$v[date_mm_dd_yyyy]', '$v[active]')");
		}
	}

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Request_availability_options");
}

$request_availability_options = func_query("SELECT * FROM $sql_tbl[request_availability_options]");

if (!empty($request_availability_options) && is_array($request_availability_options)){
	foreach ($request_availability_options as $k => $v){
		$tmp_date_mm_dd_yyyy = $v["date_mm_dd_yyyy"];
		if (!empty($tmp_date_mm_dd_yyyy)){
			$tmp_date_mm_dd_yyyy_arr = explode("/", $tmp_date_mm_dd_yyyy);
			$request_availability_options[$k]['time'] = mktime(0, 0, 0, $tmp_date_mm_dd_yyyy_arr[0], $tmp_date_mm_dd_yyyy_arr[1], $tmp_date_mm_dd_yyyy_arr[2]);
		}
	}
	$request_availability_options = my_array_sort($request_availability_options, 'time');
}

$row_max_index = count($request_availability_options);

$smarty->assign("row_max_index", $row_max_index);
$smarty->assign("request_availability_options", $request_availability_options);
?>
