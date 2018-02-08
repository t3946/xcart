<?php
/*
define("CIDEV_CRON_START", "CRON");  // needed for assigning $_SERVER['HTTP_HOST'] && $_SERVER['REQUEST_URI'] if started via ./php5 command

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);
*/
require "./auth.php";

if (empty($login))
        func_header_location("error_message.php?antibot_error");

if (!empty($login))
        require $xcart_dir."/include/security.php";

x_load("order");

$Access_Token = func_paypal_get_access_token();

if (!empty($Access_Token)){

	$Authorization_Id = "2T498658BE109284C"; //TransID  # <-------------------- change it (!)

# Look up a payment
#############################################################################################

$transaction_type = "capture";

$result = func_paypal_look_up_payment($Access_Token, $Authorization_Id, $transaction_type);
#############################################################################################


# Capture an authorization
#############################################################################################
/*
#
## change it
###
	$data_arr["amount"]["currency"] = "USD";
	$data_arr["amount"]["total"] = "0.01";
	$data_arr["is_final_capture"] = true;
###
##
#
	$result = func_paypal_capture($Access_Token, $Authorization_Id, $data_arr);
*/

#############################################################################################


# Void an authorization
#############################################################################################
/*
	$result = func_paypal_void($Access_Token, $Authorization_Id);

*/
#############################################################################################

# Reauthorize a payment
#############################################################################################
/*
#
## change it
###
        $data_arr["amount"]["total"] = "0.03";
        $data_arr["amount"]["currency"] = "USD";
###
##
#
        $result = func_paypal_reauthorize($Access_Token, $Authorization_Id, $data_arr);
*/
#############################################################################################

/*
# Create a payment
#############################################################################################
$data_json = '{
        "intent":"authorize",
        "payer":{
                "payment_method":"credit_card",
                "funding_instruments":[
                        {
                                "credit_card":{
                                        "number":"5102623425456",
                                        "type":"mastercard",
                                        "expire_month":"08",
                                        "expire_year":"2016",
                                        "cvv2":"723",
                                        "first_name":"ELENA",
                                        "last_name":"SUSLOVA",
                                        "billing_address":{
                                                "line1":"2885 Sanford Ave SW",
                                                "line2":"#12717",
                                                "city":"Grandville",
                                                "state":"MI",
                                                "postal_code":"49418",
                                                "country_code":"US"
                                        }
                                }
                        }
                ],
                "payer_info":{
                        "email":"xcartmaster@gmail.com",
                        "first_name":"ELENA",
                        "last_name":"SUSLOVA",
                        "shipping_address":{
                                "recipient_name":"ELENA SUSLOVA",
                                "type":"residential",
                                "line1":"2885 Sanford Ave SW",
                                "line2":"#12717",
                                "city":"Grandville",
                                "state":"MI",
                                "postal_code":"49418",
                                "country_code":"US",
                                "phone":"6162595711"
                        }
                }
        },
        "transactions":[
                {
                        "amount":{
                                "total":"0.07",
                                "currency":"USD",
                                "details":{
                                        "subtotal":"0.07",
                                        "tax":"0.00",
                                        "shipping":"0.00"
                                }
                        },
                        "description":"This is the payment transaction description."
                }
        ]
}';


	$result = func_paypal_create_payment($Access_Token, $data_json);
#############################################################################################
*/

	func_print_r($result);

}

?>

