<?php

die("Disabled");

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);



$products = db_query("SELECT eta_date_mm_dd_yyyy, productid FROM xcart_products WHERE eta_date_mm_dd_yyyy!=''");

$counter = 0;
while ($product = db_fetch_array($products)) {

                                $counter++;
                                if ($counter % 10 == 0) {
                                        func_flush(".");
                                        if($counter % 500 == 0) {
                                                func_flush("<br />\n");
                                        }
                                        func_flush();
                                }


	$productid = $product["productid"];
	$eta_date_mm_dd_yyyy = $product["eta_date_mm_dd_yyyy"];

//func_print_r($eta_date_mm_dd_yyyy);

	if (strpos($eta_date_mm_dd_yyyy, "/") !== false){
		$eta_date_mm_dd_yyyy = func_convert_date_mm_dd_yyyy($eta_date_mm_dd_yyyy, "seconds");
	}
	elseif (strpos($eta_date_mm_dd_yyyy, "-") !== false){
                        $date_arr = explode("-", $eta_date_mm_dd_yyyy);
			if (isset($date_arr[2])){
	                        $eta_date_mm_dd_yyyy = mktime(0, 0, 0, $date_arr[1], $date_arr[2], $date_arr[0]);
			}
	}

	

//func_print_r($eta_date_mm_dd_yyyy);

	db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date_mm_dd_yyyy' WHERE productid='$productid'");	

//func_print_r($productid);
//die();


}
db_free_result($product);


print("Done!");

?>
