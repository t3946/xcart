<?php

define("CIDEV_CRON_START", "CRON");

require "../top.inc.php";
require "../init.php";

set_time_limit(0);

$orders = db_query("SELECT SQL_NO_CACHE orderid, manufacturerid, cb_status, dc_status, bd_status FROM $sql_tbl[order_groups] WHERE cb_status IN ('P','O','3','H') AND dc_status='DP'");

while ($order = db_fetch_array($orders)) {

    func_flush(".");

    $mnfs = func_get_order_manufacturers($order["orderid"]);

    $order_manufacturer = $mnfs[$order["manufacturerid"]];

    $good_time_to_send_email_to_distributor = $order_manufacturer["good_time_to_send_email_to_distributor"];

    if ($order_manufacturer["allow_dispatch_off_working_hours"] != "Y" || $order_manufacturer["submit_to_operator"] == "through_distributor_website") {
        continue;
    }

    $order['attention_tags'] = func_query("SELECT $sql_tbl[orders_additional_tags].status_id, $sql_tbl[attention_tags_values].status FROM $sql_tbl[orders_additional_tags] LEFT JOIN $sql_tbl[attention_tags_values] ON $sql_tbl[attention_tags_values].status_id=$sql_tbl[orders_additional_tags].status_id WHERE $sql_tbl[orders_additional_tags].orderid='$order[orderid]'");

    $new_message_in_otrs_found = false;
    if (!empty($order["attention_tags"]) && is_array($order["attention_tags"])) {
        foreach ($order["attention_tags"] as $kk => $vv) {
            if (strtoupper(trim($vv["status"])) == "OTRS: NEW MESSAGE") { // OTRS: New message
                $new_message_in_otrs_found = true;
                break;
            }
        }
    }

    if ($new_message_in_otrs_found) {
        continue;
    }

    if ($good_time_to_send_email_to_distributor == "Y" || 1 == 1) {
        $message_body = func_query_first_cell("SELECT message FROM $sql_tbl[off_hours_messages] WHERE orderid = '$order[orderid]' AND manufacturerid='$order[manufacturerid]'");

        if (empty($message_body)) {
            $message_body = $order_manufacturer["mess_body"];
        }

        $mail_smarty->assign("message_body", $message_body);
        $mail_smarty->assign('d_email_subject_14', $order_manufacturer["d_subject_line_8"]);
        $mail_smarty->assign('dispatch_to_distributor_message', "Y");

        $mnf_to = $order_manufacturer["email"];

        if (!empty($mnf_to)) {
            $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
            $oMail->init();
            $oMail->to = $mnf_to;
            $oMail->from = $config['Company']['orders_department'];
            $oMail->reply_to = null;
            $oMail->subject_template = 'mail/order_notification_subj.tpl';
            $oMail->body_template = 'mail/order_notification.tpl';
            $oMail->addHeader(['X-Xcart-Label' => 'order-communication']);
            $oMail->sendEmail();

            //func_send_mail($mnf_to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $config['Company']['orders_department'], true);

            $order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid = '$order[orderid]'");
            $log = $order_manufacturer["code"] . ": 'Send (Dispatch to distributor)'. CRON";
            $log_text = "<a href='order.php?orderid=$order[orderid]' target='_blank' style='color: blue;'>" . $order_prefix . $order["orderid"] . "</a>, " . $order_manufacturer["code"] . " - dispatched by cron";
            func_backprocess_log("Auto_dispatch_cron", $log_text);

            $current_dc_status = $order["dc_status"];
            $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

            if ($current_dc_status != "C") {
                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='C'");
                $log .= "<br />dc_status: " . $current_dc_status_value . " -> " . $new_value . "<br />";
                db_query("UPDATE $sql_tbl[order_groups] SET dc_status='C', dc_dispatched_time='" . time() . "' WHERE orderid = '$order[orderid]' AND manufacturerid='$order[manufacturerid]'");
            }

            func_log_order($order["orderid"], 'X', $log);
        }
    }
}
db_free_result($orders);

print"<br />Done!";
?>
