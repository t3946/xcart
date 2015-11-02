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
# $Id: fancy_categories.php,v 1.18 2006/03/15 13:47:10 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }

if (!defined("GET_ALL_CATEGORIES"))
	return false;

# Build categories tree
$catexp_path = array();
if ($config['Fancy_Categories']['fancy_categories_skin'] == 'Explorer' && !empty($cat))
	$catexp = $cat;

if (!empty($catexp)) {
	$catexp_path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid = '$catexp'"));
}

foreach ($all_categories as $k => $v) {
	if (!empty($catexp) && in_array($k, $catexp_path)) {
		$all_categories[$k]['expanded'] = true;
		if (isset($categories[$k]))
			$categories[$k]['expanded'] = true;
	}

	if (empty($v['parentid']))
		continue;

	if (!is_array($all_categories[$v['parentid']]['childs'])) {
		$all_categories[$v['parentid']]['childs'] = array($k => &$all_categories[$k]);
	}
	else {
		$all_categories[$v['parentid']]['childs'][$k] = &$all_categories[$k];
	}

	if (isset($categories[$v['parentid']]))
		$categories[$v['parentid']]['childs'] = $all_categories[$v['parentid']]['childs'];
}

$smarty->assign("catexp", $catexp);
func_mark_last_categories($categories);
?>
