<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == "POST" && !empty($orderid) && in_array($mode, array("authorize","void_transaction","capture_transaction","re_authorize_transaction"))){

    $log = "";

    $Access_Token = func_paypal_get_access_token();

    if (empty($Access_Token)){
	$log .= "'Access_Token' - failed <br />";
    } else {
	if (!empty($transaction_logs_id)){
		$transaction_info = func_query_first("SELECT * FROM $sql_tbl[transaction_logs] WHERE id='$transaction_logs_id'");
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
				$log .= "<br />Transaction:".$transaction_id;

				$transaction_status = $result["transactions"][0]["related_resources"][0]["authorization"]["state"];
				$transaction_currency = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["currency"];
				$transaction_total = $result["transactions"][0]["related_resources"][0]["authorization"]["amount"]["total"];
				
//				db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");

			}
			else {
				$log .= "<br />Failed. Empty transaction id";
			}
		} else {
			$log .= "<br />Failed. http_code: ".$result["curl_getinfo"]["http_code"];	
		}
	} // if (!empty($Access_Token))
    } // if ($mode == "authorize")
    elseif ($mode == "void_transaction" && !empty($transaction_id)){

	$log .= "'Void selected authorized transaction' at 'Virtual Terminal'";

	if (!empty($Access_Token)){
		$result = func_paypal_void($Access_Token, $transaction_id);

		$transaction_status = $result["state"];
		$transaction_currency = $result["amount"]["currency"];
		$transaction_total = $result["amount"]["total"];

//		db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");
	}
    }
    elseif ($mode == "capture_transaction" && !empty($transaction_id) && !empty($transaction_logs_id)){

        $log .= "'Capture selected authorized transaction' at 'Virtual Terminal'";

        if (!empty($Access_Token)){

	        $data_arr["amount"]["currency"] = $transaction_info["transaction_currency"];
        	$data_arr["amount"]["total"] = $transaction_info["transaction_total"];
	        $data_arr["is_final_capture"] = false; // true

		$result = func_paypal_capture($Access_Token, $transaction_id, $data_arr);

		if ($result["name"] == "AUTHORIZATION_ALREADY_COMPLETED" || $result["name"] == "CAPTURE_AMOUNT_LIMIT_EXCEEDED"){
			$log .= "<br />".$result["name"];
		}
		else {
			$log .= "<br />Transaction: ".$transaction_id." -> ".$result["id"];

			$transaction_id = $result["id"];

	                $transaction_status = $result["state"];
        	        $transaction_currency = $result["amount"]["currency"];
                	$transaction_total = $result["amount"]["total"];

//	                db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");
		}
        }
    }
    elseif ($mode == "re_authorize_transaction" && !empty($transaction_id) && !empty($transaction_logs_id) && !empty($re_authorize_amount)){
	
	$log .= "'RE-authorize selected transaction' at 'Virtual Terminal'";

	if (!empty($Access_Token)){
	        $data_arr["amount"]["total"] = $re_authorize_amount;
        	$data_arr["amount"]["currency"] = $transaction_info["transaction_currency"];

		$result = func_paypal_reauthorize($Access_Token, $Authorization_Id, $data_arr);

		if ($result["state"] == "authorized"){
                        $transaction_status = $result["state"];
                        $transaction_currency = $result["amount"]["currency"];
                        $transaction_total = $result["amount"]["total"];

//                        db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login')");
		}
	}
    }

    $result["xcart_log"] = $log;
    $serialize_result = serialize($result);

    db_query("INSERT INTO $sql_tbl[transaction_logs] (orderid, paymentid, transaction_id, transaction_status, transaction_currency, transaction_total, date, login, transaction_log) VALUE ('$orderid', '5', '$transaction_id', '$transaction_status', '$transaction_currency', '$transaction_total', '".time()."', '$login', '".addslashes($serialize_result)."')");

    func_log_order($orderid, 'PP', $log, $login);


//func_print_r($_POST, $result);
//die("123");

    func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-VT");
}
?>
