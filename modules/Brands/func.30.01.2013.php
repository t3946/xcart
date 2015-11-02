<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2011 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2011           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# func.php, kate
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

function func_rebuild_brand_sf($brandid) {
	global $sql_tbl;
	
	$brandid = intval($brandid);
	
	$brandid_exists = func_query_first_cell('SELECT COUNT(brandid) FROM ' . $sql_tbl['brands'] . ' WHERE brandid=' . $brandid);

	if (!empty($brandid_exists)) {
		
		db_query('DELETE FROM ' . $sql_tbl['brands_sf'] . ' WHERE brandid="'. $brandid .'"');
		
		$brand_sfs = func_query_column('SELECT DISTINCT psf.sfid FROM ' . $sql_tbl['products'] . ' as p'
			. ' LEFT JOIN ' . $sql_tbl['products_sf'] . ' as psf ON p.productid=psf.productid'
			. ' WHERE p.brandid="' . $brandid . '"');

		if (is_array($brand_sfs)) {
            $brand_sfs = array_unique($brand_sfs);
			foreach ($brand_sfs as $sf) {
				if (is_numeric($sf)) {
					$b_query = array(
						'brandid'   => $brandid,
						'sfid'      => $sf
					);
					func_array2insert('brands_sf', $b_query, true);
				}
			}
		}
	}
}
?>
