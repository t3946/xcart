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
# $Id: product_thumb_image.php,v 1.0 2011/01/06 10:08:23 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('image','product', 'gd');

if ($REQUEST_METHOD == 'POST') {
	$refresh = false;
	if ($mode == 'gen_thumb') {
		$auto_thumb_error = '';
		if (func_generate_image($productid, 'P', 'T', false)) {
			$top_message['content'] = func_get_langvar_by_name('lbl_thumb_success_generated', null, false, true);
			$top_message['type'] = 'I';
			func_save_product_thumb_image($productid, 'T');
		}
		$refresh = true;
	}

    if ($mode == 'thumb_image' && is_array($HTTP_POST_FILES)) {
        $id = $productid;
        $from_parent_window = 'Y';
        $source = 'L';

        foreach ($HTTP_POST_FILES as $file_key => $ufile) {
            $filename = '';
            $userfile = '';
            if ($file_key == 'edit_product_image' && !empty($ufile['name'])) {
                $type = 'P';
                $filename = 'edit_product_image';
            } elseif ($file_key == 'edit_image' && !empty($ufile['name'])) {
                $type = 'T';
                $filename = 'edit_image';
            }
            if (!empty($filename)) {
                $userfile = $HTTP_POST_FILES[$filename]['name'];
                $userfile_size = $HTTP_POST_FILES[$filename]['size'];
                $userfile_type = $HTTP_POST_FILES[$filename]['type'];
            }
            if (!empty($userfile)) {
                include $xcart_dir . '/include/image_selection.php';
            }
        } 
        $refresh = true;
    }

	if ($refresh) {
		func_refresh();
	}
}

?>
