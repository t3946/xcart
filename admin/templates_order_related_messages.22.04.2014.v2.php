<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$department_arr = array(
			"customer" => "Customer",
			"distributor" => "Distributor",
			"our_customer_service" => "Our customer service"
			);
$department_arr_keys = array_keys($department_arr);

if (!in_array($department, $department_arr_keys) && !empty($department)){
	func_header_location("configuration.php?option=Templates_OrderRelatedMessages");
}

if ($mode == 'update_department' && $REQUEST_METHOD == 'POST'){

	db_query("DELETE FROM $sql_tbl[templates_for_communication] WHERE department='$department'");

	if (!empty($templates_for_communication) && is_array($templates_for_communication)){
		foreach ($templates_for_communication as $k => $v){
			db_query("INSERT INTO $sql_tbl[templates_for_communication] (template_name, pos, subject_line, send_to_email, message_body, department, active, ca_status) VALUES ('$v[template_name]', '$v[pos]', '$v[subject_line]', '$v[send_to_email]', '$v[message_body]', '$department', '$v[active]', '$v[ca_status]')");
		}
	}

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Templates_OrderRelatedMessages&department=".$department);
}

$ca_statuses = func_query("SELECt * FROM $sql_tbl[order_statuses] WHERE type='CA'");
$smarty->assign('ca_statuses', $ca_statuses);

if (!empty($ca_statuses)){
	$str_ca_statuses_options = "";
	foreach ($ca_statuses as $k => $v){
		$str_ca_statuses_options .= '<option value="'.$v["code"].'">'.$v["name"].'</option>';
	}
	$smarty->assign("str_ca_statuses_options", $str_ca_statuses_options);
}

$templates_for_communication = func_query("SELECT * FROM $sql_tbl[templates_for_communication] WHERE department='$department' ORDER BY pos");

$row_max_index = count($templates_for_communication);

$smarty->assign("row_max_index", $row_max_index);
$smarty->assign("department", $department);
$smarty->assign("department_arr", $department_arr);
$smarty->assign("templates_for_communication", $templates_for_communication);
?>
