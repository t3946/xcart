<?php
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array('subject', 'body');

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('mail','order','crypt');

$department_arr = array(
                        "customer" => "Customer",
                        "distributor" => "Distributor",
                        "our_customer_service" => "Our customer service",
			"third_party" => "Compose email to third party"
                        );
$department_arr_keys = array_keys($department_arr);

if (
	!is_numeric($orderid) || 
	!in_array($department, $department_arr_keys) || 
	(empty($template_id) && ($department == "customer" || $department == "our_customer_service" || $department == "third_party")) || 
	(empty($mid_templateid) && $department == "distributor") 
){
	func_header_location("error_message.php?access_denied&id=8");
}

#
# Collect infos about ordered products
#
require $xcart_dir."/include/history_order.php";

$order = $order_data["order"];
$userinfo = $order_data["userinfo"];
$products = $order_data["products"];
$giftcerts = $order_data["giftcerts"];


if ($department == "distributor" && !empty($mid_templateid)){
        $mid_templateid_arr = explode("-", $mid_templateid);
        $manufacturerid = $mid_templateid_arr[0];
        $template_id = $mid_templateid_arr[1];
}

if (($REQUEST_METHOD == "POST") && ($mode == "send_message")) {

	$body = func_eol2br(stripslashes($body));

	$mail_smarty->assign('body', $body);
	$mail_smarty->assign('subject', $subject);
	$mail_smarty->assign('attach_pdf_invoice', $attach_pdf_invoice);

	$mail_smarty->assign("products", $products);
	$mail_smarty->assign("giftcerts", $giftcerts);
	$mail_smarty->assign("userinfo", $userinfo);
	$mail_smarty->assign("order", $order);

	$mail_smarty->assign("tracking_links", $tracking_links);
	$mail_smarty->assign("tracking_links_carrier", $tracking_links_carrier);

	func_send_mail($to, "mail/compose_message_subj.tpl", "mail/compose_message.tpl", $from, false, false, false, false, '', 'Y');

	if ($department == "third_party"){
		func_send_mail("helpdesk@s3stores.com", "mail/compose_message_subj.tpl", "mail/compose_message.tpl", $from, false);
	}

#
##
###
/*
	$current_ca_status = func_query_first_cell("SELECT ca_status FROM $sql_tbl[orders] WHERE orderid='$orderid'");
	if (empty($current_ca_status)){
		$ca_status = func_query_first_cell("SELECT ca_status FROM $sql_tbl[templates_for_communication] WHERE id='$template_id'");
		if (!empty($ca_status)){
			db_query("UPDATE $sql_tbl[orders] SET ca_status='$ca_status' WHERE orderid='$orderid'");
	                $ca_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$ca_status'");
        	        $log = "CA: Not yet started -> ".$ca_status_name . " (From 'Compose message')";
	                func_log_order($orderid, 'X', $log, $login);
		}
	}
*/

	$additional_tag_status = func_query_first_cell("SELECT status_id FROM $sql_tbl[templates_for_communication] WHERE id='$template_id'");

	if ($additional_tag_status > 0){

		$is_such_additional_tag_status = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='$additional_tag_status'");

        	if (empty($is_such_additional_tag_status)){

                	db_query("INSERT INTO $sql_tbl[orders_additional_tags] (status_id, orderid) VALUES('$additional_tag_status', '$orderid')");

	                ### LOG: START
			$status_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='$additional_tag_status'");
                	$log = "'".$status_name."' attention tag added";
	                func_log_order($orderid, 'X', $log, $login);
        	        ### LOG: END
	        }
	}
###
##
#


        $top_message = array(
                "content" => "EMAIL SENT. OK.",
                "type" => "I"
        );

	func_header_location('compose_message.php?orderid='.$orderid.'&department='.$department.($department == "distributor" ? '&mid_templateid='.$mid_templateid : '&template_id='.$template_id)."&sent=Y");
}


$department_info = func_query("SELECT * FROM $sql_tbl[templates_for_communication] WHERE department='$department' AND active='Y' ORDER BY pos");

$mnfs = func_get_order_manufacturers($orderid);
$smarty->assign("order_manufacturers", $mnfs);

//func_print_r($mnfs);

if (!empty($department_info) && is_array($department_info) && !empty($mnfs) && is_array($mnfs) && !empty($products) && is_array($products)){

	if ($department == "customer" || $department == "our_customer_service" || $department == "third_party"){
        	$all__items_table__ = "";
	        $all__shipto_table__ = "";

        	foreach ($mnfs as $mid => $v){

			$shipfrom = $v["m_city"].", ".$v["m_state"]." ".$v["m_zipcode"];
			if (count($mnfs) > 1){
				$shipfrom .= "(".$v["manufacturer"].")";
			}
			$shipfrom_arr[$mid] = $shipfrom;

	                if (!empty($v["__items_table__"])){
//                        	$all__items_table__ = $v["manufacturer"] . "<br />";
//                	        $all__items_table__ .= $v["__items_table__"];
                	        $all__items_table__ = $v["__items_table__"];
				$all__items_table__arr[$mid] = $all__items_table__;
        	        }

	                if (!empty($v["__shipto_table__"])){
//                        	$all__shipto_table__ .= $v["manufacturer"] . "<br />";
                	        $all__shipto_table__arr[$mid] = $v["__shipto_table__"];
        	        }
	        }
	
		if (is_array($all__items_table__arr) && !empty($all__items_table__arr)){
			$all__items_table__ = implode("<br />", $all__items_table__arr);
		}

		if (is_array($all__shipto_table__arr) && !empty($all__shipto_table__arr)){

			$all__shipto_table__arr = array_unique($all__shipto_table__arr);

			$all__shipto_table__ = implode("<br />", $all__shipto_table__arr);
		}

		$shipfrom = implode("/", $shipfrom_arr);
	}

        foreach ($department_info as $k => $v){
                if ($template_id == $v["id"]){

                        $subject = $v["subject_line"];
			$body = $v["message_body"];
			$to = "";
			$attach_pdf_invoice = $v["attach_pdf_invoice"];

			if ($department == "customer"){
				$to = $userinfo["email"];
			} 

                        if ($department == "customer" || $department == "our_customer_service" || $department == "third_party"){
				$body = str_replace("{{items}}", $all__items_table__, $body);
				$body = str_replace("{{shipto}}", $all__shipto_table__, $body);

                                $subject = str_replace("{{items}}", $all__items_table__, $subject);
                                $subject = str_replace("{{shipto}}", $all__shipto_table__, $subject);
                        } 

			foreach ($mnfs as $mid => $vv){
				if ($manufacturerid == $mid){

					if ($department == "distributor"){

		                                if (!empty($to)) {
		                                        $to .= ", ";
                		                }

						$to .= $vv["compose_email_to_distributor"];

		                                $body = str_replace("{{shipto}}", $vv["__shipto_table__"], $body);
						$body = str_replace("{{items}}", $vv["__items_table__"], $body);

		                                $subject = str_replace("{{shipto}}", $vv["__shipto_table__"], $subject);
						$subject =  str_replace("{{items}}", $vv["__items_table__"], $subject);

	                                        $body = str_replace("{{distributorcontactname}}", $vv["d_contact_name_for_templates"], $body);
        	                                $subject = str_replace("{{distributorcontactname}}", $vv["d_contact_name_for_templates"], $subject);

					}


//func_print_r($vv["d_contact_name_for_templates"]);

//					$body = str_replace("{{distributorcontactname}}", $vv["d_contact_name_for_templates"], $body);
//					$subject = str_replace("{{distributorcontactname}}", $vv["d_contact_name_for_templates"], $subject);
				}
			}

                        if (!empty($to)) {
                               $to .= ", ";
                        }

                        $to .= $v["send_to_email"];

                        $body = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $body);
                        $body = str_replace("{{c-fullname}}", $userinfo["firstname"], $body);
//                        $body = str_replace("{{userfullname}}", $userfullname, $body);

                        $subject = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $subject);
                        $subject = str_replace("{{c-fullname}}", $userinfo["firstname"], $subject);
//                        $subject = str_replace("{{userfullname}}", $userfullname, $subject);

                }
        }

	$instock_and_outofstock_items_table = func_instock_and_outofstock_items_table($products, 'compose_message_page');
	$cidev_instock_items_table = $instock_and_outofstock_items_table["instock"];
	$cidev_outofstock_items_table = $instock_and_outofstock_items_table["outofstock"];
	$cidev_outofstock_items_eta_table = $instock_and_outofstock_items_table["outofstock_eta"];
	
        $body = str_replace("{{instock}}", $cidev_instock_items_table, $body);
        $body = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $body);
        $body = str_replace("{{outofstock_eta}}", $cidev_outofstock_items_eta_table, $body);
        $body = str_replace("{{shipfrom}}", $shipfrom, $body);

        $subject = str_replace("{{instock}}", $cidev_instock_items_table, $subject);
        $subject = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $subject);
        $subject = str_replace("{{outofstock_eta}}", $cidev_outofstock_items_eta_table, $subject);
        $subject = str_replace("{{shipfrom}}", $shipfrom, $subject);
}

$subject = str_replace("{{shiptocountry}}", $userinfo["s_countryname"], $subject);
$body = str_replace("{{shiptocountry}}", $userinfo["s_countryname"], $body);

###
$storefronts[0]["storefrontid"] = 0;
$storefronts[0]["domain"] = "www.artistsupplysource.com";
$storefronts[0]["storefront_name"] = func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name='company_name'");

$subject = str_replace("{{storefront-name}}", $storefronts[$products[0]["storefrontid"]]["storefront_name"], $subject);
$body = str_replace("{{storefront-name}}", $storefronts[$products[0]["storefrontid"]]["storefront_name"], $body);

$storefront_url = "http://".$storefronts[$products[0]["storefrontid"]]["domain"];
$subject = str_replace("{{storefront-url}}", $storefront_url, $subject);
$body = str_replace("{{storefront-url}}", $storefront_url, $body);

$first_product_category_id = func_query_first_cell("SELECT categoryid FROM $sql_tbl[products_categories] WHERE main='Y' AND productid='".$products[0]["productid"]."'");
$first_product_category_url = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$first_product_category_id'");
$first_product_category_url = $storefront_url."/".$first_product_category_url."/";

$subject = str_replace("{{first-product-category-url}}", $first_product_category_url, $subject);
$body = str_replace("{{first-product-category-url}}", $first_product_category_url, $body);

$reorder_hash_url = $storefront_url."/cart.php?mode=add_to_cart&o=".text_crypt($orderid);
$subject = str_replace("{{reorder-hash-url}}", $reorder_hash_url, $subject);
$body = str_replace("{{reorder-hash-url}}", $reorder_hash_url, $body);

if (!empty($order["alt_products"])){
	$alt_products_table = func_alt_products_table($order["alt_products"]);
	$body = str_replace("{{alt-items}}", $alt_products_table, $body);
}
###

$signature = func_get_signature(false, $products);
$body = str_replace("{{signature}}", $signature, $body);

$firstname = trim($userinfo["firstname"]);
$c_firstname_arr = explode(" ", $firstname);
$c_firstname = array_pop($c_firstname_arr);
//$body = str_replace("{{userfirstname}}", $c_firstname, $body);

$body = str_replace("{{userfirstname}}", $userfirstname, $body);
$body = str_replace("{{userfullname}}", $userfullname, $body);


##
$missing_information_replace_text = "";

if ($userinfo["phone"] == "(000) 000-0000" || strtolower($userinfo["email"]) == "unknown@unknown.com" || strtolower($userinfo["firstname"]) == "unknown"){
                $missing_information_replace_text .= "<B>Contact information</B><br />";

                if (strtolower($userinfo["firstname"]) == "unknown"){
                         $missing_information_replace_text .= "Full Name is missing<br />";
                }
                if ($userinfo["phone"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Phone number is missing<br />";
                }
                if (strtolower($userinfo["email"]) == "unknown@unknown.com"){
                         $missing_information_replace_text .= "Email address is missing<br />";
                }
}

if (strtolower($order["po_details"]["name_of_purchaser"]) == "unknown" || $order["po_details"]["purchase_manager_phone"] == "(000) 000-0000" || $order["po_details"]["po_fax"] == "(000) 000-0000" || strtolower($order["po_details"]["purchase_manager_email"]) == "unknown@unknown.com"){
                if (!empty($missing_information_replace_text)){
                        $missing_information_replace_text .= "<br />";
                }

                $missing_information_replace_text .= "<B>Purchase manager</B><br />";
                if (strtolower($order["po_details"]["name_of_purchaser"]) == "unknown"){
                        $missing_information_replace_text .= "Full name is missing<br />";
                }
                if ($order["po_details"]["purchase_manager_phone"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Phone number is missing<br />";
                }
                if ($order["po_details"]["po_fax"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Fax number is missing<br />";
                }
                if (strtolower($order["po_details"]["purchase_manager_email"]) == "unknown@unknown.com"){
                        $missing_information_replace_text .= "Email is missing<br />";
                }
}

if (strtolower($order["po_details"]["accounts_payable_full_name"]) == "unknown" || $order["po_details"]["accounts_payable_phone"] == "(000) 000-0000" || $order["po_details"]["accounts_payable_fax"] == "(000) 000-0000" || strtolower($order["po_details"]["accounts_payable_email"]) == "unknown@unknown.com"){
                if (!empty($missing_information_replace_text)){
                        $missing_information_replace_text .= "<br />";
                }

                $missing_information_replace_text .= "<B>Accounts payable</B><br />";
                if (strtolower($order["po_details"]["accounts_payable_full_name"]) == "unknown"){
                        $missing_information_replace_text .= "Full name is missing<br />";
                }
                if ($order["po_details"]["accounts_payable_phone"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Phone number is missing<br />";
                }
                if ($order["po_details"]["accounts_payable_fax"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Fax number is missing<br />";
                }
                if (strtolower($order["po_details"]["accounts_payable_email"]) == "unknown@unknown.com"){
                        $missing_information_replace_text .= "Email is missing<br />";
                }
}

$body = str_replace("{{missing_information}}", $missing_information_replace_text, $body);
$body = str_replace("{{po_number}}", $order["po_number"], $body);
##

$subject = str_replace("{{signature}}", $signature, $subject);
$subject = str_replace("{{userfirstname}}", $userfirstname, $subject);
$subject = str_replace("{{userfullname}}", $userfullname, $subject);


#
$shipping_original = 0;
foreach ($order["shipping_groups"] as $mid => $v){
	$shipping_original += $v["shipping_cost"]["net"];
}
$shipping_original = price_format($shipping_original);

$shipping_on_po = $order["total_shipping_charge_on_orig_po"];
$shipping_difference = $shipping_original - $shipping_on_po;
$shipping_difference = price_format($shipping_difference);

$subject = str_replace("{{shipping_original}}", $shipping_original, $subject);
$subject = str_replace("{{shipping_on_po}}", $shipping_on_po, $subject);
$subject = str_replace("{{shipping_difference}}", $shipping_difference, $subject);

$body = str_replace("{{shipping_original}}", $shipping_original, $body);
$body = str_replace("{{shipping_on_po}}", $shipping_on_po, $body);
$body = str_replace("{{shipping_difference}}", $shipping_difference, $body);
#

###


if ($debug == "Y"){
func_print_r($body);
}



$additional_tag_status = func_query_first_cell("SELECT status_id FROM $sql_tbl[templates_for_communication] WHERE id='$template_id'");
if (!empty($additional_tag_status)){
	$status_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='$additional_tag_status'");
	$smarty->assign("status_name", $status_name);
}

//func_print_r($additional_tag_status, $status_name);


//func_print_r($products, $storefronts, $first_product_category_id, $first_product_category_url );
###

//if ($department == "customer" || $department == "distributor"){
	$from = "orders@s3stores.com";
//} else {
//	$from = "custserv@s3stores.com";
//}

$smarty->assign("to", $to);
$smarty->assign("subject", $subject);
$smarty->assign("body", $body);
$smarty->assign("from", $from);
$smarty->assign("attach_pdf_invoice", $attach_pdf_invoice);

$smarty->assign("department_name", $department_arr[$department]);
$smarty->assign("department_info", $department_info);

$smarty->assign("department", $department);
$smarty->assign("template_id", $template_id);
$smarty->assign("manufacturerid", $manufacturerid);
$smarty->assign("mid_templateid", $mid_templateid);

$smarty->assign("products", $products);
$smarty->assign("giftcerts", $giftcerts);
$smarty->assign("userinfo", $userinfo);
$smarty->assign("order", $order);

$smarty->assign("main","compose_message");

$location[2][1] = "order.php?orderid=".$orderid;
$location[3][0] = "Compose message";

$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
