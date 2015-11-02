<?php
require "./auth.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$companies = array();

$orders = db_query("SELECT company, extra FROM $sql_tbl[orders]");

while ($order = db_fetch_array($orders)) {

	if (!empty($order["company"])){
		$company = trim($order["company"]);
		if (!empty($company) && !in_array($company, $companies)) {
			$companies[] = $company;
		}
	}

	if (!empty($order["extra"])){

		$extra = unserialize($order["extra"]);

		if (!empty($extra["additional_fields"]) && is_array($extra["additional_fields"])){
			foreach ($extra["additional_fields"] as $k => $v){
				if (strtolower($v["title"]) == "company"){
					$company = trim($v["value"]);
			                if (!empty($company) && !in_array($company, $companies)) {
                        			$companies[] = $company;
			                }   
				}
			}
		}
	}
}

asort($companies);

//func_print_r($companies);


$filename = 'var/tmp/123_test.txt';


// Let's make sure the file exists and is writable first.
if (is_writable($filename)) {

    // In our example we're opening $filename in append mode.
    // The file pointer is at the bottom of the file hence
    // that's where $somecontent will go when we fwrite() it.
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }

    foreach ($companies as $somecontent){
    // Write $somecontent to our opened file.
	    if (fwrite($handle, $somecontent."\r\n") === FALSE) {
        	echo "Cannot write to file ($filename)";
	        exit;
	    }
    }

    fclose($handle);

    print("Done: ".$filename);

} else {
    echo "The file $filename is not writable";
}

?>
