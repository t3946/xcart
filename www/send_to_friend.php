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
# $Id: send_to_friend.php,v 1.5.2.7 2007/01/04 08:26:58 twice Exp $
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

if (!$productid)
	func_header_location ("error_message.php?access_denied&id=48");

x_session_register("antibot_err");
$page = "on_send_to_friend";
if (!empty($active_modules['Image_Verification']) && $show_antibot_arr[$page] == 'Y') {
	if (isset($antibot_input_str) && !empty($antibot_input_str)) {
		$antibot_err = func_validate_image($antibot_validation_val[$page], $antibot_input_str);
	} else {
		$antibot_err = true;
	}
}
x_session_register("send_to_friend_info");
if ($mode == 'send') {
	if ($email && $from && $name && !$antibot_err) {
		x_load('mail');

		$mail_smarty->assign ("product", $product_info);
		$mail_smarty->assign ("name", $name);
		func_send_mail ($email, "mail/send2friend_subj.tpl", "mail/send2friend.tpl", $from, false);
		$top_message["content"] = func_get_langvar_by_name("txt_recommendation_sent");
	}
	else {
		$top_message["content"] = func_get_langvar_by_name("err_filling_form");
		if ($antibot_err) {
			$top_message["content"] .= "<br />".func_get_langvar_by_name("msg_err_antibot");
		}
		$top_message["type"] = "E";
		$send_to_friend_info['name'] = $name;
		$send_to_friend_info['email'] = $email;
		$send_to_friend_info['from'] = $from;
		$send_to_friend_info['antibot_err'] = $antibot_err;
		$send_to_friend_info['fill_err'] = true;
	}

	func_header_location("product.php?productid=".$productid);
}

?>
