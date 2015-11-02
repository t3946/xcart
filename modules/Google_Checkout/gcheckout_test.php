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
# $Id: gcheckout_test.php,v 1.1.2.2 2007/01/17 07:14:39 svowl Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }


if (empty($mode) || !in_array($mode, array('test_callback', 'test_gc')))
	$mode = 'test_gc';

if ($mode == 'test_callback') {
	#
	# Check if callback URL is accessible via HTTPS connection
	#
	$url_to_test = $https_location . "/payment/ps_gcheckout.php";

	$h = array(
		"Authorization" => "Basic ".base64_encode($config['Google_Checkout']['gcheckout_mid'].":".$config['Google_Checkout']['gcheckout_mkey']),
		"Accept" => "application/xml"
	);

	x_load("http");

	list($a, $return) = func_https_request("POST", $url_to_test, array('test'), "", "", "application/xml", "", "", "", $h);

	if (strtolower($return) == 'success')
		$top_message["content"] = func_get_langvar_by_name('txt_gcheckout_callback_test_success');
	else {
		$top_message["content"] = func_get_langvar_by_name('txt_gcheckout_callback_test_failure');
		$top_message["type"] = "E";
	}
}

elseif ($mode == 'test_gc') {
	#
	# Check if Google Checkout accepts requests with specified Merchant ID and Merchant Key
	#
	$test_xml =<<<OUT
<?xml version="1.0" encoding="UTF-8"?>
<hello xmlns="http://checkout.google.com/schema/2">
</hello>
OUT;

	$parsed = @func_gcheckout_send_xml($test_xml);

	$result = '';

	if (!empty($parsed))
		$result = func_array_path($parsed, "BYE");

	if (!empty($result))
		$top_message["content"] = func_get_langvar_by_name('txt_gcheckout_test_success');
	else {
		$top_message["content"] = func_get_langvar_by_name('txt_gcheckout_test_failure');
		$top_message["type"] = "E";
	}

}

func_header_location("configuration.php?option=Google_Checkout");

?>
