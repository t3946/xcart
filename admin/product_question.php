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

    $tab = "";

    if ($mode == "del_additional_tag" && !empty($del_additional_tag)){

	if ($del_additional_tag == "new_otrs_email"){
		db_query("UPDATE $sql_tbl[product_question] SET new_otrs_email='N' WHERE id='$id'");
	}
    }
    elseif ($mode == "update_customer_info"){

	$name = trim($name);

        $query_data["phone"] = trim($phone);
        $query_data["email"] = trim($email);
        $query_data["name"] = $name;
        $query_data["company"] = trim($company);
        $query_data["address"] = trim($address);
        $query_data["address2"] = trim($address2);
        $query_data["city"] = trim($city);
        $query_data["country"] = trim($country);
        $query_data["state"] = trim($state);
        $query_data["zipcode"] = trim($zipcode);

	if (!empty($name)){
		$firstname = func_query_first_cell("SELECT firstname FROM $sql_tbl[product_question] WHERE id = '$id'");
		if (empty($firstname)){
			$name_arr = explode(" ", $name);
			$query_data["firstname"] = array_shift($name_arr);
		}
	}

        func_array2update("product_question", $query_data, "id = '$id'");

	$tab = "question_tabs-info";

    }
    elseif ($mode == "update"){

	$tmp_answer = trim(strip_tags($answer));
	
	if (empty($tmp_answer) || $tmp_answer == "&nbsp;"){
		$answer='';
	}

	$query_data["firstname"] = $firstname;
	$query_data["status"] = $status;
	$query_data["question"] = $question;
	$query_data["answer"] = $answer;

	$current_answer_in_db = func_query_first_cell("SELECT answer FROM $sql_tbl[product_question] WHERE id = '$id'");
	if (!empty($answer) && empty($current_answer_in_db)){
		$query_data["login"] = $login;
	}

	func_array2update("product_question", $query_data, "id = '$id'");

    }
    elseif ($mode == "send_question_to_distr_brand"){

//        $to = "xcartmaster@gmail.com";  ///////////////////

	$product_question_message_body_to_distr = stripslashes($product_question_message_body_to_distr);

//	$prefix_product_question_id = "PRQN-".sprintf('%1$05d', $id);
	$subject = $product_question_subject_line_to_distr;
//	$subject = str_replace("{{prqnid}}", $prefix_product_question_id, $subject);


	$to_email = $email_to_arr[$to];
	$distributor_or_brand_contact_name = $name_to_arr[$to];

	$subject = str_replace("{{distributor_or_brand_contact_name}}", $distributor_or_brand_contact_name, $subject);
	$product_question_message_body_to_distr = str_replace("{{distributor_or_brand_contact_name}}", $distributor_or_brand_contact_name, $product_question_message_body_to_distr);

//func_print_r($subject, $product_question_message_body_to_distr, $_POST, $to_email, $distributor_or_brand_contact_name);
//die("asds");


	$mail_smarty->assign("subject", $subject);
	$mail_smarty->assign("body", $product_question_message_body_to_distr);

	func_send_mail($to_email, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true, false, false, false,'','N',false,false);
	func_send_mail($config["product_question_email"]["product_question_bc_email"], 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true, false, false, false,'','N',false,false);

	$query_data["status"] = "question_sent_to_distr_brand";
	func_array2update("product_question", $query_data, "id = '$id'");

	$tab = "question_tabs-question";
    }
    elseif ($mode == "send_answer_to_customer"){

	$tab = "question_tabs-info";

//        $to = "xcartmaster@gmail.com"; ////////////////////

        $product_answer_message_body = stripslashes($product_answer_message_body);

//	$prefix_product_question_id = "PRQN-".sprintf('%1$05d', $id);
	$subject = $product_answer_subject_line;
//	$subject = str_replace("{{prqnid}}", $prefix_product_question_id, $subject);

        $mail_smarty->assign("subject", $subject);
        $mail_smarty->assign("body", $product_answer_message_body);

        func_send_mail($to_send, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, false, false, false, false,'','Y',false,false);

        func_send_mail($config["product_question_email"]["product_question_bc_email"], 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true, false, false, false,'','Y',false,false);

        $query_data["status"] = "answer_sent_to_cust";
        func_array2update("product_question", $query_data, "id = '$id'");

	$tab = "question_tabs-answer";
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
//                $query_data["login"] = $login;

#                $query_data["answered_date"] = time();
                func_array2update("product_question", $query_data, "id = '$id'");
    }
    elseif ($mode == "transfer_and_publish"){
                $query_data["answered_on_page"] = "Y";
                $query_data["question_published_on_page"] = "Y";
//                $query_data["login"] = $login;


#                $query_data["answered_date"] = time();


                func_array2update("product_question", $query_data, "id = '$id'");
    }

    if (empty($top_message)){
	    $top_message = array(
                "content" => "Done."
	    );
    }

    if ($mode == "transfer" || $mode == "transfer_and_publish"){

	    $productid = func_query_first_cell("SELECT productid FROM $sql_tbl[product_question] WHERE id = '$id';");

	    func_header_location("product_modify.php?productid=".$productid."#Product_questions");
    }
    else {
	    func_header_location("product_question.php?id=".$id."&tab=y#".$tab);
    }
}

$product_question = func_query_first("SELECT * FROM $sql_tbl[product_question] WHERE id='$id'");
$prefix_product_question_id = "PRQN-".sprintf('%1$05d', $id);

$use_current_storefront = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$product_question[productid]'");
$product_info = func_select_product($product_question["productid"], 0, false, false, false, false, $use_current_storefront);


//$some_distributor_info = func_query_first("SELECT d_product_questions_send_to_email, d_product_questions_send_to_name FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
//$product_info["distributor_email"] = $some_distributor_info["d_product_questions_send_to_email"];
//$product_info["distributor_send_to_name"] = $some_distributor_info["d_product_questions_send_to_name"];

$some_brand_info = func_query_first("SELECT customer_service_name, customer_service_email, brand, customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");

$product_info["brand_email"] = $some_brand_info["customer_service_email"];
$product_info["brand_customer_service_name"] = $some_brand_info["customer_service_name"];
$product_info["brand"] = $some_brand_info["brand"];
$product_info["customer_service_phone"] = $some_brand_info["customer_service_phone"];


#
##
$distributor_info = func_query_first("SELECT * FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");

$request_availability_options = func_query("SELECT * FROM $sql_tbl[request_availability_options]");

$tmp_cur_time_sec = time();
$d_server_min_distributor_time_sec = $distributor_info["d_server_min_distributor_time"] * 60 *60;
$tmp_cur_time_sec -= $d_server_min_distributor_time_sec;
$distributor_info["distributor_time"] = $tmp_cur_time_sec;
$tmp_cur_time_date_format = date("G.i", $tmp_cur_time_sec);
$tmp_date_mm_dd_yyyy = date("m/d/Y", $tmp_cur_time_sec);
// $tmp_cur_time_sec += 2*24*60*60; // for checking
$tmp_number_of_day_of_week = date("w", $tmp_cur_time_sec); // 0 (for Sunday) through 6 (for Saturday)
// func_print_r($tmp_number_of_day_of_week, $tmp_cur_time_date_format); // for checking

if ($tmp_cur_time_date_format >= "8.30" && $tmp_cur_time_date_format <= "16.30" && ($tmp_number_of_day_of_week != "0" && $tmp_number_of_day_of_week != "6")){

	if (!empty($request_availability_options) && is_array($request_availability_options)){
		foreach ($request_availability_options as $k_r => $v_r){
			if ($v_r["date_mm_dd_yyyy"] == $tmp_date_mm_dd_yyyy && $v_r["active"] == "Y"){
				$good_time_to_send_email_to_distributor = "N";
			}
		}
	}

	if ($good_time_to_send_email_to_distributor != "N"){
		$good_time_to_send_email_to_distributor = "Y";
	}

        $distributor_info["good_time_to_send_email_to_distributor"] = $good_time_to_send_email_to_distributor;
} else {
	$distributor_info["good_time_to_send_email_to_distributor"] = "N";
}

$distributor_info["distributor_phone"] = func_query_first_cell("SELECT phone FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$product_info[manufacturerid]' AND distributor_field_code='1'");

$phone_normalized = preg_replace("/[^0-9]/S","", $distributor_info["distributor_phone"]);

if (strlen($phone_normalized) == "10"){
	$distributor_info["distributor_phone_phone_normalized"] = "+1".$phone_normalized;
}



$tmp_cur_time_sec = time();
if (!empty($product_question["state"])){
	$est_time_offset = func_query_first_cell("SELECT est_time_offset FROM $sql_tbl[states] WHERE code='$product_question[state]' AND country_code='$product_question[country]'");
} else {
	$est_time_offset = 0;
}
$est_time_offset = $est_time_offset * 60 *60;
$tmp_cur_time_sec -= $est_time_offset;
$product_question["customer_time"] = $tmp_cur_time_sec;
$tmp_cur_time_date_format = date("G.i", $tmp_cur_time_sec);
$tmp_date_mm_dd_yyyy = date("m/d/Y", $tmp_cur_time_sec);
// $tmp_cur_time_sec += 2*24*60*60; // for checking
$tmp_number_of_day_of_week = date("w", $tmp_cur_time_sec); // 0 (for Sunday) through 6 (for Saturday)
// func_print_r($tmp_number_of_day_of_week, $tmp_cur_time_date_format); // for checking

if ($tmp_cur_time_date_format >= "8.30" && $tmp_cur_time_date_format <= "16.30" && ($tmp_number_of_day_of_week != "0" && $tmp_number_of_day_of_week != "6")){
                if (!empty($request_availability_options) && is_array($request_availability_options)){
                        foreach ($request_availability_options as $k_r => $v_r){
                                if ($v_r["date_mm_dd_yyyy"] == $tmp_date_mm_dd_yyyy && $v_r["active"] == "Y"){
                                        $good_time_to_send_email_to_customer = "N";
                                }
                        }
                }

                if ($good_time_to_send_email_to_customer != "N"){
                        $good_time_to_send_email_to_customer = "Y";
                }

                $product_question["good_time_to_send_email_to_customer"] = $good_time_to_send_email_to_customer;
} else {
                $product_question["good_time_to_send_email_to_customer"] = "N";
}
##
#


$customer_name = $product_question["firstname"];

if (!empty($product_question["name"])){
	$customer_name = $product_question["name"];
}

if (empty($product_question["firstname"])){
	$customer_name = "Sir/Madam";
}

$signature = func_get_signature($use_current_storefront);

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
$product_question_subject_line_to_distr = str_replace("{{customer_name}}", $customer_name, $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{product_answer}}", $product_question["answer"], $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{prqnid}}", $prefix_product_question_id, $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{signature}}", $signature, $product_question_subject_line_to_distr);
$product_question_subject_line_to_distr = str_replace("{{userfullname}}", $userfullname, $product_question_subject_line_to_distr);

$product_question_message_body_to_distr = str_replace("{{mpn}}", $product_info["mpn"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{brand_email}}", $product_info["brand_email"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{question}}", $product_question["question"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{customer_phone}}", $product_question["phone"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{product_link}}", $product_info["customer_url"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{customer_email}}", $product_question["email"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{customer_name}}", $customer_name, $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{product_answer}}", $product_question["answer"], $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{prqnid}}", $prefix_product_question_id, $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{signature}}", $signature, $product_question_message_body_to_distr);
$product_question_message_body_to_distr = str_replace("{{userfullname}}", $userfullname, $product_question_message_body_to_distr);

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
$product_answer_subject_line = str_replace("{{customer_name}}", $customer_name, $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{product_answer}}", $product_question["answer"], $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{prqnid}}", $prefix_product_question_id, $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{signature}}", $signature, $product_answer_subject_line);
$product_answer_subject_line = str_replace("{{userfullname}}", $userfullname, $product_answer_subject_line);

$product_answer_message_body = str_replace("{{mpn}}", $product_info["mpn"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{productname}}", $product_info["product"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{brand_email}}", $product_info["brand_email"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{brand_phone}}", $product_info["customer_service_phone"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{question}}", $product_question["question"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{customer_phone}}", $product_question["phone"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{product_link}}", $product_info["customer_url"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{customer_email}}", $product_question["email"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{customer_name}}", $customer_name, $product_answer_message_body);
$product_answer_message_body = str_replace("{{product_answer}}", $product_question["answer"], $product_answer_message_body);
$product_answer_message_body = str_replace("{{prqnid}}", $prefix_product_question_id, $product_answer_message_body);
$product_answer_message_body = str_replace("{{signature}}", $signature, $product_answer_message_body);
$product_answer_message_body = str_replace("{{userfullname}}", $userfullname, $product_answer_message_body);

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
$smarty->assign("distributor_info", $distributor_info);
$smarty->assign("product_question", $product_question);
$smarty->assign("mode", $mode);
$smarty->assign("main", "product_question");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
