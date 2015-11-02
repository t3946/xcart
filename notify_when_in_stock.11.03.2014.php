<?php
require "./auth.php";

x_load("email", "product");

$current_time = time();

$all_records = func_query("SELECT * FROM $sql_tbl[notify_when_in_stock] WHERE sent='N'");

if (!empty($all_records) && is_array($all_records)){
	foreach ($all_records as $k => $v){

		$product_info = func_query_first("SELECT productcode, product, eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$v[productid]' AND forsale='Y' AND avail > '0'");

		if (!empty($product_info) && is_array($product_info)){
			$eta_date_mm_dd_yyyy = trim($product_info["eta_date_mm_dd_yyyy"]);

			$send_notify_email = false;

			if (empty($eta_date_mm_dd_yyyy)){
				$send_notify_email = true;
			} else {
				$eta_date_mm_dd_yyyy_arr = explode("/", $eta_date_mm_dd_yyyy);
				$eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $eta_date_mm_dd_yyyy_arr[0], $eta_date_mm_dd_yyyy_arr[1], $eta_date_mm_dd_yyyy_arr[2]);
				if ($eta_date_mm_dd_yyyy_time < $current_time){
					$send_notify_email = true;
				}
			}

			if ($send_notify_email){

				$mail_smarty->assign("product_info", $product_info);

				func_send_mail($v["email"], "mail/product_notify_subj.tpl", "mail/product_notify.tpl", $config['Company']['orders_department'], true);
				db_query("UPDATE $sql_tbl[notify_when_in_stock] SET sent='Y' WHERE id='$v[id]'");
			}
		}
	}
}

print"<br />Done!";
?>
