<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('EST');

defined('XCART_APP') ?: define('XCART_APP', 1);
defined('XCART_EXT_ENV') ?: define('XCART_EXT_ENV', 1);

define('XCART_APP_CONFIG', include './app/config/settings_controller.php');

require "./auth.php";

use Xcart\App\Main\Xcart;

//Xcart::init($config);
//Xcart::app()->run();
Xcart::app()->handleRequest();