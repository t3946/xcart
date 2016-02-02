<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == "POST" && $mode == "authorize" && !empty($orderid)){

        $log = "'Authorize' at 'Authorization'";

	$Access_Token = func_paypal_get_access_token();

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
                		        "email":"'.$order["email"].'"
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



func_print_r($data_json);

//		$result = func_paypal_create_payment($Access_Token, $data_json);

		if (in_array($result["curl_getinfo"]["http_code"], array("200","201"))){

			$transaction_id = $result["transactions"][0]["related_resources"][0]["authorization"]["id"];

//Create new fields in xcart_order_logs and store values there

		} else {
			$log .= "<br />Failed. http_code: ".$result["curl_getinfo"]["http_code"];	
		}
		

	}
	else {
		$log .= "<br />'Access_Token' - failed";
	}

func_print_r($_POST, $result);
die("123");



        func_log_order($orderid, 'P', $log, $login);

        func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-VT");
}



?>
