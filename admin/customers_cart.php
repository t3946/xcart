<?php
require "./auth.php";
require $xcart_dir."/include/security.php";
x_load("order");

$location[] = array("Customer's cart", "");

if (empty($cart_number)){
	func_close_window();
//	func_header_location("orders.php?page_name=dashboard");
}

$cart = "";

$customer_info = func_query_first("SELECT * FROM $sql_tbl[customers] WHERE cart_number='$cart_number'");
if (!empty($customer_info)){

	$customer_info["s_statename"] = func_get_state($customer_info["s_state"], $customer_info["s_country"]);
	$customer_info["b_statename"] = func_get_state($customer_info["b_state"], $customer_info["b_country"]);
	$customer_info["s_countryname"] = func_get_country($customer_info["s_country"]);
	$customer_info["b_countryname"] = func_get_country($customer_info["b_country"]);

	func_other_customer_orders($customer_info["email"]);

	$cart_number_info = unserialize(stripslashes($customer_info["cart"]));

	if (!empty($cart_number_info["products"])){
		$cart = $cart_number_info;
	}
}

if (empty($cart)){
	$data_in_sessions_data = func_query_first_cell("SELECT data FROM $sql_tbl[sessions_data] WHERE cart_number='$cart_number'");
	$data_in_sessions_data =  unserialize(stripslashes($data_in_sessions_data));
	$cart = $data_in_sessions_data["cart"];
}

if (empty($cart)){
        func_close_window();
//	func_header_location("cart.php?cart_number=$cart_number");
}

if (!empty($cart["paymentid"])){
        $cart["payment_method"] = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$cart[paymentid]'");
}

$smarty->assign("cart", $cart);
$smarty->assign("products", $cart["products"]);
$smarty->assign("userinfo", $customer_info);

if ($qqq == "qqq"){
	func_print_r($cart, $cart_number_info, $data_in_sessions_data);
}

$smarty->assign("main", "customers_cart");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
