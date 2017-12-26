<?php
define('XCART_START' , true);
date_default_timezone_set('EST');

$xcart_dir = __DIR__ . '/../www/';

require_once __DIR__ .'/../app/vendors/autoload.php';
use Xcart\App\Main\Xcart;
$config = include __DIR__ . '/config/settings.php';

ini_set('memory_limit', '2048M');
set_time_limit ( 0 );

Xcart::init($config);
Xcart::app()->run();