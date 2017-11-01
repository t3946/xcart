<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('EST');

require_once './include/libs/autoload.php';
use Xcart\App\Main\Xcart;


$config = include './app/config/settings.php';

Xcart::init($config);
Xcart::app()->run();