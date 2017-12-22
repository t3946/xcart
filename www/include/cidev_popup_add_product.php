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
# $Id: image_selection.php,v 1.12 2006/01/11 06:56:25 mclap Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','category','image','product', 'debug');

if ($active_modules["Manufacturers"]) {
	@include $xcart_dir."/modules/Manufacturers/product_manufacturer.php";
}

if ($REQUEST_METHOD == "POST") {

	if (empty($productcode)){
		func_header_location("cidev_popup_add_product.php?empty_sku=y");
	}

        if (empty($manufacturerid)){
                func_header_location("cidev_popup_add_product.php?empty_mid=y");
        }


	$skip_cat_checking = "";

	$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='".addslashes($productcode)."' AND manufacturerid='$manufacturerid'");

	if (empty($productid)){

		$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='".addslashes($productcode)."'");

		if (empty($productid)){
			$url = "product_modify.php?productcode=".addslashes($productcode)."&manufacturerid=".$manufacturerid."&mode_add_product=y&add_new_product=y";	
		}
		else {
			$url = "product_modify.php?productid=".$productid."&distributor_err=y";
		}
	}
	else {
		$url = "product_modify.php?productid=".$productid."&distributor_sku_exist=y";
	}


	$redirect_code = <<<JS
<script type="text/javascript">
//<![CDATA[
	window.opener.location = '$url';
//]]>
</script>
JS;

	echo $redirect_code;

	func_close_window();
}

func_display("main/cidev_popup_add_product.tpl",$smarty);

?>
