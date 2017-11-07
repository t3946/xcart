<?php
error_reporting(E_ALL ^ E_DEPRECATED);

date_default_timezone_set('EST'); //Magic;

require_once './include/libs/autoload.php';

$config = include './app/config/settings.php';

use Xcart\App\Main\Xcart;


Xcart::init($config);
Xcart::app()->run();