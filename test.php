<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir;
include "/include/class/classAmazonMWS.php";

x_load('product');

$classAmazonMWS = new classAmazonMWS();

$classAmazonMWS -> _Request('RequestReport')
    -> _Request('GetReportRequestList')
    -> _Request('GetReportList')
    -> _Request('GetReport')
    -> _Request('UpdateReportAcknowledgements')
    -> processReportFeeData();




