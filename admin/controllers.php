<?php
// $_GET;
// $_POST;
// $_SERVER;
// $_COOKIE;
// $HTTP_FILES_VARS;
// $_SESSION;
// $_ENV;

defined('XCART_EXT_ENV') ?: define('XCART_EXT_ENV', 1);

//if (!empty($_GET['patch'])) {
//    $_SERVER['HTTP_X_REWRITE_URL'] = urlencode($_GET['patch']);
//    unset ($_GET['patch']);
//}

require "./auth.php";
//require $xcart_dir."/include/security.php";

$configPath = $xcart_dir .'/app/config/settings_admin.php';

$config = include $configPath;
\Xcart\App\Main\Xcart::init($config);
\Xcart\App\Main\Xcart::app()->run();


//
//if (!empty($_GET['module']) && !empty($_GET['controller']))
//{
//    $class = "\\Modules\\" . ucfirst($_GET['module']) . "\\Controllers\\" . ucfirst($_GET['controller']) . 'Controller';
//
//    /* @var \Xcart\App\Controller\Controller $controller */
//    $controller = new $class(new Xcart\App\Request\HttpRequest());
//
//    $controller->run(!empty($_GET['action']) ? $_GET['action'] : null);
//}
