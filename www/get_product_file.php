<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: get_product_file.php,v 1.0 2010/11/08 14:59:13 kate Exp $
#

use Xcart\App\Main\Xcart;

require './top.inc.php';
require './init.php';

Xcart::app()->request->redirect('/', [], 301);

x_load('backoffice','files');

$file_exists = false;

#
# Check if file exists
#
if (!empty($productid) && is_numeric($productid) && !empty($file) && is_numeric($file)) {
	$allowed_path = realpath($product_files_dir . DIRECTORY_SEPARATOR . $productid);
	$fileinfo = func_query_first('SELECT filename, filesize FROM ' . $sql_tbl['product_files'] . ' WHERE productid=' . $productid . ' AND fileid=' . $file . ' AND avail="Y"');

	if (!empty($fileinfo)) {
		if (!@file_exists($fileinfo['filename'])) {
			$filename = realpath($allowed_path . DIRECTORY_SEPARATOR . $fileinfo['filename']);
			$file_exists = file_exists($filename);
		} else {
			$filename = realpath($fileinfo['filename']);
			$file_exists = !strncmp($filename, $allowed_path, strlen($allowed_path));
		}

		if ($file_exists) {
			#
			# Output file content
			#

//			$filename = str_replace(" ", "_", $filename);

			header('Content-Length: ' . $fileinfo['filesize']);	
			header('Content-type: application/octet-stream');
			header('Content-type: application/force-download');
			header('Content-Disposition: attachment; filename="' . basename($filename).'"');
			readfile($filename);
			exit;
		}
	}
}
func_display('customer/main/no_file_found.tpl',$smarty);
?>
