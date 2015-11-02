<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST' && $mode == 'Update_Attention_tags_invoices'){

	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_HST_charged_GT_0' WHERE name='tag_for_HST_charged_GT_0'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Unit_cost_GT_Cost_to_us' WHERE name='tag_for_Unit_cost_GT_Cost_to_us'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Unit_cost_LT_Cost_to_us' WHERE name='tag_for_Unit_cost_LT_Cost_to_us'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Tax_charged_except_HST_GT_0' WHERE name='tag_for_Tax_charged_except_HST_GT_0'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched' WHERE name='tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Shipping_charged_GT_Shipping_quoted_by_distr' WHERE name='tag_for_Shipping_charged_GT_Shipping_quoted_by_distr'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart' WHERE name='tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_Shipping_charged_EQ_0' WHERE name='tag_for_Shipping_charged_EQ_0'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_PROFIT_LT_0' WHERE name='tag_for_PROFIT_LT_0'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_extra_items_on_invoice' WHERE name='tag_for_extra_items_on_invoice'");
	db_query("UPDATE $sql_tbl[config] SET value='$tag_for_items_shipped_to_wrong_address' WHERE name='tag_for_items_shipped_to_wrong_address'");

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Attention_tags_invoices");
}

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] ORDER BY orderby, status");

$smarty->assign("attention_tags_values", $attention_tags_values);

//func_print_r($attention_tags_values);

?>
