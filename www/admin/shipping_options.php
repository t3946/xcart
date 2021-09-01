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
# $Id: shipping_options.php,v 1.30.2.3 2007/01/11 09:06:09 max Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";

#
# This function gather the FedEx meter number from FedEx server
#
function func_fedex_get_meter_number($userinfo, &$error) {
	global $config;

	x_load('http','xml');

	# FedEx host
	$fedex_host = ($config['Shipping']['FEDEX_test_server'] == 'Y' ? 'gatewaybeta.fedex.com/GatewayDC' : 'gateway.fedex.com/GatewayDC');

	$xml_contact_fields = array();
	$xml_address_fields = array();

	if (!empty($userinfo['company_name']))
		$xml_contact_fields[] = "<CompanyName>{$userinfo['company_name']}</CompanyName>";

	if (!empty($userinfo['pager_number']))
		$xml_contact_fields[] = "<PagerNumber>{$userinfo['pager_number']}</PagerNumber>";

	if (!empty($userinfo['fax_number']))
		$xml_contact_fields[] = "<FaxNumber>{$userinfo['fax_number']}</FaxNumber>";

	if (!empty($userinfo['email']))
		$xml_contact_fields[] = "<E-MailAddress>{$userinfo['email']}</E-MailAddress>";

	if (!empty($userinfo['address_2']))
		$xml_address_fields[] = "<Line2>{$userinfo['address_2']}</Line2>";

	$xml_contact_fields_str = implode("\n\t\t", $xml_contact_fields);
	$xml_address_fields_str = implode("\n\t\t", $xml_address_fields);

	$xml_query = <<<OUT
<?xml version="1.0" encoding="UTF-8" ?>
<FDXSubscriptionRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXSubscriptionRequest.xsd">
	<RequestHeader>
		<CustomerTransactionIdentifier>String</CustomerTransactionIdentifier>
		<AccountNumber>{$config['Shipping']['FEDEX_account_number']}</AccountNumber>
	</RequestHeader>
	<Contact>
		<PersonName>{$userinfo['person_name']}</PersonName>
		<PhoneNumber>{$userinfo['phone_number']}</PhoneNumber>
$xml_contact_fields_str
	</Contact>
	<Address>
		<Line1>{$userinfo['address_1']}</Line1>
$xml_address_fields_str
		<City>{$userinfo['city']}</City>
		<StateOrProvinceCode>{$userinfo['state']}</StateOrProvinceCode>
		<PostalCode>{$userinfo['zipcode']}</PostalCode>
		<CountryCode>{$userinfo['country']}</CountryCode>
	</Address>
</FDXSubscriptionRequest>
OUT;

	$data = explode("\n", $xml_query);
	$host = "https://".$fedex_host;
	list($header, $result) = func_https_request("POST", $host, $data,"","","text/xml");

	if (defined('FEDEX_DEBUG'))
		x_log_add("fedex", $xml_query . "\n\n" . $header . "\n\n" . $result);

	$parse_error = false;
	$options = array(
		'XML_OPTION_CASE_FOLDING' => 1,
		'XML_OPTION_TARGET_ENCODING' => 'ISO-8859-1'
	);

	$parsed = func_xml_parse($result, $parse_error, $options);

	$error = array();

	if (empty($parsed)) {
		x_log_flag('log_shipping_errors', 'SHIPPING', "FedEx module: Received data could not be parsed correctly.", true);
		$error['msg'] = func_get_langvar_by_name("msg_fedex_meter_number_incorrect_data_err");
		return false;
	}

	$type = key($parsed);

	$meter_number = func_array_path($parsed, $type."/METERNUMBER/0/#");

	if (empty($meter_number)) {
		
		$error['code'] = func_array_path($parsed, $type."/ERROR/CODE/0/#");
		$error['msg'] = func_array_path($parsed, $type."/ERROR/MESSAGE/0/#");
		
		if (empty($error['code'])) {
			$error['code'] = func_array_path($parsed, "ERROR/CODE/0/#");
			$error['msg'] = func_array_path($parsed, "ERROR/MESSAGE/0/#");
		}

		if (!empty($error['code'])) {
			x_log_flag('log_shipping_errors', 'SHIPPING', "FedEx module error: [{$error['code']}] {$error['msg']}", true);
		}
		else {
			x_log_flag('log_shipping_errors', 'SHIPPING', "FedEx module error: Empty meter number received", true);
			$error['msg'] = func_get_langvar_by_name("msg_fedex_meter_number_empty_err");
		}

		return false;

	}
	
	return $meter_number;

}

$location[] = array(func_get_langvar_by_name("lbl_shipping_options"), "");

$carriers = array();

if ($config["Shipping"]["use_intershipper"] == "Y") {
	$carriers[] = array("Intershipper","InterShipper");
	$carrier = "Intershipper";
}
else {
	$carriers[] = array("CPC","Canada Post");
	$carriers[] = array("FDX","FedEx");
	$carriers[] = array("USPS","U.S.P.S");
	$carriers[] = array("ARB","Airborne / DHL");
	$carriers[] = array("APOST","Australia Post");
}

$carrier_valid = false;
foreach ($carriers as $k=>$v)
	if ($v[0] == $carrier) {
		$carrier_valid = true;
		break;
	}

if (!$carrier_valid && $carrier !="FDX_IMPORT" )
	$carrier = "";

if ($REQUEST_METHOD == "POST") {
#
# Update the shipping options
#
	if ($carrier == "FDX") {
	#
	# FEDEX options update
	#
        if (isset($update_options)) {

            // Update the FedEx options
            $carrier_codes = isset($carrier_codes) && !empty($carrier_codes) ? implode('|', $carrier_codes) : '';

            $fedex_options = array(
                'carrier_codes' => $carrier_codes,
                'packaging' => $packaging,
                'dropoff_type' => $dropoff_type,
                'ship_date' => intval($ship_date),
                'dim_length' => sprintf("%.2f", $dim_length),
                'dim_width' => sprintf("%.2f", $dim_width),
                'dim_height' => sprintf("%.2f", $dim_height),
                'max_weight' => abs(doubleval($max_weight)),
                'cod_value' => sprintf("%.2f", $cod_value),
                'cod_type' => $cod_type,
                'alcohol' => (empty($alcohol) ? 'N' : 'Y'),
                'hold_at_location' => (empty($hold_at_location) ? 'N' : 'Y'),
                'dry_ice' => (empty($dry_ice) ? 'N' : 'Y'),
                'nonstandard_container' => (empty($nonstandard_container) ? 'N' : 'Y'),
                'inside_pickup' => (empty($inside_pickup) ? 'N' : 'Y'),
                'inside_delivery' => (empty($inside_delivery) ? 'N' : 'Y'),
                'saturday_pickup' => (empty($saturday_pickup) ? 'N' : 'Y'),
                'saturday_delivery' => (empty($saturday_delivery) ? 'N' : 'Y'),
                'residential_delivery' => (empty($residential_delivery) ? 'N' : 'Y'),
                'dg_accessibility' => $dg_accessibility,
                'signature' => $signature,
                'handling_charges_amount' => sprintf("%.2f", $handling_charges_amount),
                'handling_charges_type' => $handling_charges_type,
                'currency_code' => $currency_code,
                'param01' => @$param01,
                'param02' => @$param02,
                'add_smartpost_detail' => @$add_smartpost_detail,
                'smartpost_indicia' => $smartpost_indicia,
                'smartpost_ancillaryendorsement' => $smartpost_ancillaryendorsement,
                'smartpost_hubid' => $smartpost_hubid,
                'send_insured_value' => @$send_insured_value,
            );

            $fedex_options = addslashes(serialize($fedex_options));

			db_query("REPLACE INTO $sql_tbl[shipping_options] (carrier, param00) VALUES ('FDX', '$fedex_options')");

			$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

			func_header_location("shipping_options.php?carrier=FDX");

        }
		
	}

	if ($carrier == "USPS") {
	#
	# USPS options update
	#
		$check = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='USPS'");
		if (!$check)
			db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('USPS')");

		db_query("UPDATE $sql_tbl[shipping_options] SET param00='$mailtype', param01='$package_size', param02='$machinable', param03='$container_express', param04='$container_priority' WHERE carrier='USPS'");

		$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

		func_header_location("shipping_options.php?carrier=USPS");
	}

	if ($carrier == "Intershipper") {
	#
	# INTERSHIPPER options update
	#
		$check = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='INTERSHIPPER'");
		if (!$check)
			db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('INTERSHIPPER')");
	
		if($pickup)
			$pickup = implode('|',$pickup);
			
		$length = doubleval($length);
		$width = doubleval($width);
		$height = doubleval($height);
		
		$codvalue = doubleval($codvalue);
		$insvalue = doubleval($insvalue);

		db_query("UPDATE $sql_tbl[shipping_options] SET param00='$delivery', param01='$pickup', param02='$length', param03='$width', param04='$height', param05='$dunit', param06='$packaging', param07='$contents', param08='$codvalue', param09='$insvalue' WHERE carrier='INTERSHIPPER'");

		$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

		func_header_location("shipping_options.php?carrier=Intershipper");
	}

	if ($carrier == "CPC") {
	#
	# Canada Post options update
	#
		$check = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='CPC'");
		if (!$check)
			db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('CPC')");

		$currency_rate = doubleval($currency_rate);
		if ($currency_rate <= 0)
			$currency_rate = 1;
			
		$width = intval($width);
		$length = intval($length);
		$height = intval($height);
		
		db_query("UPDATE $sql_tbl[shipping_options] SET param00='$descr', param01='$length', param02='$width', param03='$height', param04='$insvalue', param05='$currency_rate' WHERE carrier='CPC'");

		$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

		func_header_location("shipping_options.php?carrier=CPC");
	}

	if ($carrier == "ARB") {
		#
		# Airborne ShipIt options update
		#
		$check = func_query_first("SELECT COUNT(*) AS cnt FROM $sql_tbl[shipping_options] WHERE carrier='ARB'");
		if (!$check["cnt"])
			db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('ARB')");

		$param01 = intval($param01);
		$param02 = intval($param02); $param03 = intval($param03); $param04 = intval($param04);
		$param06 = intval($param06);
		if ($param06 < 1) $param05 = 'NR';
		# COD payment
		if (empty($param08) || $param08 != "P") $param08 = "M";
		# COD value
		$param09 = intval($param09);

		# options: HAZ & allow customers to provide airborne account
		if (empty($opt_haz) || $opt_haz != "Y") $opt_haz = "N";
		if (empty($opt_own_account) || $opt_own_account != "Y") $opt_own_account = "N";
		$param07 = $opt_haz.",".$opt_own_account;

		db_query("UPDATE $sql_tbl[shipping_options] SET param00='$param00', param01='$param01', param02='$param02', param03='$param03', param04='$param04', param05='$param05', param06='$param06', param07='$param07', param08='$param08', param09='$param09' WHERE carrier='ARB'");

		$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

		func_header_location("shipping_options.php?carrier=ARB");
	}

	if ($carrier == "APOST") {
		#
		# Australia Post options update
		#

		$param00 = intval($param00);
		$param01 = intval($param01);
		$param02 = intval($param02);

		db_query("REPLACE INTO $sql_tbl[shipping_options] (param00,param01,param02,carrier) VALUES ('$param00','$param01','$param02','APOST')");

		$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");

		func_header_location("shipping_options.php?carrier=".$carrier);
	}
 
} # /if ($REQUEST_METHOD == "POST")

#
# Collect options for current carrier
#
$shipping_options = array ();

$shipping_options [strtolower($carrier)] = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='$carrier'");
if ($carrier == "FDX") {
#
# Get the shipping options for FedEx service
#

    // Prepare FedEx (API) information
    if (!empty($error))
        $smarty->assign('fill_error', 'Y');

        $prepared_user_data = $saved_user_data;

    if (empty($prepared_user_data)) {
        $prepared_user_data = array();
        $prepared_user_data['person_name'] = $user_account['firstname'] . ' ' . $user_account['lastname'];
        $prepared_user_data['company_name'] = $config['Company']['company_name'];
        $prepared_user_data['phone_number'] = preg_replace("/[^\d]/", "", $config['Company']['company_phone']);
        $prepared_user_data['email'] = $config['Company']['site_administrator'];
        $prepared_user_data['address_1'] = $config['Company']['location_address'];
        $prepared_user_data['city'] = $config['Company']['location_city'];
        $prepared_user_data['state'] = $config['Company']['location_state'];
        $prepared_user_data['zipcode'] = $config['Company']['location_zipcode'];
        $prepared_user_data['country'] = $config['Company']['location_country'];
    }

    $smarty->assign('prepared_user_data', $prepared_user_data);

    include_once $xcart_dir.'/include/countries.php';
    include_once $xcart_dir.'/include/states.php';

    $fedex_options = $shipping_options['fdx']['param00'];
    $shipping_options['fdx'] = @unserialize($fedex_options);

    if (!empty($shipping_options['fdx']['carrier_codes'])) {
        $ccodes = explode('|', $shipping_options["fdx"]["carrier_codes"]);
        $shipping_options['fdx']['carrier_codes'] = array();
        foreach ($ccodes as $code) {
            $shipping_options['fdx']['carrier_codes'][$code] = 1;
        }
    }
}

if ($carrier == "Intershipper") {
#
# Get the shipping options for Intershipper service
#
	$shipping_options["intershipper"]["pickup"] = explode('|',$shipping_options["intershipper"]["param01"]);
}

if ($carrier == "ARB") {
	$_data = explode(',',$shipping_options["arb"]["param07"]);
	$shipping_options["arb"]["opt_haz"] = @$_data[0];
	$shipping_options["arb"]["opt_own_account"] = @$_data[1];
}

$smarty->assign("carriers", $carriers);
$smarty->assign("carrier", $carrier);

$smarty->assign ("shipping_options", $shipping_options);

$smarty->assign("main","shipping_options");

# Assign the current location line
$smarty->assign("location", $location);

include "./shipping_tools.php";

# Assign the section navigation data
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
