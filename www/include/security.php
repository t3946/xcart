<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if (empty($login)) {
	func_header_location("error_message.php?access_denied&id=37");
}

/** @var \Modules\User\Models\RoleModel $role */
if (($uModel = \Modules\User\Models\UserModel::objects()->get(['login' => $login])) && $role = $uModel->role) {
    if (!$role->canRequest(\Xcart\App\Main\Xcart::app()->request)) {
        \Xcart\App\Main\Xcart::app()->request->redirect('error_message.php?access_denied&id=25');
    }
}

if ($user_account["flag"] == "FS") {
	$_fulfillment_scripts = array(
		"home.php",
		"orders.php",
		"order.php",
		"generator.php",
		"statistics.php",
		"register.php",
		"help.php",
		"process_order.php",
		"popup_product.php",
		"anti_fraud.php",
		"import.php",
		"get_export.php",
        'grandfathered_products.php'
	);

	if (!preg_match("/(?:^|\/)([\w\d_]+\.php)\??(.*)/", $REQUEST_URI, $_fulfillment_match) || !in_array($_fulfillment_match[1], $_fulfillment_scripts))
		func_header_location("error_message.php?access_denied&id=37");

	if ($_fulfillment_match[1] == 'statistics.php' && $mode == 'logins')
		func_header_location("error_message.php?access_denied&id=37");
}

if (!empty($user_account["flag"])) {
	$smarty->assign("current_membership_flag", $user_account["flag"]);
}
