<?php
// $_GET;
// $_POST;
// $_SERVER;
// $_COOKIE;
// $HTTP_FILES_VARS;
// $_SESSION;
// $_ENV;

defined('XCART_APP') ?: define('XCART_APP', 1);
defined('XCART_EXT_ENV') ?: define('XCART_EXT_ENV', 1);

require "./auth.php";

use \Xcart\App\Main\Xcart;

/**
 * Change storefront
 */
if (!empty($_POST['cur_sf']) && $_POST['mode'] = 'change_storefront') {
//    $current_storefront = intval($_POST['cur_sf']);
    Xcart::app()->request->session->add('current_storefront', intval( $_POST['cur_sf']));
//    Xcart::app()->request->session->close();
    func_header_location($_SERVER['REQUEST_URI']);
}

require $xcart_dir."/include/security.php";

//$configPath = $xcart_dir .'/app/config/settings_admin.php';
//$config = include $configPath;
//
//Xcart::init($config);

$smarty->assign('xcartApp', Xcart::app());
//Xcart::app()->run();
Xcart::app()->handleRequest();