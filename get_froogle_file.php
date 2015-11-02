<?php
require "./auth.php";

if (($name == "froogle" || $name == "thefind") && ($sfid > 0 || $sfid == "0")){

	$current_storefront_info = func_get_storefront_info($sfid, 'ID');	

	$prefix = $current_storefront_info["prefix"];
	if ($prefix == "MAIN_SF_PREFIX"){
		$prefix = "AR-";
	}

	$filename = $current_storefront_info["domain"]."/".$prefix.$name.".txt";

	$file_exists = false;

	if (!@file_exists($filename)) {
        	$filename = realpath($files_dir_name.DIRECTORY_SEPARATOR.$filename);
	        $file_exists = file_exists($filename);
	}
	else {
        	$filename = realpath($filename);
	        $file_exists = !strncmp($filename, $files_dir_name, strlen($files_dir_name));
	}

	if ($file_exists) {
	#
	# Output file content
	#
              	header("Content-type: application/force-download");
       	        header("Content-Disposition: attachment; filename=".basename($filename));
        	func_readfile($filename);
	}

	exit;
}
else {
	die("Not allowed!");
}
?>
