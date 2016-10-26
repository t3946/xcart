<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;

use Xcart\PaymentMethod;

$oPaymentMethod = PaymentMethod::model(['paymentid' => '17']);
var_dump($oPaymentMethod->getPaymentMethodInstance(['paymentid' => '17']));