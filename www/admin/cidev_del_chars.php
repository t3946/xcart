<?php
require "./auth.php";
if (empty($login))	func_header_location("error_message.php?antibot_error");
if (!empty($login))	require $xcart_dir."/include/security.php";
x_load("debug");
set_time_limit(0);

if (!($type == "D" || $type == "P" || $type == "T" || $type == "C")) die("Wrong type");

$image_folder_path = $xcart_dir."/images/".$type."/";
$image_tbl = 'images_'.$type;
$bad_chars = array("[","]", " ", "'", "\"", "`", ",");
$replace_to = "_";

$images = db_query("SELECT imageid, image_path FROM $sql_tbl[$image_tbl]");

while ($image = db_fetch_array($images)) {

		$image_path = $image["image_path"];
		$new_image_path = str_replace($bad_chars, $replace_to, $image_path);

		if ($image_path != $new_image_path){

			$current_filename_arr = explode("/", $image_path);
			$current_filename = array_pop($current_filename_arr);
			$current_filename_path = $image_folder_path.$current_filename;

			$is_the_same_img = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[$image_tbl] WHERE image_path='".addslashes($new_image_path)."'");
			if (!empty($is_the_same_img) || $is_the_same_img > "0"){
				$replace_type_str = "images/".$type."/";
				$insert_type_str = "images/".$type."/".time()._;
				$new_image_path = str_replace($replace_type_str, $insert_type_str, $new_image_path);
			}

                        $new_current_filename_arr = explode("/", $new_image_path);
                        $new_current_filename = array_pop($new_current_filename_arr);
			$new_current_filename_path = $image_folder_path.$new_current_filename;

			if (@copy($current_filename_path, $new_current_filename_path)) {
				print(".");
				@unlink($current_filename_path);
				db_query("UPDATE $sql_tbl[$image_tbl] SET image_path='$new_image_path' WHERE imageid='$image[imageid]'");
			}
//func_print_r($image, $new_image_path, $xcart_dir, $image_folder_path, $current_filename, $new_current_filename, $current_filename_path, $new_current_filename_path);
		}
}

print"<br />End of script";
?>
