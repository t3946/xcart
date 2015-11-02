<?php

require "./auth.php";

x_load("mail", "product");

$product_info = func_select_product($productid, @$user_account['membershipid']);

//$brandid = func_query_first_cell("SELECT brandid FROM $sql_tbl[products] WHERE productid='$productid'");
$brandid = $product_info['brandid'];
$customer_service_email = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$customer_service_phone = func_query_first_cell("SELECT customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$brandid'");
$product_question_departments_email = func_query_first_cell("SELECT email FROM $sql_tbl[departments] WHERE name='Product question'");

$product_question_subject_line = $config["product_question_email"]["product_question_subject_line"];
$product_question_message_body_to_brand = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_brand"]));
$product_question_message_body_to_customer = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_customer"]));

$question = func_eol2br(stripslashes($question));

//func_print_r($customer_service_email, $product_question_departments_email, $product_question_subject_line, $product_question_message_body_to_brand, $product_question_message_body_to_customer, $question);

$product_question_subject_line = str_replace("{{mpn}}", $product_info["mpn"], $product_question_subject_line);
$product_question_subject_line = str_replace("{{productname}}", $product_info["product"] , $product_question_subject_line);
$product_question_subject_line = str_replace("{{brand_email}}", $customer_service_email , $product_question_subject_line);
$product_question_subject_line = str_replace("{{brand_phone}}", $customer_service_phone , $product_question_subject_line);
$product_question_subject_line = str_replace("{{question}}", $question , $product_question_subject_line);
$product_question_subject_line = str_replace("{{customer_phone}}", $phone , $product_question_subject_line);

$product_question_message_body_to_brand = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{productname}}", $product_info["product"] , $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{brand_email}}", $customer_service_email , $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{brand_phone}}", $customer_service_phone , $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{question}}", $question , $product_question_message_body_to_brand);
$product_question_message_body_to_brand = str_replace("{{customer_phone}}", $phone , $product_question_message_body_to_brand);

$product_question_message_body_to_customer = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{productname}}", $product_info["product"] , $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{brand_email}}", $customer_service_email , $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{brand_phone}}", $customer_service_phone , $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{question}}", $question , $product_question_message_body_to_customer);
$product_question_message_body_to_customer = str_replace("{{customer_phone}}", $phone , $product_question_message_body_to_customer);


if ($customer_service_email != ""){

	# N 1
	$to = $customer_service_email.",".$product_question_departments_email;
	$from = $product_question_departments_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_brand;
	$mail_smarty->assign('email', $email);
	$mail_smarty->assign('phone', $phone);
	$mail_smarty->assign('question', $question);
	$mail_smarty->assign('subject', $subject);
	$mail_smarty->assign('body', $body);
	func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);

	# N 2
	$to = $email;	
	$from = $product_question_departments_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_customer;
        $mail_smarty->assign('email', $email);
        $mail_smarty->assign('phone', $phone);
        $mail_smarty->assign('question', $question);
        $mail_smarty->assign('subject', $subject);
        $mail_smarty->assign('body', $body);
        func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);

} else {

	# N 1
        $to = $product_question_departments_email;
        $from = $email;
        $subject = $product_question_subject_line;
        $body = $product_question_message_body_to_brand;
        $mail_smarty->assign('email', $email);
        $mail_smarty->assign('phone', $phone);
        $mail_smarty->assign('question', $question);
        $mail_smarty->assign('subject', $subject);
        $mail_smarty->assign('body', $body);
        func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);


	# N 2

	$to = $email;
	$from = $product_question_departments_email;
	$subject = $product_question_subject_line;
	$body = $product_question_message_body_to_customer;
        $mail_smarty->assign('email', $email);
        $mail_smarty->assign('phone', $phone);
        $mail_smarty->assign('question', $question);
        $mail_smarty->assign('subject', $subject);
        $mail_smarty->assign('body', $body);
        func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false);
}

$smarty->assign("email_sent", "Y");

func_display("customer/main/product_question_after.tpl", $smarty);
?>
