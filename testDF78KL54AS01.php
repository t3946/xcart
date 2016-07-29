<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classOrderTransaction.php";

$oOrderTransaction = new classOrderTransaction(['id'=>1917]);
$oOrderTransaction->captureTransaction(1.66);








