<?php
session_start();


require "./top.inc.php";
require "./init.php";

x_load('froogle');
x_load('backoffice');
x_load('taxes');

global $xcart_dir, $config;
include_once $xcart_dir.'/include/class/classOrders.php';
include_once $xcart_dir.'/include/class/classOrderStatusNotification.php';

$oOrder = new classOrder(['orderid'=>66064]);

$oOrderNotification = new classOrderStatusNotification(['code'=>'Q']);
if ($oOrderNotification->isEnabled()) {
    $oOrderNotification->prepareMail($oOrder)->sendEmail();
}

