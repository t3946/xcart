<?php

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

/**
 * Process POST request 
 */
if ($REQUEST_METHOD === 'POST' && $option === 'Multiple_Storefronts') {

	$selected_sf = (int) $_POST['storefrontid'];

	// Update settings for selected storefront
	$var_properties = func_get_default_config('S');

	if ($selected_sf !== null) {
		\Modules\Sites\Models\SiteConfigModel::objects()->filter(['storefrontid' => $selected_sf, 'type__in' => ['checkbox', 'multiselector']])->update(['value' => 'N']);
	}
	$errors_msg = [];

	$post = \Xcart\App\Main\Xcart::app()->request->post->all();

	foreach ($post as $key => $val) {
		
		if (!isset($var_properties[$key])) {
			continue;
					}

		$val = (is_string($val)) ? trim(stripslashes($val)) : $val;

		if (
			'opt_order_prefix' === $key
			&& !empty($val)
			&& !func_msf_is_unique_order_prefix($val, $selected_sf)
		) {
			// check order prefix
			$errors_msg[] = func_get_langvar_by_name('msg_adm_order_prefix_is_not_unique');

			continue;
				}
	
		if ($var_properties[$key] === 'numeric') {
			
			$val = doubleval(func_convert_numeric($val));

		} else if ($var_properties[$key] === 'multiselector') {

			$val = implode(';', $val);
		
		} else if ($var_properties[$key] === 'checkbox' && $val === 'on') {

			$val = (!empty($val)) ? 'Y' : 'N';
			}

		$update_query = ['value' => $val];
		if (isset(\Modules\Sites\Models\SiteConfigModel::SITE_CONFIG_PARAMS[$key])) {
			$update_query['orderby'] = \Modules\Sites\Models\SiteConfigModel::SITE_CONFIG_PARAMS[$key];
		}

		if ($selected_sf !== null) {
			/** @var \Modules\Sites\Models\SiteConfigModel $con */
			[$con] = \Modules\Sites\Models\SiteConfigModel::objects()->getOrNew(['storefrontid' => $selected_sf, 'name' => $key]);
			$con->setAttributes($update_query);
			$con->save();
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
