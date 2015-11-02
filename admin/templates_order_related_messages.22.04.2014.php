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
			db_query("INSERT INTO $sql_tbl[templates_for_communication] (template_name, pos, subject_line, send_to_email, message_body, department, active) VALUES ('$v[template_name]', '$v[pos]', '$v[subject_line]', '$v[send_to_email]', '$v[message_body]', '$department', '$v[active]')");
		}
	}

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Templates_OrderRelatedMessages&department=".$department);
}

$templates_for_communication = func_query("SELECT * FROM $sql_tbl[templates_for_communication] WHERE department='$department' ORDER BY pos");

$row_max_index = count($templates_for_communication);

$smarty->assign("row_max_index", $row_max_index);
$smarty->assign("department", $department);
$smarty->assign("department_arr", $department_arr);
$smarty->assign("templates_for_communication", $templates_for_communication);
?>
