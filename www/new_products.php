<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (����� ��� �������� ����������� "��������������" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# $Id: home.php,v 1.10 2006/03/31 06:18:48 max Exp $
#

use Xcart\App\Main\Xcart;

define('OFFERS_DONT_SHOW_NEW',1);
require "./auth.php";

Xcart::app()->request->redirect('/', [], 301);

require $xcart_dir."/include/categories.php";

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

if (!empty($keyphrase)) {
    include $xcart_dir . '/include/search_categories.php';
}

if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";

if (!empty($active_modules["Special_Offers"])) {
	include $xcart_dir."/modules/Special_Offers/category_offers.php";
}

$user_account['membershipid'] = !empty($user_account['membershipid'])?$user_account['membershipid']:0;

$last_days_30 = time() - 30*24*60*60; // <---- 30

$new_products = func_query("
        SELECT 
                $sql_tbl[products].productid, $sql_tbl[products].product, $sql_tbl[products].add_date 
        FROM $sql_tbl[products] 

/*        LEFT JOIN $sql_tbl[products_categories] 
        ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

        LEFT JOIN $sql_tbl[categories] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

        LEFT JOIN $sql_tbl[category_memberships] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[category_memberships].categoryid  

        LEFT JOIN $sql_tbl[pricing] 
        ON $sql_tbl[products].productid = $sql_tbl[pricing].productid AND $sql_tbl[pricing].quantity = 1

        LEFT JOIN $sql_tbl[quick_flags] 
        ON $sql_tbl[products].productid = $sql_tbl[quick_flags].productid
*/
        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

/*        LEFT JOIN $sql_tbl[product_memberships] 
        ON $sql_tbl[product_memberships].productid = $sql_tbl[products].productid
*/
        WHERE 
                $sql_tbl[products].forsale='Y' 
                AND $sql_tbl[products].add_date > '$last_days_30' 
/*                AND $sql_tbl[products].source_sfid='$current_storefront' */
                AND $sql_tbl[products_sf].sfid='$current_storefront' 
/*                AND ($sql_tbl[category_memberships].membershipid = '".$user_account['membershipid']."' OR $sql_tbl[category_memberships].membershipid IS NULL) 
                AND ($sql_tbl[product_memberships].membershipid = '".$user_account['membershipid']."' OR $sql_tbl[product_memberships].membershipid IS NULL)
                */
        GROUP BY $sql_tbl[products].productid
	ORDER BY $sql_tbl[products].add_date DESC
");

/*
        LEFT JOIN $sql_tbl[quick_prices] 
        ON $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid
                AND $sql_tbl[quick_prices].membershipid ".((empty($user_account['membershipid']) || empty($active_modules['Wholesale_Trading'])) ? "= 0" : "IN ('".$user_account['membershipid']."', 0)")." 
                AND $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid
*/
//if ($qqq == "qqq"){
//x_load("debug");
//func_print_r($new_products);
//}

$smarty->assign("new_products",$new_products);
$smarty->assign("main", "new_products");

$location[1][0] = "New Products";

$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
