<?php
session_start();


require "./top.inc.php";
require "./init.php";
require_once $xcart_dir . "/include/class/classProduct.php";

x_load('froogle');
x_load('backoffice');
x_load('taxes');

global $xcart_dir, $config;

$oRecord = classProduct::model(['productid'=>131751]);

var_dump($oRecord);

