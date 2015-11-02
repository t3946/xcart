<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST'){

	if ($mode == "update" && !empty($post_data) && is_array($post_data)){

		foreach ($post_data as $id => $v){

			if ($v["delete"] == "Y"){
				db_query("DELETE FROM $sql_tbl[order_page_permissions] WHERE id='$id'");
			}
			elseif (!empty($v["element_id"])) {
				$membership_ids = "";
				if (!empty($v["membershipid"]) && is_array($v["membershipid"])){
					$membershipid_arr = array_keys($v["membershipid"]);
					$membership_ids = implode(",", $membershipid_arr);
				}

				db_query("UPDATE $sql_tbl[order_page_permissions] SET element_id='".$v["element_id"]."', membership_ids='$membership_ids' WHERE id='$id'");
			}
		}
	}

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";

	if ($mode == "add"){

		$count_add_element_id = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_page_permissions] WHERE element_id='$add_element_id'");

		if (!empty($add_element_id) && empty($count_add_element_id)){

			$membership_ids = "";
			if (!empty($add_membershipid) && is_array($add_membershipid)){
				$add_membershipid_new = array_keys($add_membershipid);
				$membership_ids = implode(",", $add_membershipid_new);
			}

			db_query("INSERT INTO $sql_tbl[order_page_permissions] (element_id, membership_ids) VALUES ('$add_element_id', '$membership_ids')");
		}
		else {
		        $top_message["content"] = 'Not added.';
		        $top_message["type"] = "E";
		}
	}

	func_header_location("configuration.php?option=Order_page_permissions");
}

$all_memberships = func_query("SELECT * FROM $sql_tbl[memberships] WHERE area='A' OR area='P' ORDER BY area, membership");

$order_page_permissions = func_query("SELECT * FROM $sql_tbl[order_page_permissions] ORDER BY element_id");
if (!empty($order_page_permissions)){
	foreach ($order_page_permissions as $k => $v){
		$order_page_permissions[$k]["membership_ids_arr"] = explode(",", $v["membership_ids"]);
	}
}

$smarty->assign("order_page_permissions", $order_page_permissions);
$smarty->assign("all_memberships", $all_memberships);
?>
