<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
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
# $Id: user_modify.php,v 1.38.2.2 2006/10/02 08:02:31 twice Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('mail','user');

$location[] = array(func_get_langvar_by_name("lbl_users_management"), "users.php");
$location[] = array("User permission groups", "");

include "./users_tools.php";

if ($REQUEST_METHOD == 'POST' && $_GET['usertype'] == 'B' && ($_POST['mode'] == 'approved' || $_POST['mode'] == 'declined')) {
	if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[customers] WHERE usertype = '$_GET[usertype]' AND login = '$_GET[user]' AND status = 'Q'")) 
	{
		$userinfo = func_userinfo($_GET['user'], $_GET['usertype']);
		$mail_smarty->assign("userinfo", $userinfo);

	}

	func_header_location('user_modify.php?user=' . $_GET['user'] . '&usertype=' . $_GET['usertype']);
}


//func_print_r($all_memberships);

$all_memberships_users["A"][0] = func_query("SELECT login, firstname FROM $sql_tbl[customers] WHERE usertype='A' AND membershipid='0' AND activity='Y' ORDER BY login");
$all_memberships_users["P"][0] = func_query("SELECT login, firstname FROM $sql_tbl[customers] WHERE usertype='P' AND membershipid='0' AND activity='Y' ORDER BY login");

foreach ($all_memberships as $k => $v){

	if ($v["area"] != "A" && $v["area"] != "P"){
		continue;
	}

	$found_users = func_query("SELECT login, firstname FROM $sql_tbl[customers] WHERE usertype='$v[area]' AND activity='Y' AND
	    (
		membershipid='$v[membershipid]' OR
		allow_operate_as_membership='$v[membershipid]' OR 
		allow_operate_as_membership LIKE '%,$v[membershipid],%' OR
		allow_operate_as_membership LIKE '$v[membershipid],%' OR
		allow_operate_as_membership LIKE '%,$v[membershipid]'
	    )
	    ORDER BY login
	");

	$all_memberships_users[$v["area"]][$v["membershipid"]] = $found_users;

}
$smarty->assign("all_memberships_users", $all_memberships_users);

//func_print_r($all_memberships_users);


$smarty->assign("main", "user_permission_groups");

# Assign the current location line
$smarty->assign("location", $location);

# Assign the section navigation data
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
