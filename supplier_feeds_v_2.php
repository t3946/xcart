<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

set_time_limit(0);
ini_set('memory_limit', '512M');

if ($config["supplier_feeds_v_2"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='supplier_feeds_v_2'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");

$started_at = time();
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("supplier_feeds_v_2", $log_text);

x_load('cart','mail','order','product','taxes', 'files', 'backoffice', "image", "gd", "xml", "category");
######################################################################################

if (empty($config["Supplier_feeds"]["Feeds_storage_path"]) || empty($config["Supplier_feeds"]["Feeds_storage_login"]) || empty($config["Supplier_feeds"]["Feeds_storage_password"])){
        $log_text = "--- login credentials incorrect. Script stopped.";
        func_backprocess_log("supplier_feeds_v_2", $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
	db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");
	die($log_text);
}

$supplier_feeds = func_query("SELECT * FROM $sql_tbl[supplier_feeds] WHERE enabled='Y'");

if (empty($supplier_feeds) || !is_array($supplier_feeds)){
        $log_text = "--- xcart_supplier_feeds does not have 'enabled' rows. Script stopped.";
        func_backprocess_log("supplier_feeds_v_2", $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");
	die($log_text);
}

$feed_types = array("I"=>"inventory", "P"=>"product");

$product_cols = func_query_column("SHOW COLUMNS FROM ".$sql_tbl['products']);

$product_cols_replace = array(
	"sku" => "productcode",
	"quantity" => "r_avail",
	"eta_date" => "eta_date_mm_dd_yyyy",
	"title" => "product"
);

$manufacturerid_info = func_query_hash("SELECT code, manufacturerid, manufacturer FROM $sql_tbl[manufacturers]", 'manufacturerid', false);

foreach ($supplier_feeds as $k => $v){

	$start_supplier_time = time();

//	$last_update_time = time();
	$discontinued_products_count = 0;
	$updated_products_count = 0;
	$inserted_products_count = 0;
	$all_feed_productcodes = array();

//func_print_r($v);
//die();

	$md5_arr = explode(".",$v["feed_file_name"]);
	array_pop($md5_arr);
	$md5_file = implode(".",$md5_arr).".md5";

	$md5_file_is_found = false;
        $ftp = ftp_connect($config["Supplier_feeds"]["Feeds_storage_path"]);
        if ($ftp && @ftp_login($ftp, $config["Supplier_feeds"]["Feeds_storage_login"], $config["Supplier_feeds"]["Feeds_storage_password"])) {
                ftp_pasv($ftp, true);

                $local_file = $xcart_dir . "/files/product_feeds_v2/" .str_replace("/","_",$md5_file);
                $server_file = $md5_file;

                $file_is_found = false;
                if (@ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
                                $md5_file_is_found = true;
                }

                ftp_quit($ftp);
	}


	if ($md5_file_is_found){

	        $handle = fopen($local_file, "r");
                $md5 = fread($handle, filesize($local_file));
                fclose($handle);


                if ($md5 == $v["last_md5"]){
	                $log_text = "manufacturerid: ".$v["manufacturerid"].". md5 = last_md5. Feed skipped.";
//                      func_backprocess_log("supplier_feeds_v_2", $log_text);
                        func_backprocess_log("supplier feeds errors", $log_text);
                        continue;
                }


        } else {

#
# ### Disable for test N1
#

        	$log_text = "manufacturerid: ".$v["manufacturerid"].". md5 file is not found. Skipped.";
	        func_backprocess_log("supplier_feeds_v_2", $log_text);
                func_backprocess_log("supplier feeds errors", $log_text);
                continue;


        }


	$ftp = ftp_connect($config["Supplier_feeds"]["Feeds_storage_path"]);
#
# ### Disable for test N2
#

	if ($ftp && @ftp_login($ftp, $config["Supplier_feeds"]["Feeds_storage_login"], $config["Supplier_feeds"]["Feeds_storage_password"])) {
//	if (1==1) {
		ftp_pasv($ftp, true);

                $local_file = $xcart_dir . "/files/product_feeds_v2/" .str_replace("/","_",$v["feed_file_name"]);
                $server_file = $v["feed_file_name"];


#
# ### Disable for test N3
#

		$file_is_found = false;
                if (@ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
                                $file_is_found = true;
                }

		ftp_quit($ftp);


###
//$file_is_found = true;
//$local_file = $xcart_dir . "/files/product_feeds_v2/feed262i-1.txt";
###




		if ($file_is_found){

			$handle = fopen($local_file, "r");
			$contents = fread($handle, filesize($local_file));
			fclose($handle);


/*
			$md5 = md5($contents);

			if ($md5 == $v["last_md5"]){
	                        $log_text = "manufacturerid: ".$v["manufacturerid"].". md5 = last_md5. Feed skipped.";
//        	                func_backprocess_log("supplier_feeds_v_2", $log_text);
        	                func_backprocess_log("supplier feeds errors", $log_text);
                	        continue;
			}
*/

			$products = json_decode($contents, true);


			if (empty($products["products"]) || !is_array($products["products"])){
	                        $log_text = "manufacturerid: ".$v["manufacturerid"].". No products found. (".$feed_types[$v["feed_type"]].")";
        	                func_backprocess_log("supplier_feeds_v_2", $log_text);
        	                func_backprocess_log("supplier feeds errors", $log_text);
				continue;
			}

			$count_products_in_json = count($products["products"]);
			if ($count_products_in_json != $products["products_in_feed"]){
	                        $log_text = "manufacturerid: ".$v["manufacturerid"].". Corrupted feed file (by products in feed count). (".$feed_types[$v["feed_type"]].")".$count_products_in_json." vs ".$products["products_in_feed"];
			        func_backprocess_log("supplier_feeds_v_2", $log_text);
			        func_backprocess_log("supplier feeds errors", $log_text);
			        db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");
			        continue;
//			        die($log_text);
			}

                        if ($products["supplier_id"] != $v["manufacturerid"]){
                                $log_text = "manufacturerid: ".$v["manufacturerid"].". Wrong supplier_id. (".$feed_types[$v["feed_type"]].") . Feed skipped.";
                                func_backprocess_log("supplier_feeds_v_2", $log_text);
                                func_backprocess_log("supplier feeds errors", $log_text);
                                continue;
                        }

			if ($v["last_update_items_count"] > 0){
				if (($products["products_in_feed"] / $v["last_update_items_count"]) < $v["threshold"]){
        	                        $log_text = "manufacturerid: ".$v["manufacturerid"].". Too few products in feed in comparison with last update $v[products_in_feed] against $v[last_update_items_count]. (".$feed_types[$v["feed_type"]].")";
                	                func_backprocess_log("supplier_feeds_v_2", $log_text);
                	                continue;
				}
			}

			$create_date_arr = explode("-", $products["create_date"]);
                        $create_date_time = mktime(0, 0, 0, $create_date_arr[0], $create_date_arr[1], $create_date_arr[2]);
                        $create_date_time_diff = time() - $create_date_time;
                        if ($create_date_time_diff > (60*60*24*2)){
//                                $log_text = "manufacturerid: ".$v["manufacturerid"].". Obsolete create date. (".$feed_types[$v["feed_type"]].") . Feed skipped.";                                               
//                                func_backprocess_log("supplier_feeds_v_2", $log_text);
//                                func_backprocess_log("supplier feeds errors", $log_text);
//                                continue;
			}

                        $log_text = "manufacturerid: ".$v["manufacturerid"].". Started. (".$feed_types[$v["feed_type"]].")";
                        func_backprocess_log("supplier_feeds_v_2", $log_text);


                        if (!empty($products["dont_update_fields"]) && is_array($products["dont_update_fields"])){
	                        foreach ($products["dont_update_fields"] as $k_du => $v_du){
        	                        $idx = array_search($v_du, array_keys($product_cols_replace));
                                        if ($idx !== false) {
                	                        $products["dont_update_fields"][] = $product_cols_replace[$v_du];
                                                unset($products["dont_update_fields"][$k_du]);
                                        }
                                }
			}

			if (!empty($products["defaults"]) && is_array($products["defaults"])){
                                foreach ($products["defaults"] as $k_s => $v_s){
                                        $idx = array_search($k_s, array_keys($product_cols_replace));
                                        if ($idx !== false) {
                                                $products["defaults"][$product_cols_replace[$k_s]] = $v_s;
                                                unset($products["defaults"][$k_s]);
                                        }
                                }
			}

/* --------------------------------------------------------------------------------------------------- */

//func_print_r($products);
//die();

			foreach ($products["products"] as $kp => $p){

                                foreach ($p as $k_s => $v_s){
                                        $idx = array_search($k_s, array_keys($product_cols_replace));
                                        if ($idx !== false) {
                                                $p[$product_cols_replace[$k_s]] = $v_s;
                                                unset($p[$k_s]);
                                        }
                                }

                                if (empty($p["productcode"])){
                                        continue;
                                }

                                $productcode = strtoupper(trim($p["productcode"]));
                                $productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='$productcode'");


/*
#
#####
if ($productid != "300084"){
//if ($productid != "328244"){
//if ($productcode != "BSE-251914"){
	continue;
} else {
func_print_r($p, $productid);
}
#####
#
*/

                                if ($v["feed_type"] == "I"){ // inventory
                                        if (empty($productid)){
                                                continue;
                                        }

#
##
###
//                                        db_query("UPDATE $sql_tbl[products] SET provider='$v[feed_file_name]' WHERE productid='$productid'");
                                        db_query("UPDATE $sql_tbl[products] SET controlled_by_feed='$v[feed_file_name]' WHERE productid='$productid'");
###
##
#

                                        $all_feed_productcodes[] = $productcode;
                                }


				if ($v["feed_type"] == "P"){ // product

#
##
###
                                    if ((!empty($productid) && $v["add_new_only"] == "Y")){
                                        continue;
                                    }
###
##
#


				    if (!empty($productid)){
                                        if (!empty($products["dont_update_fields"]) && is_array($products["dont_update_fields"])){
                                                foreach ($p as $k_pdu => $v_pdu){
                                                        $idx = array_search($k_pdu, $products["dont_update_fields"]);
                                                        if ($idx !== false) {
                                                                unset($p[$k_pdu]);
                                                        }
                                                }
                                        }
				    }
				    else {
                                        if (!empty($products["defaults"]) && is_array($products["defaults"])){
                                                foreach ($products["defaults"] as $k_pdu => $v_pdu){
                                                        $idx = array_search($k_pdu, array_keys($p));
                                                        if ($idx === false) {
                                                                $p[$k_pdu] = $v_pdu;
                                                        }
                                                }
					}
				    }
				}

 
				$product = array();
				foreach ($product_cols as $name) {
					if(isset($p[$name])) {
						$product[$name] = $p[$name];
						unset($p[$name]);
					}
				}

				$not_xcart_products_fields = $p;

				$just_created = false;

				if ($v["feed_type"] == "P"){ // product

/*
if (!empty($not_xcart_products_fields["brand_name"])){
func_print_r($product, $not_xcart_products_fields, $productid);
die();
}
*/


                                        $image_data = array();
					if (!empty($not_xcart_products_fields["images"]) && is_array($not_xcart_products_fields["images"])){
						$alt_idx = 0;
 		                              foreach ($not_xcart_products_fields["images"] as $k_img => $IMAGE_URL){
                            				$alt_context = addslashes($product["product"]);

                            				if ( isset( $not_xcart_products_fields["alt_names"] ) )
                            				{
                                				if ( isset($not_xcart_products_fields["alt_names"][$alt_idx]) )
                                    					$alt_context = addslashes($not_xcart_products_fields["alt_names"][$alt_idx]);
                            				}

                            				$alt_idx = $alt_idx + 1;
#
##
                                                        $SET_IMAGE_URL = "";

                                                        if (strpos($IMAGE_URL, "?") !== false){
                                                                $tmp_IMG_URL_arr = explode("?", $IMAGE_URL);
                                                                $our_IMG_URL = $tmp_IMG_URL_arr[1];

                                                                if (strpos($our_IMG_URL, "&") !== false){
                                                                        $tmp_our_IMG_URL_arr = explode("&", $our_IMG_URL);

                                                                        foreach ($tmp_our_IMG_URL_arr as $v_img){
                                                                                $v_img = strtolower($v_img);
                                                                                if (
                                                                                        strpos($v_img, ".gif") !== false ||
                                                                                        strpos($v_img, ".jpeg") !== false ||
                                                                                        strpos($v_img, ".jfif") !== false ||
                                                                                        strpos($v_img, ".jpg") !== false ||
                                                                                        strpos($v_img, ".jpe") !== false ||
                                                                                        strpos($v_img, ".bmp") !== false ||
                                                                                        strpos($v_img, ".png") !== false
                                                                                ){
                                                                                        if (strpos($v_img, "=") !== false){
                                                                                                $tmp_our_IMG_URL_arr = explode("=", $v_img);
                                                                                                $SET_IMAGE_URL = $tmp_our_IMG_URL_arr[1];
                                                                                        }
                                                                                        else {
                                                                                                $SET_IMAGE_URL = $v_img;
                                                                                        }

                                                                                        break;
                                                                                }
                                                                        }


                                                                }
                                                                else {
                                                                        if (strpos($our_IMG_URL, "=") !== false){
                                                                                $tmp_our_IMG_URL_arr = explode("=", $our_IMG_URL);
                                                                                $SET_IMAGE_URL = $tmp_our_IMG_URL_arr[1];
                                                                        }
                                                                        else {
                                                                                $SET_IMAGE_URL = $our_IMG_URL;
                                                                        }
                                                                }
                                                        }
##
#


                                                        if (empty($SET_IMAGE_URL)){
								###
								$img_path_arr = explode("//", $IMAGE_URL);
								$img_path_arr2 = explode("/", $img_path_arr[1]);
								unset($img_path_arr2[0]);
								$img_path_after = implode("_", $img_path_arr2);
								$img_path_after_arr = explode(".", $img_path_after);
								###

								$ext = array_pop($img_path_after_arr);
								$Prod_ID = $v["manufacturerid"]."_".implode("_", $img_path_after_arr);
	
//                                                      	$ext_arr = explode(".",$IMAGE_URL);
//                                                      	$ext = array_pop($ext_arr);
	                                                        $image_file_name = $Prod_ID.".".$ext;
                                                        } else {
                                                                $image_file_name = $v["manufacturerid"]."_".$SET_IMAGE_URL;

                                                        }

                                                        $image_file_path = $xcart_dir . "/images/D/".$image_file_name;

                                                        if ($k_img % 5 == 0) {
                                                                func_flush(".");
                                                        }

                                                        if (url_exists($IMAGE_URL)){
                                                                if (@copy($IMAGE_URL, $image_file_path)){
                                                                        $img_info = getimagesize($image_file_path);

//                                                                      $image_data[$k_img]['id'] = $productid;
                                                                        $image_data[$k_img]['date'] = time();
                                                                        $image_data[$k_img]['image_path'] = $image_file_path;
                                                                        $image_data[$k_img]['image_type'] = $img_info["mime"];
                                                                        $image_data[$k_img]['image_x'] = $img_info[0];
                                                                        $image_data[$k_img]['image_y'] = $img_info[1];
                                                                        $image_data[$k_img]['image_size'] = filesize($image_file_path);
                                                                        $image_data[$k_img]['alt'] = $alt_context;
                                                                        $image_data[$k_img]['avail'] = 'Y';
                                                                        $image_data[$k_img]['orderby'] = 10*$k_img;

                                                                }
                                                        }
						}
                                        }

					if (empty($productid)){

						if (
							(empty($not_xcart_products_fields["price"]) && empty($product["cost_to_us"])) ||
							empty($product["product"]) ||
							empty($image_data)
						){
							continue; //Skip such product
						}

						################# START: Insert product #################

						$new_product_name = wordwrap($product["product"], 70, "---===---");
						$new_product_name_arr = explode("---===---", $product["product"]);
						$product["product"] = $new_product_name_arr[0];

	                                        $time = time();
//        	                                db_query("INSERT INTO $sql_tbl[products] (productcode, provider, original_provider, add_date, mod_date, source_sfid, manufacturerid, controlled_by_feed) VALUES ('$productcode', '$v[feed_file_name]', '$v[feed_file_name]','" . $time . "', '" . $time . "', '$v[storefront_id]', '$v[manufacturerid]', '$v[feed_file_name]')");
        	                                db_query("INSERT INTO $sql_tbl[products] (productcode, provider, original_provider, add_date, mod_date, source_sfid, manufacturerid) VALUES ('$productcode', '$v[feed_file_name]', '$v[feed_file_name]','" . $time . "', '" . $time . "', '$v[storefront_id]', '$v[manufacturerid]')");
                	                        $productid = db_insert_id();

						db_query("INSERT INTO $sql_tbl[products_categories] (categoryid, productid, main) VALUES ('$v[base_category_id]', '$productid', 'Y')");
	                                        db_query("INSERT INTO $sql_tbl[products_sf] (productid, sfid) VALUES ('$productid', '$v[storefront_id]')");

                                                if (empty($not_xcart_products_fields["price"])){
                                                        $price = (1.15 * $product["cost_to_us"] + 0.3)/0.97;
                                                        $price = price_format($price);
                                                        $not_xcart_products_fields["price"] = $price;
                                                }

        	                                db_query("INSERT INTO $sql_tbl[pricing] (productid, quantity, price) VALUES ('$productid', '1', '".$not_xcart_products_fields["price"]."')");

                	                        $clean_url = func_clean_url_autogenerate('P', $productid, array('product' => $product["product"], 'productcode' => $productcode));
                        	                $clean_url_save_in_history = false;
                                	        db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$productid'");
                                        	func_clean_url_add($clean_url, 'P', $productid);

						$just_created = true;
						$inserted_products_count++;

						unset($not_xcart_products_fields["price"]);
//						func_recalc_product_count(func_get_category_parents($v[base_category_id]));
						################# END: Insert product #################
					} else {
						if ($v["add_new_only"] == "Y"){
							continue;
						}


#
##
###
//						db_query("UPDATE $sql_tbl[products] SET provider='$v[feed_file_name]' WHERE productid='$productid'");
//						db_query("UPDATE $sql_tbl[products] SET controlled_by_feed='$v[feed_file_name]' WHERE productid='$productid'");
###
##
#
					}

					if (!empty($image_data)){
                                                foreach ($image_data as $k_img => $image_info){
                                                        $image_info['id'] = $productid;

                                                        #
                                                        ##
                                                        ###
                                                        # Resize image: https://basecamp.com/2070980/projects/1577907/messages/35919269
                                                        ###
                                                        ##
                                                        #

							$image_info_image_path_arr = explode("/images/D/", $image_info["image_path"]);
							$image_path_from_folder = "./images/D/".$image_info_image_path_arr[1];

//							$is_such_img = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[images_D] WHERE id='$productid' AND (image_path='$image_info[image_path]' OR image_path='$image_path_from_folder')");
							$image_id = func_query_first_cell("SELECT imageid FROM $sql_tbl[images_D] WHERE id='$productid' AND (image_path='$image_info[image_path]' OR image_path='$image_path_from_folder')");

                                                        $image_info["image_path"] = $image_path_from_folder;

							if (empty($image_id)){
/*
							        $file_name = $image_info["image_path"];
							        $width = $image_info["image_x"];
							        $height = $image_info["image_y"];

							        if ($width >= 620  || $height >= 800){
							                $im = new Imagick();
							                try {
							                  $im->pingImage($file_name);
							                } catch (ImagickException $e) {
							                  throw new Exception(_('Invalid or corrupted image file, please try uploading another image.'));
							                }

						        	          try {
//	       								 * send thumbnail parameters to Imagick so that libjpeg can resize images
//							                 * as they are loaded instead of consuming additional resources to pass back
//							                 * to PHP.
//							                 *
	
        							            $R = MIN(619/$width,799/$height,1);

							                    $new_width = abs($R*$width);
							                    $new_height = abs($R*$height);
	
        							            $im->setSize($new_width, $width);
						                	    $im->readImage($file_name);
							                    $im->thumbnailImage(abs($R*$width), 0, false);

							                    $thumbnail_name = $file_name;

								            $im->setImageFileName($thumbnail_name);
							                    $im->writeImage();

									    $image_info["image_x"] = $new_width;
									    $image_info["image_y"] = $new_height;
							                  }
							                  catch (ImagickException $e)
						        	          {
						                	    header('HTTP/1.1 500 Internal Server Error');
							                    throw new Exception(_('An error occured reszing the image.'));
							                  }

							                #* cleanup Imagick *
							                $im->destroy();
							        }
*/
	                                                        $image_id = func_array2insert('images_D', $image_info);
							}
							else {
								func_array2update("images_D", $image_info, "imageid = '$image_id'");
							}

							$image_info["imageid"] = $image_id;
							$image_info = func_set_correct_det_img($image_info, true);

                                                }
					}
				} //if ($v["feed_type"] == "P")


###
				if (!empty($product["fulldescr"])){
					$tmp_fulldescr = $product["fulldescr"];

					$br_arr = array("<br>","<BR>", "<br/>","<Br>", "<bR>", "<Br/>", "<Br />", "<BR/>", "<bR/>", "<bR />", "\n");
					$tmp_fulldescr = str_replace($br_arr, "<br />", $tmp_fulldescr);

					$tmp_fulldescr_arr = explode("<br />", $tmp_fulldescr);

					if (!empty($tmp_fulldescr_arr)){
						foreach ($tmp_fulldescr_arr as $k_br => $v_br){
							$v_br = trim($v_br);
							if (!empty($v_br)) {
								$v_br = "* ".ucfirst($v_br);
								$tmp_fulldescr_arr[$k_br] = $v_br;
							}
						}

						$product["fulldescr"] = implode("<br />", $tmp_fulldescr_arr);
					}
				}
###

				$price_updated = false;
                                if (!empty($not_xcart_products_fields) && is_array($not_xcart_products_fields)){
                                ################# START: Update NOT xcart_product tables #################
					$not_xcart_products_fields = func_addslashes($not_xcart_products_fields);

//func_print_r($not_xcart_products_fields);

                                        if (!empty($not_xcart_products_fields["similar_internal_id"]) && is_array($not_xcart_products_fields["similar_internal_id"])){

						$productid_arr_2 = array();
                                                foreach ($not_xcart_products_fields["similar_internal_id"] as $supplier_internal_product_id){
                                                        $productid2 = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE supplier_internal_product_id='$supplier_internal_product_id'");
                                                        if (!empty($productid2)){
								$productid_arr_2[] = $productid2;
                                                        }
                                                }

						$productid_str_2 = implode(",", $productid_arr_2);
						unset($productid_arr_2);

						if (!empty($productid_str_2)){
							$product["similar_productids"] = $productid_str_2;
							$product["generate_similar_products"] = "N";
						}
                                        }



                                        if (!empty($not_xcart_products_fields["related_internal_id"]) && is_array($not_xcart_products_fields["related_internal_id"])){
                                                foreach ($not_xcart_products_fields["related_internal_id"] as $supplier_internal_product_id){
                                                        $productid2 = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE supplier_internal_product_id='$supplier_internal_product_id'");
                                                        if (!empty($productid2)){
                                                                $count_productid2 = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[product_links] WHERE productid1='$productid' AND productid2='$productid2'");
                                                                if (empty($count_productid2)){
                                                                        db_query("INSERT INTO $sql_tbl[product_links] (productid1, productid2) VALUES ('$productid', '$productid2')");
                                                                }
                                                        }
						}
					}


					if (!empty($not_xcart_products_fields["related_sku"]) && is_array($not_xcart_products_fields["related_sku"])){
						foreach ($not_xcart_products_fields["related_sku"] as $related_sku){
							$productid2 = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='$related_sku'");
							if (!empty($productid2)){
								$count_productid2 = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[product_links] WHERE productid1='$productid' AND productid2='$productid2'");
								if (empty($count_productid2)){
									db_query("INSERT INTO $sql_tbl[product_links] (productid1, productid2) VALUES ('$productid', '$productid2')");
								}
							}
						}
					}

					if (!empty($not_xcart_products_fields["attributes"]) && is_array($not_xcart_products_fields["attributes"])){

						$attributes_str = "";

						db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid = '$productid'");
						foreach ($not_xcart_products_fields["attributes"] as $f_name => $fv_name_arr){
							if (!empty($fv_name_arr) && is_array($fv_name_arr)){

								if (!empty($attributes_str)){
									$attributes_str .= "<br />";
								}
								
								$f_id = func_query_first_cell("SELECT f_id FROM $sql_tbl[cidev_filters] WHERE f_name='$f_name' AND storefrontid='$v[storefront_id]'");
								if (empty($f_id)){
									db_query("INSERT INTO $sql_tbl[cidev_filters] (f_name, storefrontid) VALUES ('$f_name', '$v[storefront_id]')");
									$f_id = db_insert_id();
								}
								foreach ($fv_name_arr as $fv_name){

                                                                	$fv_id = func_query_first_cell("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id' AND fv_name='$fv_name'");
                                                        	        if (empty($fv_id)){
                                                	                        db_query("INSERT INTO $sql_tbl[cidev_filter_values] (f_id, fv_name) VALUES ('$f_id', '$fv_name')");
                                        	                                $fv_id = db_insert_id();
                                	                                }

                        	                                        $fp_id = func_query_first_cell("SELECT fp_id FROM $sql_tbl[cidev_filter_products] WHERE fv_id='$fv_id' AND productid='$productid'");
                	                                                if (empty($fp_id)){
        	                                                                db_query("INSERT INTO $sql_tbl[cidev_filter_products] (fv_id, productid) VALUES ('$fv_id', '$productid')");
	                                                                }

								}
								$attributes_str .= $f_name .": ".implode(", ", $fv_name_arr);
							}
						}

                                                if (!empty($attributes_str) && !empty($product["fulldescr"])){
	                                                $product["fulldescr"] .= "<br /><br />Specifications:<br /><br />".$attributes_str;
                                                }

//func_print_r($not_xcart_products_fields["attributes"], $productid);
//die();

					}


					if (!empty($not_xcart_products_fields["brand_name"])){

						$BRAND = $not_xcart_products_fields["brand_name"];
        	                                $brandid = func_query_first_cell("SELECT brandid FROM $sql_tbl[brands] WHERE brand='$BRAND'");
	                                        if (empty($brandid)){
                	                                db_query("INSERT INTO $sql_tbl[brands] (brand, orderby) VALUES('$BRAND', '10')");
                        	                        $brandid = db_insert_id();
                                	                db_query("INSERT INTO $sql_tbl[brands_sf] (brandid, sfid) VALUES('$brandid', '$v[storefront_id]')");
                                        	        // Autogenerate clean URL.
                                                	$clean_url = func_clean_url_autogenerate('M', $brandid, array('brand' => $BRAND));
	                                                $clean_url_save_in_history = false;
        	                                        db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid'");
                	                                func_clean_url_add($clean_url, 'M', $brandid);
                        	                }

						$product["brandid"] = $brandid;

//func_print_r($product, $not_xcart_products_fields, $productid);
//die("asd");


					}

					if (isset($not_xcart_products_fields["price"])){
						db_query("UPDATE $sql_tbl[pricing] SET price='".$not_xcart_products_fields["price"]."' WHERE productid='$productid' AND quantity='1'");
						$price_updated = true;
					}

### assign product to category
                                        if (!empty($not_xcart_products_fields["supplier_categories"][0])){

                                                $cats_arr = explode("/", $not_xcart_products_fields["supplier_categories"][0]);

                                                if (!empty($cats_arr) && is_array($cats_arr)){

                                                        $parentid = $v["base_category_id"];

                                                        foreach ($cats_arr as $v_cat){

                                                                $categoryid = func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE parentid='$parentid' AND category='".addslashes($v_cat)."'");

                                                                if (empty($categoryid)){
                                                                        db_query("INSERT INTO $sql_tbl[categories] (parentid, storefrontid, category, is_bold, order_by) VALUES ('$parentid', '$v[storefront_id]', '".addslashes($v_cat)."', 'N', '10')");
                                                                        $categoryid = db_insert_id();
                                                                        $parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$parentid'")."/".$categoryid;
                                                                        func_array2update("categories", array("categoryid_path" => $parent_categoryid_path), "categoryid = '$categoryid'");

		                                                        // Autogenerate clean URL.
                		                                        $clean_url = func_clean_url_autogenerate('C', $categoryid, array('category' => $v_cat));
                                		                        $clean_url_save_in_history = false;
                                                		        db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$categoryid'");
		                                                        func_clean_url_add($clean_url, 'C', $categoryid);
    
                                                                }
//                                                                func_recalc_product_count(func_get_category_parents($category));

                                                                $parentid = $categoryid;
                                                        }

                                                        if (!empty($categoryid)){
#
# https://basecamp.com/2070980/projects/1577907/messages/39298855
#
							    $pc_classify_status = func_query_first_cell("SELECT pc_classify_status FROM $sql_tbl[products] WHERE productid='$productid'");
							    if ($pc_classify_status != "AC" && $pc_classify_status != "ACC" && $pc_classify_status != "MC"){
                                                                db_query("UPDATE $sql_tbl[products_categories] SET categoryid='$categoryid' WHERE productid='$productid' AND main='Y'");
//                                                                func_recalc_product_count(func_get_category_parents($category));
                                                                func_recalc_product_count($categoryid);
							    }
###
                                                        }
                                                }
                                        }
###

                                ################# END: Update NOT xcart_product tables #################
                                }


				if (!empty($product) && is_array($product) && !empty($productid)){
				################# START: xcart_product update #################

					$product_in_DB_info_arr = func_query_first("SELECT forsale, r_avail, eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");

                    $product_dimension = func_query_first("SELECT dim_x, dim_y, dim_z, weight FROM $sql_tbl[products] WHERE productid='$productid'");

                    if ( !isset( $product['dim_x'] ) )
                        $product['dim_x'] = 0;
                    if ( !isset( $product['dim_y'] ) )
                        $product['dim_y'] = 0;
                    if ( !isset( $product['dim_z'] ) )
                        $product['dim_z'] = 0;

                    $dimension1 = array($product['dim_x'],$product['dim_y'],$product['dim_z']);
                    rsort($dimension1);

                    $dimension2 = array($product_dimension['dim_x'],$product_dimension['dim_y'],$product_dimension['dim_z']);
                    rsort($dimension2);

                    $dimension = array();

                    if ( !isset( $product['weight'] ) )
                        $product['weight'] = 0;

                    if ( $product['weight'] == 0 && $product_dimension['weight'] != 0 )
                        $product['weight'] = $product_dimension['weight'];

                    if ( $dimension1[0] != 0 )
                        $dimension[0] = $dimension1[0];
                    else
                        $dimension[0] = $dimension2[0];

                    if ( $dimension1[1] != 0 )
                        $dimension[1] = $dimension1[1];
                    else
                        $dimension[1] = $dimension2[1];

                    if ( $dimension1[2] != 0 )
                        $dimension[2] = $dimension1[2];
                    else
                        $dimension[2] = $dimension2[2];

                    rsort( $dimension );

                    $product['dim_x'] = $dimension[0];
                    $product['dim_y'] = $dimension[1];
                    $product['dim_z'] = $dimension[2];

					$product = func_addslashes($product);

					$discontinued_date_condition_found = false;

					if (!empty($not_xcart_products_fields["discontinued_date"])){
						$discontinued_date_arr = explode("/", $not_xcart_products_fields["discontinued_date"]);
						$discontinued_date_time = mktime(0, 0, 0, $discontinued_date_arr[0], $discontinued_date_arr[1], $discontinued_date_arr[2]);
						$discontinued_date_time_diff = $discontinued_date_time - time();
						if ($discontinued_date_time_diff < (60*60*24*20)){

							if ($product_in_DB_info_arr["forsale"] != "N"){
								$product["forsale"] = "N";
								$product["update_search_index"] = "Y";
								$discontinued_date_condition_found = true;
								$discontinued_products_count++;
							}
						}
					}

					if (!$just_created && !$discontinued_date_condition_found){
						$updated_products_count++;
					}



					func_array2update("products", $product, "productid = '$productid'");


//die("123");

				################# END: xcart_product update #################
				}

                                if ($just_created){
	                                func_build_quick_flags($productid);
					func_generate_discounts(array($productid));
					func_recalc_product_count($v["base_category_id"]);
                                }

                                if ($just_created || $price_updated){
	                                func_build_quick_prices($productid);
				}

                                if ($kp % 10 == 0) {
                                       func_flush(".");
                                       if($kp % 500 == 0) {
                                               func_flush("<br />\n");
                                       }
                                       func_flush();
                                }
			}


			if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes) && $v["disable_search_of_discontinued_items"] != 'Y'){
			
			    $mc = $manufacturerid_info[$v["manufacturerid"]]["code"];
			    $mc2 = substr($mc,0,strpos($mc,'-'));
			    print("Entering disontinue section for ".$mc." or ".$mc2." \r\n");

#
##
###
			    if ($v["multiple_feed_destinations"] == 'Y'){
                                $provider_search_cond1 = $v["feed_file_name"];

                                if ($v["feed_type"] == "I"){
                                        $current_letter_in_replacement = "i";
                                        $set_new_letter_for_replacement = "p";
                                }
                                else {
                                        // $feed_type == "P"
                                        $current_letter_in_replacement = "p";
                                        $set_new_letter_for_replacement = "i";
                                }

                                if (strpos($provider_search_cond1, "-") !== false){
                                        $provider_search_cond2 = str_replace($current_letter_in_replacement."-", $set_new_letter_for_replacement."-", $provider_search_cond1);
                                }
                                else {
                                        $provider_search_cond2 = str_replace($current_letter_in_replacement.".", $set_new_letter_for_replacement.".", $provider_search_cond1);
                                }

//                                $provider_search_cond = " AND (provider='$provider_search_cond1' OR provider='$provider_search_cond2')";
                                $provider_search_cond = " AND (controlled_by_feed='$provider_search_cond1' OR controlled_by_feed='$provider_search_cond2')";
			    }
			    else {
				$provider_search_cond = "";
			    }
###
##
#

			    print("SELECT COUNT(*) FROM $sql_tbl[products] WHERE (productcode LIKE '".$mc."-%' or productcode like '".$mc2."-%') AND forsale='Y' $provider_search_cond");
			    print("\r\n");
               	$count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE (productcode LIKE '".$mc."-%' OR productcode LIKE '".$mc2."-%') AND forsale='Y' $provider_search_cond");

			    print($count_products." for sale = Y\r\n");


                            if ($count_products > 0){

                                $manufacturer_code_products = db_query("SELECT productid, productcode, forsale, update_search_index, provider FROM $sql_tbl[products] WHERE (productcode LIKE '".$mc."-%' OR productcode LIKE '".$mc2."-%') AND forsale='Y' $provider_search_cond");

                                $line_number = 0;
                                print "<br />Second iteration:<br />";
                                while ($prod = db_fetch_array($manufacturer_code_products)) {

                                        $line_number++;
                                        if ($line_number % 100 == 0) {
                                                func_flush(".");
                                                if($line_number % 5000 == 0) {
                                                        func_flush("<br />\n");
                                                }

                                                func_flush();
                                        }


                                        $_productcode = strtoupper(trim($prod["productcode"]));
                                        if (!in_array($_productcode, $all_feed_productcodes) && $prod["forsale"] != "N") {

												$discontinued_products_count++;

                                                $update_search_index = $prod["update_search_index"];
                                                if ($update_search_index == "N"){
                                                        $update_search_index = "D";
                                                }

                                                db_query("UPDATE $sql_tbl[products] SET r_avail='0', forsale='N'  WHERE productid='".$prod["productid"]."'");
                                        }
                                }
	                    }
			}



/* --------------------------------------------------------------------------------------------------- */
		} else {
	                $log_text = "manufacturerid: ".$v["manufacturerid"].". File is not found. Skipped.";
        	        func_backprocess_log("supplier_feeds_v_2", $log_text);
        	        func_backprocess_log("supplier feeds errors", $log_text);
			continue;
		}
	} else {
		$log_text = "manufacturerid: ".$v["manufacturerid"].". Could not open host. Script stopped.";
		func_backprocess_log("supplier_feeds_v_2", $log_text);
		func_backprocess_log("supplier feeds errors", $log_text);
		db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");
		die($log_text);
	}


	$last_update_time = $v["last_update_time"]; // $v[] it is current in DB
	$last_update_period = time() - $last_update_time;
	$average_update_period  = round(($v["average_update_period"] + $last_update_period) / 2, 0);
	$new_products_count = $products["products_in_feed"] - $updated_products_count - $discontinued_products_count;

	$supplier_feed = array(
		"last_md5" => $md5,
		"last_update_time" => time(),
		"average_update_period" => $average_update_period,
		"last_update_period" => $last_update_period,
		"last_update_items_count" => $products["products_in_feed"]
	); 
	func_array2update("supplier_feeds", $supplier_feed, "feed_id = '$v[feed_id]'");


func_print_r($supplier_feed);

	###
	$date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $start_supplier_time));
	$date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', time()));
	$interval = $date1->diff($date2);
        $years = $interval->format("%y");
        $months = $interval->format("%m");
        $days = $interval->format("%d");
        $hours = $interval->format("%h");
        $mins = $interval->format("%i");
	$age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
	###

        $log_text = "manufacturerid: ".$v["manufacturerid"].":".$manufacturerid_info[$v["manufacturerid"]]["manufacturer"]." - completed. \n";
	$log_text .= "processed $products[products_in_feed] items.\n";
	$log_text .= "found new $new_products_count items.\n";
	$log_text .= "updated $updated_products_count items.\n";
	if ($v["feed_type"] == "P"){ // product
		$log_text .= "inserted $inserted_products_count items.\n";
	}
	$log_text .= "discontinued: ".$discontinued_products_count."\n";
	$log_text .= "Duration: ".$age_str."\n";
        func_backprocess_log("supplier_feeds_v_2", $log_text);
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

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='supplier_feeds_v_2'");
$log_text = "Cron completed. Duration: ".$age_str;
func_backprocess_log("supplier_feeds_v_2", $log_text);

die("DONE!");
?>
