<?php

if (!isset($Product_SKU_found)) {
    require "./auth.php";
}

x_load("mail", "product", "http");


$product_info = func_select_product($productid, @$user_account['membershipid']);

$customer_email = $email;
$brandid = $product_info['brandid'];
$brand_email = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$distributor_email = func_query_first_cell("SELECT d_product_questions_send_to_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
$customer_service_phone = func_query_first_cell("SELECT customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$product_question_departments_email = func_query_first_cell("SELECT email FROM $sql_tbl[departments] WHERE name='Product question'");

$product_question_subject_line = $config["product_question_email"]["product_question_subject_line"];
// $product_question_message_body_to_brand = Message body to us
$product_question_message_body_to_brand = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_brand"]));
$product_question_message_body_to_customer = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_customer"]));


$product_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");
if (!empty($product_sfid)) {
    $storefront_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$product_sfid'");
} else {
    $storefront_domain = "www.artistsupplysource.com";
}
$product_link = "http://" . $storefront_domain . "/" . $product_info["clean_url"] . "/";

$product_image = "";
if (!empty($product_info["image_path_P"])) {
    $product_image = str_replace("./", "/", $product_info["image_path_P"]);
} elseif (!empty($product_info["image_path_T"])) {
    $product_image = str_replace("./", "/", $product_info["image_path_T"]);
}
if (!empty($product_image)) {
    $product_image = "http://" . $storefront_domain . $product_image;
}

#
##
###
if ($Product_SKU_found) { // help.php page
    $question = addslashes($body);
}

$answered_date = time();
$answered_date_str = date("N", $answered_date);
if ($answered_date_str <= 4 || $answered_date_str == "7") {
    $answered_date += 60 * 60 * 24;
} elseif ($answered_date_str == "6") {
    $answered_date += 60 * 60 * 24 * 2;
}

$firstname = trim($firstname);
$name = $firstname;

$firstname_arr = explode(" ", $firstname);
$firstname = array_shift($firstname_arr);


db_query("INSERT INTO $sql_tbl[product_question] (productid, email, phone, question, date, company, zipcode, name, firstname, answered_date) VALUES ('$productid', '$customer_email', '$phone', '$question', '" . time() . "', '" . addslashes($company) . "', '" . addslashes($b_zipcode) . "', '" . addslashes($name) . "', '" . addslashes($firstname) . "', '$answered_date')");
$product_question_id = db_insert_id();

$prefix_product_question_id = "PRQN-" . sprintf('%1$05d', $product_question_id);

$question = func_eol2br(stripslashes($question));

$signature = func_get_signature($product_sfid);


$product_question_subject_line = str_replace(
    [
        '{{mpn}}',
        '{{supplier_internal_id}}',
        "{{productname}}",
        "{{brand_email}}",
        "{{brand_phone}}",
        "{{question}}",
        "{{customer_phone}}",
        "{{product_link}}",
        "{{customer_email}}",
        "{{prqnid}}",
        "{{signature}}",
        "{{customer_name}}"
    ],
    [
        $product_info["mpn"],
        $product_info["supplier_internal_id"],
        $product_info["product"],
        $brand_email,
        $customer_service_phone,
        $question,
        $phone,
        $product_link,
        $customer_email,
        $prefix_product_question_id,
        $signature,
        $name
    ], $product_question_subject_line);

// $product_question_message_body_to_brand = Message body to us
$product_question_message_body_to_brand = str_replace(
    [
        '{{mpn}}',
        '{{supplier_internal_id}}',
        "{{productname}}",
        "{{brand_email}}",
        "{{brand_phone}}",
        "{{question}}",
        "{{customer_phone}}",
        "{{product_link}}",
        "{{customer_email}}",
        "{{prqnid}}",
        "{{signature}}",
        "{{customer_name}}"
    ],
    [
        $product_info["mpn"],
        $product_info["supplier_internal_id"],
        $product_info["product"],
        $brand_email,
        $customer_service_phone,
        $question,
        $phone,
        $product_link,
        $customer_email,
        $prefix_product_question_id,
        $signature, $name
    ], $product_question_message_body_to_brand);

$product_question_message_body_to_customer = str_replace(
    [
        '{{mpn}}',
        '{{supplier_internal_id}}',
        "{{productname}}",
        "{{brand_email}}",
        "{{brand_phone}}",
        "{{question}}",
        "{{customer_phone}}",
        "{{product_link}}",
        "{{customer_email}}",
        "{{prqnid}}",
        "{{signature}}",
        "{{customer_name}}"
    ],
    [
        $product_info["mpn"],
        $product_info["supplier_internal_id"],
        $product_info["product"],
        $brand_email,
        $customer_service_phone,
        $question,
        $phone,
        $product_link,
        $customer_email,
        $prefix_product_question_id,
        $signature,
        $name
    ], $product_question_message_body_to_customer);

if (!$Product_SKU_found) {

    $from = $product_question_departments_email;
    $to = $customer_email;
    $subject = $product_question_subject_line;
    $body = $product_question_message_body_to_customer;
    $mail_smarty->assign('subject', $subject);
    $mail_smarty->assign('body', $body);
    func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false, false, false, false, '', 'N', false, false);

}


###
$body = $product_question_message_body_to_brand; // Message body to us
$to = $config["product_question_email"]["product_question_bc_email"];
$from = $customer_email;
$mail_smarty->assign("subject", $subject);
$mail_smarty->assign("body", $body);
func_send_mail($to, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true, false, false, false, '', 'N', false, false);


if (!$Product_SKU_found) {

    $smarty->assign("email_sent", "Y");

    func_display("customer/main/product_question_after.tpl", $smarty);
}

?>
