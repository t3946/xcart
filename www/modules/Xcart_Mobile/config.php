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

use Modules\Core\Components\Profiler;

/**
 * Module configuration
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>. All rights reserved
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: config.php 70 2012-11-13 11:37:11Z skot $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */
if (!defined('XCART_START')) {
    header('Location: ../../');
    die('Access denied');
}
/**
 * Global definitions for module
 */
$_module_dir            = $xcart_dir . XC_DS . 'modules' . XC_DS . 'Xcart_Mobile';
$_mobile_skin_dir       = $xcart_dir . $smarty_skin_dir . '/' . 'modules' . '/' . 'Xcart_Mobile';
/*
  For include/version.php
 */
$addons['Xcart_Mobile'] = true;
/*
  Load module functions
 */
if (!defined('MOBILE_FUNCS_LOADED')) {
    require_once $_module_dir . XC_DS . 'func.php';
    define('MOBILE_FUNCS_LOADED', true);
}
/**
 * Installation: skip fingerprint generating
 */
if (
    strpos($php_url['url'], 'install-xcartmobile.php') && strnatcmp($config['version'], '4.2') >= 0 && $_POST['params']['install_type'] == 1 && $_POST['current'] == 3
) {
    $_POST['current'] = 4;
}

/**
 * ADMIN SIDE:
 * Configuration page drawings
 */
if (
    func_mobile_constant('AREA_TYPE') == 'A' || func_mobile_constant('AREA_TYPE') == 'P'
) {
    $conf_dir = func_mobile_constant('AREA_TYPE') == 'A' ? DIR_ADMIN : DIR_PROVIDER;

    if (strpos($php_url['url'], $conf_dir . '/configuration.php')) {
        func_mobile_clear_compiled_tpl('admin/main/configuration.tpl');
    }
    include $_module_dir . XC_DS . 'admin.php';

    /**
     * CUSTOMER SIDE:
     * Define if we're seeing the site via the mobile device or browser
     */
} else {

    //include $_module_dir . XC_DS . 'Mobile_Detect.php';
    $detect = new Mobile_Detect;

    if ($detect->isMobile() && func_mobile_constant('AREA_TYPE') != 'A') {

        $detect_isMobile_was_created = true;

        include "minicart.php";

        x_session_register('login', '');
        x_session_register('login_type', '');
        x_session_register('logged_userid', 0);
        x_session_register('identifiers', array());


        /**
         * Switch mobile view trigger
         */
        x_session_register('mobile_view_trigger');
        x_session_register('mobile_view_dialog');
        if (isset($_GET['switch_view']) && !empty($_GET['switch_view'])) {
            $mobile_view_trigger = $_GET['switch_view']; // set trigger
        }
        // Listening to AJAX post about that the switch-dialog is closed
        if (isset($_POST['switch_dialog']) && $_POST['switch_dialog'] == 'closed') {
            $mobile_view_dialog = $_POST['switch_dialog']; // set dialog status
            x_session_save(); // save status
            die;
        }

        if (!empty($active_modules['XAuth'])) {
            $soc_login = list($passwd1, $passwd2) = func_xauth_register_php_hook($login);

            $xHash = $_SERVER['HTTP_REFERER'];
            if (preg_match('/([a-zA-Z0-9-]*)?\.rpxnow\.com/s', $xHash)) {
                if (!empty($login) && !preg_match('/cart\.php\?mode=checkout/Ss', $url)) {
                    func_header_location('cart.php?mode=checkout');
                }
            }
        }
/*
	if (strpos($php_url['url'], 'cart.php') && empty($login) && $mode == 'checkout' && $config['General']['enable_anonymous_checkout'] !== 'Y' && empty($soc_login)) {
            if (
                $REQUEST_METHOD == 'POST'
                && isset($_POST['usertype'])
            ) {
                if (empty($email) || empty($passwd1) || empty($passwd2)) {
                    if (empty($passwd1) || empty($passwd2)) {
                    $top_message = array(
                        'type' => 'E',
                        'content' => func_get_langvar_by_name('txt_registration_errors') .''. func_get_langvar_by_name('txt_registration_error') ,
                        );
                    }
                    if (empty($email) || (empty($email) && (empty($passwd1) || empty($passwd2)))) {
                    $top_message = array(
                        'type' => 'E',
                        'content' => func_get_langvar_by_name('txt_registration_errors') .''. func_get_langvar_by_name('txt_email_invalid') .'<br/>'. func_get_langvar_by_name('txt_registration_error') ,
                        );
                    }
                    x_session_register('top_message');
                    func_header_location('cart.php?mode=checkout');
                }
            }
        }
*/

        if ($mobile_view_trigger == 'common' && $mobile_view_dialog != 'closed') {
            $top_message = array(
                'type'    => 'I',
                'content' => '
                <h3>' . func_get_langvar_by_name('txt_mobile_switch_view_dialog_header') . '</h3>
                ' . func_get_langvar_by_name('txt_mobile_switch_view_dialog_content_original') . '
                <div class="buttons-row buttons-auto-separator">
                    <div class="button main-button"><a href="' . $php_url['url'] . (!empty($php_url['query_string']) ? '?' . $php_url['query_string'] . '&' : '?') . 'switch_view=mobile">' . func_get_langvar_by_name('lbl_yes') . '</a></div>
                    
                    <div class="button main"><a href="#" onclick="javascript: $(\'#dialog-message\').hide();">' . func_get_langvar_by_name('lbl_no') . '</a></div>
                </div>
                <div class="button-row">
                    <div class="button"><a href="javascript: void(0);" onclick="javascript: $(\'#dialog-message\').hide(); $.post(\'' . implode('?', $php_url) . '\', {switch_dialog: \'closed\'});">' . func_get_langvar_by_name('txt_dont_ask_again') . '</a></div>
                  </div>
                '
            );
            $smarty->assign('top_message', $top_message);
        }

        if (isset($_GET['switch_view']) && !empty($_GET['switch_view'])) {
            parse_str($php_url['query_string'], $_qry_vals);
            unset($_qry_vals['switch_view']);
            if (!empty($_qry_vals)) {
                $_qry_vals = '?' . http_build_query($_qry_vals);
            } else {
                $_qry_vals = '';
            }
            func_header_location($php_url['url'] . $_qry_vals);
        }

        if (empty($mobile_view_trigger) || $mobile_view_trigger == 'mobile') {
            include $_module_dir . XC_DS . 'customer.php';
        }

    }
}
