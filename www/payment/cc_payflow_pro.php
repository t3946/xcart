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
# $Id: cc_payflow_pro.php,v 1.1.2.1 2006/12/01 08:47:36 max Exp $
#

if (!defined('XCART_START')) { header("Location: ../"); die("Access denied"); }

x_load('http','xml');

$vs_user = $module_params["param01"];
$vs_vendor = $module_params["param02"];
$vs_partner = $module_params["param03"];
$vs_pwd = $module_params["param04"];
$vs_host = ($module_params["testmode"] != "N") ? "pilot-payflowpro.verisign.com" : "payflowpro.verisign.com";

$items = array();
if (!empty($products)) {
	foreach ($products as $p) {
		$items[] = <<<XML
									<SKU>$p[productcode]</SKU>
									<Description>$p[product]</Description>
									<Quantity>$p[amount]</Quantity>
									<UnitPrice>$p[taxed_price]</UnitPrice>
XML;
	}
}
$items = "\n\t\t\t\t\t\t\t\t<Item>\n".implode("\n\t\t\t\t\t\t\t\t</Item>\n\t\t\t\t\t\t\t\t<Item>\n", $items)."\n\t\t\t\t\t\t\t\t</Item>\n\t\t\t\t\t\t\t";

$expire = (substr($userinfo["card_expire"], 2, 2)+2000).substr($userinfo["card_expire"], 0, 2);

$post = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<XMLPayRequest Timeout='45' version="2.0">
	<RequestData>
		<Partner>$vs_partner</Partner>
		<Vendor>$vs_vendor</Vendor>
		<Transactions>
			<Transaction>
				<Sale>
					<PayData>
						<Invoice>
							<BillTo>
								<Name>$ship_name</Name>
								<Address>
									<Street>$userinfo[b_address]</Street>
									<City>$userinfo[b_city]</City>
									<State>$userinfo[b_state]</State>
									<Zip>$userinfo[b_zipcode]</Zip>
									<Country>$userinfo[b_country]</Country>
								</Address>
								<EMail>$userinfo[email]</EMail>
								<Phone>$userinfo[phone]</Phone>
								<Fax>$userinfo[fax]</Fax>
							</BillTo>
							<ShipTo>
								<Address>
									<Street>$userinfo[s_address]</Street>
									<City>$userinfo[s_city]</City>
									<State>$userinfo[s_state]</State>
									<Zip>$userinfo[s_zipcode]</Zip>
									<Country>$userinfo[s_country]</Country>
								</Address>
							</ShipTo>
							<TotalAmt>$cart[total_cost]</TotalAmt>
							<Items>$items</Items>
						</Invoice>
						<Tender>
							<Card>
								<CardNum>$userinfo[card_number]</CardNum>
								<ExpDate>$expire</ExpDate>
								<NameOnCard>$userinfo[card_name]</NameOnCard>
								<CVNum>$userinfo[card_cvv2]</CVNum>
							</Card>
						</Tender>
					</PayData>
				</Sale>
			</Transaction>
		</Transactions>
	</RequestData>
	<RequestAuth>
		<UserPass>
			<User>$vs_user</User>
			<Password>$vs_pwd</Password>
		</UserPass>
	</RequestAuth>
</XMLPayRequest>
XML;

$headers = array(
	"X-VPS-REQUEST-ID" => $module_params["param05"].join("-", $secure_oid).time(),
	"X-VPS-VIT-CLIENT-CERTIFICATION-ID" => "7894b92104f04ffb4f38a8236ca48db3"
);

$url = "https://".$vs_host.":443/transaction";
list($a, $return) = func_https_request("POST", $url, array($post), "", "", "text/xml", "", "", "", $headers);

$xml = func_xml_parse($return, $err);

$result = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/Result/0/#");
$message = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/Message/0/#");
$avsresult = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/IAVSResult/0/#");
$avsresults = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/AVSResult/StreetMatch/0/#");
$avsresultz = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/AVSResult/ZipMatch/0/#");
$cvsresult = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/CVResult/0/#");
$pnref = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/PNRef/0/#");
$authcode = func_array_path($xml, "XMLPayResponse/ResponseData/TransactionResults/TransactionResult/AuthCode/0/#");

$bill_output = array();

if (empty($xml)) {
	$bill_output['code'] = 2;
	$bill_output['billmes'] = "Response incorrect or empty";

} elseif ($result === '0') {
	$bill_output['code'] = 1;
	$bill_output['billmes'] = "AuthCode: ".$authcode."; PNRef: ".$pnref;

} else {
	$bill_output['code'] = 2;
	$bill_output['billmes'] = "Result code: ".$result."; ";

	if (!empty($message))
		$bill_output['billmes'] .= "Message: ".$message."; ";

	if (!empty($authcode))
		$bill_output['billmes'] .= "AuthCode: ".$authcode."; ";

	if (!empty($pnref))
		$bill_output['billmes'] .= "PNRef: ".$pnref."; ";

}

if (!empty($avsresult))
	$bill_output['avsmes'] = "International AVS result: ".$avsresult."; AVS result: Street match: $avsresults; Zip match: $avsresultz";

if (!empty($cvsresult))
	$bill_output['cavvmes'] = $cvsresult;

?>
