<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;

use Xcart\PaymentMethod;

global $config;
$to = $config['Company']['product_management'];
$from = 'team@s3stores.com';
func_send_mail($to, 'mail/missing_sku_subj.tpl', 'mail/missing_sku.tpl', $from, true);