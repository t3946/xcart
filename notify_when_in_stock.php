<?php
define("CIDEV_CRON_START", "CRON");
require "./top.inc.php";
require "./init.php";

x_load("email", "product");

$log_category = 'notify_when_in_stock';

db_query("REPLACE $sql_tbl[config] SET value='Y', name='$log_category'");

$from = "S3 Stores stock notification service <helpdesk@s3stores.com>";
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
				if ($eta_date_mm_dd_yyyy < $current_time){
					$send_notify_email = true;
				}
			}

			if ($send_notify_email){

				# $sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$v[productid]'");
				# https://basecamp.com/2070980/projects/1577907/messages/52795187
				$sfid = $v["storefrontid"];

				$product_info["http_location"] = "http://" . func_get_http_location_sf($sfid);
				$product_info["links"] = func_get_product_link_sf($v["productid"], $sfid);

				$mail_smarty->assign("product_info", $product_info);

				func_send_mail($v["email"], "mail/product_notify_subj.tpl", "mail/product_notify.tpl", $from, true);
				db_query("UPDATE $sql_tbl[notify_when_in_stock] SET sent='Y' WHERE id='$v[id]'");
			}
		}
	}
}
db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='$log_category'");

die("DONE!");

?>
