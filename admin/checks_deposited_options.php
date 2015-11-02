<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST' && $mode == "update"){

	db_query("UPDATE $sql_tbl[config] SET value='$Checks_deposited_Attention_tag' WHERE name='Checks_deposited_Attention_tag'");

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Checks_deposited_options");
}

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] ORDER BY orderby, status");
$smarty->assign("attention_tags_values", $attention_tags_values);
?>
