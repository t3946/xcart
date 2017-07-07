<?php

defined('XCART_APP') ?: define('XCART_APP', 1);
defined('XCART_EXT_ENV') ?: define('XCART_EXT_ENV', 1);

date_default_timezone_set('US/Pacific'); //Magic;

require_once './include/libs/autoload.php';
use Xcart\App\Main\Xcart;


$config = include './app/config/settings.php';

Xcart::init($config);
Xcart::app()->run();