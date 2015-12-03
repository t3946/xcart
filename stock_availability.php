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

		$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$m'");

		$order_data = func_order_data($o);
        	$order = $order_data["order"];
                $products = $order_data["products"];

	        if (isset($actual_shipping_net)){
                    db_query("UPDATE $sql_tbl[order_groups] SET stock_request_shipping_cost='$actual_shipping_net' WHERE orderid='$o' AND manufacturerid='$m'");
                }

		if (!empty($actual_shipping_net)){

		    $current_actual_shipping_net = func_query_first_cell("SELECT actual_shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$o' AND manufacturerid='$m'");

                    if ($current_actual_shipping_net != $actual_shipping_net){
                        $log .= "<B>".$code.":</B> actual_shipping_net: ". $current_actual_shipping_net . " -> ". $actual_shipping_net ."<br />";
		
			db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='".addslashes($actual_shipping_net)."', actual_shipping_gross='".addslashes($actual_shipping_net)."' WHERE orderid='$o' AND manufacturerid='$m'");
		    }
		}

		if (!empty($stock_status) && is_array($stock_status)){
	           foreach ($stock_status as $ks => $vs){
	
//        	        if ($vs == "all_in_stock") continue;

                	if (!empty($items_stock) && is_array($items_stock) && !empty($products) && is_array($products)){

                        	foreach ($products as $k => $v){

				    if ($v["productid"] == $ks){

                                	$productid = $v["productid"];

###
					db_query("UPDATE $sql_tbl[order_details] SET stock_request_status='$vs' WHERE orderid='$o' AND productid='$productid'");
###
	                                $amount = $v["amount"];
        	                        $item_stock = trim($items_stock[$productid]);

	                                $current_item_stock = func_query_first_cell("SELECT items_stock FROM $sql_tbl[order_details] WHERE orderid='$o' AND productid='$productid'");
        	                        $current_back = func_query_first_cell("SELECT back FROM $sql_tbl[order_details] WHERE orderid='$o' AND productid='$productid'");


					$update_in_db = false;
                	                if ($item_stock != "" && $vs == "some_in_stock"){
                        	                $item_stock = abs(intval($item_stock));
                                	        $back = $amount - $item_stock;
						$update_in_db = true;
        	                        } elseif ($vs == "discontinued" || $vs == "out_of_stock"){
	                                        $item_stock = 0;
        	                                $back = $v["amount"];
                	                        $update_in_db = true;
                        	        }

	                                if ($update_in_db){
	                                        db_query("UPDATE $sql_tbl[order_details] SET items_stock='$item_stock', back='$back' WHERE orderid='$o' AND productid='$productid'");
	
                                        	if ($current_item_stock != $item_stock){
                                                	$log .= "<B>".$v["productcode"].":</B> items_stock: ". $current_item_stock . " -> ". $item_stock ."<br />";
                                        	}

                                        	if ($current_back != $back){
                                                	$log .= "<B>".$v["productcode"].":</B> back: ". $current_back . " -> ". $back ."<br />";
                                        	}
					}
				    }
	                        }
        	        }

                	if (!empty($eta_date_mm_dd_yyyy) && is_array($eta_date_mm_dd_yyyy) && !empty($products) && is_array($products)){

                        	foreach ($products as $k => $v){

				    if ($v["productid"] == $ks){

                                	$productid = $v["productid"];
	                                $eta_date = trim($eta_date_mm_dd_yyyy[$productid]);

	                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
					$current_eta_date_mm_dd_yyyy = func_convert_date_mm_dd_yyyy($current_eta_date_mm_dd_yyyy, "m/d/Y");

        	                        $current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
                	                $current_r_avail = func_query_first_cell("SELECT r_avail FROM $sql_tbl[products] WHERE productid='$productid'");

        	                        if ($vs == "some_in_stock" || $vs == "out_of_stock"){

	                                        if ($current_eta_date_mm_dd_yyyy != $eta_date){
        	                                        $log .= "<B>".$v["productcode"].":</B> ". $current_eta_date_mm_dd_yyyy . " -> ". $eta_date ."<br />";
                	                        }

						$eta_date = func_convert_date_mm_dd_yyyy($eta_date, "seconds");
                	                        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
                        	        } elseif ($vs == "discontinued"){

                                                if ($current_eta_date_mm_dd_yyyy != ''){
                                                        $log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> <br />";
                                                }

	                                        if ($current_forsale != 'N'){
        	                                        $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> N <br />";
                	                        }

	                                        if ($current_r_avail != '0'){
        	                                        $log .= "<B>".$v["productcode"].":</B> r_avail: ". $current_r_avail . " -> 0 <br />";
                	                        }

//                                	        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='', forsale='N', r_avail='0', update_search_index='D' WHERE productid='$productid'");
                                	        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='0', forsale='N', r_avail='0' WHERE productid='$productid'");
	                                }
				    }
        	                }
                	}

#
##

	                if (!empty($products) && is_array($products)){
        	                foreach ($products as $k => $v){
                	                $productid = $v["productid"];
	
        	                        if ($vs == "all_in_stock"){

                	                        if (!empty($v["eta_date_mm_dd_yyyy"]) || $v["avail"] == "0"){


	                                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
							$current_eta_date_mm_dd_yyyy = func_convert_date_mm_dd_yyyy($current_eta_date_mm_dd_yyyy, "m/d/Y");

        	                                        $current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
                	                                $current_avail = func_query_first_cell("SELECT avail FROM $sql_tbl[products] WHERE productid='$productid'");


                        	                        if (!empty($v["eta_date_mm_dd_yyyy"])){
                                	                        $tmp_mktime = time() - 24*60*60;
                                        	                $eta_date = date("m/d/Y", $tmp_mktime);

								if ($current_eta_date_mm_dd_yyyy != $eta_date){
									$log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> ".$eta_date."<br />";
								}

								$eta_date = func_convert_date_mm_dd_yyyy($eta_date, "seconds");
                                                	        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
	                                                }

        	                                        if ($v["avail"] == "0"){

	                                                        if ($current_avail != '1000000'){
        	                                                        $log .= "<B>".$v["productcode"].":</B> avail: ". $current_avail . " -> 1000000 <br />";
                	                                        }

                	                                        db_query("UPDATE $sql_tbl[products] SET avail='1000000' WHERE productid='$productid'");
                        	                        }

                                	                if ($v["forsale"] == "N"){

	                                                        if ($current_forsale != 'Y'){
        	                                                        $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> Y <br />";
                	                                        }

                                        	                db_query("UPDATE $sql_tbl[products] SET forsale='Y' WHERE productid='$productid'");
                                                	}
	                                        }
        	                        }
                	        }
	                }
##
#

		  }
		}


                if (!empty($cost_to_us) && is_array($cost_to_us)){
       	                foreach ($cost_to_us as $k => $v){
               	                $v = trim($v);
                       	        if ($v != ""){
                               	        $v = str_replace(",", ".", $v);
                                       	$v = str_replace(" ", "", $v);

					$current_item_cost_to_us = func_query_first_cell("SELECT item_cost_to_us FROM $sql_tbl[order_details] WHERE orderid='$o' AND productid='$k'");
                                        if ($current_item_cost_to_us != $v){
                                                $product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$k'");
                                                $log .= "<B>".$product_code.":</B> item_cost_to_us: ". $current_item_cost_to_us. " -> ".$v."<br />";
                                        }

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

	        if ($current_dc_status != "M"){
        	        $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");
                	$new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='M'");
	                $log .= "<B>".$code .":</B> dc_status: ". $current_dc_status_value. " -> ".$new_value."<br />";
        	}

		db_query("UPDATE $sql_tbl[order_groups] SET dc_status='M' WHERE orderid = '$o' AND manufacturerid='$m'");

	        if (!empty($log)){
        	        func_log_order($o, 'X', $log, 'Distributor: '.$code);
	        }


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

//func_print_r($order);

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/index.tpl",$smarty);
?>

