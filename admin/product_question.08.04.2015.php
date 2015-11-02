<?php
@set_time_limit(0);

define('USE_TRUSTED_POST_VARIABLES',1);
define('USE_TRUSTED_SCRIPT_VARS',1);
$trusted_post_variables = array("question", "answer", "product_question_message_body_to_distr", "product_question_subject_line_to_distr", "product_answer_message_body", "product_answer_subject_line");

require "./auth.php";
require $xcart_dir."/include/security.php";
require $xcart_dir."/include/countries.php";
require $xcart_dir."/include/states.php";

x_session_register("search_data");

$id = isset($id) ? abs(intval($id)) : 0;

if (empty($id)){
	func_header_location("product_question_search.php");
}

x_load("product", "order");

$location[] = array("Product question search", "product_question_search.php?mode=search&page=".$search_data["product_question_search"]["page"]);
$location[] = array("Product question", "");

if ($REQUEST_METHOD == "POST") {

    if ($mode == "update"){

	$tmp_answer = trim(strip_tags($answer));
	
	if (empty($tmp_answer) || $tmp_answer == "&nbsp;"){
		$answer='';
	}

	$query_data["status"] = $status;
	$query_data["question"] = $question;
	$query_data["answer"] = $answer;
	$query_data["phone"] = $phone;
	$query_data["email"] = $email;
	$query_data["name"] = $name;
	$query_data["company"] = $company;
	$query_data["address"] = $address;
	$query_data["address2"] = $address2;
	$query_data["city"] = $city;
	$query_data["country"] = $country;
	$query_data["state"] = $state;
	$query_data["zipcode"] = $zipcode;

	func_array2update("product_question", $query_data, "id = '$id'");

//func_print_r($status);
//die();

    }
    elseif ($mode == "send_question_to_distr_brand"){

//        $to = "xcartmaster@gmail.com";  ///////////////////

	$product_question_message_body_to_distr = stripslashes($product_question_message_body_to_distr);

	$product_question_id = sprintf('%1$05d', $id);
	$subject = "PRQN-".$product_question_id.": ".$product_question_subject_line_to_distr;

	$mail_smarty->assign("subject", $subject);
	$mail_smarty->assign("body", $product_question_message_body_to_distr);

	func_send_mail($to, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true);
	func_send_mail($config["product_question_email"]["product_question_bc_email"], 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true);

	$query_data["status"] = "question_sent_to_distr_brand";
	func_array2update("product_question", $query_data, "id = '$id'");
    }
    elseif ($mode == "send_answer_to_customer"){

//        $to = "xcartmaster@gmail.com"; ////////////////////

        $product_answer_message_body = stripslashes($product_answer_message_body);

	$product_question_id = sprintf('%1$05d', $id);
	$subject = "PRQN-".$product_question_id.": ".$product_answer_subject_line;

        $mail_smarty->assign("subject", $subject);
        $mail_smarty->assign("body", $product_answer_message_body);

        func_send_mail($to, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true);
        func_send_mail($config["product_question_email"]["product_question_bc_email"], 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true);

        $query_data["status"] = "answer_sent_to_cust";
        func_array2update("product_question", $query_data, "id = '$id'");
    }
    elseif ($mode == "generate_queued_order"){

	$products_to_add = array();
	$shipping_groups_arr = array();
	$total_price = 0;

	if (empty($add_products)){
            $top_message = array(
                "content" => "Empty field. Not generated"
            );
	}
	else {
		$add_products_arr = explode(";", $add_products);
		foreach ($add_products_arr as $k => $product_qty){
			if (strpos($product_qty, '=') !== false){
				$product_qty_arr = explode("=", $product_qty);
				$productcode = trim($product_qty_arr[0]);
				$amount = trim($product_qty_arr[1]);
				$amount = abs(intval($amount));

				if (!empty($productcode) && !empty($amount)){
					$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='$productcode'");

					if (!empty($productid)){
						$product_information = func_select_product($productid, 0, false);

						if (!empty($product_information)){
							$products_to_add[$k] = $product_information;
							$products_to_add[$k]["amount"] = $amount;

							if (!isset($shipping_groups_arr[$product_information["manufacturerid"]]["sub_total"])){
								$shipping_groups_arr[$product_information["manufacturerid"]]["sub_total"] = 0;
							}

							$sub_total = $amount*$product_information["price"];
							$shipping_groups_arr[$product_information["manufacturerid"]]["sub_total"] += $sub_total;

							$total_price += $sub_total;
						}
					}
				}
			}
		}
	}

	if (!empty($products_to_add) && !empty($shipping_groups_arr)){

		$products_to_add = array_values($products_to_add);

		$storefrontid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='".$products_to_add[0]["productid"]."'");
		$product_storefront_info = func_get_storefront_info($storefrontid);

//func_print_r($products_to_add, $storefrontid, $product_storefront_info, $email, $total_price, $shipping_groups_arr);
//die();

                $insert_data = array (
                        'order_prefix' => $product_storefront_info["prefix"],
                        'login' => "pq".time(),
                        'total' => $total_price,
                        'subtotal' => $total_price,
                        'date' => time(),
                        'cb_status' => 'Q',
                        'dc_status' => 'T',
                        'bd_status' => 'W',
//                        'notes' => $email,
                        'customer_notes' => $email,
                        'firstname' => $name,
                        'company' => $company,
                        'b_firstname' => $name,
                        'b_address' => $address."\r\n".$address2,
                        'b_city' => $city,
                        'b_state' => $state,
                        'b_country' => $country,
                        'b_zipcode' => $zipcode,
                        's_firstname' => $name,
                        's_address' => $address."\r\n".$address2,
                        's_city' => $city,
                        's_state' => $state,
                        's_country' => $country,
                        's_zipcode' => $zipcode,
                        'phone' => $phone,
                        'email' => "helpdesk@s3stores.com",
                        'storefrontid' => $storefrontid,
                        'product_question_status_id' => $id,
                );

		$new_orderid = func_array2insert('orders', $insert_data);

		foreach ($shipping_groups_arr as $k => $v){

                                $insert_data2 = array (
                                        'orderid' => $new_orderid,
                                        'manufacturerid' => $k,
                                        'cb_status' => 'Q',
                                        'dc_status' => 'T',
                                        'bd_status' => 'W',
                                        'total_net' => $v['sub_total'],
                                        'total_gst' => $v['sub_total'],
                                        'total_pst' => $v['sub_total'],
                                        'total_gross' => $v['sub_total'],
                                );

                                func_array2insert('order_groups', $insert_data2);
                                unset($insert_data2);
		}

		foreach ($products_to_add as $k => $v){

                                $insert_data3 = array (
                                        'orderid' => $new_orderid,
                                        'productid' => $v['productid'],
                                        'price' => $v['price'],
                                        'amount' => $v['amount'],
                                        'provider' => $v['provider'],
                                        'productcode' => $v['productcode'],
                                        'product' => addslashes($v['product']),
                                        'original_provider' => $v['original_provider']
                                );

                                if (!empty($v['item_cost_to_us'])){
                                        $insert_data3['item_cost_to_us'] = $v['item_cost_to_us'];
                                }

                                func_array2insert('order_details', $insert_data3);
                                unset($insert_data3);
		}


                $query_data["status"] = "closed";
                func_array2update("product_question", $query_data, "id = '$id'");

                $top_message = array(
                	"content" => "Done. New <a href='order.php?orderid=$new_orderid' target='_blank'>Order # ".$product_storefront_info["prefix"].$new_orderid."</a>"
                );
	}
    } // elseif ($mode == "generate_queued_order")
    elseif ($mode == "transfer"){
                $query_data["answered_on_page"] = "Y";
                func_array2update("product_question", $query_data, "id = '$id'");
    }
    elseif ($mode == "transfer_and_publish"){
                $query_data["answered_on_page"] = "Y";
                $query_data["question_published_on_page"] = "Y";
                func_array2update("product_question", $query_data, "id = '$id'");
    }

    if (empty($top_message)){
	    $top_message = array(
                "content" => "Done."
	    );
    }

    func_header_location("product_question.php?id=$id");
}

$product_question = func_query_first("SELECT * FROM $sql_tbl[product_question] WHERE id='$id'");
$product_question_id = sprintf('%1$05d', $id);

$use_current_storefront = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$product_question[productid]'");
$product_info = func_select_product($product_question["productid"], 0, false, false, false, false, $use_current_storefront);

$product_info["distributor_email"] = func_query_first_cell("SELECT d_product_questions_send_to_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
$product_info["brand_email"] = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");
$product_info["brand"] = func_query_first_cell("SELECT brand FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");
$product_info["customer_service_phone"] = func_query_first_cell("SELECT customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");

$product_question_subject_line_to_distr = $config["product_question_email"]["product_question_subject_line_to_distr"];
$product_question_message_body_to_distr = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_distr"]));

$product_question_subject_line_to_distr = str_replace("{{mpn}}", $product_info["mpn"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{productname}}", $product_info["product"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{brand_email}}", $product_info["brand_email"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{question}}", $product_question["question"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{customer_phone}}", $product_question["phone"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{product_link}}", $product_info["customer_url"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{customer_email}}", $product_question["email"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{product_answer}}", $product_question["answer"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{prqnid}}", $product_question_id, $product_question_subject_line_to_distr);

$product_question_message_body_to_distr = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{brand_email}}", $product_info["brand_email"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{question}}", $product_question["question"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{customer_phone}}", $product_question["phone"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{product_link}}", $product_info["customer_url"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{customer_email}}", $product_question["email"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{product_answer}}", $product_question["answer"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{prqnid}}", $product_question_id, $product_question_message_body_to_distr);

$product_answer_subject_line = $config["product_question_email"]["product_answer_subject_line"];
$product_answer_message_body = func_eol2br(stripslashes($config["product_question_email"]["product_answer_message_body"]));

$product_answer_subject_line = str_replace("{{mpn}}", $product_info["mpn"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{productname}}", $product_info["product"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{brand_email}}", $product_info["brand_email"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{question}}", $product_question["question"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{customer_phone}}", $product_question["phone"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{product_link}}", $product_info["customer_url"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{customer_email}}", $product_question["email"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{product_answer}}", $product_question["answer"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{prqnid}}", $product_question_id, $product_answer_subject_line);

$product_answer_message_body = str_replace("{{mpn}}", $product_info["mpn"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{productname}}", $product_info["product"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{brand_email}}", $product_info["brand_email"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{question}}", $product_question["question"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{customer_phone}}", $product_question["phone"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{product_link}}", $product_info["customer_url"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{customer_email}}", $product_question["email"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{product_answer}}", $product_question["answer"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{prqnid}}", $product_question_id, $product_answer_message_body);

$product_info["product_answer_subject_line"] = $product_answer_subject_line;
$product_info["product_answer_message_body"] = $product_answer_message_body;
$product_info["product_question_subject_line_to_distr"] = $product_question_subject_line_to_distr;
$product_info["product_question_message_body_to_distr"] = $product_question_message_body_to_distr;

#
##
###
$url = "http://helpdesk.s3stores.com/otrs/index.pl";
$curl_err = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
$output = curl_exec($ch);

if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
        $curl_err = true;
}
curl_close($ch);

if (!$curl_err){
        require "./gi-find.php";
}
###
##
#

//func_print_r($product_info);

$smarty->assign("product_info", $product_info);
$smarty->assign("product_question", $product_question);
$smarty->assign("mode", $mode);
$smarty->assign("main", "product_question");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
