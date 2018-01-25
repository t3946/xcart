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
# $Id: image_selection.php,v 1.41.2.1 2006/06/23 07:14:17 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','files','product');

x_session_register("file_upload_data", []);
x_session_register('fields', []);
if (isset($product_field)) {
	$fields['product_image'] = $product_field;
}
if (isset($thumb_field)) {
	$fields['thumbnail'] = $thumb_field;
}

# For IE
if ($imgid == 'N') {
	$imgid = '';
}
$not_image = $notimage;
# /For IE

if (strpos($type, 'D') === false) {
	$not_image = '';
}

if ($not_image == 'avail') {
	x_session_register("file_upload_data_not_image", []);
	$image_extensions = array('jpeg', 'jpg', 'gif', 'png', 'bmp');
}

$service_fields = array("file_path", "source", "image_x", "image_y", "image_size", "image_type", "dir_upload", "id", "type", "date", "filename");

if ($a = strpos($type,'_') !== false) {
	# Multi-upload mode
	list($type, $multi_id) = explode('_', $type);
}

if ($not_image != 'avail' && !isset($config['available_images'][$type]) || empty($type)) {
	func_close_window();
}

$config_data = $config['setup_images'][$type];
$userfiles_dir = func_get_files_location().DIRECTORY_SEPARATOR;

#
# POST method
#

if ($REQUEST_METHOD == "POST") {

	$data = array();
    $file_upload_data_not_image = [];
	$data["is_copied"] = false; # file is not a copy and should not deleted

	switch($source) {
	case "S": # server path (user's files)
		$newpath = trim($newpath);
		if (!zerolen($newpath)) {
			$data["file_path"] = $userfiles_dir.$newpath;
		}
		break;
	case "U": # URL
		$fileurl = trim($fileurl);
		if (!zerolen($fileurl)) {
			if (strpos($fileurl, "/") === 0) {
				$fileurl = $http_location.$fileurl;
			} elseif (!is_url($fileurl)) {
				$fileurl = "http://".$fileurl;
			}

			$data["file_path"] = $fileurl;
		}
		break;
	case "L": # uploaded file
            if (zerolen($userfile)) {
                break;
            }

		if ($not_image != 'avail' && func_is_image_userfile($userfile, $userfile_size, $userfile_type) || $not_image == 'avail') {
			$data["is_copied"] = true; # can be deleted
            if ($from_parent_window == 'Y') {
                    $data['filename'] = basename($_FILES[$filename]['name']);
                $userfile = func_move_uploaded_file($filename);
            } else {
                    $data['filename'] = basename($_FILES['userfile']['name']);
			$userfile = func_move_uploaded_file("userfile");
            }
			$data["file_path"] = $userfile;
		}
	}

	if (isset($data["file_path"]) && !func_is_allowed_file($data["file_path"])) {
		# cannot accept this file
		if ($data["is_copied"])
			unlink($data["file_path"]);

		unset($data["file_path"]);
	}

	if (!isset($data["file_path"]) || zerolen($data["file_path"])) {
		# No file is selected
		echo "<script>window.close();</script>";
		exit;
	}

	$is_image = false;
	if ($not_image == 'avail') {
		# Get file type
		$pathinfo = pathinfo($data['filename']);
		$extension = $pathinfo['extension'];
		$is_image = in_array(strtolower($extension), $image_extensions);
	}

	if ($not_image == 'avail' && $is_image && !func_is_image_userfile($userfile, $userfile_size, $userfile_type)) {
		echo "<script>window.close();</script>";
		exit;
	}

	if ($not_image != 'avail' || $is_image) {
		if (!isset($config['available_images'][$type])) {
			func_close_window();
		}
	list(
		$data["file_size"],
		$data["image_x"],
		$data["image_y"],
		$data["image_type"]) = func_get_image_size($data["file_path"]);
	} else {
		$data['file_size'] = func_filesize($data['file_path']);
		$data['is_image'] = $is_image;
	}

	$ignore = false;
	$msg = '';
	
	if ($type == 'T') {
		$err_field_id = 'err_size_text_' . $type . '_';
		if ($data['image_x'] > $config['Appearance']['thumbnail_width']) {
			$ignore = true;
			$msg .= func_get_langvar_by_name('err_max_size_thumb_img', 
				array('SIZE' => 'width', 'N' => $config['Appearance']['thumbnail_width']), false, true);
			
		}
		if ($data['image_y'] > $config['Appearance']['thumbnail_width']) {
			$ignore = true;
			if ($msg != '') {
				$msg .= '<br /><br />' . func_get_langvar_by_name('err_max_size_thumb_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['thumbnail_width']), false, true);
			} else {
				$msg .= func_get_langvar_by_name('err_max_size_prod_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['thumbnail_width']), false, true);
			}
		}
	}

	if ($type == 'P') {
		$err_field_id = 'err_size_text_' . $type . '_';
		if ($data['image_x'] > $config['Appearance']['max_width_prod_img']) {
			$ignore = true;
			$msg .= func_get_langvar_by_name('err_max_size_prod_img', 
				array('SIZE' => 'width', 'N' => $config['Appearance']['max_width_prod_img']), false, true);
			
		}
		if ($data['image_y'] > $config['Appearance']['max_height_prod_img']) {
			$ignore = true;
			if ($msg != '') {
				$msg .= '<br /><br />' . func_get_langvar_by_name('err_max_size_prod_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['max_height_prod_img']), false, true);
			} else {
				$msg .= func_get_langvar_by_name('err_max_size_prod_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['max_height_prod_img']), false, true);
			}
		}
	}

	if ($type == 'D') {
		$err_field_id = 'err_size_text_det_';

		if ($data['image_x'] > $config['Appearance']['max_width_det_img'] || $data['image_y'] > $config['Appearance']['max_height_det_img']){
//func_print_r($data);
			$data = func_set_correct_det_img($data);
//func_print_r($data);
//die();
		}
/*
		if ($data['image_x'] > $config['Appearance']['max_width_det_img']) {
			$ignore = true;
			$msg .= func_get_langvar_by_name('err_max_size_det_img',
				array('SIZE' => 'width', 'N' => $config['Appearance']['max_width_det_img']), false, true);
		}

		if ($data['image_y'] > $config['Appearance']['max_height_det_img']) {
			$ignore = true;
			if ($msg != '') {
				$msg .= '<br /><br />' . func_get_langvar_by_name('err_max_size_det_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['max_height_det_img']), false, true);
			} else {
				$msg .= func_get_langvar_by_name('err_max_size_det_img',
					array('SIZE' => 'height', 'N' => $config['Appearance']['max_height_det_img']), false, true);
			}
		}
*/
	}

    if ($from_bulk == 'Y') {
        $ignore = false;
    }

    if ($from_parent_window == 'Y' && $from_bulk != 'Y') {
        $top_message['content'] = $msg;
        $top_message['type'] = 'E';
    }

	if ($data["file_size"] == 0 || $ignore) {
		# Ignore non readable or zero-sized
		if ($data["is_copied"])
			unlink($data["file_path"]);

		$data["file_path"] = "";
		$data["is_copied"] = false;
	}

	if (!isset($data["filename"])) {
		$data["filename"] = basename($data['file_path']);
	}

	$data["source"] = $source;
	$data["id"] = $id;
	$data["type"] = $type;
	$data["date"] = time();

	if (empty($file_upload_data) || !is_array($file_upload_data)) {
        $file_upload_data = [];
	}

	if (!empty($multi_id)) {
		if ($not_image != 'avail' || $not_image == 'avail' && $is_image) {
		if (!empty($type) && (!isset($file_upload_data[$type]) || empty($file_upload_data[$type]) || !is_array($file_upload_data[$type]))) {
			$file_upload_data[$type] = array();
		}
		$file_upload_data[$type][$multi_id] = $data;
	} else {
			if (!is_array($file_upload_data_not_image[$id])) {
				$file_upload_data_not_image[$id] = array();
			}
			$file_upload_data_not_image[$id][$multi_id] = $data;
		}
	} else {
		if ($not_image != 'avail' ||  $not_image == 'avail' && $is_image) {
	$file_upload_data[$type] = $data;
		} else {
			$file_upload_data_not_image[$id] = $data;
		}
	}

	if ($type == 'P' || $type == 'T') {
		if (!func_save_product_thumb_image($id, $type) && $from_bulk == 'Y') {
            $msg .= ' ' . func_get_langvar_by_name('err_image_not_uploaded', array(
                'FILEPATH'  =>  $fileurl), false, true);
        }
	} elseif ($type == 'B' && $from_parent_window == 'Y') {
        if (func_check_image_posted($file_upload_data, 'B') && $id > 0) {
            func_save_image($file_upload_data, 'B', $id);
        }
	} elseif ($type == 'C' && $from_parent_window == 'Y') {
		if (func_check_image_posted($file_upload_data, 'C')) {
			func_save_image($file_upload_data, 'C', $id);
		}
	} else if ($type == 'S' && $from_parent_window == 'Y') {
		if (func_check_image_posted($file_upload_data, 'S')) {
			func_save_image($file_upload_data, 'S', $id);
		}
	}

#
##
###
        else if ($type == 'F' && $from_parent_window == 'Y') {
                if (func_check_image_posted($file_upload_data, 'F')) {
                        func_save_image($file_upload_data, 'F', $id);
                }
        }
###
##
#

	x_session_save();

    if ($from_parent_window != 'Y') {

	if ($ignore) {
		$order   = array("\r\n", "\n", "\r");
		$msg = str_replace($order, '', $msg);
		echo "<script type=\"text/javascript\">
<!-- 
if (window.opener.document.getElementById('$err_field_id$id')) {
	window.opener.document.getElementById('$err_field_id$id').setAttribute('style', '');
	window.opener.document.getElementById('{$err_field_id}td_$id').innerHTML = '$msg';
	window.close();
}
--></script>";
		exit;
	} else {
		echo "<script type=\"text/javascript\">
<!-- 
if (window.opener.document.getElementById('$err_field_id$id')) {
	window.opener.document.getElementById('{$err_field_id}td_$id').innerHTML = '';
	window.opener.document.getElementById('$err_field_id$id').setAttribute('style', 'display: none;');
}
--></script>";
	}


	if (!empty($multi_id)) {
		echo "<script type=\"text/javascript\">
<!-- 
if (window.opener.document.getElementById('upload_fname_$multi_id')) {
	window.opener.document.getElementById('upload_fname_$multi_id').innerHTML = '$data[filename]';
	window.opener.document.getElementById('userfile_$multi_id').setAttribute('disabled', 'disabled');
}
window.close();
--></script>";
	} else {
		if ($not_image != 'avail' || $is_image) {
	$image_data = array(
		"image_x" => $data['image_x'],
		"image_y" => $data['image_y'],
		"image_type" => $data['image_type'],
		"image_size" => $data['file_size']
	);
	$smarty->assign("image", $image_data);
	$alt = func_display("main/image_property.tpl", $smarty, false);
			$lbl_generate_thumbnail = func_get_langvar_by_name('lbl_generate_thumbnail', null, false, true);
			$lbl_delete_image = func_get_langvar_by_name('lbl_delete_image', null, false, true);
			$gen_thumb_code = '<input type="button" value="' . $lbl_generate_thumbnail . '" onclick="javascript: submitForm(this, \\\'gen_thumb\\\');" />';
			$del_img_code = '<input id="' . $imgid . '_delete" type="button" value="' . $lbl_delete_image . '" onclick="javascript: submitForm(this, \\\'delete_product_image\\\');" />';
	echo "<script type=\"text/javascript\">
<!--

// Show filename if 'upload_fname' element is exists
if (window.opener.document.getElementById('upload_fname') && '$type' == 'S') {
	window.opener.document.getElementById('upload_fname').innerHTML = '$data[filename]';
}

// Show filename if 'upload_fname_favicon' element is exists
if (window.opener.document.getElementById('upload_fname_favicon') && '$type' == 'F') {
        window.opener.document.getElementById('upload_fname_favicon').innerHTML = '$data[filename]';
}

// Block simple file selector
if (window.opener.document.getElementById('file_$imgid')) {
	window.opener.document.getElementById('file_$imgid').setAttribute('disabled', 'disabled');
}

if (window.opener.document.getElementById('".$imgid."')) {
	window.opener.document.getElementById('".$imgid."').src = '".$xcart_web_dir."/image.php?type=".$type."&id=".$id."&tmp=".time()."';
	window.opener.document.getElementById('".$imgid."').alt = \"".str_replace(array("\n","\r",'"'), array("\\n","",'\"'), $alt)."\";
	if ('$type' == 'P') {
		window.opener.document.getElementById('gen_thumb_btn').innerHTML = '" . $gen_thumb_code . "';
		change_btn = window.opener.document.getElementById('" . $imgid . "_btns_td').innerHTML;
		if (! window.opener.document.getElementById('" . $imgid . "_delete')) {
			btns = change_btn + '$del_img_code';
			window.opener.document.getElementById('" . $imgid . "_btns_td').innerHTML = btns;
		}
	}

} else if (window.opener.document.getElementById('".$imgid."_0')) {
	var cnt = 0;
	while (window.opener.document.getElementById('".$imgid."_'+cnt)) {
		window.opener.document.getElementById('".$imgid."_'+cnt).src = '".$xcart_web_dir."/image.php?type=".$type."&id=".$id."&tmp=".time()."';
		cnt++;
	}
}

if (window.opener.document.getElementById('".$imgid."_text')) {
	window.opener.document.getElementById('".$imgid."_text').style.display = '';
	var cnt;
	for (cnt = 1; true; cnt++) {
		if (!window.opener.document.getElementById('".$imgid."_text'+cnt))
			break;
		window.opener.document.getElementById('".$imgid."_text'+cnt).style.display = '';
	}
}

if (window.opener.document.getElementById('skip_image_".$type."')) {
	window.opener.document.getElementById('skip_image_".$type."').value = '';
} else if (window.opener.document.getElementById('skip_image_".$type."_".$id."')) {
	window.opener.document.getElementById('skip_image_".$type."_".$id."').value = '';
}

if (window.opener.document.getElementById('".$imgid."_reset'))
	window.opener.document.getElementById('".$imgid."_reset').style.display = '';

if (window.opener.document.getElementById('".$imgid."_onunload'))
	window.opener.document.getElementById('".$imgid."_onunload').value = 'Y';

window.close();
-->
</script>";
	}
	}
	exit;
    }
}

if ($from_parent_window != 'Y') {
    if ($not_image != 'avail' || $is_image) {
	$_table = $sql_tbl["images_".$type];
	$_field = ($config['available_images'][$type] == 'U') ? "id" : "imageid";

	$smarty->assign("imgid", $imgid);
	$smarty->assign("config_data", $config_data);
	$smarty->assign("upload_max_filesize", ($config['setup_images'][$type]['location'] == 'DB') ? func_get_max_upload_size() : ini_get("upload_max_filesize"));
    } else {
	$smarty->assign('upload_max_filesize', ini_get('upload_max_filesize'));
    }

    $smarty->assign("type", $type . ((!empty($multi_id)) ? '_' . $multi_id : ''));
    $smarty->assign("id", $id);
    $smarty->assign("parent_window", $parent_window);
    $smarty->assign('not_image', $not_image);
    $smarty->assign('geid', $geid);

    func_display("main/popup_image_selection.tpl",$smarty);
}
?>
