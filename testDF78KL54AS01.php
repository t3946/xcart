<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";


$oProduct = \Xcart\Order::model(['orderid'=>16445]);
