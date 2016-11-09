<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";


Xcart\Order::model(['orderid'=>66186])->reCalculateTotals();