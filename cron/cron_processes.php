<?php
/**
 * Created by PhpStorm.
 * User: Vyacheslav Zababurin
 * Date: 16.10.2015
 * Time: 16:41
 */

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR ."../top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../init.php";

set_time_limit(0);

x_load("mail");

$db = "xcart_k";

$processes = db_query("SELECT B.process_id, Round(AVG(B.diff),0) As diff FROM " . $db. ".xcart_backprocess_logs B WHERE B.diff is NOT NULL and B.diff>10 GROUP BY B.process_id;");
while ($row = db_fetch_array($processes))
{
    $times = db_query("SELECT B.date As LastTime FROM " . $db . ".xcart_backprocess_logs B WHERE B.process_id = '" . $row['process_id'] . "' ORDER BY B.id desc LIMIT 1");
    $row_times = db_fetch_array($times);
    if (isset( $row_times['LastTime'] ))
    {
        $current_time = time();
        $last_time = $row_times['LastTime'];
        $diff = $row['diff'];
        $process_id = $row['process_id'];

        $pid_diff = $current_time - $last_time;

        $hour = intval($pid_diff / (60 * 60));
        $minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
        $seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60 );

        $str_time = sprintf( "%02d:%02d:%02d", $hour, $minutes, $seconds );
        //echo $last_time . ": " . $current_time . ": " . $pid_diff . ": " . $process_id . ": ". $diff . ": " . $str_time ."\n";
        if ( $pid_diff > $diff * 2 )
            send_mail_notification1($process_id, $str_time);
    }
}

db_query("UPDATE " . $db . ".xcart_supplier_feeds SF INNER JOIN(
    SELECT S.feed_id, ROUND((UNIX_TIMESTAMP(NOW()) - S.last_update_time) / S.average_update_period,1) as NC
    FROM " . $db . ".xcart_supplier_feeds S) as SA ON SA.feed_id = SF.feed_id
    SET SF.last_update_late = SA.NC
    WHERE SF.enabled = 'Y'");

$feeds = db_query("SELECT S.feed_name, S.last_update_late FROM " . $db . ".xcart_supplier_feeds S WHERE S.enabled='Y'");
while ($row = db_fetch_array($feeds))
{
    $feed_name = $row['feed_name'];
    $last_update_late = $row['last_update_late'];

    //echo  $feed_name. ": " . $last_update_late . "\n";
    if ( $last_update_late > 3 )
        send_mail_notification2($feed_name, $last_update_late);
}

function send_mail_notification1($process_id, $str_time)
{
    $to = "team@s3stores.com";

    $subject = "NOTIFICATION: CRON MONITOR - [". $process_id . "] ";
    $message = "Last logging into [" . $process_id . "] folder is aged about " . $str_time. " hours.\r\n";
    $message = $message .  "Please check it.";

    $headers = 'From: ' . $to . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    @mail($to, $subject, $message, $headers);
}

function send_mail_notification2($feed_name,$last_update_late)
{
    $to = "team@s3stores.com";

    $subject = "NOTIFICATION: SUPPLIER FEEDS MONITOR - [". $feed_name . "] ";
    $message = "Last feed import for [" . $feed_name . "] is aged nearly for " . $last_update_late . " times..\r\n";
    $message = $message .  "Please check it.";

    $headers = 'From: ' . $to . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    @mail($to, $subject, $message, $headers);
}