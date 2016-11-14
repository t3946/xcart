<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

function func_set_correct_det_img_test($image_info, $update = false)
{

    global $sql_tbl, $config;

    if (!empty($image_info["image_path"])) {
        $file_name_path = $image_info["image_path"];
    } elseif (!empty($image_info["file_path"])) {
        $file_name_path = $image_info["file_path"];
    }

    $width = $image_info["image_x"];
    $height = $image_info["image_y"];

    if ($width >= $config['Appearance']['max_width_det_img'] || $height >= $config['Appearance']['max_height_det_img']) {
        $im = new Imagick();
        try {
            $im->pingImage($file_name_path);
        } catch (ImagickException $e) {
            throw new Exception(_('Invalid or corrupted image file, please try uploading another image.'));
        }

        try {
            /* send thumbnail parameters to Imagick so that libjpeg can resize images
             * as they are loaded instead of consuming additional resources to pass back
             * to PHP. */

            $R = MIN($config['Appearance']['max_width_det_img'] / $width, $config['Appearance']['max_height_det_img'] / $height, 1);
            $new_width = round(abs($R * $width));
            $new_height = round(abs($R * $height));

            $im->setSize($new_width, $width);
            $im->readImage($file_name_path);
            $im->thumbnailImage(abs($R * $width), 0, false);

            $im->setImageFileName($file_name_path);
            $im->writeImage();

            $image_info["image_x"] = $new_width;
            $image_info["image_y"] = $new_height;
            $image_info["image_size"] = filesize($file_name_path);

            if ($update && !empty($image_info["imageid"])) {
                db_query("UPDATE $sql_tbl[images_D] SET image_x='$new_width', image_y='$new_height', image_size='$image_info[image_size]' WHERE imageid='$image_info[imageid]'");
            }

        } catch (ImagickException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            throw new Exception(_('An error occured reszing the image.'));
        }

        /* cleanup Imagick */
        $im->destroy();
    }

    return $image_info;
}


$image_data = func_query_first("SELECT * FROM $sql_tbl[images_D] WHERE id='380448' ORDER BY orderby, imageid ASC");
$image_data = func_set_correct_det_img_test($image_data, true);


