<?php
//error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('EST');

defined('XCART_APP') ?: define('XCART_APP', 1);
defined('XCART_EXT_ENV') ?: define('XCART_EXT_ENV', 1);

$XCART_APP_CONFIG = include './app/config/settings_controller.php';

require_once './include/libs/autoload.php';
//require_once "./auth.php";

use Xcart\App\Main\Xcart;

Xcart::init($XCART_APP_CONFIG);
Xcart::app()->run();
//Xcart::app()->handleRequest();