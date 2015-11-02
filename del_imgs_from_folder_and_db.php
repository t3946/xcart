<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

set_time_limit(0);
ini_set('memory_limit', '512M');

if ($config["del_imgs_from_folder_and_db"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='del_imgs_from_folder_and_db'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='del_imgs_from_folder_and_db'");

$started_at = time();
$log_text = " * * *  Images_cleanup started  * * * ";
func_backprocess_log("Images_cleanup", $log_text);
$log_text = "";

x_load('product', 'files', 'backoffice', "image");
######################################################################################

$image_types = array("T","P","D");
//$image_types = array("D");
$deleted_files = array();
$kp = 1;

foreach ($image_types as $type){

/*
######
if ($type != "D"){
	continue;
}
######
*/

  $count_deleted_files = 0;
  $count_files = 0;

  $dir = $xcart_dir."/images/".$type;
  $log_text .= $dir."\n";
  $dh  = opendir($dir);
  while (false !== ($filename = readdir($dh))) {

    if ($kp % 10 == 0) {
	func_flush(".");
        if($kp % 500 == 0) {
		func_flush("<br />\n");
	}
        func_flush();
    }
    $kp++;


    $full_filename = $xcart_dir."/images/".$type."/".$filename;
    if (!is_dir($full_filename)){

	$count_files++;

	$check_image_type_and_name_path = "/images/".$type."/".$filename;

	if ($type == "T" || $type == "P"){

		$images_product = func_query($qqq="Select xcart_images_$type.filename, xcart_images_$type.image_path, P.forsale, xcart_images_$type.imageid
			From xcart_images_$type 
		        left join xcart_products P ON P.productid = xcart_images_$type.id
			where xcart_images_$type.image_path LIKE '%$check_image_type_and_name_path'
			");

		$delete_file = true;
		if (!empty($images_product)){
			foreach ($images_product as $v){
				if ($v["forsale"] == "Y"){
					$delete_file = false;
				        print("\r\n");
				        print($type.": ".$full_filename." --> ");
					print(" forsale:".$v["forsale"]." ");
					print(" leave it ");
				} else {
					db_query("DELETE FROM xcart_images_$type WHERE imageid='$v[imageid]'");
		//			print(" db delete ");
				}
			}
		}

		if ($delete_file){
			unlink($full_filename);
//			$deleted_files[] = $full_filename;
		//	print(" fs delete ");
			$count_deleted_files++;
		}

	}
	elseif ($type == "D"){

	        print("\r\n");
	        print($type.": ".$full_filename." --> ");
		$images_product = func_query($qqq="Select IP.filename, P.productid, IP.image_path, IP.imageid
			From xcart_images_D IP
			left join xcart_products P ON P.productid = IP.id
			where IP.image_path LIKE '%$check_image_type_and_name_path'
		");

                $delete_file = true;
                if (!empty($images_product)){
                        foreach ($images_product as $v){
				print(" pid:".$v["productid"]." ");
                                if (!empty($v["productid"])){
                                        $delete_file = false;
					print(" leave it ");
                                } else {
                                        db_query("DELETE FROM xcart_images_$type WHERE imageid='$v[imageid]'");
					print(" db delete ");
                                }
                        }
                }

                if ($delete_file){
                        unlink($full_filename);
			print(" fs delete ");
                        $count_deleted_files++;
                }
	}

    }
  }


  $log_text .= "count_files: ".$count_files."\n";
  $log_text .= "delete_file: ".$delete_file."\n---\n";

}

######################################################################################

###
$date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $started_at));
$date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', time()));
$interval = $date1->diff($date2);
$years = $interval->format("%y");
$months = $interval->format("%m");
$days = $interval->format("%d");
$hours = $interval->format("%h");
$mins = $interval->format("%i");
$age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
###

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='del_imgs_from_folder_and_db'");
$log_text .= "Completed. Duration: ".$age_str;
func_backprocess_log("Images_cleanup", $log_text);

die("DONE!");
?>
