<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST' && $mode == "update"){

	if ($maximum_number_of_autoclassify_product_per_turn < 1) $maximum_number_of_autoclassify_product_per_turn = 1;
	if ($minimum_number_of_autoclassify_product_per_turn < 1) $minimum_number_of_autoclassify_product_per_turn = 1;

	db_query("UPDATE $sql_tbl[pc_options] SET maximum_number_of_autoclassify_product_per_turn='$maximum_number_of_autoclassify_product_per_turn', minimum_number_of_autoclassify_product_per_turn='$minimum_number_of_autoclassify_product_per_turn', stop_words='$stop_words', excluded_char_sequences='$excluded_char_sequences', recalc_if_approval_rate='$recalc_if_approval_rate', amount_of_products_for_autoclassify_queue='$amount_of_products_for_autoclassify_queue' WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
	func_header_location("configuration.php?option=Product_classification");
}

$pc_options = func_query_first("SELECT * FROM $sql_tbl[pc_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");

if (empty($pc_options)){
	db_query("INSERT INTO $sql_tbl[pc_options] (storefrontid, maximum_number_of_autoclassify_product_per_turn, minimum_number_of_autoclassify_product_per_turn, stop_words, excluded_char_sequences) VALUES ('".$current_storefront_info["storefrontid"]."', '50', '3', '- with for not as by this when x you your the a on and feature will would can to in must do or nor if of me is', '+#13+ +#10+')");

	$pc_options = func_query_first("SELECT * FROM $sql_tbl[pc_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
}

$smarty->assign("pc_options", $pc_options);
?>
