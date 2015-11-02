<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2007 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2007           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: inv_update_ex.php,v 1.0.0.0 2007/06/04 10:36:44 dem Exp $
#

@set_time_limit(1800);

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('files');

$location[] = array(func_get_langvar_by_name("lbl_update_inventory"), "");

if ($REQUEST_METHOD=="POST") {

	$userfile = func_move_uploaded_file("userfile");
	$pids = array();

	if ($fp = func_fopen($userfile,"r",true)) {
		while ($columns = fgetcsv ($fp, 65536, $delimiter)) {
			if (empty($columns[0])) {
				continue;
			}
			$columns[0] = addslashes($columns[0]);
			$pid = func_query_first_cell ("SELECT productid FROM $sql_tbl[products] WHERE (productcode='$columns[0]' OR BINARY productid = '$columns[0]') $provider_condition");
			$vid = 0;
			if (!empty($active_modules['Product_Options'])) {
				$vid = func_query_first_cell("SELECT $sql_tbl[variants].variantid FROM $sql_tbl[variants], $sql_tbl[products] WHERE $sql_tbl[variants].productid = $sql_tbl[products].productid AND ($sql_tbl[variants].productcode='$columns[0]' OR BINARY $sql_tbl[variants].variantid = '$columns[0]') ".$provider_condition);
			}
			if (empty($pid) && empty($vid)) {
				continue;
			}

			if (!empty($pid))
				$pids[] = $pid;
			else
				$pids[] = func_query_first_cell("SELECT productid FROM $sql_tbl[variants] WHERE variantid = '$vid'");

## Update product quantity

			if (!empty($pid)) {
					db_query ("UPDATE $sql_tbl[products] SET avail='$columns[1]', list_price='$columns[3]', weight='$columns[4]' WHERE productid='$pid'");
				}
				if (!empty($vid)) {
					db_query ("UPDATE $sql_tbl[variants] SET avail='$columns[1]', list_price='$columns[3]', weight='$columns[4]' WHERE variantid='$vid'");
				}
# Update pricing
			
				if (!empty($pid)) {
					db_query ("UPDATE $sql_tbl[pricing] SET price='".(float)$columns[2]."' WHERE productid='$pid' AND variantid = '0'");
				}
				if (!empty($vid)) {
					db_query ("UPDATE $sql_tbl[pricing] SET price='".(float)$columns[2]."' WHERE variantid = '$vid'");
				}

##############

		}
	}
	$smarty->assign ("main", "inv_updated");
	if (!empty($pids)) {
		func_build_quick_flags($pids);
		func_build_quick_prices($pids);
	}

	@unlink($userfile);

} else {
	$smarty->assign ("main", "inv_update_ex");
}

$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
