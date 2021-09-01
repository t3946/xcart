<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST' && $mode == "update"){

	db_query("UPDATE $sql_tbl[related_objects_collector] SET collecting_period_backward_months='".trim($collecting_period_backward_months)."', add_to_cart='$add_to_cart', order_submit='$order_submit', search='$search', checkout='$checkout', mobile='$mobile' WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Related_objects_collector_cron");
}

$related_objects_collector = func_query_first("SELECT * FROM $sql_tbl[related_objects_collector] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");

if (empty($related_objects_collector)){
	db_query("INSERT INTO $sql_tbl[related_objects_collector] (storefrontid) VALUES ('".$current_storefront_info["storefrontid"]."')");

	$related_objects_collector = func_query_first("SELECT * FROM $sql_tbl[related_objects_collector] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
}

$smarty->assign("related_objects_collector", $related_objects_collector);
?>
