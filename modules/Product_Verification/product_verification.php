<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
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
# $Id: manufacturers.php,v 1.18.2.3 2006/10/11 06:11:31 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

if (!empty($active_modules['Multiple_Storefronts'])) {
	$products = func_query ("SELECT $sql_tbl[featured_products].productid, $sql_tbl[products].product, $sql_tbl[featured_products].product_order, $sql_tbl[featured_products].avail from $sql_tbl[featured_products], $sql_tbl[products] where $sql_tbl[featured_products].storefrontid=$current_storefront AND $sql_tbl[featured_products].productid=$sql_tbl[products].productid AND $sql_tbl[featured_products].categoryid='$f_cat' order by $sql_tbl[featured_products].product_order");
} else {
	$products = func_query ("SELECT $sql_tbl[featured_products].productid, $sql_tbl[products].product, $sql_tbl[featured_products].product_order, $sql_tbl[featured_products].avail from $sql_tbl[featured_products], $sql_tbl[products] where $sql_tbl[featured_products].productid=$sql_tbl[products].productid AND $sql_tbl[featured_products].categoryid='$f_cat' order by $sql_tbl[featured_products].product_order");
}

$smarty->assign ("subcategories", $products);
$smarty->assign ("f_cat", $f_cat);

$smarty->assign("main","product_verification");

?>
