<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if (!empty($membership_code)){
	func_header_location("configuration.php");
}

if ($REQUEST_METHOD == 'POST'){

	if ($mode == "add"){
		db_query("INSERT INTO $sql_tbl[attention_tags_values] (status) VALUES ('')");
	} elseif ($mode == "update"){

		if (!empty($posted_data) && is_array($posted_data)){
			foreach ($posted_data as $k => $v){
				$sTagDescription = $v['description'];
				$up_arr = [
				    'orderby' => $v["orderby"],
				    'status' => $v["status"],
				    'active' => $v["active"],
				    'events' => $v["events"],
				    'color' => $v["color"],
				    'description' => $v["description"],
                ];

				func_array2update($sql_tbl['attention_tags_values'], $up_arr, ['status_id' => $v['status_id']] );

				if (!empty($v["select_login"]) && !empty($v["select_action"])){
					$is_such_str = func_query_first_cell("SELECT id FROM $sql_tbl[attention_tags_values_logins] WHERE login='$v[select_login]' AND action='$v[select_action]' AND status_id='$v[status_id]'");
					if (empty($is_such_str)){
						db_query("INSERT INTO $sql_tbl[attention_tags_values_logins] (login, action, status_id) VALUES ('$v[select_login]', '$v[select_action]', '$v[status_id]')");
					}
				}

				if (!empty($v["delete_operators"]) && is_array($v["delete_operators"])){
					foreach ($v["delete_operators"] as $kk => $vv){
						db_query("DELETE FROM $sql_tbl[attention_tags_values_logins] WHERE id='$kk'");
					}
				}
			}
		}
	}

//func_print_r($posted_data);
//die();

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Attention_tag_options");
}

$allowed_operators = func_query("SELECT login, firstname, membershipid, usertype FROM xcart_customers WHERE usertype!='C' AND activity ='Y' AND status = 'Y' ORDER BY firstname");

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] ORDER BY orderby, status");

if (!empty($attention_tags_values) && is_array($attention_tags_values)){
	foreach ($attention_tags_values as $k => $v){
		$attention_tags_values[$k]["operators"] = func_query("SELECT * FROM $sql_tbl[attention_tags_values_logins] WHERE status_id='$v[status_id]'");
	}
}

$smarty->assign("attention_tags_values", $attention_tags_values);
$smarty->assign("allowed_operators", $allowed_operators);

//func_print_r($attention_tags_values);

?>
