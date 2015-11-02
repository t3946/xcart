<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart Software license agreement                                           |
| Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>            |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT QUALITEAM SOFTWARE LTD   |
| (hereinafter referred to as "THE AUTHOR") OF REPUBLIC OF CYPRUS IS          |
| FURNISHING OR MAKING AVAILABLE TO YOU WITH THIS AGREEMENT (COLLECTIVELY,    |
| THE "SOFTWARE"). PLEASE REVIEW THE FOLLOWING TERMS AND CONDITIONS OF THIS   |
| LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY     |
| INSTALLING, COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND YOUR COMPANY   |
| (COLLECTIVELY, "YOU") ARE ACCEPTING AND AGREEING TO THE TERMS OF THIS       |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT, DO |
| NOT INSTALL OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL  |
| PROPERTY RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT FOR  |
| SALE OR FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY  |
| GRANTED BY THIS AGREEMENT.                                                  |
+-----------------------------------------------------------------------------+
\*****************************************************************************/
/**
 * Module configuration. Customer side drawings
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>. All rights reserved
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: customer.php 78 2012-12-28 13:59:37Z skot $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */
if (!defined('XCART_START')) {
    header('Location: ../../');
    die('Access denied');
}
define('XMOBILE_START', true);
/**
 * Remove the ajax header for the "add to cart" and "image verification" functionality
 */
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $smarty->assign('is_ajax_request', 'Y'); // for templates
}
unset($_SERVER['HTTP_X_REQUESTED_WITH']);
/**
 * jQm pages "data-url" attribute assigning
 */
$jqm_request_uri_info = $request_uri_info['path'] . ($request_uri_info['query'] ? '?' . $request_uri_info['query'] : '');
$smarty->assign('data_url', $jqm_request_uri_info);
/**
 * Honeypot check
 */
if (isset($antibot_input_str) && !empty($antibot_input_str)) {
    $top_message['content'] = 'You\'re a bot!';
    $top_message['type'] = 'E';
    x_session_register('top_message');
    $_jqm_redirect_url = pathinfo($jqm_current_url);
    func_header_location($_jqm_redirect_url['basename']);
} else {
    
    unset($show_antibot_arr, $antibot_input_str, $_POST['antibot_input_str'], $active_modules['Image_Verification']);
    x_session_unregister('antibot_friend_err');
}
/**
 * Getting the module configuration settings
 */
$xcart_mobile_config = unserialize(stripslashes(func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name = 'xcart_mobile_admin_configuration'")));
$smarty->assign('xcart_mobile_config', $xcart_mobile_config);
$config['General']['root_categories'] == 'Y';
// Disable internal AJAX add to cart
unset($config['ajax_add2cart']);
if (!empty($xcart_mobile_config)) {
    $config['Appearance']['products_per_page'] = $objects_per_page = $xcart_mobile_config['products_per_page'];
}
/**
 * Altskin overriding. Here we include the mobile templates instead of common ones
 */
$alt_skin_dir = $_mobile_skin_dir;
$alt_skin_info = array(
    'order' => 0,
    'author' => 'Qualiteam Software Limited',
    'name' => 'X-Cart Mobile',
    'screenshot' => '',
    'path' => $xcart_dir . $smarty_skin_dir . '/' . 'modules' . '/' . 'Xcart_Mobile',
    'alt_skin_dir' => $smarty_skin_dir . '/' . 'modules' . '/' . 'Xcart_Mobile',
    'alt_schemes_skin_name' => 'xcart_mobile',
    'web_path' => $xcart_web_dir . $smarty_skin_dir . '/' . 'modules' . '/' . 'Xcart_Mobile'
);

$smarty->template_dir = array(
    $alt_skin_dir,
    $xcart_dir . $smarty_skin_dir,
);
/**
 * Create own compile directory to prevent generated templates cashing bugs
 */
$compileDir = $var_dirs['templates_c'] . '/' . md5($_mobile_skin_dir);
if (!is_dir($compileDir)) {
    func_mkdir($compileDir);
}
$smarty->compile_dir = $compileDir;
/**
 * Variasbles reassign
 */
$smarty->assign('AltImagesDir', $alt_skin_info['web_path'] . '/images');
$smarty->assign('AltSkinDir', $alt_skin_info['web_path']);
/**
 * Register Smarty function for the clearing template's code from unecessary modules includings
 */
$modules_to_disable = array(
    'Wibiya',
    'Socialize',
    'Add_to_cart_popup',
    'Magnifier',
    'Flyout_Menus',
    'Customer_Reviews',
    'Feature_Comparison',
    'Sitemap',
    'Products_Map',
    'Cloud_Search',
    'One_Page_Checkout',
);
$smarty->register_function('func_mobile_clear_modules', 'func_mobile_clear_modules');
foreach ($modules_to_disable as $_mod_name) {
    unset($active_modules[$_mod_name], $config[$_mod_name]);
}
/**
 * Re-assign some config variables
 */
$config['Appearance']['max_nav_pages'] = min(3, $config['Appearance']['max_nav_pages']);
if ($active_modules['Detailed_Product_Images']) {
    $config['Detailed_Product_Images']['det_image_popup'] = 'Y';
}
/**
 * Register Smarty functions for templates
 */
$smarty->register_function('func_mobile_set_active_tab', 'func_mobile_set_active_tab');
$smarty->register_function('func_mobile_get_page_title', 'func_mobile_get_page_title');
$smarty->register_function('func_mobile_prepare_sort_fields', 'func_mobile_prepare_sort_fields');
$smarty->register_function('func_mobile_payment_cc_end', 'func_mobile_payment_cc_end');
$smarty->register_function('func_mobile_get_total_items', 'func_mobile_get_total_items');
/**
 * Register Smarty modifiers for templates 
 */
$smarty->register_modifier('func_mobile_variants_has_wl','func_mobile_variants_has_wl');
$smarty->register_modifier('has_string', 'strpos');
/**
 * Register filters
 */
// Post filters
$smarty->register_postfilter('func_mobile_templates_prepare');
// Output filters
if (strpos($php_url['url'], 'register.php') || strpos($php_url['url'], 'cart.php')) {
    $smarty->register_outputfilter('func_register_form_convert');
}
if (strpos($php_url['url'], 'manufacturers.php')) {
    $smarty->register_outputfilter('func_mobile_manufacturers_navigation');
}

/**
 * Variables prepare
 */
$smarty->assign('is_tablet', $detect->isTablet());


/**
 * Checkout module overriding
 */
$config['General']['checkout_module'] = $checkout_module = 'Fast_Lane_Checkout';
$active_modules['Fast_Lane_Checkout'] = 'Y';
$smarty->assign('checkout_module', $checkout_module);

/**
 * Cart processing
 */
if (strpos($php_url['url'], 'cart.php')) {
    $config['General']['redirect_to_cart'] = 'Y';
    
    if (empty($mode)) {
        x_session_register('cart');
        // Add 'mobile' mark to all products
        if (!empty($cart['products'])) {
            foreach ($cart['products'] as $k => $v) {
                if (empty($v['extra_data']['added_in_mobile']))
                    $cart['products'][$k]['extra_data']['added_in_mobile'] = true;
            }
        }
    }
}

/**
 * Subcategories page prepare
 */
if (isset($list_categories)) {
    $smarty->assign('list_categories', true);
}
/**
 * "More" page navigation preset
 */
if (isset($more_info)) {
    $smarty->assign('more_info', true);
}
/**
 * Assign mobile mode
 */
if (!empty($mobile_mode)) {

x_session_register("search_data");
x_session_register("e_search_data");

#
##
###
    if ($top_btn == "Y"){
        $e_search_data["substring"] = "";
        x_session_save("e_search_data");
    }
###
##
#

    $smarty->assign('mobile_mode', $mobile_mode);
}
/**
 * Search sort
 */
if (!empty($sort)) {
    $smarty->assign('sort', $sort);
    $smarty->assign('sort_direction', $sort_direction);
}
/**
 * Popup dialog prepare
 */
if (
        (isset($mobile_mode) && $mobile_mode == 'search')
        || (isset($popup_dialog) && $popup_dialog == 'Y')
) {
    $smarty->assign('no_nav', true);
    $smarty->assign('data_role', 'dialog');
    if (!empty($popup_title)) {
        $smarty->assign('page_title', $popup_title);
    }
}
/**
 * HTTPs workaround [issue #0042360]
 */
if (
        $config['Security']['leave_https'] == 'Y' && !empty($current_location)
) {
    $current_mobile_location = str_replace('https://', 'http://', $current_location);
    $smarty->assign('current_location', $current_mobile_location);
    /**
     * Handle 302 redirects
     */
    $handling_scripts = array(
        'popup_',
        'pages.php',
        'address_book.php',
    );
    if (
            $_SERVER['HTTPS'] == 'on' && func_mobile_strpos_array($php_url['url'], $handling_scripts)
    ) {
        $config['Security']['leave_https'] = 'N';
    }
}
?>
