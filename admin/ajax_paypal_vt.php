<?php
require './auth.php';
require '../include/security.php';

x_load("order");

if (empty($orderid)){
	die("Empty orderid");
}

$order_data = func_order_data($orderid);
$order = $order_data["order"];
$customer = $order_data["userinfo"];
$giftcerts = $order_data["giftcerts"];
$products = $order_data['products'];

require '../include/paypal_vt.php';

//func_print_r($_POST);

if ($mode == "authorize" && $AJAX_SUBMIT == "Y"){
	if (!empty($transaction_id)){
		print("Authorized");
	} else {
		print("Faild");
	}
}

exit;
?>
