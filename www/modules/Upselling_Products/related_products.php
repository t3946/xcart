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
# $Id: related_products.php,v 1.24.2.1 2006/10/17 08:04:18 max Exp $
#
# This Module forms list of upsailing products
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

$avail_condition = "";
if ($config["General"]["unlimited_products"] == "N" && $config["General"]["disable_outofstock_products"] == "Y") {
	$avail_condition = "AND $sql_tbl[products].avail > 0";
}

$product_links = func_query("SELECT $sql_tbl[products].productid, $sql_tbl[products].productcode, MIN($sql_tbl[pricing].price) as price, IF ($sql_tbl[products_lng].product IS NOT NULL AND $sql_tbl[products_lng].product != '',$sql_tbl[products_lng].product, $sql_tbl[products].product) AS product FROM $sql_tbl[pricing], $sql_tbl[product_links], $sql_tbl[products_categories], $sql_tbl[categories], $sql_tbl[quick_prices], $sql_tbl[products] LEFT JOIN $sql_tbl[products_lng] ON $sql_tbl[products_lng].productid = $sql_tbl[products].productid AND $sql_tbl[products_lng].code='$store_language' WHERE $sql_tbl[products].productid=$sql_tbl[product_links].productid2 AND $sql_tbl[product_links].productid1='$productid' AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid IN ('".intval($membershipid)."', 0) AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[products_categories].productid = $sql_tbl[products].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[categories].avail = 'Y' AND $sql_tbl[products].productid != '$productid' $avail_condition GROUP BY $sql_tbl[products].productid ORDER BY $sql_tbl[product_links].orderby, product");
if (!empty($product_links)){
# Get tax rates cache
            $ids = array();
            foreach ($product_links as $v) {
                if ($v['is_taxes'] == 'Y')
                    $ids[] = $v;
            }

            $_taxes = array();
            if (!empty($ids)) {
                x_load("taxes");
                $_taxes = func_get_product_tax_rates($products, $login);
            }
            unset($ids);
               foreach ($product_links as $k => $v) { 
                $product_links[$k]['taxed_price'] = $v['taxed_price'] = $v['price'];
                if ($v['is_taxes'] == 'Y' && isset($_taxes[$v['productid']])) {
                    $product_links[$k]["taxes"] = func_get_product_taxes($product_links[$k], $login, false, $_taxes[$v['productid']]);
                }
   }
}
$smarty->assign("product_links",$product_links);
?>
