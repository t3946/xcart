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
# $Id: mod_FEDEX_direct.php,v 1.1.2.3 2007/01/15 06:09:30 svowl Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('cart','http','xml');

function func_shipper_FEDEX_direct($weight, $userinfo, $debug, $cart) {
	global $config, $sql_tbl;
	global $products;
	global $active_modules;
	global $allowed_shipping_methods, $intershipper_rates;

	if (empty($config['Shipping']['FEDEX_account_number']) || empty($config['FEDEX_meter_number']))
		return;

	$FEDEX_FOUND = false;
	if (is_array($allowed_shipping_methods)) {
		foreach ($allowed_shipping_methods as $key=>$value) {
			if ($value["code"] == "FDX") {
				$FEDEX_FOUND = true;
				break;
			}
		}
	}

	if (!$FEDEX_FOUND)
		return;

	$fedex_services = array(
		'PRIORITYOVERNIGHT' => '45',
		'STANDARDOVERNIGHT' => '46',
		'FIRSTOVERNIGHT' => '47',
		'FEDEX2DAY' => '41',
		'FEDEXEXPRESSSAVER' => '42',
		'INTERNATIONALPRIORITY' => '48',
		'INTERNATIONALECONOMY' => '49',
		'INTERNATIONALFIRST' => '96',
		'FEDEX1DAYFREIGHT' => '133',
		'FEDEX2DAYFREIGHT' => '134',
		'FEDEX3DAYFREIGHT' => '135',
		'FEDEXGROUND' => '43',
		'GROUNDHOMEDELIVERY' => '44',
		'INTERNATIONALPRIORITY FREIGHT' => '136',
		'INTERNATIONALECONOMY FREIGHT' => '137',
		'EUROPEFIRSTINTERNATIONALPRIORITY' => '138'
	);

	#
	# Default FedEx shipping options (if it wasn't defined yet by admin)
	#
	$fedex_options = array (
		'carrier' => 'FDXE',
		'dropoff_type' => 'REGULARPICKUP',
		'packaging' => 'FEDEXENVELOPE',
		'list_rate' => 'false',
		'ship_date' => date("Y-m-d", (time() + 24*3600)),
		'package_count' => 1
	);
	
	# FedEx host
	$fedex_host = ($config['Shipping']['FEDEX_test_server'] == 'Y' ? 'gatewaybeta.fedex.com/GatewayDC' : 'gateway.fedex.com/GatewayDC');

	# Get stored FedEx options.
	$params = func_query_first ("SELECT param00 FROM $sql_tbl[shipping_options] WHERE carrier='FDX'");

	$fedex_options_saved = unserialize($params["param00"]);
	if (is_array($fedex_options_saved)) {
		$fedex_options = func_array_merge($fedex_options, $fedex_options_saved);
	}
	
	# Get the declared value of package
	if ($debug=="Y") {
		$decl_value = "1.00";
	}
	else {
		$is_admin = defined('AREA_TYPE') && (AREA_TYPE=='A' || AREA_TYPE=='P' && !empty($active_modules['Simple_Mode']));

		if ($is_admin && !empty($active_modules["Advanced_Order_Management"]) && x_session_is_registered("cart_tmp")) {
			global $cart_tmp;

			if (!isset($cart_tmp) && is_array($cart_tmp))
				$cart = $cart_tmp;
		}

		$cart2 = func_calculate($cart, $products, $userinfo["login"], $userinfo["usertype"]);
		$decl_value = $cart2["subtotal"];
	}

	$fedex_options['declared_value'] = $decl_value;

	# Calculate the package weight in LBS
	$fedex_weight = func_weight_in_grams($weight)/453.6;
	if (!preg_match("/^[\d]+.[\d]{1}$/", $fedex_weight)) {
		$fedex_weight .= ".0";
	}
	if ($fedex_weight < 1)
		$fedex_weight = 1;

	$fedex_options['weight'] = $fedex_weight;
	$fedex_options['weight_units'] = "LBS";

	$supportHome = array('US'=>1,'CA'=>1);
	$supportGrnd = array('US'=>1,'CA'=>1,'PR'=>1);

	if ($supportHome[$userinfo["s_country"]])
		$fedex_options['residential_delivery'] = 'false';
	
	$isex = (($fedex_options['carrier_code'] == "FDXE" || $fedex_options['carrier_code'] == "Both") ? true : false);
	$isgr = ((($fedex_options['carrier_code'] == "FDXG" || $fedex_options['carrier_code'] == "Both") && $fedex_options['residential_delivery'] != "true") ? true : false);
	
	$carrier_codes = array();

	if ($isex)
		$carrier_codes[] = 'FDXE';
	
	if ($isgr)
		$carrier_codes[] = 'FDXG';
	
	if (!empty($carrier_codes) && is_array($carrier_codes)) {

		if ($debug == "Y")
			print "<h1>FedEx Debug Information</h1>";

		$fedex_rates = array();

		foreach ($carrier_codes as $_carrier_code) {

			$_fedex_rates_tmp = array();

			$fedex_options['carrier_code'] = $_carrier_code;

			$xml_query = func_fedex_prepare_xml_query($fedex_options, $userinfo);

			$md5_request = md5($xml_query);

			if ($debug != "Y" && func_is_shipping_result_in_cache($md5_request)) {

			#
			# Get shipping rates from the cache
			#
				$_fedex_rates_tmp = func_get_shipping_result_from_cache($md5_request);

			}

			if (empty($_fedex_rates_tmp)) {

			#
			# Get shipping rates from FedEx server
			#

				$data = preg_split("/(\r\n|\r|\n)/",$xml_query, -1, PREG_SPLIT_NO_EMPTY);
				$host = "https://".$fedex_host;
				list($header, $result) = func_https_request("POST", $host, $data,"","","text/xml");

				if (defined('FEDEX_DEBUG'))
					x_log_add("fedex_rates", $xml_query . "\n\n" . $header . "\n\n" . $result);

				# Parse XML reply
				$parse_error = false;
				$options = array(
					'XML_OPTION_CASE_FOLDING' => 1,
					'XML_OPTION_TARGET_ENCODING' => 'ISO-8859-1'
				);

				$parsed = func_xml_parse($result, $parse_error, $options);

				$error = array();

				if (empty($parsed)) {
				# Error of parsing XML reply from FedEx
					x_log_flag('log_shipping_errors', 'SHIPPING', "FedEx module (rates): Received data could not be parsed correctly.", true);
					$error['msg'] = "FedEx module (rates): Received data could not be parsed correctly.";
					func_print_r($result, $parse_error);
					exit;
					return false;
				}

				$type = key($parsed);

				$error['code'] = func_array_path($parsed, $type."/ERROR/CODE/0/#");
				if (!empty($error['code'])) {
				# FedEx returned an error
					$error['msg'] = func_array_path($parsed, $type."/ERROR/MESSAGE/0/#");
					x_log_flag('log_shipping_errors', 'SHIPPING', "FedEx module error: [{$error['code']}] {$error['msg']}", true);
					$intershipper_error = $error['msg'];
					$shipping_calc_service = "FedEx";
				}
				else {
				# FedEx returned a valid reply, get the rates
					$entries = func_array_path($parsed, $type."/ENTRY");
				
					if (is_array($entries)) {

						foreach ($entries as $k=>$entry) {
							$service_type = func_array_path($parsed, $type."/ENTRY/$k/SERVICE/0/#");
							$estimated_time = func_array_path($parsed, $type."/ENTRY/$k/TIMEINTRANSIT/0/#");
							if (empty($estimated_time)) {
								$delivery_date = func_array_path($parsed, $type."/ENTRY/$k/DELIVERYDATE/0/#");
								if (!empty($delivery_date) && preg_match("/(\d{4})[^\d](\d{2})[^\d](\d{2})/", $delivery_date, $match)) {
									$curtime = time();
									$_time = mktime(23, 59, 59, $match[2], $match[3], $match[1]);
									if ($_time > $curtime)
										$estimated_time = ceil(($_time - $curtime)/86400);
									else
										$estimated_time = 0;
								}
							}

							$estimated_rate = func_array_path($parsed, $type."/ENTRY/$k/ESTIMATEDCHARGES/DISCOUNTEDCHARGES/NETCHARGE/0/#");
					
							foreach ($allowed_shipping_methods as $key=>$value) {
								if ($value["code"] == "FDX" && $value["subcode"] == $fedex_services[$service_type])
									$_fedex_rates_tmp[] = array("methodid"=>$value["subcode"], "rate"=>$estimated_rate, "shipping_time" => $estimated_time);
							}

						}

					}

				}

				if ($debug == "Y") {
				# Display a debug information (on testing real-time shipping page)

					if ($xml_query) {
						$display_query = preg_replace("|<AccountNumber>.+</AccountNumber>|i","<AccountNumber>xxx</AccountNumber>",$xml_query);
						$display_query = preg_replace("|<MeterNumber>.+</MeterNumber>|i","<MeterNumber>xxx</MeterNumber>",$display_query);

						$display_result = preg_replace("|><|", ">\n<", $result);

						print "<h2>FedEx Request</h2>";
						print "<pre>".htmlspecialchars($display_query)."</pre>";
						print "<h2>FedEx Response</h2>";
						print "<pre>".htmlspecialchars($display_result)."</pre>";
					}
					else {
						print "It seems, you have forgotten to fill in a FedEx account information, or destination information (City, State, Country or ZipCode). Please check it, and try again.";
					}

				}

			} // if (empty($_fedex_rates_tmp))

			if (!empty($_fedex_rates_tmp)) {
				$fedex_rates = func_array_merge($fedex_rates, $_fedex_rates_tmp);

				# Save calculated rates to the cache
				if ($debug != "Y")
					func_save_shipping_result_to_cache($md5_request, $_fedex_rates_tmp);
			}

		} // foreach ($carrier_codes as $_carrier_code)

	}

	if (!empty($fedex_rates)) {
		$tmp = array();
		foreach ($fedex_rates as $fedex_rate) {
			if (!in_array($fedex_rate['methodid'], $tmp)) {
				$tmp[] = $fedex_rate['methodid'];
				$intershipper_rates[] = $fedex_rate;
			}
		}
	}

}


#
# This function prepares the XML query
#
function func_fedex_prepare_xml_query($fedex_options, $userinfo) {
	global $config;

	$fedex_options['ship_date'] = date("Y-m-d", (time() + $fedex_options['ship_date']));

	$fedex_options['account_number'] = $config['Shipping']['FEDEX_account_number'];
	$fedex_options['meter_number'] = $config['FEDEX_meter_number'];

	$fedex_options['original_country_code'] = $config["Company"]["location_country"];
	if (in_array($fedex_options['original_country_code'], array('US', 'CA'))) {
		$fedex_options['original_postal_code'] = preg_replace("/[^A-Za-z0-9]/", "", $config["Company"]["location_zipcode"]);
		$fedex_options['original_state_code'] = $config["Company"]["location_state"];
	}

	$fedex_options['destination_country_code'] = $userinfo["s_country"];
	if (in_array($fedex_options['destination_country_code'], array('US', 'CA'))) {
		$fedex_options['destination_postal_code'] = preg_replace("/[^A-Za-z0-9]/", "", $userinfo["s_zipcode"]);
		$fedex_options['destination_state_code'] = $userinfo["s_state"];
	}

	$fedex_options['currency_code'] = "USD";
	$fedex_options['dim_units'] = "IN";

	#
	# Dimensions
	#
	if ($fedex_options['packaging'] == 'YOURPACKAGING') {
		$dimensions_xml = <<<OUT
	<Dimensions>
		<Length>{$fedex_options['dim_length']}</Length>
		<Width>{$fedex_options['dim_width']}</Width>
		<Height>{$fedex_options['dim_height']}</Height>
		<Units>{$fedex_options['dim_units']}</Units>
	</Dimensions>
OUT;
	}
	else
		$dimensions_xml = '';
	
	#
	# Special services
	#
	$special_services = array();

	if ($fedex_options['alcohol'] == 'Y')
		$special_services[] = "<Alcohol>true</Alcohol>";

	if (!empty($fedex_options['cod_value']) && doubleval($fedex_options['cod_value']) > 0) {
		$special_services[] = <<<OUT
<COD>
			<CollectionAmount>{$fedex_options['cod_value']}</CollectionAmount>
			<CollectionType>{$fedex_options['cod_type']}</CollectionType>
		</COD>
OUT;
	}

	if ($fedex_options['hold_at_location'] == 'Y')
		$special_services[] = "<HoldAtLocation>true</HoldAtLocation>";

	if (!empty($fedex_options['dg_accessibility'])) {
		$special_services[] = <<<OUT
<DangerousGoods>
			<Accessibility>{$fedex_options['dg_accessibility']}</Accessibility>
		</DangerousGoods>
OUT;
	}

	if ($fedex_options['dry_ice'] == 'Y')
		$special_services[] = "<DryIce>true</DryIce>";

	if ($fedex_options['residential_delivery'] == 'Y')
		$special_services[] = "<ResidentialDelivery>true</ResidentialDelivery>";

	if ($fedex_options['inside_pickup'] == 'Y')
		$special_services[] = "<InsidePickup>true</InsidePickup>";

	if ($fedex_options['inside_delivery'] == 'Y')
		$special_services[] = "<InsideDelivery>true</InsideDelivery>";

	if ($fedex_options['saturday_pickup'] == 'Y')
		$special_services[] = "<SaturdayPickup>true</SaturdayPickup>";

	if ($fedex_options['saturday_delivery'] == 'Y')
		$special_services[] = "<SaturdayDelivery>true</SaturdayDelivery>";

	if ($fedex_options['nonstandard_container'] == 'Y')
		$special_services[] = "<NonstandardContainer>true</NonstandardContainer>";

	if (!empty($fedex_options['signature']))
		$special_services[] = "<SignatureOption>{$fedex_options['signature']}</SignatureOption>";

	if (!empty($special_services)) {
		$_special_services = '';
		foreach ($special_services as $_service)
			$_special_services .= "\t\t".$_service."\n";

		$special_services_xml = "\t<SpecialServices>\n{$_special_services}\t</SpecialServices>";
	}
	else
		$special_services_xml = '';

	#
	# Handling charges
	#
	if (!empty($fedex_options['handling_charges_amount']) && doubleval($fedex_options['handling_charges_amount']) > 0) {
		$fedex_options['handling_charges_level'] = 'PACKAGE';
		$handling_charges_xml = <<<OUT
	<VariableHandlingCharges>
		<Level>{$fedex_options['handling_charges_level']}</Level>
		<Type>{$fedex_options['handling_charges_type']}</Type>
		<AmountOrPercentage>{$fedex_options['handling_charges_amount']}</AmountOrPercentage>
	</VariableHandlingCharges>
OUT;
	}
	else
		$handling_charges_xml = '';

	#
	# Broker Selection Option
	#
	if ($fedex_options['broker_selection'] == 'Y' && ($fedex_options['original_country_code'] != $fedex_options['destination_country_code']))
		$broker_selection_xml = "<BrokerSelectionOption>true</BrokerSelectionOption>";
	else
		$broker_selection_xml = '';

	#
	# Prepare the XML request
	#
	$xml_query = <<<OUT
<?xml version="1.0" encoding="UTF-8" ?>
<FDXRateAvailableServicesRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="FDXRateAvailableServicesRequest.xsd">
	<RequestHeader>
		<AccountNumber>{$fedex_options['account_number']}</AccountNumber>
		<MeterNumber>{$fedex_options['meter_number']}</MeterNumber>
		<CarrierCode>{$fedex_options['carrier_code']}</CarrierCode>
	</RequestHeader>
	<ShipDate>{$fedex_options['ship_date']}</ShipDate>
	<DropoffType>{$fedex_options['dropoff_type']}</DropoffType>
	<Packaging>{$fedex_options['packaging']}</Packaging>
	<WeightUnits>{$fedex_options['weight_units']}</WeightUnits>
	<Weight>{$fedex_options['weight']}</Weight>
	<ListRate>{$fedex_options['list_rate']}</ListRate>
	<OriginAddress>
		<StateOrProvinceCode>{$fedex_options['original_state_code']}</StateOrProvinceCode>
		<PostalCode>{$fedex_options['original_postal_code']}</PostalCode>
		<CountryCode>{$fedex_options['original_country_code']}</CountryCode>
	</OriginAddress>
	<DestinationAddress>
		<StateOrProvinceCode>{$fedex_options['destination_state_code']}</StateOrProvinceCode>
		<PostalCode>{$fedex_options['destination_postal_code']}</PostalCode>
		<CountryCode>{$fedex_options['destination_country_code']}</CountryCode>
	</DestinationAddress>
	<Payment>
		<PayorType>SENDER</PayorType>
	</Payment>
{$dimensions_xml}
	<DeclaredValue>
		<Value>{$fedex_options['declared_value']}</Value>
		<CurrencyCode>{$fedex_options['currency_code']}</CurrencyCode>
	</DeclaredValue>
{$special_services_xml}
	<PackageCount>1</PackageCount>
{$handling_charges_xml}
{$broker_selection_xml}
</FDXRateAvailableServicesRequest>
OUT;
	
	return $xml_query;
}

?>
