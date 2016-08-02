<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classOrderTransaction.php";

$oOrderTransaction = new classOrderTransaction();
$aOrderTransaction = ['id' => 1929, 'transaction_id'=>'FddddFFFFFFFFFFF'];
$oOrderTransaction->fillPrimaryTableValues($aOrderTransaction);
//var_dump($oOrderTransaction); exit;

$oOrderTransaction->updateFields(['transaction_id'=>'FFFFFFFFFFFF',
    'transaction_amount' => 11,
    'date'=>time(),
    'login'=>'',
    'transaction_status'=>'pending',
    'transaction_response'=> '']);

