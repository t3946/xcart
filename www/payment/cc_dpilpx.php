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
# $Id: cc_dpilpx.php,v 1.17.2.2 2007/01/05 13:20:34 max Exp $
#

@set_time_limit(100);

function decrypt_tripledes($data, $key) {
	$data = pack("H*", $data);
	$td = mcrypt_module_open('tripledes', '', 'ecb', '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$result = mdecrypt_generic($td, $data);
	mcrypt_generic_deinit($td);
	return $result;
}

function encrypt_tripledes($data, $key) {
	$td = mcrypt_module_open('tripledes', '', 'ecb', '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$result = mcrypt_generic($td, $data);
	mcrypt_generic_deinit($td);
	$enclen = strlen($result)*2;
	$tmp = unpack("H$enclen", $result);
	return array_pop($tmp);
}

function make_mac($xml, $key) {
	if (strlen($xml) % 8 != 0) {
		$extra = 8 - strlen($xml)%8;
		$xml .= str_repeat(" ", $extra);
	}

	$mac = pack("C*", 0, 0, 0, 0, 0, 0, 0, 0);

	for ($i = 0; $i < strlen($xml)/8; $i++) {
		$msg8 = substr($xml, 8*$i, 8);

		$mac ^= $msg8;
		$mac = encrypt_des($mac, $key);
	}

	$mac_result = unpack("H8", $mac);
	return array_pop($mac_result);
}

function encrypt_des($data, $key) {
	$td = mcrypt_module_open('des', '', 'ecb', '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$result = mcrypt_generic($td, $data);
	mcrypt_module_close($td);

	return $result;
}

if (!isset($REQUEST_METHOD))
	$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

if (!empty($_GET['result']) || !empty($_POST['result'])) {

#<PxPay>
#<Success>1</Success>
#<StatusRequired>0</StatusRequired>
#<Retry>0</Retry>
#<TxnType>Purchase</TxnType>
#<AuthCode>074227</AuthCode>
#<AmountSettlement>39.50</AmountSettlement>
#<CurrencySettlement>NZD</CurrencySettlement>
#<MerchantReference>12</MerchantReference>
#<CardName>Visa</CardName>
#<CurrencyInput>NZD</CurrencyInput>
#<UserId>0000000000005944</UserId>
#<ResponseText>APPROVED</ResponseText>
#<TxnData1>test</TxnData1>
#<TxnData2>test,,RU</TxnData2>
#<TxnData3>12345</TxnData3>
#<CardHolderName>xxxxxxx xxxxxxx</CardHolderName>
#<EmailAddress>xxx@xxx.xxx</EmailAddress>
#<DpsTxnRef>0000000400932012</DpsTxnRef>
#<DpsBillingId></DpsBillingId>
#<BillingId></BillingId>
#<MerchantTxnId></MerchantTxnId>
#<TS>20040511074231</TS>
#</PxPay> 

	require "./auth.php";

	x_load("payment");

	if (!func_is_active_payment("cc_dpilpx.php"))
		exit;

	$module_params = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE processor='cc_dpilpx.php'");
	$pp_pass = pack("H*", $module_params["param02"]);
	$pp_mackey = pack("H*",$module_params["param03"]);

	$return = trim(decrypt_tripledes($result, $pp_pass));

	$mac = trim(substr($return, -8));
	$return = substr($return, 0, -8);

	$checkmac = trim(make_mac($return, $pp_mackey));

	if (preg_match("/<MerchantReference>(.*)<\/MerchantReference>/i", $return, $ref))
		$bill_output["sessid"] = func_query_first_cell("SELECT sessionid FROM $sql_tbl[cc_pp3_data] WHERE ref='".$ref[1]."'");

	if (preg_match("/<Success>1<\/Success>/i",$return) && $mac == $checkmac && $ref[1]) {
		$bill_output["code"] = 1;
	} else {
		$bill_output["code"] = 2;
	}

	$bill_output["billmes"] = '';

	if ($mac != $checkmac)
		$bill_output["billmes"] .= "MAC key is invalid. ";
	if (empty($ref))
		$bill_output["billmes"] .= "Response does not contain the necessary authorization data. ";


	if (preg_match("/<ResponseText>(.+)<\/ResponseText>/i",$return,$out))
		$bill_output["billmes"] .= $out[1];

	if (preg_match("/<AuthCode>(.+)<\/AuthCode>/i",$return,$out))
		$bill_output["billmes"] .= " (AuthCode: ".$out[1].")";

	if (preg_match("/<UserId>(.+)<\/UserId>/i",$return,$out))
		$bill_output["billmes"] .= " (UserId: ".$out[1].")";

	if(preg_match("/<DpsTxnRef>(.+)<\/DpsTxnRef>/i",$return,$out))
		$bill_output["billmes"] .= " (DpsTxnRef: ".$out[1].")";

	if(preg_match("/<TS>(.+)<\/TS>/i",$return,$out))
		$bill_output["billmes"] .= " (TS: ".$out[1].")";

	if(preg_match("/<MerchantTxnId>(.+)<\/MerchantTxnId>/i",$return,$out))
		$bill_output["billmes"] .= " (MerchantTxnId: ".$out[1].")";

	if (preg_match("/<AmountSettlement>(.+)<\/AmountSettlement>/i",$return,$out)) {
		$payment_return = array(
			"total" => $out[1]
		);
	}

	$skey = $ref[1];
	require($xcart_dir."/payment/payment_ccend.php");

} else {

	if (!defined('XCART_START')) { header("Location: ../"); die("Access denied"); }

	$pp_id   = $module_params["param01"];
	$pp_pass = pack("H*", $module_params["param02"]);
	$pp_mackey = pack("H*",$module_params["param03"]);
	$_orderids  = $module_params["param04"].join("-",$secure_oid);

	$script_url = $http_location."/payment/cc_dpilpx.php";

	if(!$duplicate)
		db_query("REPLACE INTO $sql_tbl[cc_pp3_data] (ref,sessionid,trstat) VALUES ('".addslashes($_orderids)."','".$XCARTSESSID."','GO|".implode('|',$secure_oid)."')");

$xml = array();
$xml[]= "<Request>";
$xml[]= "<TxnId>".$_orderids."</TxnId>";
$xml[]= "<TxnType>Purchase</TxnType>";
$xml[]= "<AmountInput>".$cart["total_cost"]."</AmountInput>";
$xml[]= "<AppletType>PHPPxAccess</AppletType>";
$xml[]= "<AppletVersion>2.01.01</AppletVersion>";
$xml[]= "<InputCurrency>NZD</InputCurrency>";
$xml[]= "<MerchantReference>".$_orderids."</MerchantReference>";
$xml[]= "<TxnData1>".$userinfo["b_address"]."</TxnData1>";
$xml[]= "<TxnData2>".$userinfo["b_city"].",".$userinfo["b_state"].",".$userinfo["b_country"]."</TxnData2>";
$xml[]= "<TxnData3>Phone ".$userinfo["phone"]."</TxnData3>";
$xml[]= "<EmailAddress>".$userinfo["email"]."</EmailAddress>";
$xml[]= "<UrlFail>$script_url</UrlFail>";
$xml[]= "<UrlSuccess>$script_url</UrlSuccess>";
$xml[]= "<TS>".gmstrftime("%Y%m%d%H%M%S", time())."</TS>";
$xml[]= "<EnableAddBillCard></EnableAddBillCard>";
$xml[]= "<BillingId></BillingId>";
$xml[]= "<DpsBillingId></DpsBillingId>";
$xml[]= "<DpsTxnRef></DpsTxnRef>";
$xml[]= "</Request>";

	$xml =  join("", $xml);
	if (strlen($xml) % 8) 
		$xml = str_pad($xml, strlen($xml) + 8 - strlen($xml) % 8);

#$url = "www.payment.co.nz/pxpay/pxpay.asp";
$url = "www.paymentexpress.com/pxpay/pxpay.aspx";
	func_header_location("https://$url?userid=$pp_id&request=".encrypt_tripledes($xml.make_mac($xml, $pp_mackey), $pp_pass));
}

exit;
?>
