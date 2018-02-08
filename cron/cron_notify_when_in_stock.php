<?php
use Modules\Goods\Models\ProductModel;

define("CIDEV_CRON_START", "CRON");
require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

global $mail_smarty;

$log_category = 'notify_when_in_stock';

db_query_param(/** @lang MySQL */"REPLACE xcart_config SET value='Y', name=:log_category", ['log_category' => $log_category]);

$from = "S3 Stores stock notification service <helpdesk@s3stores.com>";
$current_time = time();

$all_records = func_query_param(/** @lang MySQL */
    "SELECT * FROM xcart_notify_when_in_stock WHERE sent='N'", []);

if (!empty($all_records) && is_array($all_records)){
	foreach ($all_records as $k => $v){

        /** @var ProductModel $modelProduct */
        $modelProduct = ProductModel::objects()->get(['productid' => $v['productid'], 'forsale' => 'Y', 'avail__gt' => 0]);
		if ($modelProduct) {
            $eta_date_mm_dd_yyyy = trim($modelProduct->eta_date_mm_dd_yyyy);
            $send_notify_email = false;
            if (empty($eta_date_mm_dd_yyyy)){
                $send_notify_email = true;
            } else {
                if ($eta_date_mm_dd_yyyy < $current_time){
                    $send_notify_email = true;
                }
            }
            if ($send_notify_email){
                $sfid = $v["storefrontid"];
                $product_info["http_location"] = "https://" . func_get_http_location_sf($sfid);
                $product_info["links"] = func_get_product_link_sf($v["productid"], $sfid);
                $mail_smarty->assign("product_info", $product_info);
                $mail_smarty->assign("productmodel", $modelProduct);
                func_send_mail($v["email"], "mail/product_notify_subj.tpl", "mail/product_notify.tpl", $from, true);
                db_query_param(/** @lang MySQL */
                    "UPDATE xcart_notify_when_in_stock SET sent='Y' WHERE id=:id", ['id' => $v['id']]);
            }
		}
	}
}
db_query_param(/** @lang MySQL */
    "UPDATE xcart_config SET value='N' WHERE name=:log_category", ['log_category' => $log_category]);

die("DONE!");
