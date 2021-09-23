<?php

require "./auth.php";

$mode = "notify";
extract($_POST);
$productid = (int) $productid;
$notify_email = addslashes($notify_email);
$top_message = array();

if ($mode == "notify" && !empty($productid) && !empty($notify_email)){
    $is_in_table = func_query_first_cell("SELECT COUNT(sent) FROM $sql_tbl[notify_when_in_stock] WHERE email='$notify_email' AND sent='N' AND productid='$productid' AND storefrontid='$current_storefront'");
    x_session_save('notify_email');
    if (empty($is_in_table)){

        $notify_when_in_stock[$productid] = "Y";
        x_session_save('notify_when_in_stock');

        db_query("INSERT INTO $sql_tbl[notify_when_in_stock] (productid, email, date, storefrontid) VALUES ('$productid', '$notify_email', '".time()."','$current_storefront')");
        $top_message["content"] = 'Thank you! You will be notified when the product is in stock.';
        $top_message["type"] = "I";
    } else {
        $top_message["content"] = 'You already signed up for this notification.';
        $top_message["type"] = "E";
    }
    print(json_encode($top_message));
}
return false;