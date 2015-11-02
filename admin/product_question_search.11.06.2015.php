<?php
@set_time_limit(0);

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$location[] = array("Product question search", "");

if ($REQUEST_METHOD=="POST") {

    if ($mode == "search"){

	$search_data["product_question_search"]["question"] = $question;

###
	if (!empty($search_data["product_question_search"]["status"])){
		unset($search_data["product_question_search"]["status"]);
	}
###

	x_session_save("search_data");

        func_header_location("product_question_search.php?mode=search");
    }
}

if ($mode == "search" && !empty($status) && $from_dashboard == "Y"){
	$search_data["product_question_search"]["status"] = addslashes($status);

	if (isset($search_data["product_question_search"]["question"])){
		unset($search_data["product_question_search"]["question"]);
	}

	x_session_save("search_data");
}

if ($mode == "search"){

        if (!empty($page) && $search_data["product_question_search"]["page"] != intval($page)) {
                # Store the current page number in the session
                $search_data["product_question_search"]["page"] = $page;
        } else {
		if (!empty($page)){
			$search_data["product_question_search"]["page"] = $page;
		}
		else {
	                $search_data["product_question_search"]["page"] = 1;
		}
        }
	x_session_save("search_data");

	$data['_objects_per_page'] = 30;

	$where_arr = array();

//	if (!empty($question)){
	if (!empty($search_data["product_question_search"]["question"])){
//		$where_arr[] = "$sql_tbl[product_question].question LIKE '%$question%'";
		$where_arr[] = "$sql_tbl[product_question].question LIKE '%".$search_data["product_question_search"]["question"]."%'";
	}

	if (!empty($search_data["product_question_search"]["status"])){
		$where_arr[] = "$sql_tbl[product_question].status='".$search_data["product_question_search"]["status"]."'";
	}

	$where = implode(" AND ", $where_arr);

	if (!empty($where)){
		$where = "WHERE ".$where;
	}
	
	$total_items = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[product_question] $where");

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["product_question_search"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }

	$product_questions = func_query("SELECT $sql_tbl[product_question].*, $sql_tbl[products].product, $sql_tbl[products_sf].sfid FROM $sql_tbl[product_question] LEFT JOIN $sql_tbl[products] ON $sql_tbl[products].productid=$sql_tbl[product_question].productid LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[product_question].productid $where ORDER BY date DESC $sort_string");

	if (!empty($product_questions)){
		foreach ($product_questions as $k => $v){

		}
	}

//func_print_r($product_questions);

        # Assign the Smarty variables
        $smarty->assign("product_questions", $product_questions);

        $smarty->assign("navigation_script", "product_question_search.php?mode=search");
        $smarty->assign("total_items", $total_items);
        $smarty->assign("first_item", $first_page+1);
        $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));

}

$smarty->assign("mode", $mode);
$smarty->assign("main", "product_question_search");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
