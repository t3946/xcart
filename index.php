<?php
define('XCART_START' , true);

require_once './include/libs/autoload.php';
use Xcart\App\Main\Xcart;

$config = include './app/config/settings.php';

Xcart::init($config);
Xcart::app()->run();