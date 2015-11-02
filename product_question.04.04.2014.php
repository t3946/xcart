<?php

require "./auth.php";

x_load("mail", "product", "http");

$product_info = func_select_product($productid, @$user_account['membershipid']);

$customer_email = $email;
$brandid = $product_info['brandid'];
$brand_email = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$distributor_email = func_query_first_cell("SELECT d_product_questions_send_to_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
$customer_service_phone = func_query_first_cell("SELECT customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$product_question_departments_email = func_query_first_cell("SELECT email FROM $sql_tbl[departments] WHERE name='Product question'");

$product_question_subject_line = $config["product_question_email"]["product_question_subject_line"];
$product_question_message_body_to_brand = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_brand"]));
$product_question_message_body_to_customer = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_customer"]));

$question = func_eol2br(stripslashes($question));

$product_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");
if (!empty($product_sfid)){
        $storefront_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$product_sfid'");
} else {
        $storefront_domain = "www.artistsupplysource.com";
}
$product_link = "http://".$storefront_domain."/".$product_info["clean_url"]."/";

$product_image = "";
if (!empty($product_info["image_path_P"])){
	$product_image = str_replace("./", "/", $product_info["image_path_P"]);
} elseif (!empty($product_info["image_path_T"])){
	$product_image = str_replace("./", "/", $product_info["image_path_T"]);
}
if (!empty($product_image)){
	$product_image = "http://".$storefront_domain.$product_image;
}

//func_print_r($brand_email, $product_question_departments_email, $product_question_subject_line, $product_question_message_body_to_brand, $product_question_message_body_to_customer, $question);

$product_question_subject_line = str_replace("{{mpn}}", $product_info["mpn"], $product_question_subject_line);
$product_question_subject_line = str_replace("{{productname}}", $product_info["product"], $product_question_subject_line);
$product_question_subject_line = str_replace("{{brand_email}}", $brand_email, $product_question_subject_line);
$product_question_subject_line = str_replace("{{brand_phone}}", $customer_service_phone, $product_question_subject_line);
$product_question_subject_line = str_replace("{{question}}", $question, $product_question_subject_line);
$product_question_subject_line = str_replace("{{customer_phone}}", $phone, $product_question_subject_line);
$product_question_subject_line = str_replace("{{product_link}}", $product_link, $product_question_subject_line);
$product_question_subject_line = str_replace("{{customer_email}}", $customer_email, $product_question_subject_line);

$product_question_message_body_to_brand = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{brand_email}}", $brand_email, $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{brand_phone}}", $customer_service_phone, $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{question}}", $question, $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{customer_phone}}", $phone, $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{product_link}}", $product_link, $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{customer_email}}", $customer_email, $product_question_message_body_to_brand);

$product_question_message_body_to_customer = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{brand_email}}", $brand_email, $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{brand_phone}}", $customer_service_phone, $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{question}}", $question, $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{customer_phone}}", $phone, $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{product_link}}", $product_link, $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{customer_email}}", $customer_email, $product_question_message_body_to_customer);



# N 1

if ($config["product_question_email"]["product_question_send_to_band"] == "Y"){
	$to = (!empty($brand_email)? $brand_email."," : "").$product_question_departments_email;
	$from = $product_question_departments_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_brand;
	$mail_smarty->assign('subject', $subject);
	$mail_smarty->assign('body', $body);
	func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);
}

if ($config["product_question_email"]["product_question_send_to_distributor"] == "Y"){
	$to = (!empty($distributor_email)? $distributor_email."," : "").$product_question_departments_email;
	$from = $product_question_departments_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_brand;
	$mail_smarty->assign('subject', $subject);
	$mail_smarty->assign('body', $body);
	func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);
}

if ($config["product_question_email"]["product_question_send_to_customer_service"] == "Y"){
	$to = $product_question_departments_email;
	$from = $customer_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_brand;
	$mail_smarty->assign('subject', $subject);
	$mail_smarty->assign('body', $body);
	func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);
}

# N 2

$from = $product_question_departments_email;
$to = $customer_email;
$subject = $product_question_subject_line;
$body = $product_question_message_body_to_customer;
$mail_smarty->assign('subject', $subject);
$mail_smarty->assign('body', $body);
func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);


//func_print_r($from, $to, $subject, $body);


if ($config['product_queries']['product_queries_enable'] == 'Y') {

	$brand_name = func_query_first_cell("SELECT brand FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");

	$question = strip_tags($question);

	$params="email=".htmlspecialchars($email);
	$params.="&phone=".$phone;
	$params.="&product_name=".$product_info["product"];
	$params.="&product_url=".$product_link;
	$params.="&gtin=".$product_info["upc"];
	$params.="&mpn=".$product_info["mpn"];
	$params.="&brand=".$brand_name;
	$params.="&content=".$question;
	$params.="&product_image=".$product_image;

	#perform the HTTP Post
	list($a1,$r1,$a2)=func_http_post_request("www.productqueries.com", "/xcart_product_question.php", $params);

//func_print_r($r1);
//die("!");
}


$smarty->assign("email_sent", "Y");

func_display("customer/main/product_question_after.tpl", $smarty);
?>
