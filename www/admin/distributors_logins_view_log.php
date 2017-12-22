<?php
@set_time_limit(0);

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data", []);

$location[] = array("Distributors logins view log", "");

/*
if ($REQUEST_METHOD=="POST") {

    if ($mode == "search"){

//	$search_data["distributors_logins_view_log"]["variable"] = "value";

	x_session_save("search_data");

        func_header_location("distributors_logins_view_log.php?mode=search");
    }
}
*/

if ($mode == "search"){

        if (!empty($page) && $search_data["distributors_logins_view_log"]["page"] != intval($page)) {
                # Store the current page number in the session
                $search_data["distributors_logins_view_log"]["page"] = $page;
//              $flag_save = true;
        } else {
                $search_data["distributors_logins_view_log"]["page"] = 1;
        }

//      if ($flag_save)
                x_session_save("search_data");

//      $data['_objects_per_page'] = $config["Appearance"]["users_per_page_admin"];
        $data['_objects_per_page'] = "100";

//        $total_items = func_query_first_cell($q="SELECT COUNT(*) FROM xcart_cidev_manufacturers_pass_view_log L left join xcart_manufacturers M ON M.manufacturerid = L.manufacturerid Group By L.login, L.manufacturerid");

	$query = "Select L.login, MAX(L.date) as date, M.manufacturer, M.manufacturerid  
From xcart_cidev_manufacturers_pass_view_log L
        left join xcart_manufacturers M ON M.manufacturerid = L.manufacturerid
Group By L.login, L.manufacturerid";

	$res = db_query($query);
	if ($res) {
		$total_items = db_num_rows($res);
		db_free_result($res);
	}
	else {
		$total_items = 0;
	}

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["distributors_logins_view_log"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }

        $distributors_logins_view_log = func_query($query . " Order By  L.login, MAX(L.date) desc " . $sort_string);

        if (!empty($distributors_logins_view_log) && is_array($distributors_logins_view_log)){

		foreach($distributors_logins_view_log as $k => $v){
			$distributors_logins_view_log[$k]["usertype"] = func_query_first_cell("SELECT usertype FROM $sql_tbl[customers] WHERE login='$v[login]'");
		}

                # Assign the Smarty variables
                $smarty->assign("navigation_script", "distributors_logins_view_log.php?mode=search");
                $smarty->assign("distributors_logins_view_log", $distributors_logins_view_log);
                $smarty->assign("first_item", $first_page+1);
                $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
	}

        $smarty->assign("total_items", $total_items);
        $smarty->assign("mode", $mode);
}


if (!empty($search_data["distributors_logins_view_log"])){
	$smarty->assign("search_data", $search_data["distributors_logins_view_log"]);
}

$smarty->assign("mode", $mode);
$smarty->assign("main", "distributors_logins_view_log");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
