<?php
require "./auth.php";

if (defined("IS_ROBOT")){
	die();
}

x_load("crypt");

$users_information = func_query("SELECT pbx_extension, firstname FROM $sql_tbl[customers] WHERE pbx_extension!='' AND status='Y' AND usertype!='C'");

print("<B>Current voice team list:</B>\r\n");

if (!empty($users_information)){
	print("<table><tr><th>Extension</th><th>Operator name</th></tr>");
	foreach ($users_information as $k => $v){
		print("<tr><td><a href='tel:".$v["pbx_extension"]."'>".$v["pbx_extension"]."</td><td>".$v["firstname"]."</td></tr>");
	}
	print("</table>");
 	print("<br>");
	print("To view saved call recordings use this link with anveo explorer : <a href = 'https://azurepbx.signin.aws.amazon.com/console'>https://azurepbx.signin.aws.amazon.com/console</a>  ");
}
?>
