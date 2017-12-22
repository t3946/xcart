<?php
if ($mode == 'Update_Fraud_check' && $REQUEST_METHOD == 'POST'){

	db_query("UPDATE $sql_tbl[config] SET value='$fraud_domains_free_email_provider' WHERE name='fraud_domains_free_email_provider'");
	db_query("UPDATE $sql_tbl[config] SET value='".price_format($Overall_FC_threshold_for_Clear_status)."' WHERE name='Overall_FC_threshold_for_Clear_status'");
	db_query("UPDATE $sql_tbl[config] SET value='$Threshold_status' WHERE name='Threshold_status'");
	db_query("UPDATE $sql_tbl[config] SET value='$below_threshold_status' WHERE name='below_threshold_status'");
	db_query("UPDATE $sql_tbl[config] SET value='$fraud_Google_address_search_exclusions' WHERE name='fraud_Google_address_search_exclusions'");
	db_query("UPDATE $sql_tbl[config] SET value='$fraud_Google_phone_search_exclusions' WHERE name='fraud_Google_phone_search_exclusions'");
	db_query("UPDATE $sql_tbl[config] SET value='$fraud_Google_email_search_exclusions' WHERE name='fraud_Google_email_search_exclusions'");
//	db_query("UPDATE $sql_tbl[config] SET value='$fraud_Google_search_negative_words' WHERE name='fraud_Google_search_negative_words'");

	db_query("DELETE FROM $sql_tbl[fraud_check]");

        if (!empty($fraud_checks) && is_array($fraud_checks)){
                foreach ($fraud_checks as $k => $v){
                        db_query("INSERT INTO $sql_tbl[fraud_check] (question_code, auto, importance_factor, orderby, question_template_body) VALUES ('".strtoupper($v["question_code"])."', '$v[auto]', '$v[importance_factor]', '$v[orderby]', '$v[question_template_body]')");
                }
        }

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";

	func_header_location("configuration.php?option=Fraud_check");
}

$fraud_checks = func_query("SELECT * FROM $sql_tbl[fraud_check] ORDER BY orderby");
if (empty($fraud_checks)){
	$fraud_checks[0]["id"] = "0";
}
$smarty->assign("fraud_checks", $fraud_checks);

$row_max_index = count($fraud_checks);
$smarty->assign("row_max_index", $row_max_index);

?>
