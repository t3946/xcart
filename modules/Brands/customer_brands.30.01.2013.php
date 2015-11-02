<?php /* MODIFIED: random:19543 [2009 Oct 20 17:41][Custom development (Extra changes to <List Brands in line> mod)] */ ?>
<?php /* ADDED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# customer_brands.php, random
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

if (!empty($active_modules['Multiple_Storefronts'])) {
	$sf_join = " INNER JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid=$sql_tbl[brands].brandid";
	$sf_condition = " AND $sql_tbl[brands_sf].sfid=$current_storefront";
} else {
	$sf_join = '';
	$sf_condition = '';
}

$brands_products = func_query_hash("SELECT $sql_tbl[brands].brandid, COUNT($sql_tbl[products].productid) FROM $sql_tbl[brands] $sf_join$sf_condition"
    . " LEFT JOIN $sql_tbl[products] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid"
    . " WHERE $sql_tbl[products].forsale = 'Y' GROUP BY $sql_tbl[products].brandid", 'brandid', false, true);

$brands_menu = count($brands_products);

if ($brands_menu > 0) {
	if ($config["Brands"]["brands_limit"] > 0)
		$smarty->assign("show_other_brands", $brands_menu>$config["Brands"]["brands_limit"]);
	$brands_menu = func_query("SELECT $sql_tbl[brands].*, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand, IFNULL($sql_tbl[brands_lng].descr, $sql_tbl[brands].descr) as descr"
        . " FROM $sql_tbl[brands] $sf_join$sf_condition"
        . " LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language'"
        . " WHERE avail = 'Y' AND $sql_tbl[brands].brandid IN ('" . implode("', '", array_keys($brands_products)) . "')"
        . " ORDER BY orderby, brand".(($config["Brands"]["brands_limit"] > 0) ? " LIMIT ".$config["Brands"]["brands_limit"] : ""));
	$smarty->assign("brands_menu", $brands_menu);
# START: random:19543 [2009 Oct 20 17:41] 
	if ($config["Brands"]["brands_columns"] > 0) {
		$additional_count = ($config["Brands"]["brands_limit"] > 0 && $brands_menu>$config["Brands"]["brands_limit"]) ? 2 : 1;
		$smarty->assign("brands_per_column", ceil((count($brands_menu) + $additional_count) / $config["Brands"]["brands_columns"]));
		$smarty->assign('brands_column_percent', 100 / $config['Brands']['brands_columns']);
	}
# END: random:19543 [2009 Oct 20 17:41] 
}

?>
