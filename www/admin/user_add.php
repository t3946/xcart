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
# $Id: user_add.php,v 1.38.2.1 2006/10/02 08:02:31 twice Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";

define('USER_ADD', 1);

$display_antibot = false;

$location[] = array(func_get_langvar_by_name("lbl_users_management"), "users.php");

$_usertype = (($usertype == "P" and !empty($active_modules["Simple_Mode"])) ? "A" : $usertype);
switch ($_usertype) {
	case "A":
		$location[] = array(func_get_langvar_by_name("lbl_create_admin_profile"), "");
		break;
	case "P":
		$location[] = array(func_get_langvar_by_name("lbl_create_provider_profile"), "");
		break;
	case "C":
		$location[] = array(func_get_langvar_by_name("lbl_create_customer_profile"), "");
		break;
	case "V":
		$location[] = array(func_get_langvar_by_name("lbl_create_verificator_profile"), "");
		break;
	case "B":
		$location[] = array(func_get_langvar_by_name("lbl_create_partner_profile"), "");
}

include "./users_tools.php";

$smarty->assign("usertype_name", $usertypes[$usertype]);

$mode = "add";

$login_ = $login;
$login_type_ = $login_type;

$login = $_GET['user'];
$login_type = $_GET['usertype'];

#
##
###
$smarty->assign("new_login_type", $login_type);
###
##
#

#
# Where to forward <form action
#

$smarty->assign("register_script_name",(($config["Security"]["use_https_login"]=="Y")?$xcart_catalogs_secure['admin']."/":"")."user_add.php");

require $xcart_dir."/include/register.php";

#
# Update profile or create new
#
switch ($usertype) {
	case "A" : $tpldir = "admin";
		break;
	case "P" : $tpldir = "provider";
		break;
	case "C" : $tpldir = "customer";
		break;
	case "V" : $tpldir = "verificator";
		break;
	default: $tpldir = "partner";
}

if ($active_modules["Simple_Mode"] && ($usertype=="A" || $usertype=="P"))
	$tpldir = "admin"; 

# Display the 'Activity' input box for admin, provider or partner
if (in_array($usertype, array("A", "P", "B")))
	$smarty->assign("display_activity_box", "Y");

$smarty->assign("main","user_add");
$smarty->assign("tpldir", $tpldir);

$login=$login_;
$login_type=$login_type_;

x_session_save();

# Assign the current location line
$smarty->assign("location", $location);

# Assign the section navigation data
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
