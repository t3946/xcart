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
# $Id: cc_gcomm.php,v 1.12.2.1 2006/12/25 14:32:50 max Exp $
#

if (!defined('XCART_START')) { header("Location: ../"); die("Access denied"); }

@set_time_limit(100);

x_load('http');

$avs_codes = array(
	"A" => "Street addresses matches, but the ZIP code does not. The first five numerical characters contained in the address match. However, the ZIP code does not match.",
	"E" => "Ineligible transaction. The card issuing institution is not supporting AVS on the card in question.",
	"N" => "Neither address nor ZIP matches. The first five numerical characters contained in the address do not match, and the ZIP code does not match.",
	"R" => "Retry (system unavailable or timed out).",
	"S" => "Card type not supported. The card type for this transaction is not supported by AVS. AVS can verify addresses for Visa cards, MasterCard, proprietary cards, and private label transactions.",
	"U" => "Address information unavailable. The address information was not available at the issuer.",
	"W" => "9 digit ZIP code match, address does not. The nine digit ZIP code matches that stored at the issuer. However, the first five numerical characters contained in the address do not match.",
	"X" => "Exact match (9 digit zip and address) Both the nine digit postal ZIP code as well as the first five numerical characters contained in the address match.",
	"Y" => "Address and 5 digit zip match. Both the five digit postal ZIP code as well as the first five numerical characters contained in the address match.",
	"Z" => "5 digit ZIP matches, but the address does not. The five digit postal ZIP code matches that stored at the VIC or card issuer's center. However, the first five numerical characters contained in the address do not match.",
	"B" => "Street address matches for international transaction. Postal Code not verified due to incompatible formats.",
	"C" => "Street address and Postal Code not verified for international transaction due to incompatible format.",
	"D" => "Street address and Postal Code match for international transaction.",
	"P" => "Postal Code match for international transaction. Street address not verified due to incompatible formats."
);

$cvv_codes = array(
	"M" => "CVV2/CVC2 Match",
	"N" => "CVV2/CVC2 not matched",
	"P" => "Not processed",
	"S" => "CVV2 should be printed on the card, but it was indicated that the value was not present",
	"U" => "Issuer does not support CVV2",
	"X" => "Service provider did not respond"
);

$pp_atsid = $module_params["param01"];
$pp_compname = $module_params["param02"];
$pp_prefix = $module_params["param03"];

$post = "";
$post[] = "action=ns_quicksale_cc";
$post[] = "acctid=".$pp_atsid;
$post[] = "amount=".$cart["total_cost"];
#$post[] = "subid=";
#$post[] = "authonly=0";
$post[] = "ci_companyname=".$pp_compname;
$post[] = "ci_billcity=".$userinfo["b_city"];
$post[] = "ci_billstate=".$userinfo["b_state"];
$post[] = "ci_billzip=".$userinfo["b_zipcode"];
$post[] = "ci_billcountry=".$userinfo["b_country"];
$post[] = "ci_billaddr1=".$userinfo["b_address"];
$post[] = "ci_billaddr2=".$userinfo["b_address_2"];
$post[] = "ci_shipcity=".$userinfo["s_city"];
$post[] = "ci_shipstate=".$userinfo["s_state"];
$post[] = "ci_shipzip=".$userinfo["s_zipcode"];
$post[] = "ci_shipcountry=".$userinfo["s_country"];
$post[] = "ci_shipaddr1=".$userinfo["s_address"];
$post[] = "ci_shipaddr2=".$userinfo["s_address_2"];
$post[] = "ci_phone=".$userinfo["phone"];
$post[] = "ci_email=".$userinfo["email"];
$post[] = "ci_memo=OID:".$prefix.join("-",$secure_oid);
$post[] = "ccname=".$userinfo["card_name"];
$post[] = "ccnum=".$userinfo["card_number"];
$post[] = "cvv2=".$userinfo["card_cvv2"];
$post[] = "expmon=".(0+substr($userinfo["card_expire"],0,2));
$post[] = "expyear=".(2000+substr($userinfo["card_expire"],2,2));

list($a,$return) = func_https_request("POST","https://trans.merchantpartners.com:443/cgi-bin/process.cgi",$post);
$return = "&".strtr($return, array("\n"=>"&", "\r"=>''))."&";

preg_match("/<plaintext>&(.*)=(.*)&/U", $return, $out);
$bill_output["billmes"] = $out[2];

if ($out[1] == "Accepted") {
	$bill_output["code"] = 1;

} else {
	$bill_output["code"] = 2;
}

$parts = explode(":", $out[2]);
$avs_code = isset($parts[5]) ? $parts[5] : false;
$cvv_code = isset($parts[7]) ? $parts[7] : false;
if (!empty($avs_code) && isset($avs_codes[$avs_code])) 
	$bill_output["avsmes"] = $avs_codes[$avs_code]." Code: $avs_code; ";
else
	$bill_output["avsmes"] = "Unknown. Code: $avs_code;";

if (!empty($cvv_code) && isset($cvv_codes[$cvv_code]))
	$bill_output["cvvmes"] .= $cvv_codes[$cvv_code]." Code: $cvv_code; ";
else
	$bill_output["cvvmes"] .= "Unknown. Code: $cvv_code;";
 
preg_match("/&orderid=(.*)&/U",$return,$out);
if(!empty($out[1]))
	$bill_output["billmes"].= " (OrderID=".$out[1].")";

preg_match("/&historyid=(.*)&/U",$return,$out);
if(!empty($out[1]))
	$bill_output["billmes"].= " (HistoryID=".$out[1].")";

if(empty($return))
	$bill_output["code"] = 0;

?>
