<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: payment_ccmid.php,v 1.17.2.4 2007/01/05 13:20:34 max Exp $
#

use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;

if (!defined('XCART_START')) { header("Location: ../"); die("Access denied"); }

x_load('http','order','payment','tests');

if (!defined("GET_LANGUAGE")) {
	# Let's include language
	$current_area = "C";
	require($xcart_dir."/include/get_language.php");
}

#
# This code reports error and inserts order
#
/*
$bill_output["code"];		error code
$bill_output["billmes"];	bill reason
$bill_output["cvvmes"];		cvv info
$bill_output["avsmes"];		avs info
$bill_output["sessid"];		session id for restoring session in web-based payment processors
$weblink					if 2 - JS autoredirector; if 1 - use "<a href=...>Return to X-Cart</a>, else "header_location(...);"
*/

$log_payment_failure = false;
if (!empty($bill_output["sessid"])) {
	#
	# check the security
	#
	if (func_check_webinput()=="err") {
		$log_payment_failure = true;

		if ($bill_output["code"]==1) {
			$__transaction_status = "successful";
			$bill_output["code"] = 3;
		} elseif ($bill_output["code"]==3)
			$__transaction_status = "queued";
		else
			$__transaction_status = "declined";

		$bill_output['billmes'] = "Gateway reported of $__transaction_status transaction but it's response came from the IP that is not specified in the list of valid IPs: " . func_get_valid_ip($_SERVER['REMOTE_ADDR']) . "\n-- response ----\n" . $bill_output['billmes'];
	}
	#
	$sessurl = $XCART_SESSION_NAME."=".$bill_output["sessid"]."&";

	x_session_id($bill_output["sessid"]);
	x_session_register("cart");
	x_session_register("secure_oid");
	$orderids = $secure_oid;
}
else $sessurl = "";

$bill_error = $reason = ""; $fatal = false;

if (!empty($bill_output)) {
	$saved_bill_output = $bill_output;
}
else {
	$saved_bill_output = false;
}

if (!empty($skey)) {
	# web+callback
	func_array2update("cc_pp3_data", array("is_callback" => "Y"), "ref = '".$skey."'");
	$__tmp = func_query_first_cell("SELECT trstat FROM $sql_tbl[cc_pp3_data] WHERE ref = '".$skey."'");
	$__oids = explode('|',$__tmp);
	array_shift($__oids);
	$orderids = $__oids;
}

if (empty($orderids)) {
	# order was lost
	$bill_error="error_ccprocessor_error";
	$bill_output["billmes"] = "Error: Your order was lost";
	$reason = "&bill_message=".urlencode($bill_output["billmes"]);
	$fatal = true;
}
elseif (empty($cart) && empty($skey)) {
	# cart was lost
	$bill_error="error_ccprocessor_error";
	$bill_output["billmes"] = "Error: Your cart was lost";
	$reason = "&bill_message=".urlencode($bill_output["billmes"]);
	$fatal = true;
}
elseif ($bill_output["code"] == 3) {
	# queue
	$reason = "&bill_message=".urlencode($bill_output["billmes"]);
}
elseif ($bill_output["code"] == 2) {
	# declined
	$bill_error="error_ccprocessor_error";
	$reason = "&bill_message=".urlencode($bill_output["billmes"]);
}
elseif ($bill_output["code"] == 1) {
	# approved

	# Response checking
	if (isset($payment_return) && !empty($payment_return) && $bill_output["code"] != 2) {
		if (isset($payment_return['total'])) {
			$_oids = is_array($orderids) ? $orderids : array($orderids);
			$sum = 0;
			foreach ($_oids as $_oid) {
				$o = func_order_data($_oid);
				$sum += $o['order']['total'];
			}

			if ($sum != doubleval($payment_return['total'])) {
				$bill_output["code"] = 2;
				$bill_output['billmes'] .= "; Payment amount mismatch.";
			}
		}

		if (
			$bill_output["code"] != 2 &&
			isset($payment_return['currency']) &&
			isset($payment_return['_currency']) &&
			!empty($payment_return['_currency']) &&
			$payment_return['currency'] != $payment_return['_currency']
		) {
			$bill_output["code"] = 2;
			$bill_output['billmes'] .= "; Payment amount mismatch.";
		}
	}

	if ($bill_output["code"] == 1) {
		$bill_output["billmes"] = "Approved: ".$bill_output["billmes"];

	} else {
		$bill_error = "error_ccprocessor_error";
		$reason = "&bill_message=".urlencode($bill_output["billmes"]);
		$bill_output["billmes"] = "Declined: ".$bill_output["billmes"];
	}
}
elseif ($bill_output["code"] == 4) {
	# CMPI declined
	$bill_error="error_cmpi_error";
	$reason = "&bill_message=".urlencode($bill_output["billmes"]);
}
else {
	# unavailable
	$bill_error="error_ccprocessor_unavailable";
	$bill_output["billmes"] = "Error: Payment gateway is unavailable";
}

if ($log_payment_failure || (!empty($bill_error) && ($bill_output['code'] !=1 || $bill_output['code'] !=3))) {
	x_session_register('logged_paymentid');

	$payment_name = "unable to determine";
	if (!empty($logged_paymentid)) {
		$method_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$logged_paymentid'");
		$payment_module_info = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE paymentid='$logged_paymentid'");
		$payment_name = sprintf("%s (%s%s)",
			$method_name,
			$payment_module_info["module_name"],
			(get_cc_in_testmode($payment_module_info) ? ", in test mode" : "")
		);
	}

	ob_start();
	echo "Payment method: $payment_name\n";
	echo "bill_output = ";
	print_r($bill_output);

	if ($saved_bill_output !== false) {
		echo "original_bill_output = ";
		print_r($saved_bill_output);
	}

	$https_responses = func_https_ctl('GET');
	if (!empty($https_responses)) {
		echo "responses of https requests = ";
		print_r($https_responses);
		func_https_ctl('PURGE');
	}

	if ($REQUEST_METHOD != 'POST' || empty($_POST['action']) || $_POST['action'] != 'place_order') {
        echo '_GET = ';
        print_r($_GET);
        echo '_POST = ';
        print_r($_POST);
	}

	$op_msg_data = ob_get_contents();
	ob_end_clean();

	x_session_register('login');
	$op_message = "Payment processing failure.\nLogin: $login\nIP: $REMOTE_ADDR\n----\n".$op_msg_data;

	x_log_flag('log_payment_processing_errors', 'PAYMENTS', $op_message, true);
}

if (!$fatal) {

	$check_orderid = is_array($orderids) ? $orderids[0] : $orderids;

        $order_info = func_query_first("SELECT paymentid, total, login FROM $sql_tbl[orders] WHERE orderid='$check_orderid'");
        $order_paymentid = $order_info["paymentid"];
	$payment_method_info = func_query_first("SELECT how_process_payment_at_checkout FROM $sql_tbl[payment_methods] WHERE paymentid='".$order_paymentid."'");
	$how_process_payment_at_checkout = $payment_method_info["how_process_payment_at_checkout"];



//	$order_status = ($bill_error) ? "F" : (($bill_output["code"] == 3) ? (($how_process_payment_at_checkout == "A") ? "AP" : "Q") : (($how_process_payment_at_checkout == "A" && $bill_output["code"] == 1) ? "AP" : "P"));

	if ($bill_error){
		$order_status = "F";
	}
	elseif ($bill_output["code"] == 3){
		if ($how_process_payment_at_checkout == "A"){
			$order_status = "AP";
		}
		else {
			$order_status = "Q";
		}
	}
	elseif ($bill_output["code"] == 1){
		if ($how_process_payment_at_checkout == "A"){
			$order_status = "AP";
		}
		else {
			$order_status = "P";
		}
	}
	else {
		$order_status = "P";
	}

//func_print_r($bill_output, $how_process_payment_at_checkout, $order_paymentid, $bill_error, $order_status);


	if ($bill_output["code"] == 1 || $bill_output["code"] == 3) {
		if (empty($skey) || !in_array(func_query_first_cell("SELECT is_callback FROM $sql_tbl[cc_pp3_data] WHERE ref = '$skey'"), array("R", "N"))) {
			$cart = "";
			if (!empty($active_modules['SnS_connector']))
				func_generate_sns_action("CartChanged");
		}
	}

	$advinfo = array();
	$advinfo[] = "Reason: ".$bill_output["billmes"];
	if ($bill_output["avsmes"]) $advinfo[] = "AVS info: ".$bill_output["avsmes"];
	if ($bill_output["cvvmes"]) $advinfo[] = "CVV info: ".$bill_output["cvvmes"];

	if (isset($cmpi_result)) {
		$advinfo[] = "3-D Secure Transaction:";
		if (isset($cmpi_result['Enrolled'])) {
			$advinfo[] = "  TransactionId: ".$cmpi_result['TransactionId'];
#
##
###
			$transaction_id = $cmpi_result['TransactionId'];
###
##
#
			$advinfo[] = "  Enrolled: ".$cmpi_result['Enrolled'];
		} else {
			$advinfo[] = "  PAResStatus: ".$cmpi_result['PAResStatus'];
			$advinfo[] = "  PAResStatusDesc: ".$cmpi_result['PAResStatusDesc'];
			$advinfo[] = "  CAVV: ".$cmpi_result['Cavv'];
			$advinfo[] = "  SignatureVerification: ".$cmpi_result['SignatureVerification'];
			$advinfo[] = "  Xid: ".$cmpi_result['Xid'];
			$advinfo[] = "  EciFlag: ".$cmpi_result['EciFlag'];
		}
		if (!empty($cmpi_result['ErrorNo']))
			$advinfo[] = "  ErrorNo: ".$cmpi_result['ErrorNo'];
		if (!empty($cmpi_result['ErrorDesc']))
			$advinfo[] = "  ErrorDesc: ".$cmpi_result['ErrorDesc'];
	}

	func_change_order_status($orderids, $order_status, join("\n", $advinfo));

	$transaction_total = $order_info["total"];

	if (!empty($login)){
		$transaction_login = $login;
	} else {
		$transaction_login = $order_info["login"];
	}

	if (!empty($cur)){
		$transaction_currency = $cur;
	} else {
		$transaction_currency = "USD";
	}

	if (!empty($payment_status)){
		$transaction_status = $payment_status;
	} else {
		if ($order_status == 'AP') {
            $transaction_status = 'authorized';
		} else {
            $transaction_status = $order_status;
		}
	}

	$transaction_status = strtolower($transaction_status);

        if (!empty($txn_id)){ # PayPal uses txn_id
		$transaction_id = $txn_id;
	}

	if (!empty($transaction_id)){

		$transaction_log = "";
		
		/** @var OrderTransactionModel $model */
        list($model) = OrderTransactionModel::objects()->getOrNew(['orderid' => $check_orderid, 'transaction_id' => $transaction_id]);

        $param =
            [
                'orderid' => $check_orderid,
                'paymentid' => $order_paymentid,
                'transaction_id' => $transaction_id,
                'transaction_status' => $transaction_status,
                'transaction_currency' => $transaction_currency,
                'transaction_amount' => $transaction_total,
                'login' => $transaction_login,
            ];

        if ($model->getIsNewRecord()) {
            $param['type'] = OrderTransactionModel::TYPE_AUTHORIZATION;
		}

		$model->setAttributes($param);

        $model->save();

        $param = array_merge($param,
            [
                'transaction_total' => $transaction_total,
                'order_transaction_id' => $model->id,
                'transaction_log' => $transaction_log,
            ]
        );

        $log_model = new TransactionLogModel($param);
        $log_model->save();

		func_log_order($check_orderid, 'PP', $transaction_log, $login);
        }
###
##
#


	$_orderids = func_get_urlencoded_orderids ($orderids);
}

x_session_unregister("secure_oid");
x_session_save();

?>
