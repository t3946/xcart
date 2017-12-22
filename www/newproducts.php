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
# $Id: featured_products.php,v 1.22.2.1 2006/08/17 08:05:57 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

$user_account['membershipid'] = !empty($user_account['membershipid'])?$user_account['membershipid']:0;

$last_days_30 = time() - 30*24*60*60; // <---- 30 days

$new_prod_ids = func_query("
	SELECT 
		$sql_tbl[products].productid 
	FROM $sql_tbl[products] 

/*	LEFT JOIN $sql_tbl[products_categories] 
	ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

	LEFT JOIN $sql_tbl[categories] 
	ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  


	LEFT JOIN $sql_tbl[pricing] 
	ON $sql_tbl[products].productid = $sql_tbl[pricing].productid

        LEFT JOIN $sql_tbl[quick_flags] 
        ON $sql_tbl[products].productid = $sql_tbl[quick_flags].productid
*/
	INNER JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid AND $sql_tbl[products_sf].sfid='$current_storefront'

        INNER JOIN $sql_tbl[images_T] 
        ON $sql_tbl[images_T].id = $sql_tbl[products].productid

	WHERE 
		$sql_tbl[products].forsale='Y' 
		AND $sql_tbl[products].avail > 0 
/*		AND $sql_tbl[products].productid != ''*/
		AND $sql_tbl[products].add_date > '$last_days_30' 
/*		AND $sql_tbl[products].source_sfid='$current_storefront' */
                
/*                AND locate(CONCAT(FLOOR(1 + RAND() * (99)),''),CONCAT($sql_tbl[products].productid,''))>0 */
/*	GROUP BY $sql_tbl[products].productid*/
/*	LIMIT 10*/
");

/*
        LEFT JOIN $sql_tbl[quick_prices] 
        ON $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid

                AND $sql_tbl[quick_prices].membershipid ".((empty($user_account['membershipid']) || empty($active_modules['Wholesale_Trading'])) ? "= 0" : "IN ('".$user_account['membershipid']."', 0)")." 
                AND $sql_tbl[quick_prices].priceid = $sql_tbl[pricing].priceid


*/

$count_new_prod_ids = count($new_prod_ids);

if (!empty($new_prod_ids) && is_array($new_prod_ids) && $count_new_prod_ids >= 3){
	$new_prod_ids_arr = array();
	foreach ($new_prod_ids as $k => $v){
		if (!empty($v["productid"])){
			$new_prod_ids_arr[] = $v["productid"];
		}
	}

	$tmp_rand_keys = array_rand($new_prod_ids_arr,3);

	foreach($tmp_rand_keys as $k => $v){
		$new_prod_ids_random[$k] = $new_prod_ids_arr[$v];
	}

}

$count_new_prod_ids_random = count($new_prod_ids_random);

if (!empty($new_prod_ids_random) && is_array($new_prod_ids_random) && $count_new_prod_ids_random == "3"){

	x_load("product");

	foreach ($new_prod_ids_random as $k => $v){
		$new_products[$k] = func_select_product($v, @$user_account['membershipid'], false);
	}

	$delete_new_product = false;
	foreach ($new_products as $k => $v){
		if (empty($v["productid"])){
			unset($new_products[$k]);
			$delete_new_product = true;
		}
	}

	if ($delete_new_product){
		$new_products = array_values($new_products);
	}

}



//if ($qqq == "qqq"){
//x_load("debug");
//}
//print(count($new_products));
$smarty->assign("new_products",$new_products);

?>
