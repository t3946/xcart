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

		$suffix = '';

		if (isset($update_integration_type)) {
			# Update the FedEx integration method
			$value = ($integration_type == 'A' ? 'A' : 'T');
			$is_integration_type_exist = func_query_first_cell("SELECT name FROM $sql_tbl[config] WHERE name='FEDEX_integration_type'");
			if ($is_integration_type_exist)
				db_query("UPDATE $sql_tbl[config] SET value='$value' WHERE name='FEDEX_integration_type'");
			else
				db_query("INSERT $sql_tbl[config] (name, value) VALUES('FEDEX_integration_type', '$value')");
		}
		if (isset($get_meter_number)) {
			# Get the meter number from FedEx
			if (empty($posted_data['person_name']) || empty($posted_data['phone_number']) || empty($posted_data['address_1']) || empty($posted_data['city']) || empty($posted_data['state']) || empty($posted_data['zipcode']) || empty($posted_data['country'])) {
				x_session_register('saved_user_data');
				$saved_user_data = $posted_data;

				$top_message["content"] = func_get_langvar_by_name("err_filling_form");
				$top_message["type"] = "E";
				$suffix = "&error=1";
			}
			else {
				$subscriber_info = $posted_data;
				$error = false;
				$meter_number = func_fedex_get_meter_number($subscriber_info, $error);

				if ($meter_number) {
					$is_meter_number_exist = func_query_first_cell("SELECT name FROM $sql_tbl[config] WHERE name='FEDEX_meter_number'");
					if ($is_meter_number_exist)
						db_query("UPDATE $sql_tbl[config] SET value='".addslashes($meter_number)."' WHERE name='FEDEX_meter_number'");
					else
						db_query("INSERT $sql_tbl[config] (name, value) VALUES('FEDEX_meter_number', '".addslashes($meter_number)."')");
					$top_message["content"] = func_get_langvar_by_name("msg_fedex_meter_number");
				}
				else {
					$top_message["content"] = "The following error has been received from FedEx server: " . $error['msg'];
					$top_message["type"] = "E";
				}
			}
		}
		elseif(isset($clear_meter_number)) {
			# Remove meter number
			db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='FEDEX_meter_number'");
			$top_message["content"] = func_get_langvar_by_name("msg_fedex_meter_number_removed");
		}
		elseif (isset($update_options) && $config['FEDEX_integration_type'] == 'A') {
			# Update the FedEx options
			$check = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='FDX'");
			if (!$check)
				db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('FDX')");

			$fedex_options = array(
				'carrier_code' => $carrier_code,
				'packaging' => $packaging,
				'dropoff_type' => $dropoff_type,
				'ship_date' => intval($ship_date),
				'dim_length' => intval($dim_length),
				'dim_width' => intval($dim_width),
				'dim_height' => intval($dim_height),
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
				'handling_charges_type' => $handling_charges_type
			);

			db_query("UPDATE $sql_tbl[shipping_options] SET param00='".addslashes(serialize($fedex_options))."' WHERE carrier='FDX'");

			$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");
		}
		elseif (isset($update_options)) {

			$check = func_query_first("SELECT * FROM $sql_tbl[shipping_options] WHERE carrier='FDX'");
			if (!$check)
				db_query("INSERT INTO $sql_tbl[shipping_options] (carrier) VALUES ('FDX')");

			db_query("UPDATE $sql_tbl[shipping_options] SET param05='$company_type', param01='$packaging', param02='$dropoff_type', param03='$expr_fuel_surch', param04='$grnd_fuel_surch' WHERE carrier='FDX'");

			$top_message["content"] = func_get_langvar_by_name("msg_adm_shipping_option_upd");
		}

		func_header_location("shipping_options.php?carrier=FDX".$suffix);
		
	}

	if ($carrier == "FDX_IMPORT") {
	#
	# Import shipping rates tables from CSV files
	#
		$fdx_flag = false;
		$fdx_ozip = $config["Company"]["location_zipcode"];

		include $xcart_dir."/shipping/fedex_import.php";

		$fedex_path_to_import = stripslashes($HTTP_POST_VARS["fdx_import_files_path"]);
		
		if (!preg_match("/^.*".preg_quote(DIRECTORY_SEPARATOR, "/").'$/', $fedex_path_to_import)) 
			$fedex_path_to_import .= DIRECTORY_SEPARATOR;

		$fedex_path_to_import = addslashes($fedex_path_to_import);	
	
		$cnf = func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name='fdx_files_path'");
		if ($cnf)
			db_query("UPDATE $sql_tbl[config] SET value='$fedex_path_to_import' WHERE name='fdx_files_path'");
		else
			db_query("INSERT INTO $sql_tbl[config] (name, value, type) VALUES('fdx_files_path', '$fedex_path_to_import','text')");
	
		# get real names
		fedex_check_for_files($fdx_ozip);

		# parse zone locator
		if (isset($fedex_zone_file))
		fedex_parse_zone_locator_file($fedex_path_to_import.$fedex_zone_file);

		# import rates
		$methods_count = 0;
		fedex_import_rates($methods_count);

		if ((isset($fedex_zone_file)) || ($methods_count>0)) {
		
			# Get rates count
			$r = db_query("SELECT COUNT(r_id) FROM $sql_tbl[fedex_rates] WHERE 1");
			$res = db_fetch_array($r);
			$r_cnt = $res[0];

			# Read FedEx import statistics
			$r = db_query("SELECT value FROM $sql_tbl[config] WHERE name='fedex_import_stat'");
			if (db_num_rows($r)!=0) {
				$res = db_fetch_array($r);
				$fdx_import_stat = unserialize($res['value']);
			}

			# Add info into array
			$dt = time();

			foreach($fedex_rates_files as $file) {
				if (isset($fdx_import_stat['files'][$file['name']]['updated']))
					$fdx_import_stat['files'][$file['name']]['updated'] = false;

				if (isset($file["real_name"])) {
					$fdx_import_stat['files'][$file['name']]['date'] = $dt;
					$fdx_import_stat['files'][$file['name']]['updated'] = true;
				}
			}

			if (isset($fedex_zone_file)) {
				$fdx_import_stat["ozip"]=$fdx_ozip;
				$fdx_import_stat["date"]=$dt;
				$fdx_import_stat['updated'] = true;
			}
			else
				$fdx_import_stat['updated'] = false;

			# Serialize to write into cnfig
			$s = serialize($fdx_import_stat);

			if (db_num_rows($r) == 0)
				db_query("INSERT INTO $sql_tbl[config](name, value) VALUES('fedex_import_stat','".$s."')");
			else
				db_query("UPDATE $sql_tbl[config] SET value='".$s."' WHERE name='fedex_import_stat'");

			x_session_register("fdx_import_updated");
			$fdx_import_updated = 'yes';
		}
		else {
			x_session_register("fdx_import_updated");
			$fdx_import_updated = 'no';
		}
	
		$top_message["content"] = func_get_langvar_by_name("msg_adm_fedex_rates_imported");
		
		func_header_location("shipping_options.php?carrier=FDX");
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
	if (!empty($intgr) && $intgr == 'Y')
		$smarty->assign('change_integration', 'Y');
	
	elseif ($config['FEDEX_integration_type'] == 'A') {
	# Prepare FedEx (API) information
		if (!empty($error)) {
			$smarty->assign("fill_error", 'Y');
			x_session_register('saved_user_data');
			$prepared_user_data = $saved_user_data;
			x_session_unregister('saved_user_data');
		}

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

		$smarty->assign("prepared_user_data", $prepared_user_data);

		include_once $xcart_dir."/include/countries.php";
		include_once $xcart_dir."/include/states.php";

		$fedex_options = $shipping_options['fdx']['param00'];
		$shipping_options['fdx'] = unserialize($fedex_options);
	}
	else {
	# Prepare FEDEX (tables) status information
		$fdx_files_path = ($config["fdx_files_path"] ? $config["fdx_files_path"] : $fedex_default_rates_dir);
	
		$smarty->assign ("fdx_files_path", $fdx_files_path);

		$fdx_import_stat = unserialize($config["fedex_import_stat"]);

		if (!$fdx_import_stat) 
			$fdx_import_stat = array();
	
		$smarty->assign ("fdx_import_stat", $fdx_import_stat);
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
