<?php

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data", []);

if (empty($search_data) || !is_array($search_data)) {
    $search_data = [];
}

$location[] = array("Backprocess logs", "");

$where_90 = time() - 60*60*24*30;
$total_items_90_days = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[backprocess_logs] WHERE date < $where_90");

if ($total_items_90_days > 0){
	db_query("DELETE FROM $sql_tbl[backprocess_logs] WHERE date < $where_90");
}

if ($REQUEST_METHOD == "POST") {

	if ($mode == "search"){

	    if (!empty($posted_data["start_date"])){
		$start_date_arr = explode("/", $posted_data["start_date"]);
		$start_date = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
		$search_data["backprocess_logs"]["start_date"] = $start_date;
		$search_data["backprocess_logs"]["start_date_str"] = $posted_data["start_date"];
	    }

	    if (!empty($posted_data["end_date"])){
		$end_date_arr = explode("/", $posted_data["end_date"]);
		$end_date = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
		$search_data["backprocess_logs"]["end_date"] = $end_date;
		$search_data["backprocess_logs"]["end_date_str"] = $posted_data["end_date"];
	    }

	    if (!empty($posted_data["process_id"]) && is_array($posted_data["process_id"])){
			$search_data["backprocess_logs"]["process_id"] = $posted_data["process_id"];
	    }

 	    x_session_save("search_data");
	    func_header_location("backprocess_logs.php?mode=search");
	}
}

if ($mode == "search") {

	if (!empty($page) && $search_data["backprocess_logs"]["page"] != intval($page)) {
        	# Store the current page number in the session
	        $search_data["backprocess_logs"]["page"] = $page;
	} else {
		$search_data["backprocess_logs"]["page"] = 1;
	}

       	x_session_save("search_data");

	$data['_objects_per_page'] = "100";

	$where_arr = array();
	if (!empty($search_data["backprocess_logs"]["start_date"])){
		$where_arr[] = "date >= '".$search_data["backprocess_logs"]["start_date"]."'";
	}

        if (!empty($search_data["backprocess_logs"]["end_date"])){
                $where_arr[] = "date <= '".$search_data["backprocess_logs"]["end_date"]."'";
        }

        if (!empty($search_data["backprocess_logs"]["process_id"]) && is_array($search_data["backprocess_logs"]["process_id"])){

		$all_flag_found = false;
		$where2 = array();
		foreach ($search_data["backprocess_logs"]["process_id"] as $k => $v){

			if ($v != "all"){
				$where2[] = "process_id='".$v."'";
			} else {
				$all_flag_found = true;
			}
		}

		if (!$all_flag_found && !empty($where2)){
			$where_arr[] = "(".implode(" OR ", $where2).")";
		}
        }

	$where = "";
	if (!empty($where_arr)){
		$where = "WHERE ".implode(" AND ", $where_arr);
	}

	$total_items = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[backprocess_logs] $where");

	if (!empty($data['_objects_per_page'])) {
	        #
        	# Prepare the page navigation
	        #
        	$page = $search_data["backprocess_logs"]["page"];
	        $objects_per_page = $data['_objects_per_page'];
        	$total_nav_pages = ceil($total_items/$objects_per_page)+1;

	        include $xcart_dir."/include/navigation.php";

        	$sort_string .= " LIMIT $first_page, $objects_per_page";
	}

	$backprocess_logs = func_query("SELECT * FROM $sql_tbl[backprocess_logs] $where ORDER BY id DESC".$sort_string);

	if (!empty($backprocess_logs)){
		foreach ($backprocess_logs as $k => $v){
			$log_text = trim($v["log_text"]);
			$log_text = str_replace("\r\n", "<br />", $log_text);
			$log_text = str_replace("\n", "<br />", $log_text);
			$backprocess_logs[$k]["log_text"] = $log_text;
		}
	}

        $smarty->assign("navigation_script", "backprocess_logs.php?mode=search");
	$smarty->assign("backprocess_logs", $backprocess_logs);
        $smarty->assign("first_item", $first_page+1);
        $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));

        $smarty->assign("total_items", $total_items);
        $smarty->assign("mode", $mode);
}

//func_print_r($backprocess_logs);

$smarty->assign("main","backprocess_logs");

$process_ids = func_query("SELECT DISTINCT process_id FROM $sql_tbl[backprocess_logs]");
if (!empty($process_ids)){
	$smarty->assign("process_ids", $process_ids);
}

if (!empty($search_data["backprocess_logs"])){
	$smarty->assign("search_data", $search_data["backprocess_logs"]);
}

$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
