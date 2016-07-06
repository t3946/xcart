<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
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
# $Id: manufacturers.php,v 1.18.2.3 2006/10/11 06:11:31 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','image');

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
require $xcart_dir."/include/countries.php";
require $xcart_dir."/include/states.php";
if ($config["General"]["use_counties"] == "Y")
	include $xcart_dir."/include/counties.php";

# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
$location[] = array(func_get_langvar_by_name("lbl_manufacturers"), "");

#
# NOTES.
# 1. Only administrator can activate manufacturer and set up its position in
# the manufacturers list.
# 2. Provider can view the entire list of manufacturers but edit or delete only
# manufacturers created by the same provider.
# 3. If some manufacturer have assigned products of at least one provider that
# is not owner of this manufacturer, owner will not be able to delete that
# manufacturer.
#
$provider_condition = ($single_mode || $current_area == "A"?"":"AND provider='$login'");

$manufacturerid = intval($manufacturerid);

x_session_register('manufacturer_data_form');

#
##
###
if (($distributor_section == "19" || $distributor_section == "21") && !empty($manufacturerid)){
        include $xcart_dir."/provider/shipping_rates_new.php";
}
elseif ($distributor_section == "22"){
	include $xcart_dir."/admin/product_page_locked_fields.php";
}
###
##
#

#
# Get the number of products that assigned to the manufacturer
# with different $provider (this need for checking permissions)
#
function func_manufacturer_is_used($manufacturerid, $provider) {
	global $sql_tbl;
	return func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products] WHERE manufacturerid='$manufacturerid' AND provider!='$provider'");
}

if ($REQUEST_METHOD == "POST" && $mode == "add_new_line" && $manufacturerid){

	$max_distributor_field_code = func_query_first_cell("SELECT MAX(distributor_field_code) FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid'");

	$max_distributor_field_code++;

	db_query("INSERT INTO $sql_tbl[distributor_contacts] (distributor_field_code, manufacturerid) VALUES ('$max_distributor_field_code', '$manufacturerid')");

	$top_message["content"] = 'Added';
	$top_message['type'] = 'I';
	func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
}

if ($REQUEST_METHOD == "POST" && $mode == "add_distributor_return_address" && $manufacturerid){
	db_query("INSERT INTO $sql_tbl[distributor_return_address] (manufacturerid) VALUES ('$manufacturerid')");
        $top_message["content"] = 'Added';
        $top_message['type'] = 'I';
        func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
}

if ($REQUEST_METHOD == "POST" && $mode == "delete_line" && !empty($manufacturerid) && !empty($delete_line_number)){
	db_query("DELETE FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' AND distributor_field_code='$delete_line_number'");
        $top_message["content"] = 'Deleted';
        $top_message['type'] = 'I';
        func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
}

if ($REQUEST_METHOD == "POST" && $mode == "delete_distributor_return_address" && !empty($manufacturerid) && !empty($delete_distributor_return_address_number)){      
        db_query("DELETE FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid' AND id='$delete_distributor_return_address_number'");
        $top_message["content"] = 'Deleted';
        $top_message['type'] = 'I';
        func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
}

if ($REQUEST_METHOD == "POST" && $mode == "copy_distributor" && $manufacturerid) {
	$bErrorClone = false;

	require_once $xcart_dir."/include/class/classManufacturers.php";
	require_once $xcart_dir."/include/class/classCategories.php";
	$classManufacturer = new classManufacturers();
	$classCategories = new classCategories();

	$storefont_info = func_get_storefront_info($storefront_to_copy_manufacturer, "ID");

	if (!empty($root_categoryid_for_cloned_products) && is_numeric($root_categoryid_for_cloned_products)) {
		$aCloneCategory = $classCategories->getCategoryByIdAndStoreFront($root_categoryid_for_cloned_products, $storefront_to_copy_manufacturer);
		if (empty($aCloneCategory)) {
			$top_message["type"] = "E";
			$top_message["content"] = func_get_langvar_by_name("lb_root_categoryid_for_cloned_products_not_exists");
			$bErrorClone = true;
		}
	}

	if (!$bErrorClone) {
		$aCloneParams = array(
					"manufacturerid" => $manufacturerid,
					"d_main_sf" => $storefront_to_copy_manufacturer,
					"update_approximation_shipping_rates" => "Y",
					"d_search_keyphrase_for_reconciliation" => "",
					"root_categoryid_for_cloned_products" => $root_categoryid_for_cloned_products,
					"parent_manufacturer_id" => $manufacturerid,
					"provider" => $login,
					"sf_prefix" => rtrim($storefont_info["prefix"], "-"),
		);

		$aOriginalManufacturer = $classManufacturer->getMainufacturersInfo(array($manufacturerid));
		$aOriginalManufacturer = reset($aOriginalManufacturer);

		$res = $classManufacturer->cloneManufacturer($aOriginalManufacturer, $aCloneParams);
		if (!$res) {
				$sErrorMessage = "";
				foreach ($classManufacturer->message as $eMessage) {
					$sErrorMessage .= func_get_langvar_by_name($eMessage);
				}
				$top_message["type"] = "E";
				$top_message["content"] = $sErrorMessage;
				$bErrorClone = true;

		} else {
				$top_message["type"] = "I";
				$top_message["content"] = func_get_langvar_by_name("lb_copy_manufacturer_done");
		}
	}
	unset($classCategories);
	unset($classManufacturer);
}

if ($REQUEST_METHOD == "POST" && $mode == "copy_products" && $manufacturerid) {


	require_once $xcart_dir."/include/class/classManufacturers.php";
	$classManufacturer = new classManufacturers();

	if (!empty($product_to_copy_manufacturer)) {
		$countAddedProducts = $classManufacturer->addProductsToQueue($manufacturerid, $product_to_copy_manufacturer);
		$top_message["type"] = "I";
		$top_message["content"] = sprintf('%d products added to clone queue... Processing takes some time ...', $countAddedProducts);
	} else {
		$top_message["type"] = "E";
		$top_message["content"] = "Target distributor not selected";
	}
	unset($classManufacturer);

}

if ($REQUEST_METHOD == "POST" && $mode == "update_root_category" && $manufacturerid) {

	$aUpdateParam = array (
		"root_categoryid_for_cloned_products" => $root_categoryid_for_cloned_products
	);
	if (func_array2update("manufacturers", $aUpdateParam, "manufacturerid=".$manufacturerid))
		$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_upd");
}

if ($REQUEST_METHOD == "POST" || ($mode == "delete_image" && $manufacturerid)) {  //TODO check this shit


	if ($mode == "details" && ($image_perms = func_check_image_storage_perms($file_upload_data, "M")) !== true) {
		# Check permissions
		$top_message = array(
			"content" => $image_perms['content'],
			"type" => "E"
		);

	} elseif ($mode == "details") {
	#
	# Modify manufacturer details
	#
		if ($current_area == 'P') {
			$orderby = 10;
		}

		$orderby = intval($orderby);

		if (!empty($manufacturerid)) {

#
##
###
			$current_manufacturer_info = func_query_first("SELECT * FROM $sql_tbl[manufacturers] WHERE manufacturerid = '$manufacturerid'");

			if (!empty($products_quantity_behavior) && $distributor_section == "20"){

				if ($display_quantity_of != ""){
					$display_quantity_of = abs(intval($display_quantity_of));
				}

				$current_products_quantity_behavior = $current_manufacturer_info["products_quantity_behavior"];
				$current_display_quantity_of = $current_manufacturer_info["display_quantity_of"];

				if ($products_quantity_behavior != $current_products_quantity_behavior && $products_quantity_behavior == "R"){
				// use real quantity on storefront
					db_query("UPDATE $sql_tbl[products] SET avail = r_avail WHERE manufacturerid='$manufacturerid' AND r_avail>0");
					db_query("UPDATE $sql_tbl[variants] v LEFT JOIN $sql_tbl[products] p ON p.productid = v.productid SET v.avail = p.r_avail WHERE p.manufacturerid='$manufacturerid' AND p.r_avail>0");

				}


                                if (
					$products_quantity_behavior == "D" && $display_quantity_of > 0
					&&
					($products_quantity_behavior != $current_products_quantity_behavior || $current_display_quantity_of != $display_quantity_of)
				){
					db_query("UPDATE $sql_tbl[products] SET avail = '$display_quantity_of' WHERE manufacturerid='$manufacturerid' AND r_avail>0");
					db_query("UPDATE $sql_tbl[variants] v LEFT JOIN $sql_tbl[products] p ON p.productid = v.productid SET v.avail = '$display_quantity_of' WHERE p.manufacturerid='$manufacturerid' AND p.r_avail>0");
                                }

				db_query("UPDATE $sql_tbl[products] SET avail='0' WHERE r_avail='0' AND manufacturerid='$manufacturerid'");
			}
###
##
#
			$manufacturer = trim($manufacturer);


			if (empty($manufacturer)) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_empty");
				$top_message['type'] = 'E';
				func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : "") );

			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer' AND manufacturerid != '$manufacturerid'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_exist");
				$top_message['type'] = 'E';
				func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
  			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE code = '$code' AND manufacturerid != '$manufacturerid'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_code_exist");
				$top_message['type'] = 'E';
				func_header_location("manufacturers.php?manufacturerid=".$manufacturerid .($distributor_section ? "&distributor_section=".$distributor_section : ""));
  			}

		#
		# Update the manufacturer details
		#
			if (!empty($provider_condition))
			#
			# Check the permissions to update manufacturer details
			#
				$do_not_touch = (func_manufacturer_is_used($manufacturerid, $login) > 0);
			else
				$do_not_touch = false;

			$query_data = array(
#
##
###
                        "reverse_sku" => $reverse_sku,
                        "remove_dashes" => $remove_dashes,

			"products_quantity_behavior" => $products_quantity_behavior,
			"display_quantity_of" => $display_quantity_of,
###
##
#
				"url" => trim($url),
				'cost_to_us_coef_x' => floatval($cost_to_us_coef_x),
				'map_price_coef_x' => floatval($map_price_coef_x),
				'new_map_price_coef_x' => floatval($new_map_price_coef_x),
				'price_coef_x' => floatval($price_coef_x),
				'price_coef_y' => floatval($price_coef_y),
				'price_coef_z' => floatval($price_coef_z),
				"catalog_sku" => trim($catalog_sku),
				"catalog_price" => $catalog_price != '' ? price_format($catalog_price) : '',
				"catalog_text" => $catalog_text,
#
##
###
				"add_cost_to_us_column_to_dispatch_message" => $add_cost_to_us_column_to_dispatch_message,
                                "d_pay_to_distributor_by" => addslashes($d_pay_to_distributor_by),
                                "d_we_can_save" => addslashes($d_we_can_save),
                                "d_pay_to_distributor_save_text" => addslashes($d_pay_to_distributor_save_text),

				"d_product_catalog" => addslashes($d_product_catalog),
				"d_price_list" => addslashes($d_price_list),
				"d_map_policy" => addslashes($d_map_policy),
				"d_map_prices" => addslashes($d_map_prices),
				"d_shipping_weights_dimensions" => addslashes($d_shipping_weights_dimensions),
				"d_website_search_for_sku_url" => addslashes($d_website_search_for_sku_url),

				"d_ships_to_within" => addslashes($d_ships_to_within),
				"d_shipping_methods_usps" => addslashes($d_shipping_methods_usps),
				"d_shipping_methods_ups" => addslashes($d_shipping_methods_ups),
				"d_shipping_methods_fedex" => addslashes($d_shipping_methods_fedex),
				"d_shipping_methods_trucking_company" => addslashes($d_shipping_methods_trucking_company),
				"d_shipping_methods_other" => addslashes($d_shipping_methods_other),
				"d_drop_ship_fee_select" => addslashes($d_drop_ship_fee_select),
				"d_drop_ship_fee_in_us" => addslashes($d_drop_ship_fee_in_us),
				"d_minimum_order_amount" => addslashes($d_minimum_order_amount),
				"d_minimum_order_amount_in_us" => addslashes($d_minimum_order_amount_in_us),
				"d_for_orders_below_min_order_amount" => addslashes($d_for_orders_below_min_order_amount),
				"d_dealer_discount_reduced_from" => addslashes($d_dealer_discount_reduced_from),
				"d_dealer_discount_reduced_to" => addslashes($d_dealer_discount_reduced_to),
			
				"distributor_offers_free_shipping" => $distributor_offers_free_shipping,	
				"free_shipping_on_orders_over_value" => $free_shipping_on_orders_over_value,	

				"warehouse_pickups_are_allowed" => $warehouse_pickups_are_allowed,	
				"d_our_dealer_account_n" => $d_our_dealer_account_n,	
				"d_preferred_way_submit_orders" => addslashes($d_preferred_way_submit_orders),
				"d_url_to_login_to_distributor_website" => addslashes($d_url_to_login_to_distributor_website),
				"d_login" => addslashes($d_login),
				"d_password" => addslashes($d_password),
				"d_submit_to_order_entry_operator" => addslashes($d_submit_to_order_entry_operator),
				"d_order_entry_operator_email" => addslashes($d_order_entry_operator_email),
				"d_instructions_to_order_entry_operator" => $d_instructions_to_order_entry_operator,
				"d_distributor_return_policy" => $d_distributor_return_policy,

				"d_tax_policy_in_states" => addslashes($d_tax_policy_in_states),
				"d_dispatch_instructions" => addslashes($d_dispatch_instructions),

				"d_warranty_starts_when_order_is" => addslashes($d_warranty_starts_when_order_is),
				"d_warranty_last_day" => addslashes($d_warranty_last_day),
				"d_re_stocking_fee_for_authorized_returns" => addslashes($d_re_stocking_fee_for_authorized_returns),
				"d_re_stocking_fee_for_unauthorized_returns" => addslashes($d_re_stocking_fee_for_unauthorized_returns),

				"d_we_pay_to_distributor_by" => addslashes($d_we_pay_to_distributor_by),
				"d_net_payment_terms_in_days" => addslashes($d_net_payment_terms_in_days),
				"d_bulk_or_individual_order_payments" => addslashes($d_bulk_or_individual_order_payments),

				"d_available_on_distributor_site_checkbox" => addslashes($d_available_on_distributor_site_checkbox),
				"d_sent_by_email_to" => addslashes($d_sent_by_email_to),
				"d_put_on_the_invoices" => addslashes($d_put_on_the_invoices),
				"d_available_on_distributor_site_url" => addslashes($d_available_on_distributor_site_url),
				"d_sent_by_email_to_email_address" => addslashes($d_sent_by_email_to_email_address),
				"d_invoices_sent_by_email_to" => addslashes($d_invoices_sent_by_email_to),
				"d_invoices_sent_by_fax_to" => addslashes($d_invoices_sent_by_fax_to),
				"d_invoices_sent_to" => addslashes($d_invoices_sent_to),
				"d_invoices_by_fax_sent_to" => addslashes($d_invoices_by_fax_sent_to),
				"d_invoices_mailed_to_our" => addslashes($d_invoices_mailed_to_our),
				"d_invoices_mailed_to_our_checkbox" => addslashes($d_invoices_mailed_to_our_checkbox),

				"d_availability_must_be_checked" => addslashes($d_availability_must_be_checked),
				"d_send_to_email_14" => addslashes($d_send_to_email_14),
				"d_message_body_14" => $d_message_body_14,
				"d_email_subject_14" => addslashes($d_email_subject_14),

				"d_link_to_order_distributors_website" => addslashes($d_link_to_order_distributors_website),

				"d_sec14_show_header" => $d_sec14_show_header,
				"d_sec14_show_items_stock" => $d_sec14_show_items_stock,
				"d_sec14_show_shipto" => $d_sec14_show_shipto,
				"d_sec14_show_items_cost" => $d_sec14_show_items_cost,
				"d_sec14_show_footer" => $d_sec14_show_footer,
				"allow_pre_orders" => $allow_pre_orders,
				"add_ca_status_id" => $add_ca_status_id,
				"allow_dispatch_off_working_hours" => $allow_dispatch_off_working_hours,

				"lead_time_message" => $lead_time_message,
				"d_send_to_email_for_templates" => $d_send_to_email_for_templates,
				"d_contact_name_for_templates" => $d_contact_name_for_templates,
				"d_server_min_distributor_time" => $d_server_min_distributor_time,
//				"d_product_questions_send_to_email" => $d_product_questions_send_to_email,
//				"d_product_questions_send_to_name" => $d_product_questions_send_to_name,
//				"d_product_questions_send_to_phone" => $d_product_questions_send_to_phone,
				"d_shipping_options" => trim($d_shipping_options),
				"d_specific_instructions" => trim($d_specific_instructions),
				"d_subject_line_8" => trim($d_subject_line_8),
				"d_order_entry_operator_subject_line_8" => trim($d_order_entry_operator_subject_line_8),
				"d_main_sf" => trim($d_main_sf),

				"d_enable_feed" => $d_enable_feed,
				"d_feed_updation_frequency" => trim($d_feed_updation_frequency),
				"d_ftp_host" => trim($d_ftp_host),
				"d_ftp_login" => trim($d_ftp_login),
				"d_ftp_password" => trim($d_ftp_password),
				"d_ftp_folder" => trim($d_ftp_folder),
				"product_feeds_comments" => trim($product_feeds_comments),
				"d_feed_procedure_id" => trim($d_feed_procedure_id),
				"d_product_management_team_email" => trim($d_product_management_team_email),
				"d_last_feed_rows_processed" => trim($d_last_feed_rows_processed),
				"d_validation_threshold" => trim($d_validation_threshold),
				"supplier_products_price_multiplier" => trim($supplier_products_price_multiplier),
				"d_search_keyphrase_for_reconciliation" => trim($d_search_keyphrase_for_reconciliation),
				"update_approximation_shipping_rates" => trim($update_approximation_shipping_rates),
//				"shipping_rates_last_update_date" => trim($shipping_rates_last_update_date),
				"USE_MY_UPS_FEDEX_ACCOUNT_functionality" => trim($USE_MY_UPS_FEDEX_ACCOUNT_functionality),
				"USE_MY_TRUCKING_ACCOUNT_functionality" => trim($USE_MY_TRUCKING_ACCOUNT_functionality),

				"dcad_bank_name" => trim($dcad_bank_name),
				"dcad_address" => trim($dcad_address),
				"dcad_address_2" => trim($dcad_address_2),
				"dcad_city" => trim($dcad_city),
				"dcad_country" => trim($dcad_country),
				"dcad_state" => trim($dcad_state),
				"dcad_zipcode" => trim($dcad_zipcode),
				"dcad_company_name" => trim($dcad_company_name),
				"dcad_routing_number" => trim($dcad_routing_number),
				"dcad_account_number" => trim($dcad_account_number),
###
##
#

# START: random:20341 [2010 Jul 29 14:46] 
				"code" => trim($code),
# END: random:20341 [2010 Jul 29 14:46] 
				"descr" => $descr
			);
			$query_data_lng = array(
				"manufacturerid" => $manufacturerid,
				"code" => $shop_language,
				"descr" => $descr
			);
			if (!$do_not_touch) {
				$query_data_lng['manufacturer'] = $manufacturer;
				if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer'") == 0)
					$query_data['manufacturer'] = $manufacturer;
			}

			if ($shop_language != $config['default_admin_language']) {
				func_unset($query_data, "manufacturer", "descr");
			}

//			if (empty($provider_condition)) {
				$query_data['avail'] = $avail;
				$query_data['orderby'] = $orderby;
//			}

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
//			if (!empty($b_address) && !empty($b_city) && !empty($b_country) && !empty($b_state) && !empty($b_zipcode)) {
				$query_data['m_address'] = $b_address;
				$query_data['m_address_2'] = $b_address_2;
				$query_data['m_city'] = $b_city;
				$query_data['m_country'] = $b_country;
				$query_data['m_state'] = $b_state;
				$query_data['m_zipcode'] = $b_zipcode;
//			}	
			
			if ($login_type == 'P') {
				$selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
				if (!empty($selected_manufacturers)) {
					$selected_manufacturers = unserialize($selected_manufacturers);
				}
				$selected_manufacturers[] = $manufacturerid;
				db_query("UPDATE $sql_tbl[customers] SET manufacturerids = '".addslashes(serialize($selected_manufacturers))."' WHERE  login='$login' AND usertype='$login_type'");
			}

			$query_data['email'] = $email;
			$query_data['mess_body'] = $mess_body;
			$query_data['submit_to_operator'] = ($submit_to_operator == 'through_distributor_website') ? $submit_to_operator = 'through_distributor_website' : $submit_to_operator = 'by_email_or_and_fax';
			if ($query_data['submit_to_operator'] == 'through_distributor_website') {
				$query_data['allow_dispatch_off_working_hours'] = 'N';
			}

			$query_data['manufact_text_displayed'] = $manufact_text_displayed;
			$query_data['cart_manufact_text_displayed'] = $cart_manufact_text_displayed;

# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
			func_array2update("manufacturers", $query_data, "manufacturerid='$manufacturerid' ".$provider_condition);
			func_array2insert("manufacturers_lng", $query_data_lng, true);

#
##
###
			db_query("DELETE FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid'");
			if (!empty($distributor_contacts) && is_array($distributor_contacts)){


				foreach ($distributor_contacts as $field_code => $v){
			
					if ($field_code == $pq){
						$v["pq"] = "Y";

						db_query("UPDATE $sql_tbl[manufacturers] SET d_product_questions_send_to_name='".$v["contact_name"]."', d_product_questions_send_to_phone='".addslashes($v["phone"])."', d_product_questions_send_to_email='".addslashes($v["email"])."' WHERE manufacturerid='$manufacturerid'");

					} else {
						$v["pq"] = "";
					}

					db_query("INSERT INTO $sql_tbl[distributor_contacts] (manufacturerid, distributor_field_name, distributor_field_code, contact_name, email, phone, ext, fax, pq) VALUES ('$manufacturerid', '".addslashes($v["distributor_field_name"])."', '$field_code', '".$v["contact_name"]."', '".addslashes($v["email"])."', '".addslashes($v["phone"])."', '".addslashes($v["ext"])."', '".addslashes($v["fax"])."', '$v[pq]')");
				}
			}


			$return_address_ids = func_query("SELECT id FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid'");

			if (!empty($return_address_ids) && is_array($return_address_ids)){
				foreach ($return_address_ids as $k_a => $v_a){

					$tmp_warehouse_name = "warehouse_name_".$v_a["id"];
					$tmp_full_name = "full_name_".$v_a["id"];
					$tmp_company = "company_".$v_a["id"];
					$tmp_address = "address_".$v_a["id"];
					$tmp_address_2 = "address_2_".$v_a["id"];
					$tmp_city = "city_".$v_a["id"];
					$tmp_country = "country_".$v_a["id"];
					$tmp_state = "state_".$v_a["id"];
					$tmp_zipcode = "zipcode_".$v_a["id"];
					$tmp_phone = "phone_".$v_a["id"];
					$tmp_ext = "ext_".$v_a["id"];
	
					db_query("UPDATE $sql_tbl[distributor_return_address] SET warehouse_name='".$$tmp_warehouse_name."', full_name='".$$tmp_full_name."', company='".$$tmp_company."', address='".$$tmp_address."', address_2='".$$tmp_address_2."', city='".$$tmp_city."', country='".$$tmp_country."', state='".$$tmp_state."', zipcode='".$$tmp_zipcode."', phone='".$$tmp_phone."', ext='".$$tmp_ext."' WHERE id='$v_a[id]'");
				}
			}
###
##
#

#
##
###
			if ($distributor_section == "18"){

				$current_fields_in_supplier_product_feeds = func_query_first("SELECT * FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid='$manufacturerid'");

				db_query("DELETE FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid='$manufacturerid'");
				db_query("INSERT INTO $sql_tbl[supplier_product_feeds] (manufacturerid, storefrontid, enabled_feed, ftp_host, ftp_login, ftp_password, ftp_folder, feed_procedure_id, default_productid, product_management_team_email, comments, last_import_date, last_imported_updated_products_count, is_launched, import_new_products, import_new_and_update_existing_products, updation_frequency, last_products_count_in_file, default_parent_categoryid) VALUES ('$manufacturerid', '".trim($spf_storefrontid)."', '".trim($spf_enabled_feed)."', '".trim($spf_ftp_host)."', '".trim($spf_ftp_login)."', '".trim($spf_ftp_password)."', '".trim($spf_ftp_folder)."', '".trim($spf_feed_procedure_id)."', '".trim($spf_default_productid)."', '".trim($spf_product_management_team_email)."', '".trim($spf_comments)."', '$current_fields_in_supplier_product_feeds[last_import_date]', '$current_fields_in_supplier_product_feeds[last_imported_updated_products_count]', '$current_fields_in_supplier_product_feeds[is_launched]', '$spf_import_new_products', '$spf_import_new_and_update_existing_products', '".trim($spf_updation_frequency)."', '$current_fields_in_supplier_product_feeds[last_products_count_in_file]', '".trim($spf_default_parent_categoryid)."')");
			}
###
##
#



			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_upd");

		}
		else {
		#
		# Add new manufacturer
		#
			$fillerror = true;

			$manufacturer = trim($manufacturer);

			if (empty($manufacturer)) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_empty");
				$top_message['type'] = 'E';

			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_exist");
				$top_message['type'] = 'E';

			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE code = '$code'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_code_exist");
				$top_message['type'] = 'E';
			} else {
				$fillerror = false;
			}

			if (!$fillerror) {

				if ($orderby <= 0)
					$orderby = func_query_first_cell("SELECT MAX(orderby) FROM $sql_tbl[manufacturers]") + 10;

				if ($login_type == 'P') {
					$avail = 'Y';
				}

				$query_data = array(
					"manufacturer" => $manufacturer,
					"avail" => $avail,
					"orderby" => $orderby,
					"provider" => $login,
					"descr" => $descr,
# START: random:20341 [2010 Jul 29 14:46] 
					"code" => trim($code),
# END: random:20341 [2010 Jul 29 14:46] 
					"url" => trim($url)
				);
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 

				if (!empty($b_address) && !empty($b_city) && !empty($b_country) && !empty($b_state) && !empty($b_zipcode)) {
					$query_data['m_address'] = $b_address;
					$query_data['m_address_2'] = $b_address_2;
					$query_data['m_city'] = $b_city;
					$query_data['m_country'] = $b_country;
					$query_data['m_state'] = $b_state;
					$query_data['m_zipcode'] = $b_zipcode;
				}
				$query_data['email'] = $email;
				$query_data['mess_body'] = $mess_body;
				$query_data['manufact_text_displayed'] = $manufact_text_displayed;
				$query_data['cart_manufact_text_displayed'] = $cart_manufact_text_displayed;


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
				$manufacturerid = func_array2insert("manufacturers", $query_data);

				if (!empty($operators)) {
					$customers = func_query_hash("SELECT login, manufacturerids FROM $sql_tbl[customers] WHERE login IN ('" . implode("','", $operators) . "')", 'login', false, true);
                    
					foreach ($operators as $op) {
						if (empty($customers[$op])) {
							continue;
						}
                        
						$customers[$op] = unserialize($customers[$op]);
						$customers[$op][] = $manufacturerid;
                        
						db_query("UPDATE $sql_tbl[customers] SET manufacturerids='" . serialize($customers[$op]) . "' WHERE login='$op'");
					}
				}

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
				if ($login_type == 'P') {
					$selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
					if (!empty($selected_manufacturers)) {
						$selected_manufacturers = unserialize($selected_manufacturers);
					}
					$selected_manufacturers[] = $manufacturerid;
					db_query("UPDATE $sql_tbl[customers] SET manufacturerids = '".addslashes(serialize($selected_manufacturers))."' WHERE  login='$login' AND usertype='$login_type'");
				}


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
				$query_data = array(
					"manufacturerid" => $manufacturerid,
					"code" => $shop_language,
					"manufacturer" => $manufacturer,
					"descr" => $descr
				);
				func_array2insert("manufacturers_lng", $query_data);

				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_add");
			} else {
				$manufacturer_data_form = $_POST;
				$data_names = array(
					'b_address' => 'm_address',
					'b_address_2' => 'm_address_2',
					'b_city' => 'm_city',
					'b_country' => 'm_country',
					'b_state' => 'm_state',
					'b_county' => 'm_county',
					'b_zipcode' => 'm_zipcode'
				);
				$form_names = array_keys($data_names);
				foreach ($manufacturer_data_form as $k => $v) {
					if (in_array($k, $form_names)) {
						unset($manufacturer_data_form[$k]);
						$manufacturer_data_form[$data_names[$k]] = $v;
					}
					if (!is_array($v)) {
						$manufacturer_data_form[$k] = stripslashes($v);
			}
		}
				func_header_location('manufacturers.php?mode=add');
			}
			x_session_unregister('manufacturer_data_form');
		}

		if (func_check_image_posted($file_upload_data, "M") && $manufacturerid > 0) {
			func_save_image($file_upload_data, "M", $manufacturerid);
		}

	}
	elseif ($mode == "delete" and !empty($to_delete) && is_array($to_delete)) {
	#
	# Delete selected manufacturers
	#
		$ids = func_query_column("SELECT manufacturerid FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('".implode("','", array_keys($to_delete))."') ".$provider_condition);
		if (!empty($ids)) {
			db_query("DELETE FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('".implode("','", $ids)."')");
			db_query("DELETE FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid IN ('".implode("','", $ids)."')");
			db_query("DELETE FROM $sql_tbl[manufacturers_lng] WHERE manufacturerid IN ('".implode("','", $ids)."')");
			db_query("UPDATE $sql_tbl[products] SET manufacturerid = 0 WHERE manufacturerid IN ('".implode("','", $ids)."')");
			func_delete_image($ids, "M");
			$top_message["content"] = func_get_langvar_by_name("msg_adm_manufacturer_del");
		}
	}
	elseif ($mode == "delete_image" && $manufacturerid) {
	#
	# Delete image of selected manufacturer
	#
		func_delete_image($manufacturerid, "M");
	}
	elseif ($mode == "update" and empty($provider_condition)) {
	#
	# Update manufacturers list
	#
		if (is_array($records)) {
			foreach ($records as $k=>$v) {
				$v["avail"] = (empty($v["avail"]) ? "N" : "Y");
				$v["orderby"] = intval($v["orderby"]);
				db_query("UPDATE $sql_tbl[manufacturers] SET avail='$v[avail]', orderby='$v[orderby]' WHERE manufacturerid='$k' $provider_condition");
			}
			$top_message["content"] = func_get_langvar_by_name("msg_adm_manufacturers_upd");
		}
	}
	elseif ($mode == "export_emails"){

		$distributor_contacts = func_query("SELECT * FROM $sql_tbl[distributor_contacts] WHERE email!='' AND contact_name!=''");

		$distributors_list = "";

		if (!empty($distributor_contacts)){
			foreach ($distributor_contacts as $k => $v){
				$distributors_list .= $v["contact_name"]." <".$v["email"].">\r\n";
			}
		}

		$fh=fopen($xcart_dir."/files/distributor_contacts.txt","w"); 
		fwrite($fh,$distributors_list); 
		fclose($fh); 

		func_header_location("manufacturers.php?word=num");
	}

	$page_str = (!empty($page) ? "&page=$page" : "");

	func_header_location("manufacturers.php?manufacturerid=$manufacturerid" . $page_str . '&word=' . $word .($distributor_section ? "&distributor_section=".$distributor_section : ""));
}



	if (is_file($xcart_dir."/files/distributor_contacts.txt")){
	$distributor_contacts_file_name = "distributor_contacts.txt";
	$smarty->assign('distributor_contacts_file_name', $distributor_contacts_file_name);
	$smarty->assign('distributor_contacts_file', $xcart_dir."/files/".$distributor_contacts_file_name);
}

#
# Process the GET request
#

if ($mode == "add" or !empty($manufacturerid)) {
#
# Get the manufacturer data and display manufacturer details page
#
	if ($mode == 'add') {
		$active_operators = func_query("SELECT login, b_firstname, b_lastname FROM $sql_tbl[customers] WHERE usertype='P' AND status='Y' AND activity='Y' ORDER BY login");
        
		$smarty->assign('operators', $active_operators);
	}

	$location[count($location)-1][1] = "manufacturers.php?word=num";

	if (!empty($manufacturerid)) {
		$manufacturer_data = func_query_first("SELECT $sql_tbl[manufacturers].*, IF($sql_tbl[images_M].id IS NULL, '', 'Y') as is_image, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer, IFNULL($sql_tbl[manufacturers_lng].descr, $sql_tbl[manufacturers].descr) as descr FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers_lng].manufacturerid = $sql_tbl[manufacturers].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' LEFT JOIN $sql_tbl[images_M] ON $sql_tbl[images_M].id = $sql_tbl[manufacturers].manufacturerid WHERE $sql_tbl[manufacturers].manufacturerid = '$manufacturerid'");

		if (empty($manufacturer_data)) {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_not_exists");
			$top_message["type"] = "E";
			func_header_location("manufacturers.php");
		}
		else {
			$manufacturer_data["used_by_others"] = func_manufacturer_is_used($manufacturerid, $manufacturer_data["provider"]);
			$location[] = array($manufacturer_data["manufacturer"], "");

#
##
###

        		$distributor_field_codes = func_query("SELECT distributor_field_code FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' ORDER BY distributor_field_code");
			if (!empty($distributor_field_codes) && is_array($distributor_field_codes)){
				foreach ($distributor_field_codes as $k => $v){
					$distributor_contacts_values = func_query_first("SELECT * FROM $sql_tbl[distributor_contacts] WHERE manufacturerid = '$manufacturerid' AND distributor_field_code='$v[distributor_field_code]'");
	                                if (!empty($distributor_contacts_values) && is_array($distributor_contacts_values)){
        	                                $manufacturer_data["distributor_contacts"][$v["distributor_field_code"]] = $distributor_contacts_values;
                	                }
				}
			}

			$distributor_return_addresses = func_query("SELECT * FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid' ORDER BY warehouse_name");
			$manufacturer_data["distributor_return_addresses"] = $distributor_return_addresses;


#
##
                        $tmp_cur_time_sec = time();
                        $d_server_min_distributor_time_sec = $manufacturer_data["d_server_min_distributor_time"] * 60 *60;
                        $tmp_cur_time_sec -= $d_server_min_distributor_time_sec;
                        $manufacturer_data["distributor_time"] = $tmp_cur_time_sec;
                        $tmp_cur_time_date_format = date("G.i", $tmp_cur_time_sec);
                        $tmp_date_mm_dd_yyyy = date("m/d/Y", $tmp_cur_time_sec);
                        // $tmp_cur_time_sec += 2*24*60*60; // for checking
                        $tmp_number_of_day_of_week = date("w", $tmp_cur_time_sec); // 0 (for Sunday) through 6 (for Saturday)
                        // func_print_r($tmp_number_of_day_of_week, $tmp_cur_time_date_format); // for checking

                        if ($tmp_cur_time_date_format >= "8.30" && $tmp_cur_time_date_format <= "16.30" && ($tmp_number_of_day_of_week != "0" && $tmp_number_of_day_of_week != "6")){

				$request_availability_options = func_query("SELECT * FROM $sql_tbl[request_availability_options]");

                                if (!empty($request_availability_options) && is_array($request_availability_options)){
                                	foreach ($request_availability_options as $k_r => $v_r){
		                                if ($v_r["date_mm_dd_yyyy"] == $tmp_date_mm_dd_yyyy && $v_r["active"] == "Y"){
                	                                $good_time_to_send_email_to_distributor = "N";
                                                }
                                        }
                                }

                                if ($good_time_to_send_email_to_distributor != "N"){
                        	        $good_time_to_send_email_to_distributor = "Y";
                                }

                                $manufacturer_data["good_time_to_send_email_to_distributor"] = $good_time_to_send_email_to_distributor;
                         } else {
                                $manufacturer_data["good_time_to_send_email_to_distributor"] = "N";
                         }

                         $manufacturer_data["distributor_phone"] = func_query_first_cell("SELECT phone FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' AND phone!='' ORDER BY distributor_field_code asc LIMIT 1");

                         $phone_normalized = preg_replace("/[^0-9]/S","", $manufacturer_data["distributor_phone"]);
                         if (strlen($phone_normalized) == "10"){
	                         $manufacturer_data["distributor_phone_phone_normalized"] = "+1".$phone_normalized;
                         }
##
#

###
##
#
			$smarty->assign("manufacturer", $manufacturer_data);
			$smarty->assign("image", func_image_properties("M", $manufacturerid));
		}

	} else {


		if (!empty($manufacturer_data_form))
			$smarty->assign('manufacturer', $manufacturer_data_form);


		x_session_unregister('manufacturer_data_form');
		$location[] = array(func_get_langvar_by_name("lbl_add_manufacturer"), "");
	}

	$smarty->assign("mode", "manufacturer_info");
}
else {
#
# Get and display the manufacturers list
#
	$where = '';
	if (!empty($word)) {
		if (in_array($word, range('a','z'))) {
			$where = " WHERE m.manufacturer LIKE '$word%'";
		} elseif ($word == 'num') {
			$where = " WHERE m.manufacturer REGEXP '^[0-9]+.*'";
		}
        
        $smarty->assign('word', $word);
        
		$word = 'word=' . $word;
	}

	if (!empty($active_modules['Multiple_Storefronts'])) {
		$total_items = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[manufacturers] m $where");
	} else {
		$total_items = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[manufacturers] m $where");
	}

	if ($total_items > 0) {

		#
		# Prepare the page navigation
		#
		$objects_per_page = $config["Manufacturers"]["manufacturers_per_page"];

		$total_nav_pages = ceil($total_items/$objects_per_page)+1;

		include $xcart_dir."/include/navigation.php";

		require_once $xcart_dir."/include/class/classManufacturers.php";
		$classManufacturer = new classManufacturers();

		#
		# Get the manufacturers list
		#
		if (!empty($active_modules['Multiple_Storefronts'])) {
			$manufacturers = func_query('SELECT m.*, IFNULL(m_lng.manufacturer, m.manufacturer) as manufacturer,'
                . ' CONCAT(c.lastname, ", ", c.firstname) as provider_name, IF(c.login IS NULL,"","Y") as is_provider'
                . ' FROM ' . $sql_tbl['manufacturers'] . ' as m'
                . ' LEFT JOIN ' . $sql_tbl['manufacturers_lng'] . ' as m_lng ON m_lng.manufacturerid = m.manufacturerid'
                    . ' AND m_lng.code = "' . $shop_language . '"'
                . ' LEFT JOIN ' . $sql_tbl['customers'] . ' as c ON m.provider=c.login'
		. $where
                . ' ORDER BY m.orderby, m.manufacturer LIMIT ' . $first_page . ', ' . $objects_per_page);
		} else {
    		$manufacturers = func_query('SELECT m.*, IFNULL(m_lng.manufacturer, m.manufacturer) as manufacturer,'
                . ' CONCAT(c.lastname,", ",c.firstname) as provider_name, IF(c.login IS NULL,"","Y") as is_provider'
                . ' FROM ' . $sql_tbl['manufacturers'] . ' as m'
                . ' LEFT JOIN ' . $sql_tbl['manufacturers_lng'] . ' as m_lng ON m_lng.manufacturerid = m.manufacturerid'
                    . ' AND m_lng.code = "' . $shop_language . '"'
                . ' LEFT JOIN ' . $sql_tbl['customers'] . ' as c ON m.provider=c.login'
		. $where
                . ' ORDER BY m.orderby, m.manufacturer LIMIT ' . $first_page . ', ' . $objects_per_page);
		}

		if (is_array($manufacturers)) {
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
			if ($login_type == 'P' and !empty($login)) {
				 $selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
				 if (!empty($selected_manufacturers)) {
					 $selected_manufacturers = unserialize($selected_manufacturers);
				 }
			}
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
			$products_in_manufacturers = func_query_hash("SELECT COUNT(*), manufacturerid FROM $sql_tbl[products] GROUP BY manufacturerid", 'manufacturerid', false, true);

			foreach ($manufacturers as $k => $v) {
				//$manufacturers[$k]["products_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products] WHERE manufacturerid='$v[manufacturerid]'");
				if (isset($products_in_manufacturers[$v['manufacturerid']])) {
					$manufacturers[$k]["products_count"] = $products_in_manufacturers[$v['manufacturerid']];
				}
				$manufacturers[$k]["used_by_others"] = func_manufacturer_is_used($v["manufacturerid"], $v["provider"]);
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
				if (!@in_array($v['manufacturerid'], $selected_manufacturers) and $login_type == 'P') {
					$total_items = $total_items - 1;
					$total_nav_pages = ceil($total_items / $objects_per_page) + 1;
					unset($manufacturers[$k]);
				}


#
##
###
				if (substr($v["provider_name"], 0, 2) == ", ") {
					$manufacturers[$k]["provider_name"] = substr_replace($v["provider_name"], '', 0, 2);
				}

				$I_feed = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_type='I'");
				if (!empty($I_feed)) {
					$I_feed_Y = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_type='I' AND enabled='Y'");
					if (!empty($I_feed_Y)) {
						$manufacturers[$k]["I_supplier_feeds_enabled"] = "Y(" . $I_feed_Y . ")";
					}

					$I_feed_N = $I_feed - $I_feed_Y;
					if (!empty($I_feed_N)) {
						$manufacturers[$k]["I_supplier_feeds_disabled"] = "N(" . $I_feed_N . ")";
					}
				}

				$P_feed = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_type='P'");
				if (!empty($P_feed)) {
					$P_feed_Y = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_type='P' AND enabled='Y'");
					if (!empty($P_feed_Y)) {
						$manufacturers[$k]["P_supplier_feeds_enabled"] = "Y(" . $P_feed_Y . ")";
					}

					$P_feed_N = $P_feed - $P_feed_Y;
					if (!empty($P_feed_N)) {
						$manufacturers[$k]["P_supplier_feeds_disabled"] = "N(" . $P_feed_N . ")";
					}
				}

				$aChManufacturers = $classManufacturer->getChildrenManufacturers($v['manufacturerid']);
				if (!empty($aChManufacturers)) {
					foreach ($aChManufacturers as $keyChildManufacturer => &$oChManufacturer) {
						$aSFInfo = $classManufacturer->getStoreFronInfo($oChManufacturer['d_main_sf']);
						$oChManufacturer['storefronPrefix'] = rtrim($aSFInfo['sfprefix'], '-');
					}
					$manufacturers[$k]["aChildrenManufacturers"] = $aChManufacturers;
				}



				$aParentManufacturers = $classManufacturer->getParentManufacturers($v['manufacturerid']);
				if (!empty($aParentManufacturers)) {
					foreach ($aParentManufacturers as $keyParentManufacturer => &$oParentManufacturer) {
						$aSFInfo = $classManufacturer->getStoreFronInfo($oParentManufacturer['d_main_sf']);
						$oParentManufacturer['storefronPrefix'] = rtrim($aSFInfo['sfprefix'], '-');
					}
				$manufacturers[$k]["aParentManufacturer"] = $aParentManufacturers;
				}
###
##
#

# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
			}

			$smarty->assign("navigation_script","manufacturers.php?");
			$smarty->assign("manufacturers", $manufacturers);
			$smarty->assign("first_item", $first_page+1);
			$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));

		}

	}

	$smarty->assign('navigation_script', 'manufacturers.php?' . $word);

	$smarty->assign("total_items",$total_items);

	$smarty->assign('words', range('a', 'z'));

}

if (!empty($page))
	$smarty->assign("page", $page);

#
##
###
    $distributor_sections = array();

    $distributor_sections[] = array(
        'title'  => 'General distributor information',
	'order_by' => '10',
        'distributor_section' => '1'
    );
    $distributor_sections[] = array(
        'title'  => 'Quick links',
        'order_by' => '11',
        'distributor_section' => '15'
    );
    $distributor_sections[] = array(
        'title'  => 'Front-end messages',
	'order_by' => '20',
        'distributor_section' => '2'
    );
    $distributor_sections[] = array(
        'title'  => 'Distributor contacts',
	'order_by' => '30',
        'distributor_section' => '3'
    );
/*
    $distributor_sections[] = array(
        'title'  => 'Distributor materials',
        'order_by' => '40',
        'distributor_section' => '4'
    );
*/
    $distributor_sections[] = array(
        'title'  => 'Distributor pricing equations',
        'order_by' => '50',
        'distributor_section' => '5'
    );
    $distributor_sections[] = array(
        'title'  => 'Distributor ships from (for US orders)',
        'order_by' => '60',
        'distributor_section' => '6'
    );
    $distributor_sections[] = array(
        'title'  => 'Distributor shipping policy',
        'order_by' => '70',
        'distributor_section' => '7'
    );
    $distributor_sections[] = array(
        'title'  => 'UPS shipping markups',
        'order_by' => '73',
        'distributor_section' => '19'
    );
    $distributor_sections[] = array(
        'title'  => 'Flat rate shipping markups',
        'order_by' => '74',
        'distributor_section' => '21'
    );
    $distributor_sections[] = array(
        'title'  => 'Requesting availability / shipping quote / cost to us',
        'order_by' => '75',
        'distributor_section' => '14'
    );
    $distributor_sections[] = array(
        'title'  => 'Order submission',
        'order_by' => '80',
        'distributor_section' => '8'
    );
    $distributor_sections[] = array(
        'title'  => 'Order tracking',
        'order_by' => '85',
        'distributor_section' => '12'
    );
    $distributor_sections[] = array(
        'title'  => 'Tax policy',
	'order_by' => '90',
        'distributor_section' => '9'
    );
    $distributor_sections[] = array(
        'title'  => 'Return policy',
	'order_by' => '100',
        'distributor_section' => '10'
    );
    $distributor_sections[] = array(
        'title'  => 'Product page locked fields',
        'order_by' => '105',
        'distributor_section' => '22'
    );
    $distributor_sections[] = array(
        'title'  => 'Distributor invoices',
        'order_by' => '110',
        'distributor_section' => '13'
    );
    $distributor_sections[] = array(
        'title'  => 'Payment to distributor arrangement',
	'order_by' => '120',
        'distributor_section' => '11'
    );
    $distributor_sections[] = array(
        'title'  => 'Product questions',
        'order_by' => '130',
        'distributor_section' => '16'
    );

    $distributor_sections[] = array(
        'title'  => 'Distributor feeds info',
        'order_by' => '140',
        'distributor_section' => '17'
    );

    if ($distributor_section == "17"){
        $supplier_feeds_info_I = func_query("SELECT * FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$manufacturerid' AND feed_type='I'");
	if (!empty($supplier_feeds_info_I)){
	    foreach ($supplier_feeds_info_I as $k_s => $v_s){
		$cur_time = time();
		$date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
		$date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time+$v_s["average_update_period"]));
		$interval = $date1->diff($date2);
		$years = $interval->format("%y");
		$months = $interval->format("%m");
		$days = $interval->format("%d");
		$hours = $interval->format("%h");
		$mins = $interval->format("%i");
		$age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
		$supplier_feeds_info_I[$k_s]["average_update_period_str"] = $age_str;

		$supplier_feeds_info_I[$k_s]["last_feed_fields"] = unserialize(stripslashes($v_s["last_feed_fields"]));
	    }
	}
	$smarty->assign("supplier_feeds_info_I", $supplier_feeds_info_I);

        $supplier_feeds_info_P = func_query("SELECT * FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$manufacturerid' AND feed_type='P'");
        if (!empty($supplier_feeds_info_P)){
	    foreach ($supplier_feeds_info_P as $k_s => $v_s){
                $cur_time = time();
                $date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
                $date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time+$v_s["average_update_period"]));
                $interval = $date1->diff($date2);
                $years = $interval->format("%y");
                $months = $interval->format("%m");
                $days = $interval->format("%d");
                $hours = $interval->format("%h");
                $mins = $interval->format("%i");
                $age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
                $supplier_feeds_info_P[$k_s]["average_update_period_str"] = $age_str;

		$supplier_feeds_info_P[$k_s]["last_feed_fields"] = unserialize(stripslashes($v_s["last_feed_fields"]));
	    }
        }
        $smarty->assign("supplier_feeds_info_P", $supplier_feeds_info_P);
    }

	if ($distributor_section == "30") {
		require_once $xcart_dir."/include/class/classManufacturers.php";
		$classManufacturer = new classManufacturers();
		$aParentManufacturer = $classManufacturer->getChildrenManufacturers($manufacturerid);

		$smarty->assign("aParentManufacturer", $aParentManufacturer);
		$aChildManufacturers = $classManufacturer->getParentManufacturers($manufacturerid);
		$smarty->assign("aChildManufacturers", $aChildManufacturers);
	}

//func_print_r($supplier_feeds_info_I, $supplier_feeds_info_P);


/*
    $distributor_sections[] = array(
        'title'  => 'Product feeds',
        'order_by' => '150',
        'distributor_section' => '18'
    );

if ($distributor_section == "18"){
	$product_feed_info = func_query_first("SELECT * FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid='$manufacturerid'");
	$smarty->assign("product_feed_info", $product_feed_info);
}
*/

    $distributor_sections[] = array(
        'title'  => 'SF product page behavior',
        'order_by' => '160',
        'distributor_section' => '20'
    );

	$distributor_sections[] = array(
		'title'  => 'Clone distributor to another storefront',
		'order_by' => '170',
		'distributor_section' => '30'
	);


//func_print_r($distributor_sections);

$count_rows_in_cell = ceil(count($distributor_sections)/2);

if (empty($distributor_section))
	$distributor_section = 1;

$smarty->assign("distributor_section", $distributor_section);
$smarty->assign("count_rows_in_cell", $count_rows_in_cell);
$smarty->assign("distributor_sections", $distributor_sections);

###
##
#

$ca_statuses = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' AND status!='' ORDER BY orderby");
$smarty->assign('ca_statuses', $ca_statuses);


?>
