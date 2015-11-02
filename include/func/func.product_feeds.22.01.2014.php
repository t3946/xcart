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
# $Id: func.product_feeds.php, v 1.0.0 2014/01/10 13:21:12 xcartmaster@gmail.com Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

x_load('cart','mail','order','product','taxes', 'files', 'backoffice');

function func_GENERAL_ALV_FEED($manufacturerid){
	global $sql_tbl, $xcart_dir, $launch_time;

	$file_is_found_and_uploaded = false;
	$file_is_found = false;
	$count_updated_products = 0;
	$count_marked_as_out_of_stock_products = 0;
	$count_marked_as_in_stock_products = 0;

	if (function_exists("ftp_connect")) {

		$general_info = func_query_first("SELECT manufacturer, d_ftp_host, d_ftp_login, d_ftp_password, d_ftp_folder, d_product_management_team_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

		$ftp = ftp_connect($general_info["d_ftp_host"]);
		if ($ftp && @ftp_login($ftp, $general_info["d_ftp_login"], $general_info["d_ftp_password"])) {

			ftp_pasv($ftp, true);

			$local_file = $xcart_dir . "/files/product_feeds/" .$manufacturerid."_AlvinInventoryFeed.txt";
			$server_file = $general_info["d_ftp_folder"]."AlvinInventoryFeed.txt";

			if (ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
				$file_is_found = true;
			}

			ftp_quit($ftp);

		} else {
			print("Could not open host. (Distributor: ".$general_info["manufacturer"] .")<br />");
		}
	}

	if ($file_is_found){

		$handle = @fopen($local_file, "r");
		if ($handle) {
		    $line_number = 0;
		    $NEW_PRODUCTS = array();
		    $discontinued_products = array();
		    $all_feed_productcodes = array();

		    print "<br />".$general_info["manufacturer"]."<br />";
		    print "First iteration:<br />";

		    while (($buffer = fgets($handle, 4096)) !== false) {
			$line_number++;

			if ($line_number % 100 == 0) {
				func_flush(".");
				if($line_number % 5000 == 0) {
					func_flush("<br />\n");
				}

				func_flush();
			}

/*
			if ($line_number < 10){
			        echo $line_number.": ".$buffer."<br />";
			}
*/
			
			if ($line_number > 1){

				$buffer_arr = explode("|", $buffer);

				$ITEM = trim(substr(trim($buffer_arr[0]), 1, -1));
				$ITEM = strtoupper($ITEM);
				$UPCEAN = trim(substr(trim($buffer_arr[1]), 1, -1));
				$Stock = trim(substr(trim($buffer_arr[2]), 1, -1));
				$ExpectedDate = trim(substr(trim($buffer_arr[3]), 1, -1));
				$DropShip  = trim(substr(trim($buffer_arr[4]), 1, -1));

				if ($Stock == ""){
					$Stock = 0;
				}

				$ExpectedDate_time = 0;
				if (!empty($ExpectedDate)){
					$ExpectedDate_arr = explode("/", $ExpectedDate);
					$day = $ExpectedDate_arr[1];
					$month = $ExpectedDate_arr[0];
					$year = "20".$ExpectedDate_arr[2];
					$ExpectedDate_time = mktime(0, 0, 0, $month, $day, $year);
				}

				$feed_productcode = "ALV-".$ITEM;
				$all_feed_productcodes[] = $feed_productcode;

				$product_info_arr = func_query_first("SELECT productid, productcode, forsale, avail, eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productcode='".addslashes($feed_productcode)."'");

				if (!empty($product_info_arr)){

					$productid = $product_info_arr["productid"];
					$productcode = $product_info_arr["productcode"];
					$current_forsale = $product_info_arr["forsale"];
					$current_avail = $product_info_arr["avail"];
					$current_eta_date_mm_dd_yyyy = $product_info_arr["eta_date_mm_dd_yyyy"];

					$product_is_updated = false;
					$marked_as_out_of_stock_products = false;
					$marked_as_in_stock_products = false;

					if ($current_forsale != "Y"){
						db_query("UPDATE $sql_tbl[products] SET forsale='Y' WHERE productid='$productid'");
						$product_is_updated = true;
					}

					$new_eta_date_mm_dd_yyyy_time = 0;
					$update_product = false;

	                                if ($DropShip == "N"){
        	                                if ($Stock > 0){
							$update_product = true;
							$new_avail = $Stock;

                	                        } elseif ($Stock == 0){
							$update_product = true;	
							$new_avail = $Stock;

        	                                        if ($ExpectedDate_time == 0){
        	                                                $new_eta_date_mm_dd_yyyy_time = time() + 60*60*24*20;
        	                                        } else {
        	                                                $new_eta_date_mm_dd_yyyy_time = $ExpectedDate_time;
	                                                }
        	                                }
                	                } elseif ($DropShip == "Y") {
	
        	                                if ($Stock > 0){
							$update_product = true;
							$new_avail = $Stock;
		
        	                                } elseif ($Stock == 0){
	
							$update_product = true;

        	                                        if ($ExpectedDate_time == 0){
								$new_avail = 1000000;
        	                                        } else {
								$new_avail = 0;
        	                                                $new_eta_date_mm_dd_yyyy_time = $ExpectedDate_time;
                                                	}
	                                        }
        	                        }

					if ($update_product){

						if ($new_eta_date_mm_dd_yyyy_time == 0){
							$new_eta_date_mm_dd_yyyy = "";
						} else {
							$new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy_time);
						}

                                                if ($new_avail == "0"){
	                                                if ($current_avail > 0){
        	                                                $marked_as_out_of_stock_products = true;
                                                        }
                                                } else {
                	                                if ($current_avail == 0){
                        	                                $marked_as_in_stock_products = true;
                                                        }
                                                }

						db_query("UPDATE $sql_tbl[products] SET avail='$new_avail', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy' WHERE productid='$productid'");

						if ($new_eta_date_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy || $current_avail != $new_avail){
							$product_is_updated = true;
						}
					}

/*
					if ($DropShip == "N"){

						if ($Stock != $current_avail){
							db_query("UPDATE $sql_tbl[products] SET avail='$Stock' WHERE productcode='".addslashes($productcode)."'");
							$product_is_updated = true;

							if ($Stock == "0"){
								if ($current_avail > 0){
									$marked_as_out_of_stock_products = true;
								}
							} else {
								if ($current_avail == 0){
									$marked_as_in_stock_products = true;
								}
							}
						}

						if ($CorrectED_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy){
							db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$CorrectED_mm_dd_yyyy' WHERE productcode='".addslashes($productcode)."'");
							$product_is_updated = true;
						}

					} elseif ($DropShip == "Y" && $Stock > 0){

						if ($Stock != $current_avail){
							db_query("UPDATE $sql_tbl[products] SET avail='$Stock' WHERE productcode='".addslashes($productcode)."'");
							$product_is_updated = true;

							if ($current_avail == 0){
								$marked_as_in_stock_products = true;
							}
						}

						if ($CorrectED_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy){
							db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$CorrectED_mm_dd_yyyy' WHERE productcode='".addslashes($productcode)."'");
							$product_is_updated = true;
						}

					} elseif ($DropShip == "Y" && $Stock == 0){

						if (!empty($CorrectED_mm_dd_yyyy)){

							if ($Stock != $current_avail){
								db_query("UPDATE $sql_tbl[products] SET avail='$Stock' WHERE productcode='".addslashes($productcode)."'");
								$product_is_updated = true;

								if ($current_avail > 0){
									$marked_as_out_of_stock_products = true;
								}
							}

							if ($CorrectED_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy){
								db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$CorrectED_mm_dd_yyyy' WHERE productcode='".addslashes($productcode)."'");
								$product_is_updated = true;
							}

						} else {

							if ($current_avail != "1000000"){
								db_query("UPDATE $sql_tbl[products] SET avail='1000000' WHERE productcode='".addslashes($productcode)."'");
	                                                        $product_is_updated = true;

								if ($current_avail == 0){
	        	                                                $marked_as_in_stock_products = true;
								}
							}

							if ($current_eta_date_mm_dd_yyyy != ""){
								db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='' WHERE productcode='".addslashes($productcode)."'");
								$product_is_updated = true;
							}
						}
					}

*/

					if ($product_is_updated){
						$file_is_found_and_uploaded = true;
						$count_updated_products++;
					}

					if ($marked_as_out_of_stock_products){
						$count_marked_as_out_of_stock_products++;
					}

					if ($marked_as_in_stock_products){
						$count_marked_as_in_stock_products++;
					}
				} else {
					$NEW_PRODUCTS[] = $buffer;
				}
			}

		    }
		    if (!feof($handle)) {
		        echo "Error: unexpected fgets() fail\n";
		    }
		    fclose($handle);


		    if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes)){

			$count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode LIKE 'ALV-%' AND forsale='Y'");

                        if ($count_products > 0){

				$ALV_products = db_query("SELECT productid, productcode, forsale FROM $sql_tbl[products] WHERE productcode LIKE 'ALV-%' AND forsale='Y'");

				$line_number = 0;
				print "<br />Second iteration:<br />";
				while ($product = db_fetch_array($ALV_products)) {

		                        $line_number++;
        		                if ($line_number % 100 == 0) {
                		                func_flush(".");
                        		        if($line_number % 5000 == 0) {
                                		        func_flush("<br />\n");
	                                	}

	        	                        func_flush();
        	        	        }

                                        $productcode = strtoupper(trim($product["productcode"]));

                                        if (!in_array($productcode, $all_feed_productcodes) && $product["forsale"] != "N") {
						$file_is_found_and_uploaded = true;
						$discontinued_products[] = $product;
						db_query("UPDATE $sql_tbl[products] SET avail='0', forsale='N' WHERE productid='".$product["productid"]."'");
					}
				}
			}
		    }
		}
	}

//	if ($file_is_found_and_uploaded){
		db_query("UPDATE $sql_tbl[manufacturers] SET d_most_recent_feed_updation_date='".$launch_time."' WHERE manufacturerid='$manufacturerid'");
//	}


	$count_discontinued_products = count($discontinued_products);
        if (!empty($discontinued_products) && is_array($discontinued_products)){

		$subj = "ALVIN FEED UPDATE - discontinued products";
		$body = "Discontinued products count: ".$count_discontinued_products."\n\n";

		$body .= "<table border='1'>\n<tr><td>ProductCode</td><td>Link to SF backend</td></tr>\n";
		foreach ($discontinued_products as $k => $v){

			$store_url = func_query_first_cell("SELECT $sql_tbl[storefronts].domain FROM $sql_tbl[storefronts] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid WHERE $sql_tbl[products_sf].productid='".$v["productid"]."'");
			if (empty($store_url)){
				$store_url = "www.artistsupplysource.com";
			}

			$body .= "<tr><td> ".$v["productcode"]." </td><td> <a href='http://".$store_url."/product.php?productid=".$v["productid"]."'>http://".$store_url."/product.php?productid=".$v["productid"]."</a> </td></tr>\n";
		}
		$body .= "</table>";

		func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//                print"<br />For test purpose: discontinued_products:";
//                func_print_r($discontinued_products);
        }

	$count_NEW_PRODUCTS = count($NEW_PRODUCTS);
	if (!empty($NEW_PRODUCTS) && is_array($NEW_PRODUCTS)){
		$subj = "ALVIN FEED UPDATE - new products";
		$body = "Expected new products count: ".$count_NEW_PRODUCTS."\n\n";

		$body .= "ITEM|UPCEAN|Stock|ExpectedDate|DropShip\n";
		foreach ($NEW_PRODUCTS as $k => $v){
			$body .= $v."\n";
		}

		func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//		print"<br />For test purpose: NEW_PRODUCTS:";
//		func_print_r($NEW_PRODUCTS);
	}

	$count_all_feed_productcodes = count($all_feed_productcodes);
	$sum_updated_products = $count_marked_as_out_of_stock_products + $count_marked_as_in_stock_products + $count_discontinued_products;

	$subj = "ALVIN FEED UPDATE - summary";
	$body = "products in feed: ".$count_all_feed_productcodes."\n";
	$body .= "updated products: ".$sum_updated_products."\n";
	$body .= "marked as `out of stock` products: ".$count_marked_as_out_of_stock_products."\n";
//	$body .= "marked as `out of stock` products (in second iteration): ".$count_discontinued_products."\n";
	$body .= "marked as `in stock` products: ".$count_marked_as_in_stock_products."\n";
	$body .= "new products: ".$count_NEW_PRODUCTS."\n";
	$body .= "discontinued products: ".$count_discontinued_products."\n";

	func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");
}


function func_GENERAL_EDR_FEED($manufacturerid){
        global $sql_tbl, $xcart_dir, $launch_time;

        $file_is_found_and_uploaded = false;
        $file_is_found = false;
        $count_updated_products = 0;
        $count_marked_as_out_of_stock_products = 0;
        $count_marked_as_in_stock_products = 0;
	$current_time = time();

        if (function_exists("ftp_connect")) {

                $general_info = func_query_first("SELECT manufacturer, d_ftp_host, d_ftp_login, d_ftp_password, d_ftp_folder, d_product_management_team_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

                $ftp = ftp_connect($general_info["d_ftp_host"]);
                if ($ftp && @ftp_login($ftp, $general_info["d_ftp_login"], $general_info["d_ftp_password"])) {

                        ftp_pasv($ftp, true);

                        $local_file = $xcart_dir . "/files/product_feeds/" .$manufacturerid."_ERQTY.csv";
                        $server_file = $general_info["d_ftp_folder"]."ERQTY.csv";

                        if (ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
                                $file_is_found = true;
                        }

                        ftp_quit($ftp);

                } else {
                        print("Could not open host. (Distributor: ".$general_info["manufacturer"] .")<br />");
                }
        }

        if ($file_is_found){

                $handle = @fopen($local_file, "r");
                if ($handle) {
                    $line_number = 0;
                    $NEW_PRODUCTS = array();
                    $discontinued_products = array();
                    $all_feed_productcodes = array();

                    print "<br />".$general_info["manufacturer"]."<br />";
                    print "First iteration:<br />";

		    while (($buffer = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $line_number++;

                        if ($line_number % 100 == 0) {
                                func_flush(".");
                                if($line_number % 5000 == 0) {
                                        func_flush("<br />\n");
                                }

                                func_flush();
                        }

                        if ($line_number > 1){

				$Part_Id = trim($buffer[0]);
				$Part_Id = strtoupper($Part_Id);
				$Description = trim($buffer[1]);
				$Available = trim($buffer[2]);
				$Base_Um = trim($buffer[3]);
				$Base_Price = trim($buffer[4]);
				$Base_Price = price_format($Base_Price);
				$On_Order_Qt = trim($buffer[5]);

                                if ($Available == ""){
                                        $Available = 0;
                                }

                                $feed_productcode = "EDR-".$Part_Id;
                                $all_feed_productcodes[] = $feed_productcode;

                                $product_info_arr = func_query_first("SELECT productcode, forsale, avail, eta_date_mm_dd_yyyy, list_price FROM $sql_tbl[products] WHERE productcode='".addslashes($feed_productcode)."'");

                                if (!empty($product_info_arr)){

                                        $productcode = $product_info_arr["productcode"];
                                        $current_forsale = $product_info_arr["forsale"];
                                        $current_avail = $product_info_arr["avail"];
                                        $current_list_price = $product_info_arr["list_price"];
                                        $current_eta_date_mm_dd_yyyy = $product_info_arr["eta_date_mm_dd_yyyy"];

                                        $product_is_updated = false;
                                        $marked_as_out_of_stock_products = false;
                                        $marked_as_in_stock_products = false;

//                                        $current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productcode='".addslashes($productcode)."'");

                                        if ($current_forsale != "Y"){
                                                db_query("UPDATE $sql_tbl[products] SET forsale='Y' WHERE productcode='".addslashes($productcode)."'");
                                                $product_is_updated = true;
                                        }

//					$current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productcode='".addslashes($feed_productcode)."'");
					if (!empty($current_eta_date_mm_dd_yyyy)){
						$current_eta_date_mm_dd_yyyy_arr = explode("/", $current_eta_date_mm_dd_yyyy);
						$current_eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $current_eta_date_mm_dd_yyyy_arr[0], $current_eta_date_mm_dd_yyyy_arr[1], $current_eta_date_mm_dd_yyyy_arr[2]);
					}
					else {
						$current_eta_date_mm_dd_yyyy_time = 0;
					}

                                        if ($Available > 0){

						$new_eta_date_mm_dd_yyyy = $current_time - 60*60*24*1;
						$new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy);

						if ($current_list_price != $Base_Price || $current_avail != $Available || $current_eta_date_mm_dd_yyyy != $new_eta_date_mm_dd_yyyy){
	                                                db_query("UPDATE $sql_tbl[products] SET list_price='$Base_Price', avail='$Available', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy' WHERE productcode='".addslashes($productcode)."'");
                                                	$product_is_updated = true;
		
							if ($current_avail == 0){
	                	                                $marked_as_in_stock_products = true;
							}
						}
                                        } elseif ($Available == 0 && $current_eta_date_mm_dd_yyyy_time < $current_time){

                                                $new_eta_date_mm_dd_yyyy = $current_time + 60*60*24*35;
                                                $new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy);

						if ($current_list_price != $Base_Price || $current_avail != $Available || $current_eta_date_mm_dd_yyyy != $new_eta_date_mm_dd_yyyy){
							db_query("UPDATE $sql_tbl[products] SET list_price='$Base_Price', avail='$Available', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy' WHERE productcode='".addslashes($productcode)."'");

        	                                        $product_is_updated = true;

							if ($current_avail > 0){
	                	                                $marked_as_out_of_stock_products = true;
							}
						}
                                        }

                                        if ($product_is_updated){
                                                $file_is_found_and_uploaded = true;
                                                $count_updated_products++;
                                        }

                                        if ($marked_as_out_of_stock_products){
                                                $count_marked_as_out_of_stock_products++;
                                        }

                                        if ($marked_as_in_stock_products){
                                                $count_marked_as_in_stock_products++;
                                        }

                                } else {
                                        $NEW_PRODUCTS[] = $buffer;
                                }
                        }

                    }
                    fclose($handle);


                    if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes)){

			$count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode LIKE 'EDR-%' AND forsale='Y'");

			if ($count_products > 0){
	                        $EDR_products = db_query("SELECT productid, productcode, forsale FROM $sql_tbl[products] WHERE productcode LIKE 'EDR-%' AND forsale='Y'");

        	                $line_number = 0;
                	        print "<br />Second iteration:<br />";
                        	while ($product = db_fetch_array($EDR_products)) {
	
        	                        $line_number++;
                	                if ($line_number % 100 == 0) {
                        	                func_flush(".");
                                	        if($line_number % 5000 == 0) {
                                        	        func_flush("<br />\n");
	                                        }

        	                                func_flush();
                	                }

                                        $productcode = strtoupper(trim($product["productcode"]));

                                        if (!in_array($productcode, $all_feed_productcodes) && $product["forsale"] != "N") {
                                	        $file_is_found_and_uploaded = true;
                                        	$discontinued_products[] = $product;
	                                        db_query("UPDATE $sql_tbl[products] SET avail='0', forsale='N' WHERE productid='".$product["productid"]."'");
        	                        }
                	        }
			}
                    }
                }
        }

//        if ($file_is_found_and_uploaded){
                db_query("UPDATE $sql_tbl[manufacturers] SET d_most_recent_feed_updation_date='".$launch_time."' WHERE manufacturerid='$manufacturerid'");
//        }


        $count_discontinued_products = count($discontinued_products);
        if (!empty($discontinued_products) && is_array($discontinued_products)){

                $subj = "EDR FEED UPDATE - discontinued products";
                $body = "Discontinued products count: ".$count_discontinued_products."\n\n";

                $body .= "<table border='1'>\n<tr><td>ProductCode</td><td>Link to SF backend</td></tr>\n";
                foreach ($discontinued_products as $k => $v){

                        $store_url = func_query_first_cell("SELECT $sql_tbl[storefronts].domain FROM $sql_tbl[storefronts] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid WHERE $sql_tbl[products_sf].productid='".$v["productid"]."'");
                        if (empty($store_url)){
                                $store_url = "www.artistsupplysource.com";
                        }

                        $body .= "<tr><td> ".$v["productcode"]." </td><td> <a href='http://".$store_url."/product.php?productid=".$v["productid"]."'>http://".$store_url."/product.php?productid=".$v["productid"]."</a> </td></tr>\n";
                }
                $body .= "</table>";

                func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//                print"<br />For test purpose: discontinued_products:";
//                func_print_r($discontinued_products);
        }

        $count_NEW_PRODUCTS = count($NEW_PRODUCTS);
        if (!empty($NEW_PRODUCTS) && is_array($NEW_PRODUCTS)){
                $subj = "EDR FEED UPDATE - new products";
                $body = "Expected new products count: ".$count_NEW_PRODUCTS."\n\n";

		$body .= "Part Id,Description,Available,Base Um,Base Price,On Order Qty\n";
                foreach ($NEW_PRODUCTS as $k => $v){
                        $body .= implode(",", $v)."\n";
                }

                func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//              print"<br />For test purpose: NEW_PRODUCTS:";
//              func_print_r($NEW_PRODUCTS);
        }

	$sum_updated_products = $count_marked_as_out_of_stock_products + $count_marked_as_in_stock_products + $count_discontinued_products;

        $subj = "EDR FEED UPDATE - summary";
        $body = "products in feed: ".count($all_feed_productcodes)."\n";
        $body .= "updated products: ".$sum_updated_products."\n";
        $body .= "marked as `out of stock` products: ".$count_marked_as_out_of_stock_products."\n";
//        $body .= "marked as `out of stock` products (in second iteration): ".$count_discontinued_products."\n";
        $body .= "marked as `in stock` products: ".$count_marked_as_in_stock_products."\n";
        $body .= "new products: ".$count_NEW_PRODUCTS."\n";
        $body .= "discontinued products: ".$count_discontinued_products."\n";

        func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");
}


function func_GENERAL_MOT_FEED($manufacturerid){
        global $sql_tbl, $xcart_dir, $launch_time;

        $file_is_found_and_uploaded = false;
        $file_is_found = false;
        $count_updated_products = 0;
        $count_marked_as_out_of_stock_products = 0;
        $count_marked_as_in_stock_products = 0;
        $current_time = time();

        if (function_exists("ftp_connect")) {

                $general_info = func_query_first("SELECT manufacturer, d_ftp_host, d_ftp_login, d_ftp_password, d_ftp_folder, d_product_management_team_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

                $ftp = ftp_connect($general_info["d_ftp_host"]);
                if ($ftp && @ftp_login($ftp, $general_info["d_ftp_login"], $general_info["d_ftp_password"])) {

                        ftp_pasv($ftp, true);

                        $local_file = $xcart_dir . "/files/product_feeds/" .$manufacturerid."_InvStatFile.txt";
                        $server_file = $general_info["d_ftp_folder"]."InvStatFile.txt";

                        if (ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
                                $file_is_found = true;
                        }

/* -------------------- */
                        $local_file2 = $xcart_dir . "/files/product_feeds/" .$manufacturerid."PricingFile.txt";
                        $server_file2 = $general_info["d_ftp_folder"]."PricingFile.txt";

			$file_is_found2 = false;
                        if (ftp_get($ftp, $local_file2, $server_file2, FTP_BINARY)) {
                                $file_is_found2 = true;
                        }
/* -------------------- */

                        ftp_quit($ftp);

                } else {
                        print("Could not open host. (Distributor: ".$general_info["manufacturer"] .")<br />");
                }
        }

/* -------------------- */
        if ($file_is_found2){

                $handle = @fopen($local_file2, "r");
                if ($handle) {
                    $line_number = 0;

                    print "<br />".$general_info["manufacturer"]."<br />";
                    print "Updating price...:<br />";

                    while (($buffer = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                        $line_number++;

                        if ($line_number % 100 == 0) {
                                func_flush(".");
                                if($line_number % 5000 == 0) {
                                        func_flush("<br />\n");
                                }

                                func_flush();
                        }

			if ($line_number > 0){
                                $SKU = trim($buffer[0]);
                                $SKU = strtoupper($SKU);
                                $cost_to_us = trim($buffer[1]);
				$cost_to_us = price_format($cost_to_us);

                                $feed_productcode = "MOT-".$SKU;

				db_query("UPDATE $sql_tbl[products] SET cost_to_us='$cost_to_us' WHERE productcode='".addslashes($feed_productcode)."'");
			}
                    }
                    fclose($handle);
                }
        }
/* -------------------- */


        if ($file_is_found){

                $handle = @fopen($local_file, "r");
                if ($handle) {
                    $line_number = 0;
                    $NEW_PRODUCTS = array();
                    $discontinued_products = array();
                    $all_feed_productcodes = array();

                    print "<br />".$general_info["manufacturer"]."<br />";
                    print "First iteration:<br />";

                    while (($buffer = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                        $line_number++;

                        if ($line_number % 100 == 0) {
                                func_flush(".");
                                if($line_number % 5000 == 0) {
                                        func_flush("<br />\n");
                                }

                                func_flush();
                        }

                        if ($line_number > 0){  //<-------------------
                                $SKU = trim($buffer[0]);
                                $SKU = strtoupper($SKU);
                                $AVAIL = trim($buffer[1]); //FORSALE
                                $ONORDER = trim($buffer[2]);
                                $ETA_DAYS = trim($buffer[3]);
                                $QUALIFIER = trim($buffer[4]);

                                if ($AVAIL == ""){
                                        $AVAIL = 0;
                                }

                                $feed_productcode = "MOT-".$SKU;
                                $all_feed_productcodes[] = $feed_productcode;

                                $product_info_arr = func_query_first("SELECT productid, productcode, forsale, avail, eta_date_mm_dd_yyyy, list_price FROM $sql_tbl[products] WHERE productcode='".addslashes($feed_productcode)."'");

                                if (!empty($product_info_arr)){

                                        $productcode = $product_info_arr["productcode"];
                                        $productid = $product_info_arr["productid"];
                                        $current_forsale = $product_info_arr["forsale"];
                                        $current_avail = $product_info_arr["avail"];
                                        $current_list_price = $product_info_arr["list_price"];
                                        $current_eta_date_mm_dd_yyyy = $product_info_arr["eta_date_mm_dd_yyyy"];

                                        $product_is_updated = false;
                                        $marked_as_out_of_stock_products = false;
                                        $marked_as_in_stock_products = false;

/*
                                        if ($current_forsale != "Y"){
                                                db_query("UPDATE $sql_tbl[products] SET forsale='Y' WHERE productcode='".addslashes($productcode)."'");
                                                $product_is_updated = true;
                                        }
*/

                                        if (!empty($current_eta_date_mm_dd_yyyy)){
                                                $current_eta_date_mm_dd_yyyy_arr = explode("/", $current_eta_date_mm_dd_yyyy);
                                                $current_eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $current_eta_date_mm_dd_yyyy_arr[0], $current_eta_date_mm_dd_yyyy_arr[1], $current_eta_date_mm_dd_yyyy_arr[2]);
                                        }
                                        else {
                                                $current_eta_date_mm_dd_yyyy_time = 0;
                                        }


					if ($QUALIFIER == "D"){

                        	                if ($current_forsale != "N"){
	                                                $count_discontinued_products = count($discontinued_products);
        	                                        $discontinued_products[$count_discontinued_products]["productid"] = $productid;
                	                                $discontinued_products[$count_discontinued_products]["First_iteration"] = "Y";
						}

						$new_forsale = "N";
						$new_avail = 0;

               	                                db_query("UPDATE $sql_tbl[products] SET avail='$new_avail', forsale='$new_forsale' WHERE productid='$productid'");

					} elseif ($QUALIFIER == "O" || $QUALIFIER == "P"){

						$new_forsale = "Y";

						if ($current_forsale != $new_forsale){
							$product_is_updated = true;
						}

						$new_avail = 0;

						if ($current_avail > 0){
							$marked_as_out_of_stock_products = true;
							$product_is_updated = true;
						}

						db_query("UPDATE $sql_tbl[products] SET avail='$new_avail', forsale='$new_forsale' WHERE productid='$productid'");

					} elseif ($QUALIFIER == "S"){

                                                $new_forsale = "Y";

                                                if ($current_forsale != $new_forsale){
                                                        $product_is_updated = true;
                                                }

                                                $new_avail = $AVAIL;

                                                if ($current_avail > 0 && $AVAIL == 0){
                                                        $marked_as_out_of_stock_products = true;
                                                        $product_is_updated = true;
                                                }

						if ($current_avail == 0 && $AVAIL > 0) {
							$product_is_updated = true;
							$marked_as_in_stock_products = true;
						}

						if ($ETA_DAYS > 0){
							$new_eta_date_mm_dd_yyyy = $current_time + 60*60*24*$ETA_DAYS;
							$new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy);
						} else {
							$new_eta_date_mm_dd_yyyy = "";
						}

						if ($current_eta_date_mm_dd_yyyy != $new_eta_date_mm_dd_yyyy){
							$product_is_updated = true;
						}

                                                db_query("UPDATE $sql_tbl[products] SET avail='$new_avail', forsale='$new_forsale', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy' WHERE productid='$productid'");
					}

                                        if ($product_is_updated){
                                                $file_is_found_and_uploaded = true;
                                                $count_updated_products++;
                                        }

                                        if ($marked_as_out_of_stock_products){
                                                $count_marked_as_out_of_stock_products++;
                                        }

                                        if ($marked_as_in_stock_products){
                                                $count_marked_as_in_stock_products++;
                                        }

                                } else {
                                        $NEW_PRODUCTS[] = $buffer;
                                }
                        }

                    }
                    fclose($handle);


                    if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes)){

                        $count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode LIKE 'MOT-%' AND forsale='Y'");

                        if ($count_products > 0){
                                $MOT_products = db_query("SELECT productid, productcode, forsale FROM $sql_tbl[products] WHERE productcode LIKE 'MOT-%' AND forsale='Y'");

                                $line_number = 0;
                                print "<br />Second iteration:<br />";
                                while ($product = db_fetch_array($MOT_products)) {

                                        $line_number++;
                                        if ($line_number % 100 == 0) {
                                                func_flush(".");
                                                if($line_number % 5000 == 0) {
                                                        func_flush("<br />\n");
                                                }

                                                func_flush();
                                        }

					$productcode = strtoupper(trim($product["productcode"]));

                                        if (!in_array($productcode, $all_feed_productcodes) && $product["forsale"] != "N") {
                                                $file_is_found_and_uploaded = true;
                                                $discontinued_products[] = $product;
                                                db_query("UPDATE $sql_tbl[products] SET avail='0', forsale='N' WHERE productid='".$product["productid"]."'");
                                        }
                                }
                        }
                    }
                }
        }

//        if ($file_is_found_and_uploaded){
                db_query("UPDATE $sql_tbl[manufacturers] SET d_most_recent_feed_updation_date='".$launch_time."' WHERE manufacturerid='$manufacturerid'");
//        }

        $count_discontinued_products = count($discontinued_products);
        if (!empty($discontinued_products) && is_array($discontinued_products)){

                $subj = "MOT FEED UPDATE - discontinued products";
                $body = "Discontinued products count: ".$count_discontinued_products."\n\n";

                $body .= "<table border='1'>\n<tr><td>ProductCode</td><td>Link to SF backend</td></tr>\n";
                foreach ($discontinued_products as $k => $v){

                        $store_url = func_query_first_cell("SELECT $sql_tbl[storefronts].domain FROM $sql_tbl[storefronts] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid WHERE $sql_tbl[products_sf].productid='".$v["productid"]."'");
                        if (empty($store_url)){
                                $store_url = "www.artistsupplysource.com";
                        }

                        $body .= "<tr><td> ".$v["productcode"]." </td><td> <a href='http://".$store_url."/product.php?productid=".$v["productid"]."'>http://".$store_url."/product.php?productid=".$v["productid"]."</a> </td></tr>\n";
                }
                $body .= "</table>";

                func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//                print"<br />For test purpose: discontinued_products:";
//                func_print_r($discontinued_products);
        }

        $count_NEW_PRODUCTS = count($NEW_PRODUCTS);
        if (!empty($NEW_PRODUCTS) && is_array($NEW_PRODUCTS)){
                $subj = "MOT FEED UPDATE - new products";
                $body = "Expected new products count: ".$count_NEW_PRODUCTS."\n\n";

                $body .= "SKU\tFORSALE\tONORDER\tETA_DAYS\tQUALIFIER\n";
                foreach ($NEW_PRODUCTS as $k => $v){
                        $body .= implode("\t", $v)."\n";
                }

                func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//                print"<br />For test purpose: NEW_PRODUCTS:";
//                func_print_r($NEW_PRODUCTS);
        }

        $sum_updated_products = $count_marked_as_out_of_stock_products + $count_marked_as_in_stock_products + $count_discontinued_products;

        $subj = "MOT FEED UPDATE - summary";
        $body = "products in feed: ".count($all_feed_productcodes)."\n";
        $body .= "updated products: ".$sum_updated_products."\n";
        $body .= "marked as `out of stock` products: ".$count_marked_as_out_of_stock_products."\n";
//        $body .= "marked as `out of stock` products (in second iteration): ".$count_discontinued_products."\n";
        $body .= "marked as `in stock` products: ".$count_marked_as_in_stock_products."\n";
        $body .= "new products: ".$count_NEW_PRODUCTS."\n";
        $body .= "discontinued products: ".$count_discontinued_products."\n";

        func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");
}

function func_GENERAL_HTTPS_LWF_FEED($manufacturerid){
        global $sql_tbl, $xcart_dir, $launch_time;

	$general_info = func_query_first("SELECT manufacturer, d_ftp_host, d_ftp_login, d_ftp_password, d_ftp_folder, d_product_management_team_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");


	$lines = file($general_info["d_ftp_host"]);

	$needed_lines = array();
	$needed_first_line_is_found = false;

	if (!empty($lines) && is_array($lines)){
		foreach ($lines as $line_num => $line) {

			if (strpos($line, 'id="rgReport_ctl00__0"') !== false){
				$needed_first_line_is_found = true;
			}

	                if (strpos($line, '</tbody>') !== false && $needed_first_line_is_found){
					break;
                	}

			if ($needed_first_line_is_found){
				$needed_lines[] = $line;
			}
		}
	}

	if (!empty($needed_lines)){

                print "<br />".$general_info["manufacturer"]."<br />";
                print "First iteration:<br />";
		$count_marked_as_in_stock_products = 0;
		$count_marked_as_out_of_stock_products = 0;
		$count_updated_products = 0;
		$line_number = 0;

		foreach ($needed_lines as $k => $v){
			if ($k % 2 != 0){

        	                $line_number++;

	                        if ($line_number % 100 == 0) {
	                                func_flush(".");
                                	if($line_number % 5000 == 0) {
                        	                func_flush("<br />\n");
                	                }

        	                        func_flush();
	                        }

				$line = trim($v);
				$line = str_replace("</td><td>", "|---delimiter---|", $line);
				$line = str_replace("</td>", "", $line);
				$line = str_replace("<td>", "", $line);

				$line_arr = explode("|---delimiter---|", $line);

				$SKU = trim($line_arr[0]);
				$SKU = strtoupper($SKU);
				$Item_Name = trim($line_arr[1]);
				$MSRP = trim($line_arr[2]);
				$MSRP = price_format($MSRP);
				$MAP = trim($line_arr[3]);
				$MAP = price_format($MAP);
				$Retail_Price = trim($line_arr[4]);
				$Retail_Price = price_format($Retail_Price);
				$Your_Price = trim($line_arr[5]);
				$Your_Price = price_format($Your_Price);
				$Inventory_Status = trim($line_arr[6]);
				$Inventory_Status = strtolower($Inventory_Status);

                                $feed_productcode = "LWF-".$SKU;
                                $all_feed_productcodes[] = $feed_productcode;

                                $product_info_arr = func_query_first("SELECT productid, productcode, forsale, avail, eta_date_mm_dd_yyyy, list_price, cost_to_us, new_map_price FROM $sql_tbl[products] WHERE productcode='".addslashes($feed_productcode)."'");

                                if (!empty($product_info_arr)){

                                        $productcode = $product_info_arr["productcode"];
                                        $productid = $product_info_arr["productid"];
                                        $current_forsale = $product_info_arr["forsale"];
                                        $current_avail = $product_info_arr["avail"];
                                        $current_cost_to_us = $product_info_arr["cost_to_us"];
                                        $current_new_map_price = $product_info_arr["new_map_price"];
                                        $current_list_price = $product_info_arr["list_price"];
                                        $current_eta_date_mm_dd_yyyy = $product_info_arr["eta_date_mm_dd_yyyy"];

                                        $product_is_updated = false;
                                        $marked_as_out_of_stock_products = false;
                                        $marked_as_in_stock_products = false;

					$new_forsale = "Y";
					$new_avail = $current_avail;
                                        $new_eta_date_mm_dd_yyyy = $current_eta_date_mm_dd_yyyy;
					$new_eta_date_mm_dd_yyyy_time = "";
					$new_list_price = $current_list_price;
					$new_cost_to_us = $current_cost_to_us;
					$new_new_map_price = $current_new_map_price;

					if ($Inventory_Status == "ample stock"){
						$new_avail = 100;
						$new_eta_date_mm_dd_yyyy = "";
						$new_list_price = $MSRP;
						$new_cost_to_us = $Your_Price;
						$new_new_map_price = $MAP;
					} elseif ($Inventory_Status == "low stock"){
                                                $new_avail = 5;
                                                $new_eta_date_mm_dd_yyyy = "";
                                                $new_list_price = $MSRP;
                                                $new_cost_to_us = $Your_Price;
                                                $new_new_map_price = $MAP;
					} elseif ($Inventory_Status == "back ordered"){
						$new_avail = 0;
						$new_cost_to_us = $Your_Price;
						$new_eta_date_mm_dd_yyyy_time = time() + 60*60*24*35;
					}


                                        if (!empty($new_eta_date_mm_dd_yyyy_time)){
	                                        $new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy_time);
                                        }

                                        if ($new_avail == "0"){
	                                        if ($current_avail > 0){
        	                                        $marked_as_out_of_stock_products = true;
                                                }
                                        } else {
                                                if ($current_avail == 0){
                	                                $marked_as_in_stock_products = true;
                                                }
                                        }

                                        if ($new_forsale != $current_forsale || $new_eta_date_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy || $current_avail != $new_avail || $new_list_price != $current_list_price || $new_cost_to_us != $current_cost_to_us || $new_new_map_price != $current_new_map_price){
                                        		db_query("UPDATE $sql_tbl[products] SET avail='$new_avail', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy', forsale='$new_forsale', list_price='$new_list_price', cost_to_us='$new_cost_to_us', new_map_price='$new_new_map_price' WHERE productid='$productid'");
                                                        $product_is_updated = true;
                                        }

                                        if ($product_is_updated){
                                                $count_updated_products++;
                                        }

                                        if ($marked_as_out_of_stock_products){
                                                $count_marked_as_out_of_stock_products++;
                                        }

                                        if ($marked_as_in_stock_products){
                                                $count_marked_as_in_stock_products++;
                                        }
                                } else {
                                        $NEW_PRODUCTS[] = implode("|", $line_arr);
                                }

//func_print_r($SKU, $Item_Name, $MSRP, $MAP, $Retail_Price, $Your_Price, $Inventory_Status);

			}
		}

		if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes)){

                        $count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE productcode LIKE 'LWF-%' AND forsale='Y'");

                        if ($count_products > 0){

                                $LWF_products = db_query("SELECT productid, productcode, forsale FROM $sql_tbl[products] WHERE productcode LIKE 'LWF-%' AND forsale='Y'");

                                $line_number = 0;
                                print "<br />Second iteration:<br />";
                                while ($product = db_fetch_array($LWF_products)) {

                                        $line_number++;
                                        if ($line_number % 100 == 0) {
                                                func_flush(".");
                                                if($line_number % 5000 == 0) {
                                                        func_flush("<br />\n");
                                                }

                                                func_flush();
                                        }

                                        $productcode = strtoupper(trim($product["productcode"]));

                                        if (!in_array($productcode, $all_feed_productcodes) && $product["forsale"] != "N") {
                                                $discontinued_products[] = $product;
                                                db_query("UPDATE $sql_tbl[products] SET avail='0', forsale='N' WHERE productid='".$product["productid"]."'");
                                        }
                                }
                        }
		}


		db_query("UPDATE $sql_tbl[manufacturers] SET d_most_recent_feed_updation_date='".$launch_time."' WHERE manufacturerid='$manufacturerid'");

	        $count_discontinued_products = count($discontinued_products);
        	if (!empty($discontinued_products) && is_array($discontinued_products)){

                	$subj = "LONEWOLF FEED UPDATE - discontinued products";
        	        $body = "Discontinued products count: ".$count_discontinued_products."\n\n";

	                $body .= "<table border='1'>\n<tr><td>ProductCode</td><td>Link to SF backend</td></tr>\n";
                	foreach ($discontinued_products as $k => $v){

        	                $store_url = func_query_first_cell("SELECT $sql_tbl[storefronts].domain FROM $sql_tbl[storefronts] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[storefronts].storefrontid=$sql_tbl[products_sf].sfid WHERE $sql_tbl[products_sf].productid='".$v["productid"]."'");
	                        if (empty($store_url)){
                                	$store_url = "www.artistsupplysource.com";
                        	}

                	        $body .= "<tr><td> ".$v["productcode"]." </td><td> <a href='http://".$store_url."/product.php?productid=".$v["productid"]."'>http://".$store_url."/product.php?productid=".$v["productid"]."</a> </td></tr>\n";
        	        }
	                $body .= "</table>";

                	func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//                print"<br />For test purpose: discontinued_products:";
//                func_print_r($discontinued_products);
        	}

	        $count_NEW_PRODUCTS = count($NEW_PRODUCTS);
        	if (!empty($NEW_PRODUCTS) && is_array($NEW_PRODUCTS)){
	                $subj = "LONEWOLF FEED UPDATE - new products";
                	$body = "Expected new products count: ".$count_NEW_PRODUCTS."\n\n";

        	        $body .= "Item Number|Item Name|MSRP|MAP|Retail Price|Your Price|Inventory Status\n";
	                foreach ($NEW_PRODUCTS as $k => $v){
                        	$body .= $v."\n";
                	}

        	        func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");

//              print"<br />For test purpose: NEW_PRODUCTS:";
//              func_print_r($NEW_PRODUCTS);
	        }

        	$count_all_feed_productcodes = count($all_feed_productcodes);
	        $sum_updated_products = $count_marked_as_out_of_stock_products + $count_marked_as_in_stock_products + $count_discontinued_products;

	        $subj = "LONEWOLF FEED UPDATE - summary";
	        $body = "products in feed: ".$count_all_feed_productcodes."\n";
	        $body .= "updated products: ".$sum_updated_products."\n";
	        $body .= "marked as `out of stock` products: ".$count_marked_as_out_of_stock_products."\n";
	        $body .= "marked as `in stock` products: ".$count_marked_as_in_stock_products."\n";
	        $body .= "new products: ".$count_NEW_PRODUCTS."\n";
	        $body .= "discontinued products: ".$count_discontinued_products."\n";

	        func_send_simple_mail($general_info["d_product_management_team_email"], $subj, $body, "supplier.feeds@s3stores.com");
	}
}

?>
