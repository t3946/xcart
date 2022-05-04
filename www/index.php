<?php
if ($_SERVER['XHGUI_PROFILING'] && file_exists('/xhgui/vendor/perftools/xhgui-collector/external/header.php')) {
    require_once('/xhgui/vendor/perftools/xhgui-collector/external/header.php');
}

error_reporting(E_ALL ^ E_DEPRECATED);

date_default_timezone_set('EST'); //Magic;

require_once '../app/include/vendors/autoload.php';

$config = include '../app/config/settings.php';

use Xcart\App\Main\Xcart;


Xcart::init($config);
Xcart::app()->run();