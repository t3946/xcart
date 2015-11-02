<?php
require "./auth.php";

if (defined("IS_ROBOT")){
	die();
}

x_load("crypt");

header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"bria_contacts.csv\"");
//header("Content-Length: ".func_filesize($full_filename));


$header_arr[0] = "business_number";
$header_arr[1] = "business_number2";
$header_arr[2] = "business_number3";
$header_arr[3] = "business_number4";
$header_arr[4] = "business_number5";
$header_arr[5] = "business_number6";
$header_arr[6] = "categories";
$header_arr[7] = "default_address";
$header_arr[8] = "default_address_comm";
$header_arr[9] = "default_address_type";
$header_arr[10] = "display-name";
$header_arr[11] = "email_address";
$header_arr[12] = "email_address2";
$header_arr[13] = "email_address3";
$header_arr[14] = "email_address4";
$header_arr[15] = "email_address5";
$header_arr[16] = "email_address6";
$header_arr[17] = "fax_number";
$header_arr[18] = "fax_number2";
$header_arr[19] = "fax_number3";
$header_arr[20] = "fax_number4";
$header_arr[21] = "fax_number5";
$header_arr[22] = "fax_number6";
$header_arr[23] = "given_name";
$header_arr[24] = "guid";
$header_arr[25] = "home_number";
$header_arr[26] = "home_number2";
$header_arr[27] = "home_number3";
$header_arr[28] = "home_number4";
$header_arr[29] = "home_number5";
$header_arr[30] = "home_number6";
$header_arr[31] = "is_favorite";
$header_arr[32] = "mobile_number";
$header_arr[33] = "mobile_number2";
$header_arr[34] = "mobile_number3";
$header_arr[35] = "mobile_number4";
$header_arr[36] = "mobile_number5";
$header_arr[37] = "mobile_number6";
$header_arr[38] = "other_address";
$header_arr[39] = "other_address2";
$header_arr[40] = "other_address3";
$header_arr[41] = "other_address4";
$header_arr[42] = "other_address5";
$header_arr[43] = "other_address6";
$header_arr[44] = "pres_subscription";
$header_arr[45] = "sip_address";
$header_arr[46] = "sip_address2";
$header_arr[47] = "sip_address3";
$header_arr[48] = "sip_address4";
$header_arr[49] = "sip_address5";
$header_arr[50] = "sip_address6";
$header_arr[51] = "surname";
$header_arr[52] = "web_page";
$header_arr[53] = "web_page2";
$header_arr[54] = "web_page3";
$header_arr[55] = "xmpp_address";
$header_arr[56] = "xmpp_address2";
$header_arr[57] = "xmpp_address3";
$header_arr[58] = "xmpp_address4";
$header_arr[59] = "xmpp_address5";
$header_arr[60] = "xmpp_address6";

$header_line = implode(",", $header_arr);

print($header_line."\r\n");

$users_information = func_query("SELECT pbx_extension, firstname FROM $sql_tbl[customers] WHERE pbx_extension!='' AND status='Y' AND usertype!='C'");

if (!empty($users_information)){
	foreach ($users_information as $k => $v){

		$line_arr = array();
		$line_arr[0] = "";
		$line_arr[1] = "";
		$line_arr[2] = "";
		$line_arr[3] = "";
		$line_arr[4] = "";
		$line_arr[5] = "";
		$line_arr[6] = "S3 Stores Team";
		$line_arr[7] = "sip:".addslashes($v["pbx_extension"])."@sip.s3stores.com";
		$line_arr[8] = "im";
		$line_arr[9] = "sip";
		$line_arr[10] = addslashes($v["pbx_extension"]);
		$line_arr[11] = "";
		$line_arr[12] = "";
		$line_arr[13] = "";
		$line_arr[14] = "";
		$line_arr[15] = "";
		$line_arr[16] = "";
		$line_arr[17] = "";
		$line_arr[18] = "";
		$line_arr[19] = "";
		$line_arr[20] = "";
		$line_arr[21] = "";
		$line_arr[22] = "";
		$line_arr[23] = "";
		$line_arr[24] = "";
		$line_arr[25] = "";
		$line_arr[26] = "";
		$line_arr[27] = "";
		$line_arr[28] = "";
		$line_arr[29] = "";
		$line_arr[30] = "";
		$line_arr[31] = "FALSE";
		$line_arr[32] = "";
		$line_arr[33] = "";
		$line_arr[34] = "";
		$line_arr[35] = "";
		$line_arr[36] = "";
		$line_arr[37] = "";
		$line_arr[38] = "";
		$line_arr[39] = "";
		$line_arr[40] = "";
		$line_arr[41] = "";
		$line_arr[42] = "";
		$line_arr[43] = "";
		$line_arr[44] = "TRUE";
		$line_arr[45] = "sip:".addslashes($v["pbx_extension"])."@sip.anveo.com";
		$line_arr[46] = "sip:".addslashes($v["pbx_extension"])."@sip.s3stores.com";
		$line_arr[47] = "";
		$line_arr[48] = "";
		$line_arr[49] = "";
		$line_arr[50] = "";
		$line_arr[51] = "";
		$line_arr[52] = "";
		$line_arr[53] = "";
		$line_arr[54] = "";
		$line_arr[55] = "";
		$line_arr[56] = "";
		$line_arr[57] = "";
		$line_arr[58] = "";
		$line_arr[59] = "";
		$line_arr[60] = "";

		$line = implode(",", $line_arr);
		unset($line_arr);

		print($line."\r\n");
	}
}
?>
