<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array("authorize","void_transaction","capture_transaction","re_authorize_transaction", "refund_transaction", "self_transaction", "look_up_payment", "add_manual_transaction"))){

    $log = "";

    $Access_Token = func_paypal_get_access_token();

    if (empty($Access_Token)){
	$log .= "'Access_Token' - failed <br />";
    } else {
	if (!empty($order_transaction_id)){
		$transaction_info = func_query_first("SELECT * FROM $sql_tbl[order_transactions] WHERE id='$order_transaction_id'");
	}
    }


    if ($mode == "authorize"){

        $log .= "'Authorize' at 'Authorization'";

	if (!empty($Access_Token)){

		switch ($paypal_vt["card_number"]{0}){
			case '3':
				$credit_card_typy = "amex";
				break;
                        case '4':
                                $credit_card_typy = "visa";
                                break;
                        case '5':
                                $credit_card_typy = "mastercard";
                                break;
                        case '6':
                                $credit_card_typy = "discover";
                                break;
			default:
				$credit_card_typy = "";
		}

		$cardholderl_name = trim($paypal_vt["cardholderl_name"]);
		$cardholderl_name_arr = explode(" ", $cardholderl_name);
		$first_name = trim($cardholderl_name_arr[0]);
		unset($cardholderl_name_arr[0]);
		$last_name = implode(" ", $cardholderl_name_arr);
		$last_name = trim($last_name);

		$shipping_address_type = 'residential';
		if (!empty($order["extra"]["additional_fields"]) && is_array($order["extra"]["additional_fields"])){
			foreach ($order["extra"]["additional_fields"] as $k_ea => $v_ea){
				if (!empty($v_ea["value"]) && $v_ea["title"] == "Company" && $v_ea["section"] == "S"){
					$shipping_address_type = 'business';
				}
			}
		}

//func_print_r($order);
//die();

		$data_json = '{
		        "intent":"authorize",
		        "payer":{
                		"payment_method":"credit_card",
		                "funding_instruments":[
                		        {
                                		"credit_card":{
		                                        "number":"'.$paypal_vt["card_number"].'",
                		                        "type":"'.$credit_card_typy.'",
                                		        "expire_month":"'.$paypal_vt["expiration_month"].'",
		                                        "expire_year":"'.$paypal_vt["expiration_year"].'",
                		                        "cvv2":"'.$paypal_vt["csc"].'",
		                                        "first_name":"'.addslashes($first_name).'",
                		                        "last_name":"'.addslashes($last_name).'",
                                		        "billing_address":{
                                                		"line1":"'.addslashes($paypal_vt["b_address"]).'",
		                                                "line2":"'.addslashes($paypal_vt["b_address_2"]).'",
                		                                "city":"'.addslashes($paypal_vt["b_city"]).'",
                                		                "state":"'.addslashes($paypal_vt["b_state"]).'",
                                                		"postal_code":"'.addslashes($paypal_vt["b_zipcode"]).'",
		                                                "country_code":"'.addslashes($paypal_vt["b_country"]).'"
                		                        }
                                		}
		                        }
                		],
		                "payer_info":{
                		        "email":"'.$order["email"].'",
		                        "first_name":"'.addslashes($first_name).'",
                		        "last_name":"'.addslashes($last_name).'",
		                        "shipping_address":{
                		                "recipient_name":"'.addslashes($order["s_firstname"]).'",
		                                "type":"'.$shipping_address_type.'",
                		                "line1":"'.addslashes($order["s_address"]).(!empty($order["s_address_2"])?" ".addslashes($order["s_address_2"]):"").'",
                		                "city":"'.addslashes($order["s_city"]).'",
		                                "state":"'.addslashes($order["s_state"]).'",
		                                "postal_code":"'.addslashes($order["s_zipcode"]).'",
                		                "country_code":"'.addslashes($order["s_country"]).'"
                		        }
		                }
		        },
		        "transactions":[
                		{
		                        "amount":{
                		                "total":"'.$paypal_vt["grand_total"].'",
		                                "currency":"'.$paypal_vt["currency"].'",
                		                "details":{
		                                        "subtotal":"'.$paypal_vt["grand_total"].'",
                		                        "tax":"0.00",
                                		        "shipping":"0.00"
		                                }
                		        },
					"invoice_number":"'.$order["order_prefix"].$order["orderid"].'",
		                        "description":""
		                }
		        ]
		}';

//func_print_r($data_json);
//die();

		$result = func_paypal_create_payment($Access_Token, $data_json);

		if (in_array($result["curl_getinfo"]["http_code"], array("200","201"))){

			$transaction_id = $result["transactions"][0]["related_resources"][0]["authorization"]["id"];

			if (!empty($transaction_id)){

#
##
		                $result2 = func_paypal_look_up_payment($Access_Token, $transaction_id, "authorization");
				if (!empty($result2["links"]) && is_array($result2["links"])){
					$result["links"] = $result2["links"];
				}
##
#


				$log .= "<br />Transaction:".$transaction_id;

				$transaction_status = $result["transactions"][0]["related_resources"][0]["authorization"]["state"];
				$transaction_currency = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["currency"];
				$transaction_total = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["total"];
				
//				db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");

				$count_valid_transactions = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[transaction_logs] WHERE transaction_status!='' AND transaction_id!='' AND orderid='$orderid'");

				if (empty($count_valid_transactions) && !empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){

					$new_cb_status_flag = false;

					$new_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='AP'");
					foreach ($order["shipping_groups"] as $ko => $vo){
						if (in_array($vo["cb_status"], array('Q','N'))){

							db_query("UPDATE $sql_tbl[order_groups] SET cb_status='AP' WHERE orderid='$orderid' AND manufacturerid='$ko'");
							$current_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='".$vo["cb_status"]."'");
							$log .= "<br /><B>".$vo["all_distributor_info"]["code"].":</B> cb_status: ". $current_cb_status_value . " -> ". $new_cb_status_value;
							$new_cb_status_flag = true;
						}
					}

					if ($new_cb_status_flag){
						func_send_order_status_notification($orderid, "AP");
					}
				}


			}
			else {
				$log .= "<br />Failed. Empty transaction id";
			}
		} else {
			$log .= "<br />Failed. http_code: ".$result["curl_getinfo"]["http_code"];	
		}
	} // if (!empty($Access_Token))
    } // if ($mode == "authorize")
    elseif ($mode == "void_transaction" && !empty($transaction_info["transaction_id"])){

	$log .= "'Void authorized transaction' at 'Virtual Terminal'";

	if (!empty($Access_Token)){
		$result = func_paypal_void($Access_Token, $transaction_info["transaction_id"]);

		$transaction_id = $result["id"];

		$transaction_status = $result["state"];
		$transaction_currency = $result["amount"]["currency"];
		$transaction_total = $result["amount"]["total"];
	}
    }
    elseif ($mode == "capture_transaction" && !empty($transaction_info["transaction_id"]) && !empty($transaction_amount[$order_transaction_id])){

        $log .= "'Capture authorized transaction' at 'Virtual Terminal'";

        if (!empty($Access_Token)){

	        $data_arr["amount"]["currency"] = $transaction_info["transaction_currency"];
//        	$data_arr["amount"]["total"] = $transaction_info["transaction_total"];
        	$data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
	        $data_arr["is_final_capture"] = false; // true

		$result = func_paypal_capture($Access_Token, $transaction_info["transaction_id"], $data_arr);

		if ($result["name"] == "AUTHORIZATION_ALREADY_COMPLETED" || $result["name"] == "CAPTURE_AMOUNT_LIMIT_EXCEEDED"){
			$log .= "<br />".$result["name"];
		}
		else {
			$log .= "<br />Transaction: ".$transaction_info["transaction_id"]." -> ".$result["id"];

			$transaction_id = $result["id"];

	                $transaction_status = $result["state"];
        	        $transaction_currency = $result["amount"]["currency"];
                	$transaction_total = $result["amount"]["total"];

			func_send_order_status_notification($orderid, "P");
		}
        }
    }
    elseif ($mode == "re_authorize_transaction" && !empty($transaction_info["transaction_id"]) && !empty($transaction_amount[$order_transaction_id])){
	
	$log .= "'RE-authorize transaction' at 'Virtual Terminal'";

	if (!empty($Access_Token)){
	        $data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
        	$data_arr["amount"]["currency"] = $transaction_info["transaction_currency"];

		$result = func_paypal_reauthorize($Access_Token, $transaction_info["transaction_id"], $data_arr);

		if ($result["state"] == "authorized"){

			$transaction_id = $result["id"];

                        $transaction_status = $result["state"];
                        $transaction_currency = $result["amount"]["currency"];
                        $transaction_total = $result["amount"]["total"];
		}
	}
    }
    elseif ($mode == "refund_transaction" && !empty($transaction_info["transaction_id"]) && !empty($transaction_amount[$order_transaction_id])){

        $log .= "'Refund transaction' at 'Virtual Terminal'";

        if (!empty($Access_Token)){
                $data_arr["amount"]["total"] = $transaction_amount[$order_transaction_id];
                $data_arr["amount"]["currency"] = $transaction_info["transaction_currency"];

                $result = func_paypal_refund($Access_Token, $transaction_info["transaction_id"], $data_arr);

//func_print_r($result);
//die();

                if (!empty($result["id"])){

                        $transaction_id = $result["id"];

                        if ($result["state"] == "completed"){
	                        $transaction_status = "refunded";
			} else {
				$transaction_status = $result["state"];
			}

                        $transaction_currency = $result["amount"]["currency"];
                        $transaction_total = $result["amount"]["total"];
                }
        }

    }
    elseif ($mode == "self_transaction" && !empty($transaction_info["transaction_id"])){

        $log .= "'Self transaction' at 'Virtual Terminal'";

	$transaction_status = $transaction_info["transaction_status"];

        if (!empty($Access_Token)){

# ???????????????????????????????????????????????????????????????????????????????????????

		if (!empty($result["id"])){
			$transaction_id = $result["id"];
		}
	}
    }
    elseif ($mode == "look_up_payment" && !empty($transaction_info["transaction_id"])){

        $log .= "'Look up payment (Get links)' at 'Virtual Terminal'";

	$transaction_status = $transaction_info["transaction_status"];

        if (!empty($Access_Token)){

		$transaction_type = "authorization";
		if (in_array(strtolower($transaction_status), array('completed','p'))){
			$transaction_type = "capture";
		}
		elseif (in_array(strtolower($transaction_status), array('refunded','refund'))){
			$transaction_type = "refund";
		}

                $result = func_paypal_look_up_payment($Access_Token, $transaction_info["transaction_id"], $transaction_type);

                if (!empty($result["id"])){

                        $transaction_id = $result["id"];
                }
        }
    }
    elseif ($mode == "add_manual_transaction"){

	if (empty($transaction_amount) || $transaction_amount <= 0 || empty($paymentid) || empty($transaction_id)){

	        $top_message = array(
        	        'type' => 'I',
	                'content' => func_get_langvar_by_name("lbl_manual_transaction_no_required_fileds")
	        );

	        $section_name_top_message = $top_message;
	        x_session_save("section_name_top_message");

		func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-VT");
	}

	if ($transaction_status == "authorized"){
		$transaction_type = "authorization";
	}
	else {
		$transaction_type = "capture";
	}

	$result = func_paypal_look_up_payment($Access_Token, $transaction_id, $transaction_type);

	$transaction_total = $transaction_amount;
	$result["FIELD_avs_code"] = $avs_code;
    }

    $log .= "'Add transaction' at 'Add manual transaction' section";

    $result["xcart_log"] = $log;
    $result["FIELD_transaction_id"] = $transaction_id;
    $result["FIELD_transaction_status"] = $transaction_status;
    $result["FIELD_transaction_currency"] = $transaction_currency;
    $result["FIELD_transaction_total"] = $transaction_total;

    $serialize_result = serialize($result);

    if (empty($paymentid)){
	$paymentid = "5";
    }

    db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login, transaction_log) VALUE ('$orderid', '$paymentid', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login', '".addslashes($serialize_result)."')");

//    func_log_order($orderid, 'PP', $log, $login);
    func_log_order($orderid, 'PP', $serialize_result, $login);

#
##
    if (!empty($transaction_id)){

	$order_transactions_data = array (
		"transaction_id" => $transaction_id,
		"transaction_response" => addslashes($serialize_result),
		"transaction_status" => $transaction_status,
		"login" => $login,
		"date" => time()
	);


	if ($mode == "authorize" || $mode == "add_manual_transaction"){

		$order_transactions_data["orderid"] = $orderid;
		$order_transactions_data["paymentid"] = $paymentid;
		$order_transactions_data["transaction_currency"] = $transaction_currency;
		$order_transactions_data["transaction_amount"] = $transaction_total;

		func_array2insert("order_transactions", $order_transactions_data);
	}
	else {

		if (in_array($mode, array("re_authorize_transaction", "capture_transaction", "refund_transaction"))){
			$order_transactions_data["transaction_amount"] = $transaction_amount[$order_transaction_id];
		}

		func_array2update("order_transactions", $order_transactions_data, "id='$order_transaction_id'");
	}

    }
##
#

//func_print_r($_POST);

    if (!($mode == "authorize" && $AJAX_SUBMIT == "Y")){
	func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-VT");
    }
}
?>
