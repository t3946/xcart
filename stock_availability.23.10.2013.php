<?php
require "./top.inc.php";
$search_all_website = true;
$current_area = 'C';
$page_pos = 500;
require "./init.php";

require "./include/get_language.php";

x_load('crypt', 'mail','order');

$secure_check = text_decrypt($s);
$om = $o.$m;

if ($secure_check == $om && !empty($s) && !empty($o) && !empty($m)){

	$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$o' AND manufacturerid='$m'");


	if ($mode == "sent" && $REQUEST_METHOD == "POST"){

	    if ($current_dc_status == "K"){

                $order_data = func_order_data($o);
                $order = $order_data["order"];
                $products = $order_data["products"];

		if (!empty($actual_shipping_net)){
			db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='".addslashes($actual_shipping_net)."', actual_shipping_gross='".addslashes($actual_shipping_net)."' WHERE orderid='$o' AND manufacturerid='$m'");
		}

		if (!empty($items_stock) && is_array($items_stock) && !empty($products) && is_array($products)){

			foreach ($products as $k => $v){
				$productid = $v["productid"];
				$amount = $v["amount"];
				$item_stock = trim($items_stock[$productid]);

				if ($item_stock != ""){
					$item_stock = abs(intval($item_stock));
					$back = $amount - $item_stock;
					db_query("UPDATE $sql_tbl[order_details] SET items_stock='$item_stock', back='$back' WHERE orderid='$o' AND productid='$productid'");
				}
			}
		}

                if (!empty($cost_to_us) && is_array($cost_to_us)){
                        foreach ($cost_to_us as $k => $v){
				$v = trim($v);
                                if ($v != ""){
					$v = str_replace(",", ".", $v);
					$v = str_replace(" ", "", $v);
                                        db_query("UPDATE $sql_tbl[order_details] SET item_cost_to_us='$v' WHERE orderid='$o' AND productid='$k'");
                                }
                        }
                }

                $mail_smarty->assign("products", $products);
                $mail_smarty->assign("order", $order);
                $mail_smarty->assign("actual_shipping_net", $actual_shipping_net);
                $mail_smarty->assign("items_stock", $items_stock);
                $mail_smarty->assign("cost_to_us", $cost_to_us);
                $mail_smarty->assign("m", $m);
		$smarty->assign("stock_availability_page", "sent");

//		$to = "xcartmaster@gmail.com"; // for tests
		$to = $config['Company']['orders_department'];
		$from = $config['Company']['orders_department'];
		func_send_mail($to, 'mail/stock_availability_subj.tpl', 'mail/stock_availability.tpl', $from, true);
	    } else {
		func_header_location($xcart_web_dir . DIR_CUSTOMER . '/index.php');
	    }

	} else {
		if ($current_dc_status == "K"){
			$order_data = func_order_data($o);
			$order = $order_data["order"];
			$products = $order_data["products"];
			$smarty->assign("products", $products);
			$smarty->assign("order", $order);
			$smarty->assign("stock_availability_page", "Y");
			$smarty->assign("o", $o);
			$smarty->assign("m", $m);
			$smarty->assign("s", $s);

		} else {
			func_header_location($xcart_web_dir . DIR_CUSTOMER . '/index.php');
		}
	}
} else {
	func_header_location($xcart_web_dir . DIR_CUSTOMER . '/index.php');
}

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/index.tpl",$smarty);
?>

