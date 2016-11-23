<?php
//session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

$oShippingZone = \Xcart\Shipping::model()->getShippingMethods(\Xcart\Customer::model(['login' => 'anonymous-92700']), (new \Xcart\Manufacturer(12)));
var_dump($oShippingZone);