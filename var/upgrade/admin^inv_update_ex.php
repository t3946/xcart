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
x_load('files', 'db');

#
# Get the manufacturers list
#
if (!empty($active_modules['Multiple_Storefronts'])) {
	$manufacturers = func_query("SELECT $sql_tbl[manufacturers].manufacturerid, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' ORDER BY $sql_tbl[manufacturers].orderby, $sql_tbl[manufacturers].manufacturer");
} else {
	$manufacturers = func_query("SELECT $sql_tbl[manufacturers].manufacturerid, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' ORDER BY $sql_tbl[manufacturers].orderby, $sql_tbl[manufacturers].manufacturer");
}

$smarty->assign("manufacturers", $manufacturers);

$location[] = array(func_get_langvar_by_name("lbl_update_inventory"), "");

if ($REQUEST_METHOD=="POST") {
	$modeex = intval($HTTP_POST_VARS['modeex']);
	$manid = intval($HTTP_POST_VARS['cmb_manufacturers']);

	$userfile = func_move_uploaded_file("userfile");
	$pids = array();

	if (!empty($active_modules['Multiple_Storefronts'])) {
		$baseprodsdb = func_query ('SELECT p.productid, p.productcode FROM ' . $sql_tbl['products'] . ' as p'
			. ' LEFT JOIN ' . $sql_tbl['products_sf'] . ' as psf ON p.productid=psf.productid'
			. ' WHERE psf.sfid="' . $current_storefront . '" AND (p.manufacturerid="' . $manid . '")'
			. $provider_condition);
	} else {
	$baseprodsdb = func_query ("SELECT productid, productcode FROM $sql_tbl[products] WHERE (manufacturerid='$manid') $provider_condition");
	}

	$baseprods = array ();
	if (is_array($baseprodsdb)) {
	foreach ($baseprodsdb as $prod) {
		$pvarsdb = func_query ("SELECT variantid, productid, productcode FROM $sql_tbl[variants] WHERE (productid='$prod[productid]') $provider_condition");
		$baseprods [] = $prod['productcode'];
		if(is_array($pvarsdb))
			foreach ($pvarsdb as $var)
				if ($var['productcode'] != $prod['productcode'])
					$baseprods [] = $var['productcode']; 
	}
	}

	$fileprods = array ();
	$newprods = array ();
	$discprods = array ();

	if ($fp = func_fopen($userfile,"r",true)) {
		while ($columns = fgetcsv ($fp, 65536, $delimiter)) {
			if (empty($columns[0])) {
				continue;
			}
			$columns[0] = addslashes($columns[0]);

			if ($modeex == 1) {

				if (!empty($active_modules['Multiple_Storefronts'])) {
					$pid = func_query_first_cell ('SELECT p.productid FROM ' . $sql_tbl['products'] . ' as p'
						. ' LEFT JOIN ' . $sql_tbl['products_sf'] . ' as psf ON p.productid=psf.productid'
						. ' WHERE psf.sfid="' . $current_storefront . '" AND (p.productcode="' . $columns[0] . '"'
						. ' OR BINARY p.productid = "' . $columns[0] . '")' . $provider_condition);
				} else {
			$pid = func_query_first_cell ("SELECT productid FROM $sql_tbl[products] WHERE (productcode='$columns[0]' OR BINARY productid = '$columns[0]') $provider_condition");
				}
			$vid = 0;
			if (!empty($active_modules['Product_Options'])) {
					if (!empty($active_modules['Multiple_Storefronts'])) {
					$vid = func_query_first_cell('SELECT v.variantid FROM ' . $sql_tbl['variants'] . ' as v, '
						. $sql_tbl['products'] . ' as p'
						. ' LEFT JOIN ' . $sql_tbl['products_sf'] . ' as psf ON p.productid=psf.productid' 
						. ' WHERE psf.sfid="' . $current_storefront . '" AND v.productid = p.productid'
						. ' AND (v.productcode="' . $columns[0] . '" OR BINARY v.variantid = "' . $columns[0] . '")' . $provider_condition);
					} else {
				$vid = func_query_first_cell("SELECT $sql_tbl[variants].variantid FROM $sql_tbl[variants], $sql_tbl[products] WHERE $sql_tbl[variants].productid = $sql_tbl[products].productid AND ($sql_tbl[variants].productcode='$columns[0]' OR BINARY $sql_tbl[variants].variantid = '$columns[0]') ".$provider_condition);
			}
			}

			if (empty($pid) && empty($vid)) {
				continue;
			}

			if (!empty($pid))
				$pids[] = $pid;
			else
				$pids[] = func_query_first_cell("SELECT productid FROM $sql_tbl[variants] WHERE variantid = '$vid'");
			## Update product quantity
$query="";
if(!empty($columns[1]))
        $query.=", avail='$columns[1]'";
if(!empty($columns[3]) && empty($vid))
        $query.=", list_price='$columns[3]'";
if(!empty($columns[4]))
        $query.=", weight='$columns[4]'";

			if (!empty($pid)) {
					db_query ("UPDATE $sql_tbl[products] SET  productid='$pid'".$query." WHERE productid='$pid'");
				}
				if (!empty($vid)) {
					db_query ("UPDATE $sql_tbl[variants] SET  variantid='$vid'".$query." WHERE variantid='$vid'");
				}
# Update pricing
			
				if (!empty($pid) && !empty($columns[2])) {
					db_query ("UPDATE $sql_tbl[pricing] SET price='".(float)$columns[2]."' WHERE productid='$pid' AND variantid = '0'");
				}
				if (!empty($vid) && !empty($columns[2])) {
					db_query ("UPDATE $sql_tbl[pricing] SET price='".(float)$columns[2]."' WHERE variantid = '$vid'");
				}

##############

			}
			elseif ($modeex == 2) {
				# Compare function
	
				$fileprods [] = $columns[0];
			}
			else {
				echo "Invalid mode.";
			}
		}
	}
	$smarty->assign ("main", "inv_update_ex");
	if (!empty($pids) && ($modeex == 1)) {
		func_build_quick_flags($pids);
		func_build_quick_prices($pids);
	}

	if ($modeex == 2) {
		# Compare function

		$newprods = array_diff ($fileprods, $baseprods);
		$discprods = array_diff ($baseprods, $fileprods);

		$smarty->assign("newprods", $newprods);
		$smarty->assign("discprods", $discprods);
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
