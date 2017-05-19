<?php
define("CIDEV_CRON_START", "CRON");
global $config, $mail_smarty;

require "../top.inc.php";
require "../init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$curr_time = time();
$thank_you_days = trim($config["thankyou_for_order"]["thank_you_days"]);
$thank_you_days = isset($thank_you_days) ? abs(intval($thank_you_days)) : 0;
$days_to_check = 60 * 60 * 24 * $thank_you_days;
$diff_time = $curr_time - $days_to_check;

$log_category = 'cron_tracking_number_and_email';
$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

$orders = func_query_param(/** @lang MySQL */
    <<<SQL
	SELECT orderid, order_prefix, firstname, email FROM xcart_orders
	WHERE 
		thankyou_for_order_email_sent != 'Y'
		AND tracking_all_filled = 'Y'
		AND tracking_fill_time != '0'
		AND amazon_fulfillment_channel = ''
		AND tracking_fill_time < :diff_time
SQL
, ['diff_time' => $diff_time]);

if (!empty($orders)) {
    $from = $config["thankyou_for_order"]["thank_you_from"];
    foreach ($orders as $k => $v) {
        $cb_dc_statuses = func_query_param(/** @lang MySQL */
            "SELECT cb_status, dc_status FROM xcart_order_groups WHERE orderid=:orderid", ['orderid' => $v['orderid']]);

        if (!empty($cb_dc_statuses)) {
            $counter = 0;
            $count_cb_dc_statuses = count($cb_dc_statuses);
            foreach ($cb_dc_statuses as $kk => $vv) {
                if (($vv["cb_status"] == "P" || $vv["cb_status"] == "O" || $vv["cb_status"] == "H")
                    && ($vv["dc_status"] == "S" || $vv["dc_status"] == "L" || $vv["dc_status"] == "C")){
                    $counter++;
                }
            }

            if ($count_cb_dc_statuses == $counter) {
                $to = $v["email"];
                $subj = $config["thankyou_for_order"]["thank_you_subject"];
                $subj = str_replace("{{orderid}}", $v["order_prefix"] . $v["orderid"], $subj);
                $subj = str_replace("{{c-fullname}}", $v["firstname"], $subj);

                $body = $config["thankyou_for_order"]["thank_you_message_body"];
                $body = str_replace("{{orderid}}", $v["order_prefix"] . $v["orderid"], $body);
                $body = str_replace("{{c-fullname}}", $v["firstname"], $body);

                $mail_smarty->assign('subj', $subj);
                $mail_smarty->assign('body', $body);

                func_send_mail($to, 'mail/simple_email_subj.tpl', 'mail/simple_email_body.tpl', $from, false);
                func_send_mail('igor@s3stores.com', 'mail/simple_email_subj.tpl', 'mail/simple_email_body.tpl', $from, false);

                db_query_param(/** @lang MySQL */
                    "UPDATE xcart_orders SET thankyou_for_order_email_sent='Y' WHERE orderid=:orderid", ['orderid' => $v['orderid']]);

                $log = "Thank you email sent by system <br />";
                func_log_order($v["orderid"], 'X', $log);
            }
        }
    }
}
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log($log_category, $log_text);

print "Done.";