<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: sf_configuration.php,v 1.0 2010/12/10 15:29:24 kate Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

x_load('backoffice', 'image');
x_session_register('file_upload_data');


/**
 * Process POST request 
 */
if ($REQUEST_METHOD == 'POST' && $option == 'Multiple_Storefronts' && is_array($domain_specific_config)) {

	$selected_sf = (int) $HTTP_POST_VARS['storefrontid'];

	// Update settings for selected storefront
	$var_properties = func_get_default_config('S');

	if (!empty($selected_sf)) {
		func_array2update('storefronts_config', array('value' => 'N'), "type IN('checkbox','multiselector') AND storefrontid = '$selected_sf'");
					} else {
		func_array2update('config', array('value' => 'N'), "type IN('checkbox','multiselector') AND name IN('" . implode("','", array_keys($var_properties)) . "')");
					}

	// Check image permissions
	$perms_S = func_check_image_storage_perms($file_upload_data, 'S');

	if ($perms_S !== true) {

		$top_message['type'] = 'E';
		$top_message['content'] = $perms_S['content'];

		func_header_location('configuration.php?option=Multiple_Storefronts');
	}

	// Image processing
	if (func_check_image_posted($file_upload_data, 'S')) {
		func_save_image($file_upload_data, 'S', $selected_sf);
	}

	// Image processing (custom field)
	if (is_array($HTTP_POST_FILES) && isset($HTTP_POST_FILES['file_edit_image'])) {

		$custom_image_options = array(
			'id'                 => $selected_sf,
			'from_parent_window' => 'Y',
			'source'             => 'L',
			'filename'           => 'file_edit_image',
			'type'               => 'S',
			'userfile'           => $HTTP_POST_FILES['file_edit_image']['name'],
			'userfile_size'      => $HTTP_POST_FILES['file_edit_image']['size'],
			'userfile_type'      => $HTTP_POST_FILES['file_edit_image']['type'],
		);
		
		if (!empty($custom_image_options['userfile'])) {
			extract($custom_image_options);
			include $xcart_dir . '/include/image_selection.php';
		}
	}
	
	$errors_msg = array();

	foreach ($HTTP_POST_VARS as $key => $val) {
		
		if (!isset($var_properties[$key])) {
			continue;
					}

		$val = (is_string($val)) ? trim($val) : $val;

		if (
			'opt_order_prefix' == $key
			&& !empty($val)
			&& !func_msf_is_unique_order_prefix($val, $selected_sf)
		) {
			// check order prefix
			$errors_msg[] = func_get_langvar_by_name('msg_adm_order_prefix_is_not_unique');

			continue;
				}
	
		if ($var_properties[$key] == 'numeric') {
			
			$val = doubleval(func_convert_numeric($val));

		} else if ($var_properties[$key] == 'multiselector') {

			$val = implode(';', $val);
		
		} else if ($var_properties[$key] == 'checkbox' && $val == 'on') {

			$val = (!empty($val)) ? 'Y' : 'N';
			}

		$update_query = array('value' => $val);
		
		if (!empty($selected_sf)) {
			func_array2update('storefronts_config', $update_query, "name = '$key' AND storefrontid = '$selected_sf'");
		} else {
			func_array2update('config', $update_query, "name = '$key'");
		}

		$section_data[stripslashes($key)] = stripslashes($val);
	}

	if (!empty($errors_msg)) {
		$top_message['type'] = 'E';
		$top_message['content'] = implode('<br />', $errors_msg);
	}

	func_header_location('configuration.php?option=Multiple_Storefronts');
}

if (!empty($active_modules['XML_Sitemap']) && $current_storefront > 0) {
	$smarty->assign('additional_config', 'modules/XML_Sitemap/config.tpl');
	$smarty->assign('option', $option);
} else {
	$smarty->assign('additional_config', '');
}

$storefront_info = func_get_storefront_info($current_storefront, 'ID', true);

$smarty->assign('storefront_info', $storefront_info);
$smarty->assign(
	'sf_page_title', 
	func_get_langvar_by_name('lbl_sel_sf_prop', array('sf' => $storefront_info['config']['Company']['company_name']))
);


#
# Check if image selected is not expired
#
if ($file_upload_data['counter'] == 1) {
	$file_upload_data['counter']++;
	$smarty->assign('file_upload_data', $file_upload_data);
} else {
	if ($file_upload_data['source'] == 'L') {
		@unlink($file_upload_data['file_path']);
	}
	x_session_unregister('file_upload_data');
}

?>
