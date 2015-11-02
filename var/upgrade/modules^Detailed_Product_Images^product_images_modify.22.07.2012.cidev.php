<?php /* MODIFIED: random:536881009 [2010 Mar 22 14:23][Custom development ("Ability to upload several files at once" and "Modifications to products clone")] */ ?>
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
# $Id: product_images_modify.php,v 1.30.2.1 2006/06/02 08:29:19 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

x_load('backoffice','product');
x_session_register('file_upload_data_not_image');

function func_upload_product_file($productid, $upload_data, $insert_id = false, $main_id = false) {
    global $product_files_dir, $sql_tbl, $alt, $message_content, $multi_id;
    
    $path = $product_files_dir . '/' . $productid;
    if (!is_dir($path)) {
        func_mkdir($path, 0755);
    }
    $path = $path . '/' . $upload_data['filename'];

    if ($main_id !== false) {
        $source_path = $product_files_dir . '/' . $main_id . '/' . $upload_data['filename'];
    } else {
        $source_path = $upload_data['file_path'];
    }

    if (@file_exists($path)) {
        # File already exists
        $message_content .= $path . ': ' . func_get_langvar_by_name('msg_err_file_exists') . '<br />';
    } elseif ($upload_data['filename'] == '') {
        # No files to upload
        $message_content .= $path . ': ' . func_get_langvar_by_name('msg_err_file_upload') . '<br />';
    } elseif ($main_id === false && !func_transfer_file($source_path, $path, true)) {
        # File operation is failed
        $message_content .= $path . ': ' . func_get_langvar_by_name('msg_err_file_operation') . '<br />';
    } elseif ($main_id !== false && !@copy($source_path, $path)) {
        # File operation is failed
        $message_content .= $path . ': ' . func_get_langvar_by_name('msg_err_file_operation') . '<br />';
    } else {
        # Success
        
        $file_orderby = func_query_first_cell('SELECT MAX(orderby) FROM '. $sql_tbl['product_files']
            . ' WHERE productid="' . $productid . '"') + 10;

        if (!$insert_id) {
            $insert_id = func_query_first_cell('SELECT MAX(fileid) FROM ' . $sql_tbl['product_files']) + 1;
        }
        
        $query = array(
            'fileid'	=>	$insert_id,
            'filename'	=> $upload_data['filename'],
            'description'	=> $alt[$multi_id],
            'filesize'	=> $upload_data['file_size'],
            'avail'	=> 'Y',
            'orderby'	=> $file_orderby,
            'date'	=> $upload_data['date'],
            'productid'	=> $productid
        );
        
        func_array2insert('product_files', $query);

        return $insert_id;
    }		

    return false;
}


# Upload additional product image
if ($mode == "product_images") {
    
    if ($REQUEST_METHOD == 'POST') {
        $ge_d_images = $fields['new_d_image'];
    }

    if (is_array($HTTP_POST_FILES)) {
        $from_parent_window = 'Y';
        $source = 'L';
        $id = $productid;
        $notimage = 'avail';
        foreach ($HTTP_POST_FILES as $file_key => $ufile) {
            if (substr($file_key, 0, 8) == 'userfile' && !empty($ufile['name'])) {
                list($prefix, $type, $multi_id) = explode('_', $file_key);
                $filename = $file_key;
                $userfile = basename($ufile['name']);
                $userfile_size = $ufile['size'];
                $userfile_type = $ufile['type'];
                include $xcart_dir . '/include/image_selection.php';
            }
        }
    }

	if (!empty($file_upload_data_not_image[$productid]) && is_array($file_upload_data_not_image[$productid])) {
        
        $message_content = '';
        
		foreach ($file_upload_data_not_image[$productid] as $multi_id => $upl_data) {
			if (!array_key_exists($multi_id, $alt)) {
				unset($file_upload_data_not_image[$productid][$multi_id]);
				continue;
			}
			
            if ($inserted_id = func_upload_product_file($productid, $upl_data)) {
				if ($geid && $ge_d_images == 'Y') {
					while ($pid = func_ge_each($geid, 1, $productid)) {
                        func_upload_product_file($pid, $upl_data, $inserted_id, $productid);
					}
					}
				}
		}

        if (empty($message_content)) {
				$top_message['content'] = func_get_langvar_by_name('msg_adm_product_files_add');
				$top_message['type'] = 'I';
        } else {
            $top_message['content'] = $message_content;
            $top_message['type'] = 'E';
		}
	}
  
# START: random:536881009 [2010 Mar 22 14:23] 
	if (!empty($file_upload_data["D"]) && is_array($file_upload_data["D"])) {
		foreach ($file_upload_data["D"] as $multi_id => $upl_data) {
			if (!array_key_exists($multi_id, $alt)) {
				unset($file_upload_data["D"][$multi_id]);
				continue;
			}
# END: random:536881009 [2010 Mar 22 14:23] 

# START: random:536881009 [2010 Mar 22 14:23] 
			$_file_upload_data = array("D" => $upl_data);
			$image_perms = func_check_image_storage_perms($_file_upload_data, "D");
# END: random:536881009 [2010 Mar 22 14:23] 
	if ($image_perms !== true) {
		$top_message["content"] = $image_perms['content']; 
		$top_message["type"] = "E";
		func_refresh("images");
	}

# START: random:536881009 [2010 Mar 22 14:23] 
			$image_posted = func_check_image_posted($_file_upload_data, "D");
# END: random:536881009 [2010 Mar 22 14:23] 

	if ($image_posted) {
# START: random:536881009 [2010 Mar 22 14:23] 
				$image_id = func_save_image($_file_upload_data, "D", $productid, array("alt" => $alt[$multi_id]));
# END: random:536881009 [2010 Mar 22 14:23] 
		if ($geid && $ge_d_images == 'Y') {
			$data = func_query_first("SELECT * FROM $sql_tbl[images_D] WHERE id = '$productid' AND imageid = '$image_id'");
			unset($data['imageid']);
			$data = func_array_map("addslashes", $data);
			while($pid = func_ge_each($geid, 1, $productid)) {
				$id = func_query_first_cell("SELECT imageid FROM $sql_tbl[images_D] WHERE id = '$pid' AND md5 = '$data[md5]'");
				if (!empty($id))
					func_delete_image($id, "D", true);
				$data['id'] = $pid;
				func_array2insert("images_D", $data);
			}
		}
		$top_message["content"] = func_get_langvar_by_name("msg_adm_product_images_add");
		$top_message["type"] = "I";

	}
# START: random:536881009 [2010 Mar 22 14:23] 
		}
	}
# END: random:536881009 [2010 Mar 22 14:23] 
	func_refresh("images");

# Update product image
} elseif ($mode == "update_availability" && !empty($image)) {

	foreach ($image as $key => $value) {
		func_array2update("images_D", $value, "imageid = '$key'");
		if($geid && $fields['d_image'][$key] == 'Y') {
			$data = func_query_first("SELECT * FROM $sql_tbl[images_D] WHERE imageid = '$key'");
			unset($data['imageid']);
			$data = func_array_map("addslashes", $data);
			while($pid = func_ge_each($geid, 1, $productid)) {
				$id = func_query_first_cell("SELECT imageid FROM $sql_tbl[images_D] WHERE id = '$pid' AND md5 = '$data[md5]'");
				if (!empty($id))
					func_delete_image($id, "D", true);
				$data['id'] = $pid;
				func_array2insert("images_D", $data);
			}
		}
	}
	$top_message["content"] = func_get_langvar_by_name("msg_adm_product_images_upd");
	$top_message["type"] = "I";
	func_refresh("images");

# Delete product image
} elseif ($mode == "product_images_delete") {
	if (!empty($iids)) {
		foreach($iids as $imageid => $tmp) {
			$md5 = func_query_first_cell("SELECT md5 FROM $sql_tbl[images_D] WHERE imageid = '$imageid'");
			func_delete_image($imageid, "D", true);
			if ($geid && $fields['d_image'][$imageid] == 'Y') {
				while($pid = func_ge_each($geid, 1, $productid)) {
					$id = func_query_first_cell("SELECT imageid FROM $sql_tbl[images_D] WHERE id = '$pid' AND md5 = '$md5'");
					if (!empty($id))
						func_delete_image($id, "D", true);
				}
			}
		}

		$top_message["content"] = func_get_langvar_by_name("msg_adm_product_images_del");
		$top_message["type"] = "I";
	}
	func_refresh("images");

} elseif ($mode == 'update_files') {
	if (!empty($file)) {
		foreach ($file as $fileid => $tmp) {
			$query = array(
				'orderby'	=> intval($tmp['orderby']),
				'description'	=> mysql_real_escape_string($tmp['file_descr']),
				'avail'	=> $tmp['avail']
			);
			$where = 'fileid = ' . $fileid . ' AND productid = ' . $productid;
			func_array2update('product_files', $query, $where);
			
			if ($geid && $fields['p_files'][$fileid] == 'Y') {
				while ($pid = func_ge_each($geid, 1, $productid)) {
					$id = func_query_first_cell('SELECT fileid FROM ' . $sql_tbl['product_files'] . ' WHERE productid = ' . $pid . ' AND fileid = ' . $fileid);
					if (!empty($id)) {
						$where = 'fileid = ' . $id . ' AND productid = ' . $pid;
						func_array2update('product_files', $query, $where);
					}
				}
			}
		}

		$top_message['content'] = func_get_langvar_by_name('msg_adm_product_files_upd');
		$top_message['type'] = 'I';
	}
	func_refresh('product_files');

} elseif ($mode == 'delete_files') {
	if (!empty($fids)) {
		$fids_str = implode(', ', array_keys($fids));
		$filenames = func_query_hash('SELECT fileid, filename FROM ' . $sql_tbl['product_files'] . ' WHERE fileid IN (' . $fids_str . ') AND productid=' . $productid, 'fileid', false, true);
		foreach ($filenames as $filename) {
			@unlink($product_files_dir . '/' . $productid . '/' . $filename);
		}
		db_query('DELETE FROM ' . $sql_tbl['product_files'] . ' WHERE fileid IN (' . $fids_str . ') AND productid=' . $productid);

		foreach ($filenames as $fid => $filename) {
            if ($geid && $fields['p_files'][$fid] == 'Y') {
			while ($pid = func_ge_each($geid, 1, $productid)) {
					@unlink($product_files_dir . '/' . $pid . '/' . $filename);
                    db_query('DELETE FROM ' . $sql_tbl['product_files'] . ' WHERE fileid = "' . $fid . '" AND productid=' . $pid);
				}
			}
		}

		$top_message['content'] = func_get_langvar_by_name('msg_adm_product_files_del');
		$top_message['type'] = 'I';
	} else {
		$top_message['content'] = func_get_langvar_by_name('lbl_no_items_have_been_selected');
		$top_message['type'] = 'E';
	}
	func_refresh('product_files');
} elseif ($mode == 'gen_thumb_d' && !empty($thumbid)) {
		$auto_thumb_error = '';
		if (func_generate_image($productid, 'D', 'T', false, false, $thumbid)) {
			func_save_product_thumb_image($productid, 'T');
			$top_message['content'] = func_get_langvar_by_name('lbl_thumb_success_generated', null, false, true);
			$top_message['type'] = 'I';
		}
		func_refresh();
}
?>
