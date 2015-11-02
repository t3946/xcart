<?php
require "./auth.php";

if (empty($login))
        func_header_location("error_message.php?antibot_error");

if (!empty($login))
        require $xcart_dir."/include/security.php";

x_load('order');

if ($REQUEST_METHOD == "POST"){

	if ($mode == "update" && !empty($posted_data) && is_array($posted_data)){

		foreach ($posted_data as $k => $v){
			db_query("UPDATE $sql_tbl[order_statuses] SET name='".addslashes($v["name"])."', orderby='".addslashes($v["orderby"])."' WHERE code='$v[code]' AND type='CA'");
		}

                $top_message["content"] = "Updated.";
                $top_message["type"] = "I";
	}
	elseif ($mode == "delete" && !empty($posted_data) && is_array($posted_data)){


		$deleted_flag = false;
                foreach ($posted_data as $k => $v){
			if (!empty($v["to_delete"])) {
	                        db_query("DELETE FROM $sql_tbl[order_statuses] WHERE code='$v[to_delete]' AND type='CA'");
				$deleted_flag = true;
			}
                }

		if ($deleted_flag){
	                $top_message["content"] = "Deleted.";
        	        $top_message["type"] = "I";
		} else {
                        $top_message["content"] = "Nothing to delete.";
                        $top_message["type"] = "I";
		}
	}
	elseif ($mode == "add"){

		if (!empty($code) && !empty($name)){

			$is_code = func_query_first_cell("SELECT code FROM $sql_tbl[order_statuses] WHERE code='$code'");

			if (empty($is_code)){

				$orderby = isset($orderby) ? abs(intval($orderby)) : 0;
				if (empty($orderby)) $orderby = 0;

				db_query("INSERT INTO $sql_tbl[order_statuses] (code, name, type, orderby) VALUES ('".addslashes($code)."', '".addslashes($name)."', 'CA', '$orderby')");

		                $top_message["content"] = "Added.";
		                $top_message["type"] = "I";
			}
			else {
	                        $top_message["content"] = "Please use another code.";
        	                $top_message["type"] = "E";
			}
		}
		else {
	                $top_message["content"] = "Please try again.";
        	        $top_message["type"] = "E";
		}
	}

//	func_header_location("order_statuses.php");
	func_header_location("configuration.php?option=currently_assigned_to_statuses");
}

$order_statuses = func_query("SELECT * FROM $sql_tbl[order_statuses] WHERE type='CA' ORDER BY orderby, code");

//func_print_r($order_statuses);

$smarty->assign("order_statuses", $order_statuses);

/*
$smarty->assign("main","order_status");

$location[] = array("Order status", "");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
*/
?>

