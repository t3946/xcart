<?php /* ADDED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
<?php /* MODIFIED: random:19778 [2009 Nov 26 09:45][Custom development (Упорядочивание методов отправки)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2009 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2009           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# track_links.php, random
#

define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array("add", "data");

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice');

$location[] = array(func_get_langvar_by_name("lbl_tracking_links"), "");

# Add title
if ($mode == "add" && !empty($add['shipping'])) {
# START: random:19778 [2009 Nov 26 09:45] 
	if (empty($add['orderby'])) {
		$add['orderby'] = func_query_first_cell("SELECT MAX(orderby)+1 FROM $sql_tbl[tracking_links]");
	}
# END: random:19778 [2009 Nov 26 09:45] 
	func_array2insert("tracking_links", $add);

# Update title(s)
} elseif ($mode == "update" && !empty($data)) {
	foreach ($data as $id => $v) {
		func_array2update("tracking_links", $v, "linkid = '$id'");
	}

# Delete title(s)
} elseif ($mode == "delete" && !empty($ids)) {
	$string = "linkid IN ('".implode("','", $ids)."')";
	db_query("DELETE FROM $sql_tbl[tracking_links] WHERE ".$string);
}

if (!empty($mode)) {
	func_header_location("track_links.php");
}

# START: random:19778 [2009 Nov 26 09:45] 
$links = func_query("SELECT * FROM $sql_tbl[tracking_links] ORDER BY orderby");
# END: random:19778 [2009 Nov 26 09:45] 
if (!empty($links)) {
	$smarty->assign("links", $links);
}

#
# Assign Smarty variables and show template
#
$smarty->assign("main","tracking_links");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
