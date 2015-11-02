<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$location[] = array("Order dashboard", "orders.php?page_name=dashboard");
$location[] = array("Inquiries", "");

if ($REQUEST_METHOD == 'POST'){

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";

	if ($mode == "update" && !empty($post_data) && is_array($post_data)){
		foreach ($post_data as $inq_id => $v){
			if (!empty($v["add_inq_tag_id"]) && $v["add_inq_tag_id"] > 0) {
				db_query("INSERT INTO $sql_tbl[inquirires_tags] (inq_id, inq_tag_id) VALUES ('$inq_id', '$v[add_inq_tag_id]')");
			}

			if (!empty($v["close"]) && $v["close"] == "Y"){
				db_query("UPDATE $sql_tbl[inquiries] SET status='C' WHERE inq_id='$inq_id'");
			}
		}
	}

	if ($mode == "delete" && !empty($del_inq_id__inq_tag_id)){

		$del_inq_id__inq_tag_id_arr = explode("_", $del_inq_id__inq_tag_id);

		if (!empty($del_inq_id__inq_tag_id_arr[0]) && !empty($del_inq_id__inq_tag_id_arr[1])){
			db_query("DELETE FROM $sql_tbl[inquirires_tags] WHERE inq_id='$del_inq_id__inq_tag_id_arr[0]' AND inq_tag_id='$del_inq_id__inq_tag_id_arr[1]'");
		}
	}

/*
	if (!empty($inq_type_id)){
		$search_data["inquiries"]["inq_type_id"] = $inq_type_id;
	}

	if (!empty($inq_tag_id)){
		$search_data["inquiries"]["inq_tag_id"] = $inq_tag_id;
	}
*/


	if (!empty($page)){
		$search_data["inquiries"]["page"] = $page;
		$page = "&page=".$page;
	} else {
		$page = "";
	}

	x_session_save("search_data");

	func_header_location("inquiries.php?".(!empty($inq_type_id)?"inq_type_id=$inq_type_id":"").(!empty($inq_tag_id)?"inq_tag_id=$inq_tag_id":"").$page);
}


if (!empty($page)){
	if ($search_data["inquiries"]["page"] != intval($page)) {
		# Store the current page number in the session
		$search_data["inquiries"]["page"] = $page;
	}
} else {
	$search_data["inquiries"]["page"] = 1;
}

x_session_save("search_data");

$data['_objects_per_page'] = "20";

if (!empty($inq_type_id)){
	$total_items = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[inquiries] LEFT JOIN $sql_tbl[inquiry_types] ON $sql_tbl[inquiry_types].inq_type_id=$sql_tbl[inquiries].inq_type_id WHERE $sql_tbl[inquiries].inq_type_id='$inq_type_id' AND $sql_tbl[inquiries].status='O'");

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["inquiries"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }

	$inquiries = func_query("SELECT $sql_tbl[inquiries].*, $sql_tbl[inquiry_types].inquiry_type FROM $sql_tbl[inquiries] LEFT JOIN $sql_tbl[inquiry_types] ON $sql_tbl[inquiry_types].inq_type_id=$sql_tbl[inquiries].inq_type_id WHERE $sql_tbl[inquiries].inq_type_id='$inq_type_id' AND $sql_tbl[inquiries].status='O' ORDER BY $sql_tbl[inquiries].status DESC, datetime DESC".$sort_string);

	$smarty->assign("inq_type_id", $inq_type_id);
	$smarty->assign("navigation_script", "inquiries.php?inq_type_id=$inq_type_id");
}
elseif (!empty($inq_tag_id)){

	$total_items_query = "SELECT $sql_tbl[inquiries].*, $sql_tbl[inquiry_types].inquiry_type FROM $sql_tbl[inquiries] LEFT JOIN $sql_tbl[inquiry_types] ON $sql_tbl[inquiry_types].inq_type_id=$sql_tbl[inquiries].inq_type_id LEFT JOIN $sql_tbl[inquirires_tags] ON $sql_tbl[inquirires_tags].inq_id=$sql_tbl[inquiries].inq_id WHERE $sql_tbl[inquirires_tags].inq_tag_id='$inq_tag_id' GROUP BY $sql_tbl[inquiries].inq_id";

        db_query("SET OPTION SQL_BIG_SELECTS=1");
        $_res = db_query($total_items_query);
        $total_items = db_num_rows($_res);
        db_free_result($_res);

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["inquiries"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }

        $inquiries = func_query("SELECT $sql_tbl[inquiries].*, $sql_tbl[inquiry_types].inquiry_type FROM $sql_tbl[inquiries] LEFT JOIN $sql_tbl[inquiry_types] ON $sql_tbl[inquiry_types].inq_type_id=$sql_tbl[inquiries].inq_type_id LEFT JOIN $sql_tbl[inquirires_tags] ON $sql_tbl[inquirires_tags].inq_id=$sql_tbl[inquiries].inq_id WHERE $sql_tbl[inquirires_tags].inq_tag_id='$inq_tag_id' GROUP BY $sql_tbl[inquiries].inq_id ORDER BY $sql_tbl[inquiries].status DESC, datetime DESC".$sort_string);
	
	$smarty->assign("inq_tag_id", $inq_tag_id);
	$smarty->assign("navigation_script", "inquiries.php?inq_tag_id=$inq_tag_id");
}

if (!empty($inquiries)){

	if (!empty($inq_type_id)){
		$page_name = $inquiries[0]["inquiry_type"];
	}
	elseif (!empty($inq_tag_id)){
		$page_name = func_query_first_cell("SELECT inquiry_attn_tag FROM $sql_tbl[inquiries_attention_tags] WHERE inq_tag_id='$inq_tag_id'");
	}

	foreach ($inquiries as $k => $v){
		$inquiries[$k]["inquiries_attention_tags"] = func_query("SELECT $sql_tbl[inquiries_attention_tags].* FROM $sql_tbl[inquiries_attention_tags] LEFT JOIN $sql_tbl[inquirires_tags] ON $sql_tbl[inquirires_tags].inq_tag_id=$sql_tbl[inquiries_attention_tags].inq_tag_id WHERE $sql_tbl[inquirires_tags].inq_id='$v[inq_id]' ORDER BY $sql_tbl[inquiries_attention_tags].inquiry_attn_tag");
		$inquiries[$k]["inq_subject"] = str_replace("\r\n", "<br />", $v["inq_subject"]);
	}

//func_print_r($inquiries);

	$smarty->assign("inquiries", $inquiries);
	$smarty->assign("page_name", $page_name);
}

$smarty->assign("first_item", $first_page+1);
$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
$smarty->assign("total_items", $total_items);

if (!empty($search_data["inquiries"])){
        $smarty->assign("search_data", $search_data["inquiries"]);
}

$inquiries_attention_tags = func_query("SELECT * FROM $sql_tbl[inquiries_attention_tags] WHERE active='Y' ORDER BY inquiry_attn_tag");
$smarty->assign("inquiries_attention_tags", $inquiries_attention_tags);

$smarty->assign("main", "inquiries");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
