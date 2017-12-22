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
# $Id: product_manufacturer.php,v 1.10 2006/01/11 06:56:12 mclap Exp $
#
# Update product's manufacturer id
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

x_load('product');

# Update manufacturer id
if ($REQUEST_METHOD == "POST" && isset($manufacturerid) && $productid) {
	$query_data = array(
		"manufacturerid" => $manufacturerid
	);

	if ($geid && $fields['manufacturer'] == 'Y') {
		while ($pid = func_ge_each($geid, 100)) {
			func_array2update("products", $query_data, "productid IN ('".implode("','", $pid)."')");
		}
	} else {
		func_array2update("products", $query_data, "productid = '$productid'");
	}

# Get manufacturers list
} else {
	if (!empty($active_modules['Multiple_Storefronts'])) {
		$manufacturers = func_query("SELECT $sql_tbl[manufacturers].manufacturerid, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' ORDER BY $sql_tbl[manufacturers].orderby, $sql_tbl[manufacturers].manufacturer");
	} else {
	$manufacturers = func_query("SELECT $sql_tbl[manufacturers].manufacturerid, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' ORDER BY $sql_tbl[manufacturers].orderby, $sql_tbl[manufacturers].manufacturer");
	}
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	if ($login_type == 'P') {
		$selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
		if (!empty($selected_manufacturers)) {
			$selected_manufacturers = unserialize($selected_manufacturers);
			foreach ($manufacturers as $k=>$v) {
				if (!@in_array($v['manufacturerid'], $selected_manufacturers)) {
					$total_items = $total_items - 1;
					$total_nav_pages = ceil($total_items/$objects_per_page)+1;
					unset($manufacturers[$k]);
				}	

			}

		}
	}
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

	if (!empty($manufacturers)) {
		$smarty->assign("manufacturers", $manufacturers);
	}
}
?>
