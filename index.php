<?php
//@TODO: remove this -> add logger
error_reporting(E_ALL ^ E_DEPRECATED);

//define('AREA_TYPE' , 'C');
date_default_timezone_set('US/Pacific'); //Magic;

require_once 'xcart_tables.php';
require_once './include/libs/autoload.php';
use Xcart\App\Main\Xcart;

$config = include './app/config/settings.php';

Xcart::init($config);
Xcart::app()->run();