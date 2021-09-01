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

    if (!empty($file_upload_data["S"])){
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

    }
    // Image processing (custom field)
    if (is_array($_FILES) && isset($_FILES['file_edit_image'])) {

        $custom_image_options = array(
            'id'                 => $selected_sf,
            'from_parent_window' => 'Y',
            'source'             => 'L',
            'filename'           => 'file_edit_image',
            'type'               => 'S',
            'userfile'           => $_FILES['file_edit_image']['name'],
            'userfile_size'      => $_FILES['file_edit_image']['size'],
            'userfile_type'      => $_FILES['file_edit_image']['type'],
        );

        if (!empty($custom_image_options['userfile'])) {
            extract($custom_image_options);
            include $xcart_dir . '/include/image_selection.php';
        }
    }


    if (!empty($file_upload_data["F"])){
        // Check image permissions
        $perms_F = func_check_image_storage_perms($file_upload_data, 'F');

        if ($perms_F !== true) {

            $top_message['type'] = 'E';
            $top_message['content'] = $perms_F['content'];

            func_header_location('configuration.php?option=Multiple_Storefronts');
        }

        // Image processing
        if (func_check_image_posted($file_upload_data, 'F')) {
            func_save_image($file_upload_data, 'F', $selected_sf);
        }
    }

    // Image processing (custom field)
    if (is_array($_FILES) && isset($_FILES['file_edit_image_favicon'])) {

        $custom_image_options = array(
            'id'                 => $selected_sf,
            'from_parent_window' => 'Y',
            'source'             => 'L',
            'filename'           => 'file_edit_image_favicon',
            'type'               => 'F',
            'userfile'           => $_FILES['file_edit_image_favicon']['name'],
            'userfile_size'      => $_FILES['file_edit_image_favicon']['size'],
            'userfile_type'      => $_FILES['file_edit_image_favicon']['type'],
        );

        if (!empty($custom_image_options['userfile'])) {
            extract($custom_image_options);
            include $xcart_dir . '/include/image_selection.php';
        }
    }

	$errors_msg = [];

	$post = \Xcart\App\Main\Xcart::app()->request->post->all();

    foreach ($post as $key => $val) {

        $val = is_string($val) ? trim(stripslashes($val)) : $val;

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
            [$con] = \Modules\Sites\Models\SiteConfigModel::objects()->updateOrCreate(['storefrontid' => $selected_sf, 'name' => $key], $update_query);
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
