<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST' && $mode == "update"){

//	db_query("UPDATE $sql_tbl[otrs_options] SET OTRS_passphrase='$OTRS_passphrase', status_id='$status_id' WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
	db_query("UPDATE $sql_tbl[otrs_options] SET OTRS_passphrase='$OTRS_passphrase', status_id='$status_id'");

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=OTRS_options");
}

//$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options]");

if (empty($otrs_options)){
//	db_query("INSERT INTO $sql_tbl[otrs_options] (storefrontid) VALUES ('".$current_storefront_info["storefrontid"]."')");
	db_query("INSERT INTO $sql_tbl[otrs_options] (OTRS_passphrase) VALUES ('passphrase')");
//	$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
	$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options]");
}

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' ORDER BY orderby, status");

$smarty->assign("attention_tags_values", $attention_tags_values);
$smarty->assign("otrs_options", $otrs_options);
?>
