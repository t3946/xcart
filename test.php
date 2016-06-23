<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir;

require_once $xcart_dir."/include/class/classElasticSearch.php";


var_dump($config['Paypal_API']['debug_mode']);




