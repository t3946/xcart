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

/**
 * Change storefront
 */
if (!empty($_POST['cur_sf']) && $_POST['mode'] = 'change_storefront') {
    $current_storefront = intval( $_POST['cur_sf']);
    x_session_save('current_storefront');
    x_session_save_to_db();
    func_header_location($_SERVER['REQUEST_URI']);
}

use \Xcart\App\Main\Xcart;

require $xcart_dir."/include/security.php";

$configPath = $xcart_dir .'/app/config/settings_admin.php';
$config = include $configPath;

Xcart::init($config);
Xcart::app()->run();
