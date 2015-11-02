<?php
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array('subject', 'body');

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('mail','order');

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

if ($department == "distributor" && !empty($mid_templateid)){
        $mid_templateid_arr = explode("-", $mid_templateid);
        $manufacturerid = $mid_templateid_arr[0];
        $template_id = $mid_templateid_arr[1];
}

if (($REQUEST_METHOD == "POST") && ($mode == "send_message")) {

	$body = func_eol2br(stripslashes($body));

	$mail_smarty->assign('body', $body);
	$mail_smarty->assign('subject', $subject);

	func_send_mail($to, "mail/compose_message_subj.tpl", "mail/compose_message.tpl", $from, false);

	if ($department == "third_party"){
		func_send_mail("helpdesk@s3stores.com", "mail/compose_message_subj.tpl", "mail/compose_message.tpl", $from, false);
	}

#
##
###
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
###
##
#


        $top_message = array(
                "content" => "EMAIL SENT. OK.",
                "type" => "I"
        );

	func_header_location('compose_message.php?orderid='.$orderid.'&department='.$department.($department == "distributor" ? '&mid_templateid='.$mid_templateid : '&template_id='.$template_id)."&sent=Y");
}

#
# Collect infos about ordered products
#
require $xcart_dir."/include/history_order.php";

$order = $order_data["order"];
$userinfo = $order_data["userinfo"];
$products = $order_data["products"];
$giftcerts = $order_data["giftcerts"];

/*
if ($department == "distributor" && !empty($mid_templateid)){
        $mid_templateid_arr = explode("-", $mid_templateid);
        $manufacturerid = $mid_templateid_arr[0];
        $template_id = $mid_templateid_arr[1];
}
*/

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
                        	$all__items_table__ .= $v["manufacturer"] . "<br />";
                	        $all__items_table__ .= $v["__items_table__"] . "<br />";
        	        }

	                if (!empty($v["__shipto_table__"])){
//                        	$all__shipto_table__ .= $v["manufacturer"] . "<br />";
                	        $all__shipto_table__ .= $v["__shipto_table__"] . "<br />";
        	        }
	        }

		$shipfrom = implode("/", $shipfrom_arr);
	}

        foreach ($department_info as $k => $v){
                if ($template_id == $v["id"]){

                        $subject = $v["subject_line"];
			$body = $v["message_body"];
			$to = "";

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
                        $body = str_replace("{{userfullname}}", $cidev_firstname, $body);

                        $subject = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $subject);
                        $subject = str_replace("{{c-fullname}}", $userinfo["firstname"], $subject);
                        $subject = str_replace("{{userfullname}}", $cidev_firstname, $subject);

                }
        }

	$instock_and_outofstock_items_table = func_instock_and_outofstock_items_table($products);
	$cidev_instock_items_table = $instock_and_outofstock_items_table["instock"];
	$cidev_outofstock_items_table = $instock_and_outofstock_items_table["outofstock"];
	
        $body = str_replace("{{instock}}", $cidev_instock_items_table, $body);
        $body = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $body);
        $body = str_replace("{{shipfrom}}", $shipfrom, $body);

        $subject = str_replace("{{instock}}", $cidev_instock_items_table, $subject);
        $subject = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $subject);
        $subject = str_replace("{{shipfrom}}", $shipfrom, $subject);
}

$subject = str_replace("{{shiptocountry}}", $userinfo["s_countryname"], $subject);
$body = str_replace("{{shiptocountry}}", $userinfo["s_countryname"], $body);

//if ($department == "customer" || $department == "distributor"){
	$from = "orders@s3stores.com";
//} else {
//	$from = "custserv@s3stores.com";
//}

$smarty->assign("to", $to);
$smarty->assign("subject", $subject);
$smarty->assign("body", $body);
$smarty->assign("from", $from);

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
