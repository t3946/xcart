<?php
define('XCART_START' , true);
date_default_timezone_set('US/Pacific'); //Magic;

$xcart_dir = __DIR__ . '/../www/';

require_once '../www/include/libs/autoload.php';
use Xcart\App\Main\Xcart;
$config = include './config/settings.php';

ini_set('memory_limit', '2048M');
set_time_limit ( 0 );

Xcart::init($config);
Xcart::app()->run();