<?php

use Modules\User\Helpers\PasswordHelper;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;

require "./auth.php";

if (empty($Username) || empty($Password) || defined("IS_ROBOT")){
	die();
}

$Success = true;

$user = UserModel::objects()->get(['login' => $Username, 'pbx_extension__isnt' => '', 'status' => 'Y']);

if (!$user) {
	$Success = false;
} elseif (!PasswordHelper::verify($Password, $user->password)){
	$Success = false;
} else {
	$anveo = PbxOptionsModel::objects()->get(['extension' => $user->pbx_extension]);
	if (!$anveo){
		$Success = false;
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
$SIP_phone_settings_template = str_replace("{{pbxextension}}", $user->pbx_extension, $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{xcartusername}}", $user->firstname, $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{anveoaccount}}", $anveo->anveo_account, $SIP_phone_settings_template);
$SIP_phone_settings_template = str_replace("{{anveopassword}}", $anveo->anveo_password, $SIP_phone_settings_template);

print($SIP_phone_settings_template);
