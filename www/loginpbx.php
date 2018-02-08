<?php
require "./auth.php";

if (empty($Username) || empty($Password) || defined("IS_ROBOT")){
	die();
}

x_load("crypt");
$Success = true;

$user_information = func_query_first("SELECT password, pbx_extension, firstname FROM $sql_tbl[customers] WHERE login='".addslashes($Username)."' AND pbx_extension!='' AND status='Y'");

if (empty($user_information)) {
	$Success = false;
} else {
	$user_password = text_decrypt($user_information["password"]);

	if ($Password != $user_password){
		$Success = false;
	} else {
		$anveoaccount_info = func_query_first("SELECT * FROM $sql_tbl[pbx_options] WHERE extension='".addslashes($user_information["pbx_extension"])."'");
		if (empty($anveoaccount_info)){
			$Success = false;
		}
	}
}



if (!$Success){
	print('1
[DATA]
Success=0
Failure="Not authenticated..."
<CRLF>
');
die();
}

$SIP_phone_settings_template = $config["PBX_options"]["SIP_phone_settings_template"];
$SIP_phone_settings_template = str_replace("{{pbxextension}}", $user_information["pbx_extension"], $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{xcartusername}}", $user_information["firstname"], $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{anveoaccount}}", $anveoaccount_info["anveo_account"], $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{anveopassword}}", $anveoaccount_info["anveo_password"], $SIP_phone_settings_template);

print($SIP_phone_settings_template);
?>
