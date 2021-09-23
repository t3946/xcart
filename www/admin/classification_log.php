<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Classification log", "");

x_session_register("search_data", []);

if ($mode == "search") {

        if (!empty($page) && $search_data["pc_runs_log"]["page"] != intval($page)) {
                # Store the current page number in the session
                $search_data["pc_runs_log"]["page"] = $page;
//              $flag_save = true;
        } else {
                $search_data["pc_runs_log"]["page"] = 1;
        }

//      if ($flag_save)
                x_session_save("search_data");

        $data['_objects_per_page'] = $config["Appearance"]["users_per_page_admin"];
//      $data['_objects_per_page'] = "3";

	$where = " WHERE storefrontid='".$current_storefront_info["storefrontid"]."'";
//        $time_30 = time() - 60*60*24*30;
//        $where = " AND datetime > $time_30";

        $total_items = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[pc_runs_log] $where");

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["pc_runs_log"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }


        $pc_runs_logs = func_query("SELECT * FROM $sql_tbl[pc_runs_log] $where ORDER BY id DESC".$sort_string);

        if (!empty($pc_runs_logs) && is_array($pc_runs_logs)){

		foreach ($pc_runs_logs as $k => $v){

			$duration = $v["date_time_end"] - $v["date_time_start"];
			$duration /= 60;
			$duration = price_format($duration);
			$pc_runs_logs[$k]["duration"] = $duration;

			$total = $v["products_assigned"] + $v["products_incorrect_assigned"] + $v["products_skipped"] + $v["products_approved"];
			$pc_runs_logs[$k]["total"] = $total;

			$approval_rate = ($v["products_approved"]/$total)*100;
			$approval_rate = ceil($approval_rate);
//			$approval_rate = price_format($approval_rate);
			$pc_runs_logs[$k]["approval_rate"] = $approval_rate;

			$pc_runs_logs[$k]["firstname"] = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$v[login]'");
		}

                # Assign the Smarty variables
                $smarty->assign("navigation_script", "classification_log.php?mode=search");
                $smarty->assign("pc_runs_logs", $pc_runs_logs);
                $smarty->assign("first_item", $first_page+1);
                $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
        }

        $smarty->assign("total_items", $total_items);
        $smarty->assign("mode", $mode);
}

//func_print_r($pc_runs_logs);


$smarty->assign("main", "categorization_log");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
