<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

if ($config["cidev_image_generator"] == "Y"){
	$log_text = "--- already launched";
	func_backprocess_log("image generator", $log_text);

//        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_image_generator'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_image_generator'");

$started_at = time();
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("image generator", $log_text);

x_load("image", "gd", "product");

ini_set('memory_limit', '1024M');
set_time_limit(0);

######################################################################################

$sleep_time = 2; //  seconds
$cnt = 0;

$query = <<<SQL
Select 
		P.productid,
		ID.image_path,
		IT.id as no_image_T,
		IP.id as no_image_P
from xcart_products P
		left join xcart_images_D ID ON ID.id = P.productid
		left join xcart_images_T IT ON IT.id = P.productid
		left join xcart_images_P IP ON IP.id = P.productid
where P.forsale = 'Y' and ID.id is not null and (IT.id is null or IP.id is null)
group by P.productid
limit 500
SQL;

$products = db_query($query);

while ($product = db_fetch_array($products)){

	if (empty($product["image_path"]) || empty($product["productid"]))
		continue;
	
//	$image_id = func_query_first_cell("SELECT imageid FROM $sql_tbl[images_D] WHERE id='$product[productid]' ORDER BY orderby, imageid ASC");
	$image_data = func_query_first("SELECT * FROM $sql_tbl[images_D] WHERE id='$product[productid]' ORDER BY orderby, imageid ASC");
	$image_id = $image_data["imageid"];

	if (empty($image_id))
		continue;

	$image_data = func_set_correct_det_img($image_data, true);

/*
	$file_name = $image_data["image_path"];
	$width = $image_data["image_x"];
	$height = $image_data["image_y"];

	if ($width >= 620  || $height >= 800){
		$im = new Imagick();
		try {
		  $im->pingImage($file_name);
		} catch (ImagickException $e) {
		  throw new Exception(_('Invalid or corrupted image file, please try uploading another image.'));
		}

//		$width  = $im->getImageWidth();
//		$height = $im->getImageHeight();

		  try {

		    $R = MIN(619/$width,799/$height,1);

		    $new_width = abs($R*$width);
		    $new_height = abs($R*$height);

		    $im->setSize($new_width, $width);
		    $im->readImage($file_name);
		    $im->thumbnailImage(abs($R*$width), 0, false);

		    $thumbnail_name = $file_name;

		    $im->setImageFileName($thumbnail_name);
		    $im->writeImage();

		    db_query("UPDATE $sql_tbl[images_D] SET image_x='$new_width', image_y='$new_height' WHERE imageid='$image_id'");

		  }
		  catch (ImagickException $e)
		  {
		    header('HTTP/1.1 500 Internal Server Error');
		    throw new Exception(_('An error occured reszing the image.'));
		  }

        	$im->destroy();
	}
*/

	print($product["productid"]."\n\r");
	if (empty($product["no_image_T"])){
		if (func_generate_image($product["productid"], 'D', 'T', false, false, $image_id)) {
			func_save_product_thumb_image($product["productid"], 'T');
		} else {
			$log_text = 'Error generate thumbnail. Imageid:'.$image_id;
			func_backprocess_log("image generator", $log_text);
		}
	}

        if (empty($product["no_image_P"])){
		if (func_generate_image($product["productid"], 'D', 'P', false, false, $image_id)) {
			func_save_product_thumb_image($product["productid"], 'P');
		} else {
			$log_text = 'Error generate product image. Imageid:'.$image_id;
			func_backprocess_log("image generator", $log_text);
		}
	}

        $cnt++;
        if ($cnt % 10 == 0) {
                func_flush(".");
                if($cnt % 500 == 0) {
                        func_flush("<br />\n");
                }
                func_flush();
        }

	sleep($sleep_time);
}
db_free_result($products);

######################################################################################

$finished_at = time();
$duration = $started_at - $finished_at;
$duration = $duration/(60*60);
$duration = round($duration,1);
db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_image_generator'");
$log_text = "Cron completed. Duration: ".$duration." hours, processed ".$cnt." records";
func_backprocess_log("image generator", $log_text);
die("DONE!");
?>
