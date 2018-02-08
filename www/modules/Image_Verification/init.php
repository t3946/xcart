<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
#$Id: init.php,v 1.1.2.6 2007/01/16 09:06:31 twice Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }

$display_antibot = true;

$config['Image_Verification']['spambot_arrest_login_attempts'] = 3;

$show_antibot_arr = array (
							"on_send_to_friend" => $config['Image_Verification']['spambot_arrest_on_send_to_friend'],
							"on_contact_us" => $config['Image_Verification']['spambot_arrest_on_contact_us'],
							"on_registration" => $config['Image_Verification']['spambot_arrest_on_registration'],
							"on_login" => $config['Image_Verification']['spambot_arrest_on_login'],
							"on_reviews" => $config['Image_Verification']['spambot_arrest_on_reviews'],
							"on_surveys" => $config['Image_Verification']['spambot_arrest_on_surveys'],
						  );

x_session_register("antibot_validation_val");

// Check for GD library presence 
$gd_not_loaded = false;
if (!extension_loaded('gd') || !function_exists("gd_info")) { 
// Turn off ImageVerification module if GD is not installed
	unset($active_modules['Image_Verification']); 
} elseif (empty($section) || $section=='contactus') {
	require $xcart_dir."/modules/Image_Verification/spambot_arrest_func.php";	
	$antibot_validation_val = func_generate_codes($show_antibot_arr, $antibot_validation_val);
	$smarty->assign("show_antibot", $show_antibot_arr);
	
	$antibot_sections = array();
	foreach($show_antibot_arr as $key=>$valuee) {
		$antibot_sections[$key] = $key;
	}
	$smarty->assign("antibot_sections", $antibot_sections);
}
?>
