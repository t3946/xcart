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
# $Id: cc_cpac.php,v 1.15.2.3 2007/01/05 13:20:34 max Exp $
#

if (!isset($REQUEST_METHOD))
	$REQUEST_METHOD = $HTTP_SERVER_VARS["REQUEST_METHOD"];

if ($REQUEST_METHOD == 'GET' && in_array($HTTP_GET_VARS['mode'], array("ok","ko")) && !empty($HTTP_GET_VARS['Ds_Date'])) {

	# Return to the shop
	require_once "./auth.php";

	$module_params = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE processor = 'cc_cpac.php'");
	$bill_output["sessid"] = func_query_first_cell("SELECT sessionid FROM $sql_tbl[cc_pp3_data] WHERE ref='".$Ds_Order."'");
	$bill_output['code'] = ($HTTP_GET_VARS['mode'] == 'ok' ? 1 : 2);

	$res_codes = array(
		"0000" => "Transaction authorized for payments and pre-authorizations",
		"0099" => "Transaction authorized for payments and pre-authorizations",
		"0900" => "Transaction authorized for refunds and confirmations",
		"101" => "Card expired",
		"102" => "Card temporarily suspended or under suspicion of fraud",
		"104" => "Transaction not allowed for the card or terminal",
		"116" => "Insufficient funds",
		"118" => "Card not registered",
		"129" => "Security code (CVV2/CVC2) incorrect",
		"180" => "Card not recognized",
		"184" => "Cardholder authentication failed",
		"190" => "Transaction declined without explanation",
		"191" => "Wrong expiration date",
		"202" => "Card temporarily suspended or under suspicion of fraud with confiscation order",
		"912" => "Issuing bank not available",
		"9912" => "Issuing bank not available",
	);

	$bill_output["billmes"] = "Response ($Ds_Response): ".(isset($res_codes[$Ds_Response]) ? $res_codes[$Ds_Response] : 'Unknown')."; Secure transaction: ".($Ds_SecurePayment ? "Yes" : "No")."; AuthCode: $Ds_AuthorisationCode; Terminal: $Ds_Terminal;";

	if (isset($Ds_Amount)) {
		$payment_return = array(
			"total" => (empty($Ds_Amount) ? 0 : $Ds_Amount / 100)
		);
	}

	require_once($xcart_dir."/payment/payment_ccend.php");
	exit;

} else {

	if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

	x_load("http");
	@require_once($xcart_dir."/payment/sha1.php");

	$total_cost = price_format($cart['total_cost'])*100;
	$_oid = $module_params['param06'].join("",$secure_oid);
	$_soid = join("-",$secure_oid);
	$descr = substr("Order #".$module_params['param06'].$_soid, 0, 125);
	$post_url = $current_location."/payment/cc_cpac.php?oid=".$_soid;
	$ok_url = $post_url."&mode=ok";
	$ko_url = $post_url."&mode=ko";

	if (empty($module_params['param03']) || !is_numeric($module_params['param03']))
		$module_params['param03'] = '0';

	$ttype = 0;
	$sig = strtoupper(sha1($total_cost.$_oid.$module_params['param01'].$module_params['param02'].$ttype.$post_url.$module_params['param05']));

	if(!$duplicate)
		db_query("REPLACE INTO $sql_tbl[cc_pp3_data] (ref,sessionid) VALUES ('".addslashes($_oid)."','".$XCARTSESSID."')");

	$fields = array(
		"Ds_Merchant_Amount" => $total_cost,
		"Ds_Merchant_Currency" => $module_params['param02'],
		"Ds_Merchant_Order" => $_oid,
		"Ds_Merchant_MerchantCode" => $module_params['param01'],
		"Ds_Merchant_Terminal" => $module_params['param04'],
		"Ds_Merchant_TransasctionType" => $ttype,
		"Ds_Merchant_MerchantURL" => $post_url,
		"Ds_Merchant_MerchantSignature" => $sig,
		"Ds_Merchant_UrlOK" => $ok_url,
		"Ds_Merchant_UrlKO" => $ko_url,
		"DS_Merchant_ConsumerLanguage" => $module_params['param03'],
		"DS_Merchant_ProductDescription" => substr($descr, 0, 124),
		"Ds_Merchant_Cardholder" => substr($bill_name, 0, 60)
	);

	$url = $module_params["testmode"] == "N" ? "https://sis.sermepa.es:443/sis/realizarPago" : "https://sis-t.sermepa.es:25443/sis/realizarPago";

	func_create_payment_form($url, $fields, "CyberPack (laCaixa)");
	exit;
}
?>
